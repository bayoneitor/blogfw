<?php

namespace App\Controllers;

use App\Controller;
use App\Request;
use App\View;
use App\Model;
use App\Blog;
use App\Session;

final class BlogController extends Controller implements View, Model
{
    public function __construct(Request $request, Session $session)
    {
        parent::__construct($request, $session);
    }
    public function index()
    {

        header('Location: ' . BASE);
    }
    public function new()
    {
        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASE);
        } else {
            $user = $this->session->get('user');
            $dataview = ['title' => "Nuevo Post", 'user' => $user, 'controller' => 'newPost'];
            $this->render($dataview, 'post');
        }
    }
    public function edit()
    {
        if (isset($_SESSION["user"])) {
            $db = $this->getDB();
            if ($db) {
                $user = $this->session->get('user');
                $blogDB = new Blog();

                $params = $this->request->getParams();
                $idPost = $params['id'];

                $posts = $blogDB->selectPost('edit', $idPost, $user['id']);
                if ($posts['post'] != null) {
                    $user = $this->session->get('user');
                    $dataview = ['title' => "Actualizar Post", 'user' => $user, 'controller' => 'updatePost', 'posts' => $posts];
                    $this->render($dataview, 'post');
                    die();
                }
            }
        }
        header('Location: ' . BASE);
        die();
    }
    public function view()
    {
        $params = $this->request->getParams();
        $idPost = $params['id'];

        $blogDB = new Blog();
        $posts = $blogDB->selectPost($idPost);
        if ($posts['post'] != null) {
            $dataview = ['title' => "Viendo post", 'controller' => 'viewPost', 'posts' => $posts];
            if (isset($_SESSION["user"])) {
                $user = $this->session->get('user');
                $dataview += ['user' => $user];
            }
            $this->render($dataview, 'post');
            die();
        }
        header('Location: ' . BASE);
        die();
    }
    public function delete()
    {
        if (isset($_SESSION["user"])) {
            $user = $this->session->get('user');
            $params = $this->request->getParams();
            $idComment = $params['idcomment'] ?? null;
            $idPost = $params['idpost'];
            $idUser = $user['id'];

            $blogDB = new Blog();
            if ($idComment != null) {
                $action = $blogDB->deleteComment($idComment, $idUser);
                if ($action) {
                    header('Location: ' . BASE . 'blog/view/id/' . $idPost . '#success');
                } else {
                    header('Location: ' . BASE . 'blog/view/id/' . $idPost . '#error');
                }
                die();
            } else {
                $action = $blogDB->deletePost($idPost, $idUser);
                if ($action) {
                    header('Location: ' . BASE . '#success');
                } else {
                    header('Location: ' . BASE . '#error');
                }
                die();
            }
        }
        header('Location: ' . BASE);
        die();
    }
    public function search()
    {
        $params = $this->request->getParams();
        $action = key($params);
        $blogDB = new Blog();
        $posts = $blogDB->selectPost($action, $params[$action]);

        $dataview = ['title' => "Viendo posts de $action", 'controller' => 'searchPost', 'posts' => $posts, 'action' => $action, 'value' => $params[$action]];
        if (isset($_SESSION["user"])) {
            $user = $this->session->get('user');
            $dataview += ['user' => $user];
        }
        $this->render($dataview, 'post');
    }
    public function newcomment()
    {
        $user = $this->session->get('user');
        if (isset($_SESSION["user"])) {
            if (isset($_POST['new-comment-button'])) {
                // Miramos que ninguno este vacio
                if (filter_input(INPUT_POST, 'inputCont') != null && filter_input(INPUT_POST, 'idBlog') != null) {
                    $cont = filter_input(INPUT_POST, 'inputCont', FILTER_SANITIZE_SPECIAL_CHARS);
                    $idBlog = filter_input(INPUT_POST, 'idBlog', FILTER_SANITIZE_SPECIAL_CHARS);
                    $db = $this->getDB();
                    if ($db) {
                        $blogDB = new Blog();
                        $newComment = $blogDB->newComment($user['id'], $cont, $idBlog);

                        if ($newComment == true) {
                            header('Location: ' . BASE . 'blog/view/id/' . $idBlog . '#success');
                        } else {
                            header('Location: ' . BASE . 'blog/view/id/' . $idBlog . '#error');
                        }
                    } else {
                        //Error conexion BD
                        header('Location: ' . BASE . 'blog/view/id/' . $idBlog . '#error=db');
                    }
                } else {
                    //Elementos vacios
                    header('Location: ' . BASE . '#error=emptyfields');
                }
            } else {
                header('Location: ' . BASE);
            }
        } else {
            header('Location: ' . BASE);
        }
    }
    public function newaction()
    {
        $user = $this->session->get('user');
        if (isset($_SESSION["user"])) {
            if (isset($_POST['new-post-button'])) {
                // Miramos que ninguno este vacio
                if (filter_input(INPUT_POST, 'inputTitle') != null && filter_input(INPUT_POST, 'inputCont') != null && filter_input(INPUT_POST, 'inputTags') != null) {

                    $title = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_SPECIAL_CHARS);
                    $cont = filter_input(INPUT_POST, 'inputCont', FILTER_SANITIZE_SPECIAL_CHARS);
                    $tags = filter_input(INPUT_POST, 'inputTags', FILTER_SANITIZE_SPECIAL_CHARS);
                    $db = $this->getDB();
                    if ($db) {
                        $blogDB = new Blog();
                        $idPost = null;
                        $newPost = $blogDB->newPost($title, $cont, $tags, $user['id'], $idPost);

                        if ($newPost == true) {
                            header('Location: ' . BASE . 'blog/view/id/' . $idPost . '#success');
                        } else {
                            header('Location: ' . BASE . 'blog/new#error');
                        }
                    } else {
                        //Error conexion BD
                        header('Location: ' . BASE . 'blog/new#error=db');
                    }
                } else {
                    //Elementos vacios
                    header('Location: ' . BASE . 'blog/new#error=emptyfields');
                }
            } else {
                header('Location: ' . BASE);
            }
        } else {
            header('Location: ' . BASE);
        }
    }
    public function updateaction()
    {
        $user = $this->session->get('user');
        if (isset($_SESSION["user"])) {
            if (isset($_POST['update-post-button'])) {
                // Miramos que ninguno este vacio
                if (filter_input(INPUT_POST, 'idPost') != null && filter_input(INPUT_POST, 'inputTitle') != null && filter_input(INPUT_POST, 'inputCont') != null && filter_input(INPUT_POST, 'inputTags') != null) {
                    $title = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_SPECIAL_CHARS);
                    $cont = filter_input(INPUT_POST, 'inputCont', FILTER_SANITIZE_SPECIAL_CHARS);
                    $tags = filter_input(INPUT_POST, 'inputTags', FILTER_SANITIZE_SPECIAL_CHARS);
                    $idPost = filter_input(INPUT_POST, 'idPost', FILTER_SANITIZE_SPECIAL_CHARS);

                    $db = $this->getDB();
                    if ($db) {
                        $blogDB = new Blog();
                        $updatePost = $blogDB->updatePost($title, $cont, $tags, $user['id'], $idPost);

                        if ($updatePost == true) {
                            header('Location: ' . BASE . 'blog/view/id/' . $idPost . '#success');
                        } else {
                            header('Location: ' . BASE . 'blog/view/id/' . $idPost . '#error');
                        }
                    } else {
                        //Error conexion BD
                        header('Location: ' . BASE . 'blog/view/id/' . $idPost . '#error=db');
                    }
                } else {
                    //Elementos vacios
                    header('Location: ' . BASE . '#error=emptyfields');
                }
            } else {
                header('Location: ' . BASE);
            }
        } else {
            header('Location: ' . BASE);
        }
    }
}
