<?php
include 'base.tpl.php';
?>
<main role="main" class="inner cover" style="height:70vh;">
  <h1 class="cover-heading">Perfil</h1>
  <br> <br>
  <p class="lead">Nombre de Usuario: <span style="color:grey; font-weight:bold;"><?= $user["username"] ?></span></p>

  <p class="lead">Correo Electrónico: <span style="color:grey; font-weight:bold;"><?= $user["email"] ?></span></p>
  <a type="button" class="btn btn-outline-primary btn-lg" href="<?= BASE ?>blog/search/user/<?= $user["username"] ?>">Ver tus POSTS</a>
  <!-- pepe -->
</main>

<?php

include 'footer.tpl.php';
?>