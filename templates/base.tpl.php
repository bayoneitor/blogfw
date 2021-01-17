<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php
  if ($controller == "home" || $controller == "viewPost" || $controller == "searchPost") {
  ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <?php
  } else {
  ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <?php
  }

  ?>
  <link href="<?= BASE; ?>public/css/cover.css" rel="stylesheet">
  <title> <?= $title; ?> - Foro</title>
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .nav-link.user {
      text-transform: capitalize;
    }

    .blog {
      padding-top: 40px;
    }

    .blog .card {
      text-shadow: none;
      background-color: #212529;
    }

    .blog .card-link {
      color: #6c757d;
    }

    .blog .card-title {
      font-size: 25px;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
</head>
<?php
if (!isset($controller)) {
  $controller = "home";
}
?>

<body class="text-center">
  <div class="container-sm d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="masthead mb-auto">
      <div class="inner">
        <h3 class="masthead-brand"><a class="nav-link" href="<?= BASE ?>">Foro</a></h3>
        <nav class="nav nav-masthead justify-content-center">
          <?php
          if ($controller == "home") {
            echo '<a class="nav-link active" href="' . BASE . '">Inicio</a>';
          } else {
            echo '<a class="nav-link" href="' . BASE . '">Inicio</a>';
          }
          if (isset($user)) {

            if ($controller == "newPost") {
              echo '<a class="nav-link active user" href="' . BASE . 'blog/new">Nuevo Post</a>';
            } else {
              echo '<a class="nav-link user" href="' . BASE . 'blog/new">Nuevo Post</a>';
            }
            if ($controller == "profile") {
              echo '<a class="nav-link active user" href="' . BASE . 'user/profile">' . $user['username'] . '</a>';
            } else {
              echo '<a class="nav-link user" href="' . BASE . 'user/profile">' . $user['username'] . '</a>';
            }
            echo '<a class="nav-link" href="' . BASE . 'user/logout">Cerrar Sesión</a>';
          } else {
            if ($controller == "login") {
              echo ' <a class="nav-link active" href="' . BASE . 'user/login">Login</a>';
            } else {
              echo ' <a class="nav-link" href="' . BASE . 'user/login">Login</a>';
            }
            if ($controller == "register") {
              echo ' <a class="nav-link active" href="' . BASE . 'user/register">Register</a>';
            } else {
              echo ' <a class="nav-link" href="' . BASE . 'user/register">Register</a>';
            }
          }
          ?>
        </nav>
      </div>
    </header>