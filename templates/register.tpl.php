<?php
include 'base.tpl.php';
?>
<main role="main" class="inner cover">
  <h1 class="cover-heading">Registro</h1>
  <br>
  <form class="form-signin" action="<?= BASE ?>user/regaction" method="post">
    <label for="inputUser" class="sr-only">Usuario</label>
    <input type="text" id="inputUser" name="inputUser" class="form-control" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;" placeholder="Usuario" required autofocus>

    <label for="inputEmail" class="sr-only">Correo Electrónico</label>
    <input type="email" id="inputEmail" name="inputEmail" class="form-control" style="border-radius:0px;" placeholder="Correo Electrónico" required autofocus>

    <label for="inputPassword" class="sr-only">Contraseña</label>
    <input type="password" id="inputPassword" name="inputPassword" class="form-control" style="border-radius:0px;" placeholder="Contraseña" required>
    <label for="inputPasswordRepeat" class="sr-only">Contraseña</label>
    <input type="password" id="inputPasswordRepeat" name="inputPasswordRepeat" class="form-control" style="border-top-left-radius:0px;border-top-right-radius:0px;"placeholder="Repetir Contraseña" required>
<br>
    <button class="btn btn-lg btn-primary btn-block" type="submit" name="register-button">Registrarse</button>
</form>
</main>

<?php

include 'footer.tpl.php';
?>