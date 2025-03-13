<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '/var/www/html/class/class.usuario.php';

$usuario = new usuario();

$email_token = $_GET['token'];

if ($email_token !== null) {
  $stmt = $usuario->verificaEmail($email_token);
  $confirmado = $stmt->fetch(PDO::FETCH_ASSOC);
  
  if ($confirmado) {
    $usuario->updateEmailVerificado($email_token);
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Obrigado!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #242949;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 40px;
        }

        a {
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid #fff;
        }

        a:hover {
            color: #fff;
            border-bottom: 1px solid transparent;
        }

        .aviso {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
    <link rel="stylesheet" href="/menu/assets/css/modo_dark.css">
</head>

<body>
    <div class="aviso">
        <h1>Email confirmado com sucesso!</h1>
        <p>Aguarde, você será redirecionado em <span id="five">3</span> segundos.</p>
    </div>
    <script>
      five = document.getElementById("five");
      setInterval(function() {
        five.textContent -= 1;
      }, 1000)

      setTimeout(function() {
        window.location.href = 'http://66.70.170.171/'
      }, 3000)
    </script>
</body>
</html>