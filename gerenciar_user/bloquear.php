<?php
session_start();

if ($_SESSION['cargo'] == 2) {
  header("Location: /index.php");
  exit();
}

if (!isset($_SESSION['usuario_id'])) {
  header("Location: index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';
include '/var/www/html/class/class.credenciais.php';

$id_usuario = (int) $_GET['id'];

$obj = new usuario();
$obj_credenciais = new credenciais();
$usuario = $obj->getUsuariosID($id_usuario);
$usuarios = $usuario->fetch(PDO::FETCH_ASSOC);

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $delete = $obj->bloquearUsuarios($id_usuario);
  $obj_credenciais->updateBloqueado($id_usuario);
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/content.css">
  <link rel="stylesheet" href="/assets/css/effect-fade.css">
  <link rel="stylesheet" href="/assets/css/swiper.min.css">
  <link rel="stylesheet" href="/assets/css/app-dollar.css">
  <link rel="stylesheet" href="/assets/css/app-tournaments.css">
  <link rel="stylesheet" href="/assets/css/background.css">
  <link rel="stylesheet" href="/assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="/assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="/assets/css/cup-violet.css">
  <link rel="stylesheet" href="/assets/css/tabela.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include '../includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="app-background -top-position -dark"><img src="/assets/default.060da614.jpg" alt=""></div>
        <div class="dashboard-route__first-group">
          <form style="width: 100%" class="card-panel" action="bloquear.php?id=<?= $id_usuario?>" method="POST">
            <div
              style="margin-bottom: 20px;border-bottom: 1px dashed gray;padding-bottom: 16px;display: flex;align-items: center;gap: 7px;">
              <a style="cursor: pointer;" onclick="window.history.back()"><img src="/assets/left-arrow.svg"
                  alt=""></a>BLOQUEAR USUÁRIO
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px">
              <div style="margin-right: 10px;">
                <p>Deseja realmente bloquear o usuário <?= $usuarios['nome_usuario'] ?>?</p>
                <br>
                <button type="submit" id="deletar" class="btn-bloquear">Bloquear usuário</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>