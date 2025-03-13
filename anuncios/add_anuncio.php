<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.anuncio.php';
$obj = new Anuncio();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_POST["titulo"] != "" && $_POST["subtitulo"] != "" && $_POST["conteudo"] != "") {
    $ativo = $_POST["toggleStatus"];
    $titulo = $_POST["titulo"];
    $subtitulo = $_POST["subtitulo"];
    $conteudo = $_POST["conteudo"];

    $stmt = $obj->insertAnuncio($titulo, $subtitulo, $conteudo, $ativo);

    $_SESSION['mensagem'] = "Anúncio criado com sucesso!";
    $_SESSION['mensagem_tipo'] = "success";
    header("Location: index.php");
    exit();
  } else {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    $_SESSION['mensagem_tipo'] = "erro";
    header("Location: add_anuncio.php");
    exit();
  }
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
  <link rel="stylesheet" href="/assets/css/switch.css">
</head>

<body>
  <?php include '/var/www/html/includes/alert.php'; ?>
  <div class="authenticated-layout">
    <?php include '/var/www/html/includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="app-background -top-position -dark"><img src="//assets/default.060da614.jpg" alt=""></div>
        <div class="dashboard-route__first-group">
          <form class="card-panel" action="add_anuncio.php" method="POST" style="width: auto;">
            <div style="margin-bottom: 20px;border-bottom: 1px dashed gray;padding-bottom: 16px;">CRIAR NOVO ANÚNCIO
            </div>
            <div class="group" style="margin: -10px 0px 10px -10px;">
              <div class="toggle-switch">
                <input class="toggle-input" id="toggleStatus" name="toggleStatus" type="checkbox" checked>
                <label class="toggle-label" for="toggleStatus"></label>
              </div>
              <label for="toggleStatus">Ativar/desativar o anúncio</label>
            </div>
            <div class="form-group">
              <label for="titulo">Título do anúncio</label>
              <input type="text" name="titulo" id="titulo">
              <span>Insira um título de até 35 caracteres</span>
            </div>
            <div class="form-group">
              <label for="subtitulo">Subtítulo</label>
              <input type="text" name="subtitulo" id="subtitulo">
              <span>Insira um subtítulo de até 75 caracteres</span>
            </div>
            <div class="form-group">
              <label for="conteudo">Conteúdo</label>
              <textarea name="conteudo" id="conteudo" maxlength="450" cols="60" rows="5"></textarea>
              <span>Insira um conteúdo de até 450 caracteres</span>
            </div>
            <button type="submit" class="btn-primary">Criar anúncio</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>