<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

if ($_SESSION['cargo'] == 2) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.infobot.php';
include '/var/www/html/class/class.usuario.php';

$obj_bot = new infobot();
$obj2 = new usuario();
if(!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))){
  header("Location: tela_bloqueado.php");
  exit();
}

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';
require 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $obj2 = new usuario();
  $nome = $_POST['usuario'];
  $sen = $_POST['senha'];
  $senha = password_hash($sen, PASSWORD_DEFAULT); // Utilizando a função password_hash para uma criptografia mais segura
  $email = $_POST['email'];
  $nome_completo = $_POST['nome_completo'];
  $cargo = (int)$_POST['tipo_usuario'];

  $email_token = $obj2->generateEmailToken();

  $obj2->addUsuario($nome_completo, $nome, $senha, $email, $email_token, $cargo);
  $lastinsert = $obj2->lastinsert()->fetch(PDO::FETCH_ASSOC);
  $lastinsertID = $lastinsert['id_usuario'];
  $obj_bot->addUser($lastinsertID);
  $obj_bot->initDepositos($lastinsertID);

  try {
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    // Configurações do servidor SMTP da Goutec
    $mail->isSMTP();
    $mail->Host = 'mail.goutec.com.br'; // Servidor SMTP da Goutec
    $mail->SMTPAuth = true; // Ativa a autenticação SMTP
    $mail->Username = 'smtp@goutec.com.br'; // Nome de usuário SMTP
    $mail->Password = 'Amorim@123'; // Senha SMTP
    $mail->SMTPSecure = 'ssl'; // Use SSL
    $mail->Port = 465; // Porta para SSL
    error_log("SMTP Configurations set for Goutec");

    // Remetentes e destinatários
    $mail->setFrom('smtp@goutec.com.br', 'Projeto BOT'); // Mudar para seu endereço de e-mail e nome
    $mail->addAddress($email); // O e-mail do destinatário é o e-mail recebido do POST
    error_log("Sender and Recipient set");

    // Conteúdo do e-mail
    $mail->isHTML(true);
    $mail->Subject = 'Verifique seu email';
    $mail->Body = 'Clique para verificar seu email <a style="display: block;
    background: #4c579d;
    text-decoration: none;
    color: white;
    padding: 2px 10px;
    border-radius: 5px;
    width: fit-content;
    margin: 8px 0;" href="http://66.70.170.171/verificar_email?token=' . $email_token . '">Confirmar Email</a> ou acesse o link abaixo:
    <a style="display: block;" href="http://66.70.170.171/verificar_email?token=' . $email_token . '>http://66.70.170.171/verificar_email?token=' . $email_token . '</a>';
    error_log("Email body and subject set");

    $mail->send();
  } catch (\Throwable $e) {
    error_log("Error: " . $e->getMessage());
    error_log("Trace: " . $e->getTraceAsString());
    echo "Erro: " . $e->getMessage();
  }


  $_SESSION['mensagem'] = "A conta foi criada com sucesso!";
  $_SESSION['mensagem_tipo'] = "success";
  // Redirecionar para a página de criar conta
  header("Location: criar_conta.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/content.css">
  <link rel="stylesheet" href="assets/css/effect-fade.css">
  <link rel="stylesheet" href="assets/css/swiper.min.css">
  <link rel="stylesheet" href="assets/css/app-dollar.css">
  <link rel="stylesheet" href="assets/css/app-tournaments.css">
  <link rel="stylesheet" href="assets/css/background.css">
  <link rel="stylesheet" href="assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="assets/css/cup-violet.css">
  <link rel="stylesheet" href="assets/css/temas.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="dashboard-route__first-group">
          <form class="card-panel" action="criar_conta.php" method="POST">
            <div style="margin-bottom: 20px;border-bottom: 1px dashed gray;padding-bottom: 16px;">CRIAR NOVO USUÁRIO
            </div>
            <div class="form-group">
              <label for="">Nome Completo</label>
              <input type="text" name="nome_completo">
              <span>Insira um nome completo do usuário</span>
            </div>
            <div class="form-group">
              <label for="">Usuário</label>
              <input type="text" name="usuario">
              <span>Insira um nome de usuário</span>
            </div>
            <div class="form-group">
              <label for="">Email</label>
              <input type="email" name="email">
              <span>Email para logar e recuperar acesso</span>
            </div>
            <div class="form-group">
              <label for="">Senha</label>
              <input type="password" name="senha">
              <span>Senha de 8 dígitos</span>
            </div>
            <div class="form-group">
              <label for="">Confirme sua senha</label>
              <input type="password" name="confirmar_senha">
              <span>Repita sua senha</span>
            </div>
            <div class="form-group">
              <label for="">Tipo de Usuário</label>
              <select name="tipo_usuario">
                <option value="1">CEO</option>
                <option value="2" selected>Assinante</option>
              </select>
              <span>Selecione o tipo de usuário</span>
            </div>
            <button class="btn-primary">Criar conta</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>

</html>