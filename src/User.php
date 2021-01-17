<?php

namespace App;

use App\Controller;
use App\Session;

use PDO;
use PDOException;

class User extends Controller
{
    public function __construct()
    {
    }
    public function newUser($pwd, $user, $email): bool
    {
        $db = $this->getDB();
        //Comprobamos que no exista el correo
        if ($this->checkEmail($email)) {
            $pwd = password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 4]);

            return $db->insert('user', ['username' => $user, 'email' => $email, 'passwd' => $pwd]);
        }
        return false;
    }
    public function checkEmail($email): bool
    {
        $db = $this->getDB();
        $email = $db->selectAllWhere('user', ['email'], ['email', $email]);
        if ($email[0] == null) {
            return true;
        }
        return false;
    }
    function auth($db, $email, $pass): bool
    {
        try {
            //preparem sentència
            $stmt = $db->prepare('SELECT * FROM `user` WHERE email=:email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $count = $stmt->rowCount();
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // ha trobat incidència
            if ($count == 1) {
                $user = $row[0];
                $res = password_verify($pass, $user['passwd']);

                if ($res) {
                    // establim sessió
                    $session = new Session;
                    $session->set('user', ['username' => $user['username'], 'email' => $user['email'], 'id' => $user['id'], 'idRol' => $user['idRol']]);
                    // retornem true
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
}
