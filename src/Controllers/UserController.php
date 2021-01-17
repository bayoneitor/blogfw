<?php

namespace App\Controllers;

use App\Controller;
use App\Request;
use App\View;
use App\Model;
use App\User;
use App\Session;

final class UserController extends Controller implements View, Model
{
    public function __construct(Request $request, Session $session)
    {
        parent::__construct($request, $session);
    }
    public function index()
    {

        header('Location: ' . BASE);
    }
    public function login()
    {

        if (isset($_SESSION["user"])) {
            header('Location: ' . BASE);
        } else {
            $dataview = ['title' => "Login", 'controller' => 'login', 'email' => $_COOKIE['email'] ?? ''];
            $this->render($dataview, 'login');
        }
    }
    public function profile()
    {

        if (!isset($_SESSION["user"])) {
            header('Location: ' . BASE);
        } else {
            $user = $this->session->get('user');
            $dataview = ['title' => "Perfil", 'controller' => 'profile', 'user' => $user];
            $this->render($dataview, 'profile');
        }
    }
    public function register()
    {

        if (isset($_SESSION["user"])) {
            header('Location: ' . BASE);
        } else {
            $dataview = ['title' => "Register", 'controller' => 'register'];
            $this->render($dataview, 'register');
        }
    }
    public function logout()
    {
        Session::logout();

        header('Location: ' . BASE);
    }
    public function logaction()
    {

        //Primero miramos que no este la session definida
        if (!isset($_SESSION["user"])) {
            //Miramos si viene por login y si viene por cookie le damos otros parametros arriba
            if (isset($_POST['login-button'])) {
                // Miramos que ninguno este vacio
                if (filter_input(INPUT_POST, 'inputEmail') != null || filter_input(INPUT_POST, 'inputPassword') != null) {

                    $email = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_SPECIAL_CHARS);
                    $pwd = filter_input(INPUT_POST, 'inputPassword', FILTER_SANITIZE_SPECIAL_CHARS);
                    $db = $this->getDB();
                    if ($db) {
                        //Selecciona el email
                        $userDB = new User();
                        $log = $userDB->auth($db, $email, $pwd);

                        if ($log == true) {
                            if (isset($_POST['remember-me'])) {
                                //Recordamos el email
                                setcookie("email", $email, time() + 60 * 60 * 24 * 365, BASE);
                            } else {
                                setcookie("email", "", time() - 1, BASE);
                            }
                            header('Location: ' . BASE . 'index#success');
                        } else {
                            header('Location: ' . BASE . 'user/login#error=notExists');
                        }
                    } else {
                        //Error conexion BD
                        header('Location: ' . BASE . 'user/login#error=db');
                    }
                } else {
                    //Elementos vacios
                    header('Location: ' . BASE . 'user/login#error=emptyfields');
                }
            } else {
                header('Location: ' . BASE);
            }
        } else {
            header('Location: ' . BASE);
        }
    }

    public function regaction()
    {

        if (!isset($_SESSION["user"])) {
            //Miramos si entrar por el boton register
            if (isset($_POST['register-button'])) {
                // Miramos que ninguno este vacio
                if (
                    filter_input(INPUT_POST, 'inputUser') != null &&
                    filter_input(INPUT_POST, 'inputEmail') != null &&
                    filter_input(INPUT_POST, 'inputPassword') != null
                ) {
                    if (filter_input(INPUT_POST, 'inputPassword') != filter_input(INPUT_POST, 'inputPasswordRepeat')) {
                        header('Location: ' . BASE . 'user/register#error=password');
                        die();
                    }
                    $db = $this->getDB();
                    if ($db) {

                        $user = filter_input(INPUT_POST, 'inputUser', FILTER_SANITIZE_SPECIAL_CHARS);
                        $email = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_SPECIAL_CHARS);
                        $pwd = filter_input(INPUT_POST, 'inputPassword', FILTER_SANITIZE_SPECIAL_CHARS);

                        $userDB = new User();
                        $insert = $userDB->newUser($pwd, $user, $email);

                        if ($insert) {
                            header('Location: ' . BASE . 'user/login#success');
                        } else {
                            header('Location: ' . BASE . 'user/register#error=insert');
                        }
                    } else {
                        //Error conexion BD
                        header('Location: ' . BASE . 'user/register#error=db');
                    }
                } else {
                    //error vacio
                    header('Location: ' . BASE . 'user/register#error=emptyfields');
                }
            } else {
                header('Location: ' . BASE);
            }
        } else {
            header('Location: ' . BASE);
        }
    }
}
