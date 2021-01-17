<?php
include 'base.tpl.php';

switch ($controller) {
  case 'newPost':
?>
    <main role="main" class="inner cover">
      <h1 class="cover-heading">Nuevo Post</h1>
      <br>
      <form class="form-signin" action="<?= BASE ?>blog/newaction" method="post">
        <label for="inputTitle" class="sr-only">Título</label>
        <input type="text" id="inputTitle" name="inputTitle" class="form-control" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;" placeholder="Título" required autofocus>

        <label for="inputCont" class="sr-only">Contenido</label>
        <textarea id="inputCont" name="inputCont" class="form-control" style="border-radius:0px;" placeholder="Contenido" required></textarea>

        <label for="inputTags" class="sr-only">Tags</label>
        <input type="text" id="inputTags" name="inputTags" class="form-control" style="border-top-left-radius:0px;border-top-right-radius:0px;" placeholder="Tag1,Tag2,Tag3" required>
        <br><br>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="new-post-button">Crear Post</button>
      </form>
    </main>

  <?php
    break;
  case 'viewPost':
    echo '
      <main role="main" class="blog inner cover">
      <h1 class="cover-heading">Post</h1>
      <br><br>
        <div class="card border-dark mb-3" >
        <div class="card-body text-start">
          <h5 class="card-title">' . $posts['post'][0]['title'] . '</h5>
          <p class="card-text">' . $posts['post'][0]['cont'] . '</p>
          <h6 class="card-subtitle mb-2 text-muted">Autor: <a href="' . BASE . 'blog/search/user/' . $posts['post'][0]['username'] . '" class="card-link">' . $posts['post'][0]['username'] . '</a></h6>
          <h6 class="card-subtitle mb-2 text-muted">Fecha Creación: ' . $posts['post'][0]['createDate'] . '</h6>
          <h6 class="card-subtitle mb-2 text-muted">Fecha Modificación: ' . $posts['post'][0]['modifyDate'] . '</h6>
          <h6 class="card-subtitle mb-2 text-muted">Tags: ';
    for ($e = 0; $e < count($posts['tag'][0]); $e++) {
      echo '<a href="' . BASE . 'blog/search/tag/' . $posts['tag'][0][$e]['tag'] . '" class="card-link">' . $posts['tag'][0][$e]['tag'] . '</a>';
    }
    echo ' 
          </h6>
          ';
    if (isset($user)) {
      if ($posts['post'][0]['username'] == $user['username']) {
        echo '
            <div class="text-end">
              <a href="' . BASE . 'blog/edit/id/' . $posts['post'][0]['id'] . '" type="button" class="btn btn-light">Editar</a>
              <a href="' . BASE . 'blog/delete/idpost/' . $posts['post'][0]['id'] . '" type="button" class="btn btn-danger">Eliminar</a>
            </div>
         ';
      }
    }

    echo '  <br>
            <div style="width:100%;padding-top:5px;background-color:grey;border-radius:1000px;"></div>
            <br>
          <p class="card-text">Hay ' . count($posts['comment'][0]) . ' comentarios</p>';
    if (isset($user)) {
      echo '
            <form action="' . BASE . 'blog/newcomment" method="post" class="form-floating">
              <input type="hidden" name="idBlog" value="' . $posts['post'][0]['id'] . '" required>
              <textarea class="form-control" placeholder="Dejar un comentario" id="floatingTextarea" name="inputCont" required></textarea>
              <label for="floatingTextarea" style="color:grey;">Dejar un comentario</label>
              <div class="text-end">
                <button class="btn btn-light" type="submit" name="new-comment-button" style="margin-top:10px;">Enviar</button>
              </div>
            </form>
            </br>';
    }

    for ($c = 0; $c < count($posts['comment'][0]); $c++) {
      echo '<div class="card border-grey mb-3" style="background-color:#404952;">
              <div class="card-body text-start">
                <h5 class="card-title">
                ';
      if (isset($user) && $posts['post'][0]['username'] == $user['username']) {
        echo    '<div class="text-end">
                  <a href="' . BASE . 'blog/delete/idcomment/' . $posts['comment'][0][$c]['id'] . '/idpost/' . $posts['post'][0]['id'] . '" type="button" class="btn btn-outline-warning btn-sm">Eliminar</a>
                </div>';
      }
      echo     '<a href="' . BASE . 'blog/search/user/' . $posts['comment'][0][$c]['username'] . '" class="card-link" style="color:white;font-weight:bold;">
                ' . $posts['comment'][0][$c]['username'] . '</a></h5>
                <p class="card-text">' . $posts['comment'][0][$c]['comment'] . '</p>
              </div>
            </div>';
    }
    echo '</div>
      </div>
      </main>
     ';
    break;
  case 'searchPost':
  ?>

    <main role="main" class="blog inner cover">
      <h1 class="cover-heading">Viendo posts <?= $action ?> de <?= $value ?></h1>
      <h2 class="cover-heading">Hay <?= count($posts['post']) ?> entradas</h2>
      <br><br>
      <?php
      for ($i = 0; $i < count($posts['post']); $i++) {
        echo '
        <div class="card border-dark mb-3">
        <div class="card-body text-start">
          <h5 class="card-title">' . $posts['post'][$i]['title'] . '</h5>
          <p class="card-text">' . $posts['post'][$i]['cont'] . '</p>
          <h6 class="card-subtitle mb-2 text-muted">Autor: <a href="' . BASE . 'blog/search/user/' . $posts['post'][$i]['username'] . '" class="card-link">' . $posts['post'][$i]['username'] . '</a></h6>
          <h6 class="card-subtitle mb-2 text-muted">Fecha: ' . $posts['post'][$i]['createDate'] . '</h6>
          <h6 class="card-subtitle mb-2 text-muted">Tags: ';
        for ($e = 0; $e < count($posts['tag'][$i]); $e++) {
          echo '<a href="' . BASE . 'blog/search/tag/' . $posts['tag'][$i][$e]['tag'] . '" class="card-link">' . $posts['tag'][$i][$e]['tag'] . '</a>';
        }
        echo ' 
          </h6>
          <h6 class="card-subtitle mb-2 text-muted">Tiene ' . count($posts['comment'][$i]) . ' comentarios</h6>
          <div class="text-end">
            <a href="' . BASE . 'blog/view/id/' . $posts['post'][$i]['id'] . '" type="button" class="btn btn-light">Leer más</a>
          </div>
        </div>
      </div>
     ';
      }
      ?>

    </main>
  <?php
    break;
  case 'updatePost':
    $tags = "";
    for ($e = 0; $e < count($posts['tag'][0]); $e++) {
      $tags .= $posts['tag'][0][$e]['tag'] . ',';
    }
    $tags = substr($tags, 0, -1);
  ?>
    <main role="main" class="inner cover">
      <h1 class="cover-heading">Actualizar Post</h1>
      <br>
      <form class="form-signin" action="<?= BASE ?>blog/updateaction" method="post">
        <label for="inputTitle" class="sr-only">Título</label>
        <input type="text" id="inputTitle" name="inputTitle" class="form-control" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;" placeholder="Título" required autofocus value="<?= $posts['post'][0]['title'] ?>">

        <label for="inputCont" class="sr-only">Contenido</label>
        <textarea id="inputCont" name="inputCont" class="form-control" style="border-radius:0px;" placeholder="Contenido" required><?= $posts['post'][0]['cont'] ?></textarea>

        <label for="inputTags" class="sr-only">Tags</label>
        <input type="text" id="inputTags" name="inputTags" class="form-control" style="border-top-left-radius:0px;border-top-right-radius:0px;" placeholder="Tag1,Tag2,Tag3" required value="<?= $tags ?>">
        <br><br>
        <input type="hidden" name="idPost" value="<?= $posts['post'][0]['id'] ?>">
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="update-post-button">Actualizar Post</button>
      </form>
    </main>

<?php
    break;
  default:
    # code...
    break;
}
?>





<?php

include 'footer.tpl.php';
?>