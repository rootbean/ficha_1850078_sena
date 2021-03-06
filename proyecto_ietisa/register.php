<?php

$msg = "";

// Variables para guardar los datos del usuario a registrar en la base de datos
$nombres = '';
$email = '';
$password = '';
$repite_password = '';

if (
  isset($_POST['nombres']) &&
  isset($_POST['email']) &&
  isset($_POST['password']) &&
  isset($_POST['repite_password']) &&
  isset($_POST['terminos'])
) {

  if ($_POST['nombres'] == '') {
    $msg.= "El nombre es requerido <br>";     // $msg = $msg."El nombre es requerido <br>";
  }

  if ($_POST['email'] == '') {
    $msg.= "El email es requerido <br>";
  }

  if ($_POST['password'] == '') {
    $msg.= "La contraseña es requerida <br>";
  }

  if ($_POST['repite_password'] == '') {
    $msg.= "Debe repetir la contraseña! <br>";
  }

  $nombres = strip_tags($_POST['nombres']);
  $email = strip_tags($_POST['email']);
  $password = strip_tags($_POST['password']);
  $repite_password = strip_tags($_POST['repite_password']);

  if ($password != $repite_password) {
    $msg.="Las contraseñas no coinciden! <br>";
  } else if (strlen($password) < 8) {
    $msg.="La contraseña debe tener mínimo 8 caracteres <br>";
  } else {

    try {
      $connection_bd = new PDO('mysql:host=localhost; dbname=clase_ietisa', 'root', '');
      $connection_bd -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $connection_bd -> exec('SET CHARACTER SET utf8');

      $sql_users = "SELECT * FROM usuarios WHERE usuarios_email=?";
      $result_query = $connection_bd->prepare($sql_users);

      $result_query -> execute(array($email));

      $result_query = $result_query->fetchAll(PDO:: FETCH_ASSOC);

      $cantidad_usuarios = count($result_query);

      if ($cantidad_usuarios == 0) {
        $password = sha1($password);
  
        $insert_usuario = "INSERT INTO usuarios(usuarios_nombres, usuarios_email, usuarios_password) VALUES (?, ?, ?)";
        
        $result_insert = $connection_bd->prepare($insert_usuario);
        $result_insert->execute(array($nombres, $email, $password));

        $msg.="Usuario registrado correctamente! <br>";
      
      } else {
        $msg.="El email ya se encuentra registrado! <br>";
      }

    } catch (Exception $e) {
      die('Error: '.$e->GetMessage());

    }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Registration Page</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="../../index2.html"><b>Admin</b>LTE</a>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Registro de Usuarios</p>

      <form action="register.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Nombres" name="nombres">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Repite password" name="repite_password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terminos" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Registro</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p> <?php echo $msg ?>  </p>
      <a href="login.php" class="text-center">Iniciar sesión</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
</body>
</html>
