<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

if (!isset($_GET["id"])) {
  $_SESSION['mensagem'] = "Erro ao apagar, informe o ID do anúncio!";
  $_SESSION['mensagem_tipo'] = "erro";
  header("Location: index.php");
  exit();
}

include '/var/www/html/class/class.anuncio.php';

$id = $_GET['id'];

$obj = new Anuncio();
$stmt = $obj->getAnuncio($id);
$anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $obj->delAnuncio($id);

  $_SESSION['mensagem'] = "Anúncio deletado com sucesso!";
  $_SESSION['mensagem_tipo'] = "success";
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar anúncio</title>
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
      <?php include '/var/www/html/includes/sidebar.php'; ?>
      <div class="authenticated-layout__content">
        <div class="dashboard-route">
          <div class="app-background -top-position -dark"><img src="/assets/default.060da614.jpg" alt=""></div>
          <div class="dashboard-route__first-group">
            <form style="width: 100%" class="card-panel" action="deletar.php?id=<?= $id ?>" method="POST">
              <div
                style="margin-bottom: 20px;border-bottom: 1px dashed gray;padding-bottom: 16px;display: flex;align-items: center;gap: 7px;">
                <a style="cursor: pointer;" onclick="window.history.back()"><img src="/assets/left-arrow.svg"
                    alt=""></a>DELETAR ANÚNCIO
              </div>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px">
                <div style="margin-right: 10px;">
                  <p>Deseja realmente deletar o anúncio <?= $anuncio['titulo'] ?>?<br><br><strong>ATENÇÃO:</strong> O anúncio será deletado do banco de dados.</p>
                  <br>
                  <button type="submit" id="deletar" class="btn-deletar">Deletar anúncio</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>