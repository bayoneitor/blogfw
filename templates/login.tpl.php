<?php
include 'base.tpl.php';
?>
<main role="main" class="inner cover">
  <h1 class="cover-heading">Iniciar Sesión</h1>
  <br>
  <form class="form-signin" action="<?= BASE ?>user/logaction" method="post">
    <label for="inputUser" class="sr-only">Usuario</label>
    <input type="email" id="inputEmail" name="inputEmail" class="form-control" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;" placeholder="Email" value="<?= $email ?>" required autofocus>

    <label for="inputPassword" class="sr-only">Contraseña</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" style="border-top-left-radius:0px;border-top-right-radius:0px;" placeholder="Contraseña" required>
    <div class="checkbox mb-3">
      <label>
        <br>
        <input type="checkbox" value="true" name="remember-me"> Recordar correo
      </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="login-button">Iniciar Sesión</button>
  </form>
</main>

<?php

include 'footer.tpl.php';
?>