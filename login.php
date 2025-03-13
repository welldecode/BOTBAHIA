<?php
session_start();
include '/var/www/html/class/class.login.php';
include '/var/www/html/class/class.session.php';

$sessionManager = new SessionManager();

// Verifica se o formulário de login foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    // Obtém os dados do formulário
    $email = $_POST["email"];
    $senha = $_POST["password"]; // Recebe a senha sem aplicar hash MD5

    $login = new Login();
    $stmtUsuario = $login->getUsuarioByEmail($email); // Modifique para buscar apenas pelo e-mail
    $user = $stmtUsuario->fetch(PDO::FETCH_ASSOC);
    if (($user && password_verify($senha, $user['senha_usuario'])) && $user['bloqueado'] == 0) { // Usa password_verify para comparar a senha
      $sessionManager->createSession($user['id'], $user);

      $response = [
        'status' => 'success',
        'redirect' => 'dash.php'
      ];
    } else if ($user['bloqueado'] == 1) {
      $response = [
        'status' => 'success',
        'redirect' => 'tela_bloqueado.php'
      ];
    } else {
      $response = [
        'status' => 'error',
        'message' => 'E-mail ou senha incorretos!'
      ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
  } catch (PDOException $e) {
    $response = [
      'status' => 'error',
      'message' => 'Erro ao fazer o login: ' . $e->getMessage()
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
  }
}