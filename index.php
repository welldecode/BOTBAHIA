<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
  header("Location: dash.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Entrar</title>
  <link rel="stylesheet" href="assets/css/login.css">
  <style>
    *{
      color: white; 
    }
  </style>
</head>

<body>
  <main>
    <section class="login">
      <div class="header">
        <div id="loginErro" class="alert alert-danger" role="alert"></div>
        <h1>Fazer login</h1>
        <span>Entre em sua conta pessoal.</span>
      </div>
      <form action="login.php" method="POST" id="loginForm">
        <input type="email" name="email" placeholder="E-mail">
        <input type="password" name="password" placeholder="Senha">
        <!-- <a class="forgot" href="#">Esqueceu a senha?</a> -->
        <button type="button" onclick="login()" class="btn-login">Entrar</button>
        <!-- <span>ou</span>
        <button class="btn-register">Criar uma conta</button> -->
      </form>
    </section>
    <a class="goutec" href="https://goutec.com.br/" target="_blank">GouTec Software</a>
  </main>
</body>

</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  function login() {
    var formData = new FormData($("#loginForm")[0]);

    $.ajax({
      method: "POST",
      url: 'login.php',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (res) {
        console.log('Resposta do servidor:', res);
        if (res.status === 'success') {
          $('#loginErro').hide();
          window.location.href = res.redirect;
        } else {
          $('#loginErro').text(res.message).show();
        }
      },
      error: function (err) {
        console.log('Erro na requisição AJAX:', err);
      }
    });
  }

</script>