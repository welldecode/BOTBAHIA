<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';
include '/var/www/html/class/class.credenciais.php';

$obj_credenciais = new credenciais();

$obj2 = new usuario();

if (!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))) {
  header("Location: tela_bloqueado.php");
  exit();
}

$dados_usuario = $obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
$status_bot_pessoal = $obj_credenciais->getInfo($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);

if($status_bot_pessoal){
  if ($status_bot_pessoal['ativo_dmarket'] == 1) {
    $statusbot = 'checked';
  } else {
    $statusbot = '';
  }
  $credenciais_errada = 0;
}else{
  $credenciais_errada = 1;
}

// Função para ler o uptime do arquivo JSON
function getUptime()
{
  $uptimeFile = '/var/www/html/uptime.json';
  if (file_exists($uptimeFile)) {
    $uptimeData = json_decode(file_get_contents($uptimeFile), true);
    return time() - $uptimeData['start_time'];
  }
  return 0;
}

$uptime = getUptime();
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
  <link rel="stylesheet" href="assets/css/switch.css">
  <link rel="stylesheet" href="/assets/css/temas.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div style="justify-content: flex-start; gap: 40px" class="dashboard-route__first-group">
          <?php if($credenciais_errada == 0) {?>
          <div class="card-panel" style="position: relative">
            <!-- <div class="overlay-breve" style="height: calc(100% - 174px); top: 200px">
              <h1>EM BREVE</h1>
            </div> -->
            <div style="display: flex; gap: 10px; margin-left: 10px">
              <img src="/assets/gear-22px.ff194763.svg" alt="" style="transform: rotate(60deg);">
              <h3>Configurações gerais do BOT</h3>
            </div>
            <br>

            <div class="group">
              <div class="toggle-switch">
                <input class="toggle-input" id="toggleStatus" type="checkbox" <?= $statusbot ?>>
                <label class="toggle-label" for="toggleStatus"></label>
              </div>
              <span>Ligar</span>&nbsp;BOT
            </div><br>

            <div class="group">
              <form action="gerenciar/alterar_valor_maxminbot.php" style="display: flex; align-items: center; gap: 8px;" method="POST">
                <!-- <p>Defina o intervalo de preços para o bot do DMarket. Insira os valores em USD, sem vírgulas ou pontos (ex: $5 para 5 dólares e $100 para 100 dólares).</p> -->
                <label for="min-value" style="margin-right: 4px;">De:</label>
                <input type="number" id="min-value" name="min-value"
                  style="padding: 4px 8px; margin-right: 8px; width: 110px; color: black;" value="<?= $dados_usuario['min_price_dmarket']?>" required>

                <label for="max-value" style="margin-right: 4px;">Até:</label>
                <input type="number" id="max-value" name="max-value"
                  style="padding: 4px 8px; margin-right: 8px; width: 110px; color: black;" value="<?= $dados_usuario['max_price_dmarket']?>" required>

                <button type="submit" style="padding: 4px 8px; color: black;">Salvar</button>
              </form>
            </div><br><br>

            <!-- <div class="group">
              <div class="toggle-switch">
                <input class="toggle-input" id="toggleAutoBuy" type="checkbox">
                <label class="toggle-label" for="toggleAutoBuy"></label>
              </div>
              <span id="toggleAtivar">Ativar</span>&nbsp;compras automáticas
            </div>

            <div class="group">
              <div class="toggle-switch">
                <input class="toggle-input" id="toggleDmarketBuy" type="checkbox" checked>
                <label class="toggle-label" for="toggleDmarketBuy"></label>
              </div>
              <span id="toggleDesativar">Desativar</span>&nbsp;compras em DMarket
            </div>

            <div class="group">
              <div class="toggle-switch">
                <input class="toggle-input" id="toggleBuffBuy" type="checkbox">
                <label class="toggle-label" for="toggleBuffBuy"></label>
              </div>
              <span id="toggleBuff">Ativar</span>&nbsp;compras em Buff
            </div>

            <div class="group">
              <div class="group-side">
                <div class="group-input">
                  <div class="group">
                    <div class="toggle-switch">
                      <input class="toggle-input" id="toggleBuyLimit" type="checkbox">
                      <label class="toggle-label" for="toggleBuyLimit"></label>
                    </div>
                    <span>Limite de compras de skins</span>
                  </div>
                  <div class="group-input-p">
                    <span>Selecione uma opção:</span>
                    <select name="opcao" id="opcao-currency">
                      <option value="opcao1" selected disabled>Selecione</option>
                      <option value="usd">Dólar USD</option>
                      <option value="eur">Euro EUR</option>
                      <option value="brl">Real BRL</option>
                    </select>
                    <div id="usd-currency" class="group-input-body" style="margin-top: 10px; display: none;">
                      <span>Dólar USD ($):</span>
                      <div class="group-input-item">
                        <label for="eurmin">Valor mínimo</label>
                        <input type="number" value="25">
                      </div>
                      <div class="group-input-item">
                        <label for="eurmax">Valor máximo</label>
                        <input type="number" value="2500">
                      </div>
                      <button class="btn-primary">Aplicar</button>
                    </div>
                    
                    <div id="eur-currency" class="group-input-body" style="margin-top: 10px; display: none;">
                      <span>Euro EUR (€):</span>
                      <div class="group-input-item">
                        <label for="eurmin">Valor mínimo</label>
                        <input type="number" value="25">
                      </div>
                      <div class="group-input-item">
                        <label for="eurmax">Valor máximo</label>
                        <input type="number" value="2500">
                      </div>
                      <button class="btn-primary">Aplicar</button>
                    </div>
                    <div id="brl-currency" class="group-input-body" style="margin-top: 10px; display: none;">
                      <span>Real BRL (R$):</span>
                      <div class="group-input-item">
                        <label for="brlmin">Valor mínimo</label>
                        <input type="number" value="25">
                      </div>
                      <div class="group-input-item">
                        <label for="brlmax">Valor máximo</label>
                        <input type="number" value="2500">
                      </div>
                      <button class="btn-primary">Aplicar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
          <?php }else { ?>
            <h3 style="margin-top: 100px;">Atualize suas credenciais para configurar seu bot</h3><br>
          <?php } ?>

          <div style="margin-top: 40px" class="dashboard-route__1-dollar">
            <p class="dashboard-route__1-dollar-label caption-1 mobile-body-5">API</p>
            <p class="dashboard-route__1-dollar-title title-3 mobile-heading-3">Uptime do Bot</p>
            <div class="dashboard-route__1-dollar-widgets" style="position: relative;">
              <div class="dollar-day-timer -vertical">
                <div class="dollar-day-timer__timer">
                  <div class="dollar-day-timer__timer-col">
                    <p class="dollar-day-timer__timer-col-value --basement-grotesque-font" id="dias">00</p>
                    <p class="dollar-day-timer__timer-col-title caption-1">Dias</p>
                  </div>
                  <div class="dollar-day-timer__timer-col">
                    <p class="dollar-day-timer__timer-col-value --basement-grotesque-font">:</p>
                  </div>
                  <div class="dollar-day-timer__timer-col">
                    <p class="dollar-day-timer__timer-col-value --basement-grotesque-font" id="horas">00</p>
                    <p class="dollar-day-timer__timer-col-title caption-1">Horas</p>
                  </div>
                  <div class="dollar-day-timer__timer-col">
                    <p class="dollar-day-timer__timer-col-value --basement-grotesque-font">:</p>
                  </div>
                  <div class="dollar-day-timer__timer-col">
                    <p class="dollar-day-timer__timer-col-value --basement-grotesque-font" id="minutos">00</p>
                    <p class="dollar-day-timer__timer-col-title caption-1">Minutos</p>
                  </div>
                </div>
                <p class="dollar-day-timer__title mobile-body-3 --basement-grotesque-font caption-1">Qualidade ótima</p>
              </div>
              <!-- <div class="app-info-widget -left-top dollar-day-widget-total-winners"><img
                  src="/assets/cup-violet-31px.28185a17.svg" alt="" class="app-info-widget__icon">
                <div class="app-info-widget__info">
                  <p class="app-info-widget__info-title subhead-1">Karambit</p>
                  <p class="app-info-widget__info-value headline-1 --basement-grotesque-font">45 vendas<span
                      class="subhead-2 mobile-body-3 --default-font"></span></p>
                </div>
              </div>
              <div class="app-info-widget -left-bottom dollar-day-widget-amount-paid"><img
                  src="/assets/dollar-coin-violet-30px.ed43d0ac.svg" alt="" class="app-info-widget__icon">
                <div class="app-info-widget__info">
                  <p class="app-info-widget__info-title subhead-1">Karambit</p>
                  <p class="app-info-widget__info-value headline-1 --basement-grotesque-font">R$7.456 <span
                      class="subhead-2 mobile-body-3 --default-font"></span></p>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script>
    function updateUptime() {
      let uptime = <?php echo $uptime; ?>;

      function formatTime() {
        const dias = Math.floor(uptime / 86400);
        const horas = Math.floor((uptime % 86400) / 3600);
        const minutos = Math.floor((uptime % 3600) / 60);

        document.getElementById('dias').textContent = dias.toString().padStart(2, '0');
        document.getElementById('horas').textContent = horas.toString().padStart(2, '0');
        document.getElementById('minutos').textContent = minutos.toString().padStart(2, '0');
      }

      formatTime();
      setInterval(() => {
        uptime++;
        formatTime();
      }, 1000);
    }

    document.addEventListener('DOMContentLoaded', function () {
      updateUptime();

      document.getElementById("toggleStatus").addEventListener('change', () => {
        status = document.getElementById("toggleStatus").checked;
        var data = {
          status: status,
        };

        $.ajax({
          url: 'gerenciar/ligarbotpessoal.php',
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

    /* document.getElementById("toggleStatus").addEventListener('change', () => {
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
          } else if (data == 'stopped') {
            document.getElementById("toggleStatus").checked = false;
          }
          window.location.reload();
        },
        error: function (xhr, status, error) {
          window.location.reload();
        }
      });
    }) */

    document.addEventListener('DOMContentLoaded', function () {
      const toggleBuyLimit = document.getElementById('toggleBuyLimit');
      const groupInputP = document.querySelector('.group-input-p');

      if (toggleBuyLimit.checked) {
        groupInputP.style.display = 'flex';
      } else {
        groupInputP.style.display = 'none';
      }

      toggleBuyLimit.addEventListener('change', function () {
        if (toggleBuyLimit.checked) {
          groupInputP.style.display = 'flex';
        } else {
          groupInputP.style.display = 'none';
        }
      });
    });


    const toggleBuyLimit = document.getElementById('toggleBuyLimit');
    const groupInputP = document.querySelector('.group-input-p');
    const selectCurrency = document.getElementById('opcao-currency');
    const usdCurrency = document.getElementById('usd-currency');
    const eurCurrency = document.getElementById('eur-currency');
    const brlCurrency = document.getElementById('brl-currency');

    if (toggleBuyLimit.checked) {
      groupInputP.style.display = 'flex';
    } else {
      groupInputP.style.display = 'none';
    }

    toggleBuyLimit.addEventListener('change', function () {
      if (toggleBuyLimit.checked) {
        groupInputP.style.display = 'flex';
      } else {
        groupInputP.style.display = 'none';
      }
    });

    selectCurrency.addEventListener('change', function () {
      if (selectCurrency.value === 'usd') {
        usdCurrency.style.display = 'flex';
        eurCurrency.style.display = 'none';
        brlCurrency.style.display = 'none';
      } else if (selectCurrency.value === 'eur') {
        usdCurrency.style.display = 'none';
        brlCurrency.style.display = 'none';
        eurCurrency.style.display = 'flex';
      } else if (selectCurrency.value === 'brl') {
        usdCurrency.style.display = 'none';
        eurCurrency.style.display = 'none';
        brlCurrency.style.display = 'flex';
      } else {
        usdCurrency.style.display = 'none';
        eurCurrency.style.display = 'none';
        brlCurrency.style.display = 'none';
      }
    });
  </script>
</body>

</html>