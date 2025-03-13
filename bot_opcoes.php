<?php
session_start();

include '/var/www/html/class/class.botopcoes.php';

$bot = new BotOpcoes();
$info_opcoes = $bot->getPerms()->fetchAll(PDO::FETCH_ASSOC);

$stmt = $bot->getPerms();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($dados[0]['status'] == 1) {
  $statusbot = 'checked';
} else {
  $statusbot = '';
}

if ($dados[1]['status'] == 1) {
  $initsystem = 'checked';
} else {
  $initsystem = '';
}

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';
$obj2 = new usuario();
if (!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))) {
  header("Location: tela_bloqueado.php");
  exit();
}

/* if ($_SESSION['cargo'] == 2) {
  header("Location: /index.php");
  exit();
} */
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
  <link rel="stylesheet" href="/assets/css/switch.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div style="display: flex; flex-direction: column;">
          <h2>Opções avançadas do BOT</h2>
          <div style="display: flex; flex-wrap: wrap; gap: 25px; margin-top: 30px;">
            <div class="group">
              <div class="toggle-switch">
                <input class="toggle-input" id="toggleStatus" type="checkbox" <?= $statusbot ?>>
                <label class="toggle-label" for="toggleStatus"></label>
              </div>
              <span>Ligar</span>&nbsp;BOT
            </div><br>
            <div class="group">
              <div class="toggle-switch">
                <input class="toggle-input" id="initsystem" type="checkbox" <?= $initsystem ?>>
                <label class="toggle-label" for="initsystem"></label>
              </div>
              <span>Inicialização do sistema</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <script>
    $(".switch-bot").click(function () {
      $(this).toggleClass("desativado");
      var buttonText = $(this).hasClass("desativado") ? "Desativado" : "Ativado";
      $(this).text(buttonText);
    });

    document.addEventListener('DOMContentLoaded', function () {
      document.getElementById("toggleStatus").addEventListener('change', () => {
        status = document.getElementById("toggleStatus").checked;
        var data = {
          status: status,
        };

        $.ajax({
          url: 'config_bot.php',
          type: 'POST',
          data: data,
          success: function (data) {
            if (data == 'started') {
              document.getElementById("toggleStatus").checked = true;
              updateUptime();
              window.location.reload();
            } else if (data == 'stopped') {
              document.getElementById("toggleStatus").checked = false;
              document.getElementById('dias').textContent = '00';
              document.getElementById('horas').textContent = '00';
              document.getElementById('minutos').textContent = '00';
              window.location.reload();
            }
          },
          error: function (xhr, status, error) {
            // Trate o erro aqui
          }
        });
      });
    });

    document.getElementById("initsystem").addEventListener('change', () => {
      initsystem = document.getElementById("initsystem").checked;
      var data = {
        status: initsystem,
      };

      $.ajax({
        url: 'ligarbot.php',
        type: 'POST',
        data: data,
        success: function (data) {
          if (data == 'started') {
            document.getElementById("initsystem").checked = true;
          } else if (data == 'stopped') {
            document.getElementById("initsystem").checked = false;
          }
          window.location.reload();
        },
        error: function (xhr, status, error) {
          window.location.reload();
        }
      });
    })
  </script>
</body>

</html>