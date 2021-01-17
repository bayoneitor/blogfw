<?php
include 'base.tpl.php';

?>
<main role="main" class="blog inner cover">
  <h1 class="cover-heading">Las últimas <?= count($posts['post']) ?> entradas</h1>
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

include 'footer.tpl.php';
?>