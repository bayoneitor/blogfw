<?php

namespace App;

use App\Controller;
use App\Session;

use PDO;
use PDOException;

class Blog extends Controller
{
    public function __construct()
    {
    }
    public function selectPost($post, $param = null, $param2 = null)
    {
        $db = $this->getDB();
        if ($db) {
            $sqlPost = "SELECT post.id as id, post.title as title, post.cont as cont, user.username as username, post.`create-date` as createDate, post.`modify-date` as modifyDate 
                        FROM post 
                        INNER JOIN user ON user.id = post.user ";

            if ($post == "latest") {
                $sqlPost .= "ORDER BY post.`create-date` DESC";
            } else if ($post == "user") {
                $sqlPost .= "WHERE user.username = :post
                            ORDER BY post.`create-date` DESC";
            } else if ($post == "tag") {
                $sqlPost .= "INNER JOIN post_has_tags ON post_has_tags.idPost=post.id
                            INNER JOIN tags ON tags.id = post_has_tags.idTags
                            WHERE tags.tag = :post
                            ORDER BY post.`create-date` DESC";
            } else if ($post == "edit") {
                $sqlPost .= "WHERE post.id = :idpost AND post.user = :iduser";
            } else {
                $sqlPost .= "WHERE post.id = :post";
            }

            $stmt = $db->prepare($sqlPost);
            if ($post == "latest") {
                $stmt->execute();
            } else if ($post == "user" || $post == "tag") {
                $stmt->execute([':post' => $param]);
            } else if ($post == "edit") {
                $stmt->execute([':idpost' => $param, ':iduser' => $param2]);
            } else {
                $stmt->execute([':post' => $post]);
            }
            $rowsPost = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if ($post == "latest" || $post == "user" || $post == "tag") {
                for ($i = 0; $i < count($rowsPost); $i++) {
                    $rowsPost[$i]['cont'] = $this->limit_text($rowsPost[$i]['cont'], 40);
                }
            }
            $rows = $this->postMixArray($rowsPost, $post);
            return $rows;
        }
    }
    public function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $text  = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }

    private function postMixArray($rowsPost, $post): array
    {
        $rowsTag = [];
        $rowsComment = [];
        for ($i = 0; $i < count($rowsPost); $i++) {
            $rowsTagFor = $this->selectTagPost($rowsPost[$i]['id']);
            $rowsTag[] = $rowsTagFor;

            $rowsCommentFor = $this->selectCommentPost($rowsPost[$i]['id']);
            $rowsComment[] = $rowsCommentFor;
        }

        $rows = ['post' => $rowsPost, 'tag' => $rowsTag, 'comment' => $rowsComment];
        return $rows;
    }
    public function selectCommentPost($idPost): array
    {
        $db = $this->getDB();
        if ($db) {
            $rowsTag = $db->selectWhereWithJoin('comments', 'user', ['comments.id as id', 'comment', 'user.username as username'], 'idUser', 'id', ['comments.idPost', "$idPost"]);
            return $rowsTag;
        }
    }
    public function selectTagPost($idPost): array
    {
        $db = $this->getDB();
        if ($db) {
            $rowsTag = $db->selectWhereWithJoin('post_has_tags', 'tags', ['post_has_tags.idPost as idPost', 'tag'], 'idTags', 'id', ['post_has_tags.idPost', "$idPost"]);
            return $rowsTag;
        }
    }
    public function newComment($idUser, $cont, $idPost): bool
    {
        $db = $this->getDB();
        if ($db) {
            $insertPost = $db->insert('comments', ['comment' => $cont, 'idUser' => $idUser, 'idPost' => $idPost]);
            if ($insertPost) {
                return true;
            }
        }
        return false;
    }
    public function newPost($title, $cont, $tags, $idUser, &$idPost): bool
    {
        $db = $this->getDB();
        if ($db) {
            $now = date("Y-m-d H:i:s");
            $insertPost = $db->insert('post', ['title' => $title, 'cont' => $cont, 'user' => $idUser, 'create-date' => $now, 'modify-date' => $now]);
            //Miramos si se ha creado el insert
            if ($insertPost) {
                //La id del post
                $idPost = $db->lastInsertId();
                $this->insertTags($tags, $idPost);
                return true;
            }
        }
        return false;
    }
    public function deleteComment($idComment, $idUser)
    {
        $db = $this->getDB();
        if ($db) {
            $action = $db->delete('comments', ['id', $idComment], ['idUser', $idUser]);
            if ($action) {
                return true;
            }
        }
        return false;
    }
    private function insertTags($tags, $idPost)
    {
        $db = $this->getDB();
        $tagArr = explode(',', $tags);
        //Comprobar si el tag esta creado o no, si esta creado saldra un error porque es UNIQUE
        //Entonces en el else hacemos un select y seleccionamos la id, y ahí lo metemos en post_hash_tags
        for ($i = 0; $i < count($tagArr); $i++) {
            $tagName = $tagArr[$i];

            $tagSel = $db->selectAllWhere('tags', ['id'], ['tag', $tagName]);

            if ($tagSel == null) {
                $db->insert('tags', ['tag' => $tagName]);
                $tagId = $db->lastInsertId();
            } else {
                $tagId = $tagSel[0]['id'];
            }

            $db->insert('post_has_tags', ['idPost' => $idPost, 'idTags' => $tagId]);
        }
    }
    public function deletePost($idPost, $idUser)
    {
        $db = $this->getDB();
        if ($db) {
            $check = $db->selectAllWhere('post', ['id'], ['id', $idPost], ['user', $idUser]);
            if ($check != null) {
                $actionPostTags = $db->delete('post_has_tags', ['idPost', $idPost]);
                if ($actionPostTags) {
                    $actionPostComments = $db->delete('comments', ['idPost', $idPost]);
                    if ($actionPostComments) {
                        $actionPost = $db->delete('post', ['id', $idPost], ['user', $idUser]);
                        if ($actionPost) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
    public function updatePost($title, $cont, $tags, $idUser, $idPost): bool
    {
        $db = $this->getDB();
        if ($db) {
            $now = date("Y-m-d H:i:s");
            $updatePost = $db->update('post', ['title' => $title, 'cont' => $cont, '`modify-date`' => $now], ['id', $idPost], ['user', $idUser]);
            //Miramos si ha hecho el update, si lo hace quiere decir que es suyo, entonces borramos los tags, y los volvemos a poner
            if ($updatePost) {
                $actionPostTags = $db->delete('post_has_tags', ['idPost', $idPost]);
                if ($actionPostTags) {
                    $this->insertTags($tags, $idPost);
                    return true;
                }
            }
        }
        return false;
    }
}
