<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

include '/var/www/html/class/class.historico_dmarket.php';
include '/var/www/html/class/class.usuario.php';
include '/var/www/html/class/class.credenciais.php';
include '/var/www/html/class/class.config.php';
include '/var/www/html/credenciais/encryption_functions.php';
include '/var/www/html/class/class.infobot.php';

$id_usuario = $_GET['id_usuario'];

$obj2 = new usuario();
$obj_infobot = new infobot();
$obj_credenciais = new credenciais();
$historico = new historico_dmarket();

// Recupera as credenciais
$cred_info = $obj_credenciais->getInfo($id_usuario)->fetch(PDO::FETCH_ASSOC);

$dados_usuario = $obj2->getUsuariosID($id_usuario)->fetch(PDO::FETCH_ASSOC);

$primeiro_deposito = $historico->getTransacaoMaisAntiga($id_usuario)->fetch(PDO::FETCH_ASSOC);

if ($primeiro_deposito) {
  $data_deposito = new DateTime($primeiro_deposito['data_transacao']);
}

// Verifica se o usuário existe
if (!($obj2->getUsuariosID($id_usuario)->fetch(PDO::FETCH_ASSOC))) {
  header("Location: tela_bloqueado.php");
  exit();
}

if ($cred_info) { //  se o usuario tiver credenciais cadastradas 
  $info_dmarket = $obj_infobot->getInfoUser($id_usuario)->fetch(PDO::FETCH_ASSOC);
  $obj_config = new config();

  if (isset($info_dmarket['nome']) == NULL) {
    $verify_dmarket = 0;
  } else {
    $verify_dmarket = 1;
  }


  $inventario_venda_json = $historico->getInventarioJsonVendas($id_usuario)->fetch(PDO::FETCH_ASSOC);
  if($inventario_venda_json){
    $valor_inventario_venda = $inventario_venda_json['valor_inventario'];
  }else{
    $valor_inventario_venda = 0;
  }

  $inventario_compra_json = $historico->getInventarioJsonCompras($id_usuario)->fetch(PDO::FETCH_ASSOC);
  if($inventario_compra_json){
    $valor_inventario_compra = $inventario_compra_json['valor_inventario'];
  }else{
    $valor_inventario_compra = 0;
  }

  $info_dmarket = $obj_infobot->getInfoUser($id_usuario)->fetch(PDO::FETCH_ASSOC);

  $valor_depositado = $historico->getTotalDepositado($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  if ($valor_depositado == null) {
    $valor_depositado = 0;
  }

  $lucro_mes = $historico->totalVendasUltimoMes($id_usuario)->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_3mes = $historico->totalVendasUltimos3Meses($id_usuario)->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_6mes = $historico->totalVendasUltimos6Meses($id_usuario)->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_ano = $historico->totalVendasUltimoAno($id_usuario)->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_geral = $historico->totalVendas($id_usuario)->fetchAll(PDO::FETCH_ASSOC)[0]['total'];

  $lucro_semanals = $historico->totalVendasSemanal($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_semanalp = $historico->totalComprasSemanal($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_semanal = $lucro_semanals + ($lucro_semanalp * -1);

  $lucro_mensals = $historico->totalVendasMensal($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_mensalp = $historico->totalComprasMensal($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_mensal = $lucro_mensals + ($lucro_mensalp * -1);

  $lucro_diarios = $historico->totalVendasDiario($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_diariop = $historico->totalComprasDiario($id_usuario)->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_diario = $lucro_diarios + ($lucro_diariop * -1);

  $lucro_total = (float) ($valor_inventario_compra + $valor_inventario_venda) - $valor_depositado + $info_dmarket['usd'];

  $cotacao_dolar = (float) $obj_config->getCotacaoDolar()->fetch(PDO::FETCH_ASSOC)['key'];
  $cotacao_euro = (float) $obj_config->getCotacaoEuro()->fetch(PDO::FETCH_ASSOC)['key'];
} else {
  $verify_dmarket = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Informações Gerais</title>
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
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-duotone-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-thin.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-solid.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-regular.css">
  <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/sharp-light.css">


  <style>
    .info,
    .tabelas {
      background: #1E1E1E;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      margin-bottom: 20px;
    }

    .currency-selected {
      background-color: #007bff;
      color: white;
      font-weight: bold;
      padding: 5px 10px;
      border-radius: 5px;
    }

    label {
      cursor: pointer;
      padding: 5px 10px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    label:hover {
      background-color: #007bff;
    }

    .info h2,
    .tabelas h2 {
      font-size: 20px;
      margin-bottom: 10px;
      color: #E0E0E0;
    }

    .info h3 {
      font-size: 18px;
      color: #B0B0B0;
      margin: 10px 0;
    }

    .lucratividade {
      color: #27ae60;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 15px;
      border-bottom: 1px solid #333;
      text-align: left;
    }

    th {
      background-color: #161616;
      color: #E0E0E0;
    }

    td {
      text-align: start !important;
      background-color: #2C2C2C;
    }

    span {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route" style="margin-bottom: 20px">
        <h1 class="dashboard-route__second-group">INFORMAÇÕES DO PERFIL</h1><br><br>
      </div>
      <?php if ($verify_dmarket == 1) { ?>
        <section class="profile-overview">
          <header class="card-header">
          </header>
          <div class="profile-header" style="display: flex;">
            <div class="avatar-image">
              <img src="<?= $dados_usuario['avatar']; ?>" alt="Avatar"
                style="width: 130px;height: 130px;object-fit: cover;">
            </div>
            <div style="display: flex; flex-direction: column;">
              <h1 class="--basement-grotesque-font" style="padding: 20px 30px"><?= $dados_usuario['nome_usuario']; ?>
              </h1>
              <div class="card" style="margin-left: 28px;">
                <div class="card-content">
                  <p><span>Cargo:</span> <?= ($dados_usuario['cargo_usuario'] == 1) ? "CEO" : "Assinante"; ?></p>
                  <p><span>Nome:</span> <?= $dados_usuario['nome_completo']; ?></p><br><br>
                </div>
              </div>
            </div>
            <br><br>
          </div>
        </section><br>
        <div id="retirado" class="info">
          <h3>Escolha em qual moeda deseja visualizar os valores:</h3>
          <div id="currency-selector">
            <label><input type="radio" name="currency" value="usd" checked> USD</label>
            <label><input type="radio" name="currency" value="brl"> BRL</label>
            <label><input type="radio" name="currency" value="eur"> EUR</label>
          </div><br><br>
          <h2>Informações Gerais de Saldo</h2>
          <div class="currency-display" data-currency="usd">
            <h3>Valor total depositado: <span class="lucratividade">$
                <?= $valor_depositado == null ? "0,00" : number_format($valor_depositado, 2, ',', '.') ?></span>
            </h3>
          </div>
          <div class="currency-display" data-currency="brl" style="display:none;">
            <h3>Valor total depositado: <span class="lucratividade">R$
                <?= $valor_depositado == null ? "0,00" : number_format(($valor_depositado * $cotacao_dolar), 2, ',', '.') ?></span>
            </h3>
          </div>
          <div class="currency-display" data-currency="eur" style="display:none;">
            <h3>Valor total depositado: <span class="lucratividade">€
                <?= $valor_depositado == null ? "0,00" : number_format(($valor_depositado * $cotacao_dolar) / $cotacao_euro, 2, ',', '.') ?></span>
            </h3>
          </div>
          <div class="currency-display" data-currency="usd">
            <h3>Valor estimado do inventário à venda: <span class="lucratividade">$
                <?= number_format(($valor_inventario_venda), 2, ',', '.') ?></span></h3>
          </div>
          <div class="currency-display" data-currency="brl" style="display:none;">
            <h3>Valor estimado do inventário à venda: <span class="lucratividade">R$
                <?= number_format(($valor_inventario_venda * $cotacao_dolar), 2, ',', '.') ?></span></h3>
          </div>
          <div class="currency-display" data-currency="eur" style="display:none;">
            <h3>Valor estimado do inventário à venda: <span class="lucratividade">€
                <?= number_format(($valor_inventario_venda * $cotacao_dolar) / $cotacao_euro, 2, ',', '.') ?></span></h3>
          </div>
          <br><?php if ($primeiro_deposito): ?>
            <p><span>Primeira transação em: </span> <?= $data_deposito->format('d/m/Y') ?></p>
          <?php else: ?>
            <p>O usuário ainda não fez a primeira transação.</p>
          <?php endif; ?>
        </div>

        <div id="lucro" class="tabelas">
          <h2>Informações de Lucro <span
              style="color: white; background: #c5564a; margin-left: 10px; font-size: 13px; font-weight: 400; padding: 4px 6px; border-radius: 14px;">Se
              o dono do perfil tiver depositado algum valor na conta do DMarket recentemente, os lucros podem apresentar
              erros temporários.</span></h2>
          <div class="currency-display" data-currency="usd">
            <table>
              <thead>
                <tr>
                  <th>Período</th>
                  <th>Valor</th>
                  <th>Porcentagem</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Lucro total liquido</td>
                  <td>$ <?= number_format($lucro_total, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_total / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 3 meses</td>
                  <td>$ <?= number_format(($lucro_3mes - $valor_depositado), 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_3mes / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 6 meses</td>
                  <td>$ <?= number_format(($lucro_6mes - $valor_depositado), 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_6mes / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 1 ano</td>
                  <td>$ <?= number_format(($lucro_ano - $valor_depositado), 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_ano / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro geral</td>
                  <td>$ <?= number_format(($lucro_geral - $valor_depositado), 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_geral / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="currency-display" data-currency="brl" style="display:none;">
            <table> <!-- MOEDA DE REAL -->
              <thead>
                <tr>
                  <th>Período</th>
                  <th>Valor</th>
                  <th>Porcentagem</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Lucro total liquido</td>
                  <td>R$ <?= number_format($lucro_total * $cotacao_dolar, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_total / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 3 meses</td>
                  <td>R$ <?= number_format(($lucro_3mes - $valor_depositado) * $cotacao_dolar, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_3mes / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 6 meses</td>
                  <td>R$ <?= number_format(($lucro_6mes - $valor_depositado) * $cotacao_dolar, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_6mes / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 1 ano</td>
                  <td>R$ <?= number_format(($lucro_ano - $valor_depositado) * $cotacao_dolar, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_ano / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro geral</td>
                  <td>R$ <?= number_format(($lucro_geral - $valor_depositado) * $cotacao_dolar, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_geral / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="currency-display" data-currency="eur" style="display:none;">
            <table> <!-- COM MOEDAS DE EURO -->
              <thead>
                <tr>
                  <th>Período</th>
                  <th>Valor</th>
                  <th>Porcentagem</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Lucro total liquido</td>
                  <td>€ <?= number_format($lucro_total * $cotacao_dolar / $cotacao_euro, 2, ',', '.') ?></td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_total / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 3 meses</td>
                  <td>€
                    <?= number_format(($lucro_3mes - $valor_depositado) * $cotacao_dolar / $cotacao_euro, 2, ',', '.') ?>
                  </td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_3mes / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 6 meses</td>
                  <td>€
                    <?= number_format(($lucro_6mes - $valor_depositado) * $cotacao_dolar / $cotacao_euro, 2, ',', '.') ?>
                  </td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_6mes / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro em 1 ano</td>
                  <td>€
                    <?= number_format(($lucro_ano - $valor_depositado) * $cotacao_dolar / $cotacao_euro, 2, ',', '.') ?>
                  </td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_ano / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
                <tr>
                  <td>Lucro geral</td>
                  <td>€
                    <?= number_format(($lucro_geral - $valor_depositado) * $cotacao_dolar / $cotacao_euro, 2, ',', '.') ?>
                  </td>
                  <td>
                    <?php if ($lucro_total !== 0 && $valor_depositado !== 0) {
                      echo number_format(($lucro_geral / $valor_depositado) * 100, 2, ',', '.');
                    } else {
                      echo "0";
                    }
                    ; ?>%
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      <?php } else { ?><br>
        <h3>Talvez o dono do perfil tenha alterado as chaves públicas e privadas no site do DMarket e ainda não as
          atualizou aqui.<br><br>
          Entre em contato com o dono do perfil ou administrador para informar o problema.</h3>
      <?php } ?>
      <?php if ($verify_dmarket == 1) { ?>
        <div class="tabelas" style="display: none;">
          <!-- Por algum motivo se retirar essa tag 'a', os botões de alterar a moeda quebram -->
          <div class="user-info__api">
            <h2>Informações do Usuário</h2>
            <a id="ocult_userinfo" style="cursor: pointer"><i id="icon-userinfo"
                class="fa-sharp-duotone fa-solid fa-eye"></i></a>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <script>
    document.getElementById('ocult_userinfo').addEventListener('click', () => {
      const iconUserInfo = document.getElementById('icon-userinfo');
      const userInfo = document.getElementById('user-info');
      const ocultarInfoElements = document.querySelectorAll('.ocultar-info');

      if (iconUserInfo.classList.contains('fa-eye')) {
        userInfo.style.filter = 'blur(8px)';
        ocultarInfoElements.forEach(element => {
          element.style.filter = 'blur(8px)';
        });
        iconUserInfo.classList.remove('fa-eye');
        iconUserInfo.classList.add('fa-eye-slash');
      } else {
        userInfo.style.filter = 'blur(0px)';
        ocultarInfoElements.forEach(element => {
          element.style.filter = 'blur(0px)';
        });
        iconUserInfo.classList.remove('fa-eye-slash');
        iconUserInfo.classList.add('fa-eye');
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
      const currencySelector = document.getElementById('currency-selector');
      const currencyElements = document.querySelectorAll('.currency-display');
      const labels = currencySelector.querySelectorAll('label');

      currencySelector.addEventListener('change', function (event) {
        const selectedCurrency = event.target.value;

        currencyElements.forEach(function (element) {
          const currency = element.getAttribute('data-currency');
          if (currency === selectedCurrency) {
            element.style.display = '';
          } else {
            element.style.display = 'none';
          }
        });

        labels.forEach(function (label) {
          if (label.querySelector('input').value === selectedCurrency) {
            label.classList.add('currency-selected');
          } else {
            label.classList.remove('currency-selected');
          }
        });
      });

      // Marcar a moeda inicial como selecionada
      labels.forEach(function (label) {
        if (label.querySelector('input').checked) {
          label.classList.add('currency-selected');
        }
      });
    });


  </script>
</body>

</html>