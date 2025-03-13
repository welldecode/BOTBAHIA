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
include '/var/www/html/class/class.cotacao.moedas.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

$obj2 = new usuario();
$obCotacao = new Cotacao;
$obj_infobot = new infobot();
$obj_credenciais = new credenciais();
$historico = new historico_dmarket();
$obj_config = new config();


$cotacao_dolar = (float) $obj_config->getCotacaoDolar()->fetch(PDO::FETCH_ASSOC)['key'];
$cotacao_euro = (float) $obj_config->getCotacaoEuro()->fetch(PDO::FETCH_ASSOC)['key'];



// Recupera as credenciais
$cred_info = $obj_credenciais->getInfo($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);

$info_dmarket = $obj_infobot->getInfoUser($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);

if ($info_dmarket) {
  if ($info_dmarket['nome'] == NULL) {
    $verify_dmarket = 0;
  } else {
    $verify_dmarket = 1;
  }
}

// Verifica se o usuário existe
if (!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))) {
  header("Location: tela_bloqueado.php");
  exit();
}

if ($cred_info) { //  se o usuario tiver credenciais cadastradas  

  $inventario_venda_json = $historico->getInventarioJsonVendas($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
  $valor_inventario_venda = $inventario_venda_json['valor_inventario'];

  $inventario_compra_json = $historico->getInventarioJsonCompras($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
  $valor_inventario_compra = $inventario_compra_json['valor_inventario'];

  $info_dmarket = $obj_infobot->getInfoUser($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);

  $valor_depositado = $historico->getTotalDepositado($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  if ($valor_depositado == null) {
    $valor_depositado = 0;
  }

  $lucro_mes = $historico->totalVendasUltimoMes($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_3mes = $historico->totalVendasUltimos3Meses($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_6mes = $historico->totalVendasUltimos6Meses($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_ano = $historico->totalVendasUltimoAno($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC)[0]['total'];
  $lucro_geral = $historico->totalVendas($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC)[0]['total'];

  $lucro_semanals = $historico->totalVendasSemanal($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_semanalp = $historico->totalComprasSemanal($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_semanal = $lucro_semanals + ($lucro_semanalp * -1);

  $lucro_mensals = $historico->totalVendasMensal($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_mensalp = $historico->totalComprasMensal($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_mensal = $lucro_mensals + ($lucro_mensalp * -1);

  $lucro_diarios = $historico->totalVendasDiario($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_diariop = $historico->totalComprasDiario($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC)['total'];
  $lucro_diario = $lucro_diarios + ($lucro_diariop * -1);

  $lucro_total = (float) (($valor_inventario_compra + $valor_inventario_venda) - $valor_depositado) + $info_dmarket['usd'];
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
        <h1 class="dashboard-route__second-group">INFORMAÇÕES GERAIS</h1>
      </div>
      <?php if ($verify_dmarket == 1) { ?>
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
        </div>

        <div id="lucro" class="tabelas">
          <h2>Informações de Lucro <span
              style="color: white; background: #c5564a; margin-left: 10px; font-size: 13px; font-weight: 400; padding: 4px 6px; border-radius: 14px;">Se
              houver algum depósito recente na conta do DMarket, os lucros podem apresentar erros temporários.</span></h2>
          </h2>
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
        <div class="tabelas">
          <div class="currency-display" data-currency="usd">
            <h2>Informações de Saldo do DMARKET</h2>
            <table>
              <thead>
                <tr>
                  <th>Moeda</th>
                  <th>Saldo</th>
                  <th>Saldo Disponível para Retirada</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>DMC</td>
                  <td><span class="lucratividade"><?= number_format($info_dmarket['dmc'], 2, ',', '.') ?></span></td>
                  <td><span class="lucratividade"><?= number_format($info_dmarket['dmcretirar'], 2, ',', '.') ?></span>
                  </td>
                </tr>
                <tr>
                  <td>USD</td>
                  <td><span class="lucratividade"><?= number_format($info_dmarket['usd'], 2, ',', '.') ?></span></td>
                  <td><span class="lucratividade"><?= number_format($info_dmarket['usdretirar'], 2, ',', '.') ?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="currency-display" data-currency="brl" style="display:none;">
            <h2>Informações de Saldo do DMARKET</h2>
            <table>
              <thead>
                <tr>
                  <th>Moeda</th>
                  <th>Saldo</th>
                  <th>Saldo Disponível para Retirada</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>BRL</td>
                  <td><span
                      class="lucratividade"><?= number_format(($info_dmarket['dmc'] * $cotacao_dolar), 2, ',', '.') ?></span>
                  </td>
                  <td><span
                      class="lucratividade"><?= number_format(($info_dmarket['dmcretirar'] * $cotacao_dolar), 2, ',', '.') ?></span>
                  </td>
                </tr>
                <tr>
                  <td>BRL</td>
                  <td><span
                      class="lucratividade"><?= number_format(($info_dmarket['usd'] * $cotacao_dolar), 2, ',', '.') ?></span>
                  </td>
                  <td><span
                      class="lucratividade"><?= number_format(($info_dmarket['usdretirar'] * $cotacao_dolar), 2, ',', '.') ?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="currency-display" data-currency="eur" style="display:none;">
            <h2>Informações de Saldo do DMARKET</h2>
            <table>
              <thead>
                <tr>
                  <th>Moeda</th>
                  <th>Saldo</th>
                  <th>Saldo Disponível para Retirada</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>EUR</td>
                  <td><span
                      class="lucratividade"><?= number_format(($info_dmarket['dmc'] * $cotacao_dolar) / $cotacao_euro, 2, ',', '.') ?></span>
                  </td>
                  <td>
                    <span
                      class="lucratividade"><?= number_format(($info_dmarket['dmcretirar'] * $cotacao_dolar) / $cotacao_euro, 2, ',', '.') ?></span>
                  </td>
                </tr>
                <tr>
                  <td>EUR</td>
                  <td>
                    <span
                      class="lucratividade"><?= number_format(($info_dmarket['usd'] * $cotacao_dolar) / $cotacao_euro, 2, ',', '.') ?></span>
                  </td>
                  <td>
                    <span
                      class="lucratividade"><?= number_format(($info_dmarket['usdretirar'] * $cotacao_dolar) / $cotacao_euro, 2, ',', '.') ?></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        <?php } else { ?>
          <br>
          <h3>Você não cadastrou suas credenciais do DMarket, elas estão incorretas!<br>Ou talvez você tenha alterado as
            chaves públicas e privadas no site do DMarket e não as atualizou aqui.<br><br>Clique aqui para atualizá-las:
            <a style="background-color: chocolate; padding: 4px 8px; border-radius: 5px;"
              href="/credenciais/index.php">Credenciais</a>
          </h3>
          <br><br>
          <p>Caso você tenha acabado de cadastrar suas credenciais, pode levar até 2 minutos para o sistema atualizar suas
            informações do DMarket.</p>
          <p>Se você já cadastrou suas credenciais, por favor, **não crie novas** no site do DMarket. Apenas aguarde a
            sincronização, e se após 2 minutos as informações não forem atualizadas, entre em contato com o administrador.
          </p>
        <?php } ?>
      </div>
      <?php if ($verify_dmarket == 1) { ?>
        <div class="tabelas">
          <div class="user-info__api">
            <h2>Informações do Usuário</h2>
            <a id="ocult_userinfo" style="cursor: pointer"><i id="icon-userinfo"
                class="fa-sharp-duotone fa-solid fa-eye"></i></a>
          </div>
          <table>
            <thead>
              <tr>
                <th>Atributo</th>
                <th>Informação</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Nome de Usuário</td>
                <td class="ocultar-info"><?= $info_dmarket['nome'] ?></td>
              </tr>
              <tr>
                <td>Email</td>
                <td class="ocultar-info"><?= $info_dmarket['email'] ?></td>
              </tr>
              <tr>
                <td>País</td>
                <td class="ocultar-info"><?= $info_dmarket['local'] ?></td>
              </tr>
              <tr>
                <td>Nível</td>
                <td class="ocultar-info"><?= $info_dmarket['nivel'] ?></td>
              </tr>
              <tr>
                <td>ID</td>
                <td class="ocultar-info"><?= $info_dmarket['id_dmarket'] ?></td>
              </tr>
              <tr>
                <td>Verificação de Email</td>
                <td class="ocultar-info"><?= $info_dmarket['email_verificado'] ? 'Verificado' : 'Não Verificado' ?></td>
              </tr>
            </tbody>
          </table>
        <?php } ?>
      </div>
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