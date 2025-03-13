<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';
$obj2 = new usuario();
if(!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))){
  header("Location: tela_bloqueado.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $obj2 = new usuario();
  $nome = $_POST['usuario'];
  $sen = $_POST['senha'];
  $senha = md5($sen);
  $email = $_POST['email'];
  $nome_completo = $_POST['nome_completo'];

  $obj2 = $obj2->addUsuario($nome_completo, $nome, $senha, $email);

  header("Location: gerenciar_user/index.php");
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
  <link rel="stylesheet" href="/assets/css/temas.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="dashboard-route__first-group">
          <div class="card-panel">
            <h3>Sites mais comprados</h3>
            <div class="card-body">
              <canvas id="doughnutChart" class="chartjs mb-4" data-height="350" height="408"
                style="display: block; box-sizing: border-box; height: 408px; width: 408px; margin: 20px 0px;"
                width="408"></canvas>
              <ul class="grafico1">
                <li class="ct-series-0 item">
                  <h3 class="mb-0">BUFF</h3>
                  <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                    style="background-color: rgb(102, 110, 232);width:35px; height:6px;"></span>
                  <div class="text-muted">80 %</div>
                </li>
                <li class="ct-series-1 item">
                  <h3 class="mb-0">DMarket</h3>
                  <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                    style="background-color: rgb(40, 208, 148);width:35px; height:6px;"></span>
                  <div class="text-muted">10 %</div>
                </li>
                <li class="ct-series-2 item">
                  <h3 class="mb-0">Goutec</h3>
                  <span class="badge badge-dot my-2 cursor-pointer rounded-pill"
                    style="background-color: rgb(253, 172, 52);width:35px; height:6px;"></span>
                  <div class="text-muted">10 %</div>
                </li>
              </ul>
            </div>
          </div>

          <div class="card-panel viewport-graph">
            <div class="card-header">
              <h3 class="card-title mb-0">Estatísticaaas</h3>
            </div>
            <div class="card-body pt-2">
              <canvas class="chartjs" id="radarChart" data-height="700" height="1408"
                style="display: block; box-sizing: border-box; height: 510px; width: 644px;" width="644"></canvas>
            </div>
          </div>
        </div>

        <div class="dashboard-route__second_group">
        <div class="card-panel">
            <div class="card-header header-elements pb-2">
              <div class="d-flex flex-column">
                <h3 class="card-title mb-1">Saldo</h3>
                <p class="text-muted mb-0">R$74.123,00</p>
              </div>
              <div class="card-action-element ms-auto py-0">
              </div>
              <div class="card-body">
                <canvas id="horizontalBarChart" class="chartjs" data-height="400" height="400"
                  style="display: block; box-sizing: border-box; height: 400px; width: 644px;" width="644"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="/assets/js/chartjs.js"></script>
    <script src="/assets/js/charts-chartjs.js"></script>
</body>

</html>