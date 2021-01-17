<?php

namespace App\Controllers;

use App\Request;
use App\Session;
use App\Controller;
use App\Blog;

final class IndexController extends Controller
{

    public function __construct(Request $request, Session $session)
    {
        parent::__construct($request, $session);
    }

    public function index()
    {
        $blogDB = new Blog();
        $posts = $blogDB->selectPost("latest");
        $user = $this->session->get('user');
        $dataview = [
            'title' => 'Inicio', 'user' => $user, 'controller' => 'home', 'posts' => $posts
        ];
        $this->render($dataview);
    }
}
