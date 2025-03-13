<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: index.php");
  exit();
}

if ($usuario['bloqueado'] == '1') {
  header("Location: tela_bloqueado.php");
  exit();
}

include '/var/www/html/class/class.anuncio.php';
include '/var/www/html/class/class.historico_dmarket.php';
include '/var/www/html/class/class.usuario.php';
include '/var/www/html/class/class.config.php';
include '/var/www/html/class/class.credenciais.php';
include '/var/www/html/class/class.infobot.php';
include '/var/www/html/credenciais/encryption_functions.php';

$obj_infobot = new infobot();
$obj = new Anuncio();
$obHistorico = new historico_dmarket();
$historico = new historico_dmarket();

$ano_atual = date('Y'); // Obtém o ano atual, ex: 2024
$mes_atual = (int) date('m'); // Obtém o mês atual, ex: 08

$obj_config = new config();

$cotacao_dolar = (float) $obj_config->getCotacaoDolar()->fetch(PDO::FETCH_ASSOC)['key'];
$cotacao_euro = (float)  $obj_config->getCotacaoEuro()->fetch(PDO::FETCH_ASSOC)['key'];

$vendas_mes = $obHistorico->totalVendasMensalDash($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC);
$vendas_mes_json = json_encode($vendas_mes);

$compras_mes = $obHistorico->totalComprasMensalDash($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC);
$compras_mes_json = json_encode($compras_mes);

$lucros_resultado = $obHistorico->getLucros($_SESSION['usuario_id'], $ano_atual)->fetchAll(PDO::FETCH_ASSOC);

// Cria um array para armazenar os resultados de cada mês
$lucros_por_mes = array_fill(1, 12, 0);

// Preenche o array com os dados obtidos da consulta
foreach ($lucros_resultado as $lucro) {
  $mes = (int) $lucro['mes'];
  $lucros_por_mes[$mes] = (float) $lucro['total'];
}

$obj2 = new usuario();
if (!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))) {
  header("Location: tela_bloqueado.php");
  exit();
}

$usuarios_all = $obj2->getUsuariosRankLucro()->fetchAll(PDO::FETCH_ASSOC);
$usuarios_allMensal = $obj2->getUsuariosRankLucroMensal()->fetchAll(PDO::FETCH_ASSOC);
$usuarios_allSemanal = $obj2->getUsuariosRankLucroSemanal()->fetchAll(PDO::FETCH_ASSOC);

$usuarios = $obj2->getUsuariosID($_SESSION['usuario_id']);
$usuario = $usuarios->fetch(PDO::FETCH_ASSOC);

$stmt = $obj->getAnunciosAtivos();
$anuncio = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $obj->countAnunciosAtivos();
$countAnuncio = $stmt->fetch(PDO::FETCH_ASSOC);

$obj_credenciais = new credenciais();
$cred_info = $obj_credenciais->getInfo($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC);

if ($cred_info) {

  $inventario_venda_json = $historico->getInventarioJsonVendas($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
  $valor_inventario_venda = $inventario_venda_json['valor_inventario'];

  $inventario_compra_json = $historico->getInventarioJsonCompras($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
  $valor_inventario_compra = $inventario_compra_json['valor_inventario'];

  $valor_depositado = $obHistorico->getTotalDepositado($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
  if ($valor_depositado == null || !$valor_depositado) {
    $valor_depositado = 0;
  }

  $info_dmarket = $obj_infobot->getInfoUser($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC);

  $valorTotal = $valor_inventario_venda;

  $valorFinal = $valorTotal;
  $lucro_total = (float) $valorFinal - $valor_depositado + $info_dmarket['usd'];
  $lucros_por_mes[$mes_atual] = $lucro_total;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php include 'includes/links_head.php'; ?>
</head>

<body>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="dashboard-route__first-group">
          <div class="dashboard-slider">
            <?php if ($countAnuncio["total"] > 0) { ?>
              <div class="dashboard-slider__category caption-1 mobile-body-5 --basement-grotesque-font"
                style="width: 132.844px; background: rgb(99, 32, 238);">
                <div class="dashboard-slider__category-value -active">
                  <p>Anúncios</p>
                </div>
              </div>
              <div
                class="swiper swiper-fade swiper-initialized swiper-horizontal swiper-pointer-events swiper-watch-progress swiper-backface-hidden">
                <div class="swiper-wrapper" style="transition-duration: 0ms;">
                  <div
                    class="swiper-slide swiper-slide-duplicate swiper-slide-visible swiper-slide-active swiper-slide-duplicate"
                    data-swiper-slide-index="0" style="transition-duration: 0ms; opacity: 1;">
                    <div class="dashboard-slider__slide -image-out">
                      <div class="dashboard-slider__slide-content"
                        style="background: linear-gradient(rgb(99, 32, 238) 0%, rgb(83, 0, 255) 100%); overflow-y: auto; padding-right: 30px;">
                        <p class="dashboard-slider__slide-label caption-1 mobile-body-5"><?= $anuncio["titulo"] ?></p>
                        <p class="dashboard-slider__slide-title --basement-grotesque-font"><?= $anuncio["sub_titulo"] ?>
                        </p>
                        <p class="dashboard-slider__slide-description headline-2 mobile-body-2"
                          style="max-width: inherit;">
                          <?= $anuncio["descricao"] ?>
                        </p>

                        <picture class="dashboard-slider__slide-bg-image">
                          <source srcset="assets/slide-bg-mob.7c2b0215.svg" media="(max-width: 760px)"><img
                            src="assets/slide-bg.9fe8f720.svg" alt="">
                        </picture>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <div class="dashboard-slider__category caption-1 mobile-body-5 --basement-grotesque-font"
                style="width: 132.844px; background: rgb(99, 32, 238);">
                <div class="dashboard-slider__category-value -active">
                  <p>Anúncios</p>
                </div>
              </div>
              <div
                class="swiper swiper-fade swiper-initialized swiper-horizontal swiper-pointer-events swiper-watch-progress swiper-backface-hidden">
                <div class="swiper-wrapper" style="transition-duration: 0ms;">
                  <div
                    class="swiper-slide swiper-slide-duplicate swiper-slide-visible swiper-slide-active swiper-slide-duplicate"
                    data-swiper-slide-index="0" style="transition-duration: 0ms; opacity: 1;">
                    <div class="dashboard-slider__slide -image-out">
                      <div class="dashboard-slider__slide-content"
                        style="background: linear-gradient(rgb(99, 32, 238) 0%, rgb(83, 0, 255) 100%);">
                        <p class="dashboard-slider__slide-label caption-1 mobile-body-5">:c</p>
                        <p class="dashboard-slider__slide-title --basement-grotesque-font">Nenhum anúncio encontrado.</p>
                        <p class="dashboard-slider__slide-description headline-2 mobile-body-2">&nbsp;</p>
                        <picture class="dashboard-slider__slide-bg-image">
                          <source srcset="assets/slide-bg-mob.7c2b0215.svg" media="(max-width: 760px)"><img
                            src="assets/slide-bg.9fe8f720.svg" alt="">
                        </picture>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
          <div class="dashboard-leaderboards -has-shadow" style="position: relative; overflow-y: auto">
            <!-- <div class="overlay-breve">
              <h1>EM BREVE</h1>
            </div> -->
            <div style="display: flex; align-items: center; justify-content: space-between;">
              <p class="dashboard-leaderboards__title headline-1 mobile-heading-4 --basement-grotesque-font">TOP MAIS
                LUCRATIVOS</p>
              <!-- <select name="periodo" id="periodo">
                <option value="1">Semanal</option>
                <option value="2">Mensal</option>
                <option value="3" selected>Total</option>
              </select> -->
            </div>
            <p class="dashboard-leaderboards__subtitle subhead-3 mobile-body-3">Usuários mais lucrativos</p>
            <ul id="semanal" class="dashboard-leaderboards__users-list" style="display: none;">
              <?php foreach ($usuarios_allSemanal as $userS): ?>
                <?php
                $lucro_totalS = $userS['lucro_semanal'] / 100; // para os 2 ultimos numeros se tornar centavoss
                $lucro_total_formatadoS = 'R$ ' . number_format($lucro_totalS, 2, ',', '.');
                ?>
                <?php if ($user['rank_ativo'] == 0): ?>
                  <li class="dashboard-leaderboards__user">
                    <div class="dashboard-leaderboards__user-info">
                      <div class="dashboard-leaderboards__user-info-avatar -first"><img src="<?= $userS['avatar'] ?>"
                          alt="">
                      </div>
                      <div class="dashboard-leaderboards__user-info-group">
                        <p class="dashboard-leaderboards__user-info-group-username subhead-2 mobile-body-2">
                          <?= $userS['nome_usuario'] ?>
                        </p>
                      </div>
                    </div>
                    <div style="text-align: end">
                      <p>Lucro total</p>
                      <p class="lucratividade"><?= $lucro_total_formatadoS ?></p>
                    </div>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
            <ul id="mensal" class="dashboard-leaderboards__users-list" style="display: none;">
              <?php foreach ($usuarios_all as $userM): ?>
                <?php
                $lucro_totalM = $userM['lucro_mensal'] / 100; // para os 2 ultimos numeros se tornar centavoss
                $lucro_total_formatadoM = 'R$ ' . number_format($lucro_totalM, 2, ',', '.');
                ?>
                <?php if ($user['rank_ativo'] == 0): ?>
                  <li class="dashboard-leaderboards__user">
                    <div class="dashboard-leaderboards__user-info">
                      <div class="dashboard-leaderboards__user-info-avatar -first"><img src="<?= $userM['avatar'] ?>"
                          alt="">
                      </div>
                      <div class="dashboard-leaderboards__user-info-group">
                        <p class="dashboard-leaderboards__user-info-group-username subhead-2 mobile-body-2">
                          <?= $userM['nome_usuario'] ?>
                        </p>
                      </div>
                    </div>
                    <div style="text-align: end">
                      <p>Lucro total</p>
                      <p class="lucratividade"><?= $lucro_total_formatadoM ?></p>
                    </div>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
            <ul id="total" class="dashboard-leaderboards__users-list" style="display: block;">
              <?php foreach ($usuarios_all as $user): ?>
                <?php
                $lucro_total = $user['lucro_total'] * $cotacao_dolar; // para os 2 ultimos numeros se tornar centavoss
                $lucro_total_formatado = 'R$ ' . number_format($lucro_total, 2, ',', '.');
                ?>
                <?php if ($user['rank_ativo'] == 1): ?>
                  <li class="dashboard-leaderboards__user">
                    <div class="dashboard-leaderboards__user-info">
                      <a href="perfil_lucros.php?id_usuario=<?= $user['id_usuario'] ?>"><div class="dashboard-leaderboards__user-info-avatar -first"><img src="<?= $user['avatar'] ?>" alt="">
                      </div></a>
                      <div class="dashboard-leaderboards__user-info-group">
                        <p class="dashboard-leaderboards__user-info-group-username subhead-2 mobile-body-2">
                          <a href="perfil_lucros.php?id_usuario=<?= $user['id_usuario'] ?>"><?= $user['nome_usuario'] ?></a>
                        </p>
                      </div>
                    </div>
                    <div style="text-align: end">
                      <p>Lucro total</p>
                      <p class="lucratividade"><?= $lucro_total_formatado ?></p>
                    </div>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="dashboard-route__second-group">
          <div class="responsive-graph" style="position: relative">
            <div class="card-panel" style="padding: 24px 37px;">
              <div class="card-header header-elements pb-2">
                <div class="d-flex flex-column">
                  <h3 class="card-title mb-1">Resumo do ano</h3>
                  <p class="text-muted mb-0">Visão geral de Compras e Vendas em <?= $ano_atual ?></p>
                </div>
                <div class="card-action-element ms-auto py-0"></div>
                <div class="card-body">
                  <canvas id="groupedBarChart" class="chartjs" data-height="400" height="400"
                    style="display: block; box-sizing: border-box; height: 400px; width: 644px;" width="644"></canvas>
                </div>
              </div>
            </div>
            <div class="card-panel" style="padding: 24px 37px;">
              <div class="card-header header-elements pb-2">
                <div class="d-flex flex-column">
                  <h3 class="card-title mb-1">Resumo do ano</h3>
                  <p class="text-muted mb-0">Acompanhamento de lucro em <?= $ano_atual ?></p>
                </div>
                <div class="card-action-element ms-auto py-0"></div>
                <div class="card-body">
                  <canvas id="groupedBar" class="chartjs" data-height="400" height="400"
                    style="display: block; box-sizing: border-box; height: 400px; width: 644px;" width="644"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/chartjs.js"></script>
  <script src="assets/js/charts-chartjs.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    
    // Obter os dados PHP como variáveis JavaScript
    const vendasMes = <?php echo $vendas_mes_json; ?>;
    const comprasMes = <?php echo $compras_mes_json; ?>;
    const cotacaoDolar = <?php echo $cotacao_dolar; ?>; // Cotação do dólar para BRL

    // Processar os dados para o gráfico
    const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    // Inicializar o array de vendas e compras com 0s
    const vendas = new Array(12).fill(0);
    const compras = new Array(12).fill(0);

    // Converter valores de dólares para reais
    vendasMes.forEach(item => {
      vendas[item.mes - 1] = item.total * cotacaoDolar;
    });

    comprasMes.forEach(item => {
      compras[item.mes - 1] = item.total * cotacaoDolar;
    });

    // Configurar o gráfico
    const ctx = document.getElementById('groupedBarChart').getContext('2d');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: meses,
        datasets: [
          {
            label: 'Compras',
            data: compras,
            backgroundColor: 'rgba(54, 162, 235, 0.6)', // Azul claro
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            barPercentage: 0.3 // Ajusta a largura das barras
          },
          {
            label: 'Vendas',
            data: vendas,
            backgroundColor: 'rgba(75, 192, 192, 0.6)', // Verde claro
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            barPercentage: 0.3 // Ajusta a largura das barras
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            labels: {
              color: '#76728e',
              font: {
                size: 14
              }
            }
          },
          tooltip: {
            callbacks: {
              label: function (tooltipItem) {
                return tooltipItem.dataset.label + ': R$ ' + tooltipItem.formattedValue.toLocaleString('pt-BR');
              }
            },
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: '#fff',
            bodyColor: '#fff'
          }
        },
        scales: {
          x: {
            stacked: false,
            grid: {
              color: '#e9ecef',
              borderColor: '#dee2e6'
            },
            title: {
              display: true,
              text: 'Meses do Ano',
              color: '#76728e',
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            ticks: {
              color: '#76728e'
            }
          },
          y: {
            stacked: false,
            beginAtZero: true,
            grid: {
              color: '#e9ecef',
              borderColor: '#dee2e6'
            },
            title: {
              display: true,
              text: 'Valor em R$',
              color: '#76728e',
              font: {
                size: 14,
                weight: 'bold'
              }
            },
            ticks: {
              callback: function (value) {
                return 'R$ ' + value.toLocaleString('pt-BR');
              },
              color: '#495057'
            }
          }
        },
        responsive: true,
        maintainAspectRatio: false
      }
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const select = document.getElementById('periodo');
      const semanal = document.getElementById('semanal');
      const mensal = document.getElementById('mensal');
      const total = document.getElementById('total');

      function updateVisibility() {
        const value = select.value;
        semanal.style.display = value === '1' ? 'block' : 'none';
        mensal.style.display = value === '2' ? 'block' : 'none';
        total.style.display = value === '3' ? 'block' : 'none';
      }

      select.addEventListener('change', updateVisibility);

      // Chama a função inicialmente para garantir que a configuração inicial seja correta
      updateVisibility();
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Dados dos lucros por mês fornecidos pelo PHP
      const lucrosPorMes = <?php echo json_encode($lucros_por_mes); ?>;
      const cotacaoDolar = <?php echo $cotacao_dolar; ?>; // Cotação do dólar para BRL

      // Preparar os rótulos dos meses
      const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

      // Extrair os valores de lucro em dólares e converter para reais
      const valoresDolares = meses.map((_, index) => {
        return lucrosPorMes[index + 1] ? lucrosPorMes[index + 1] : 0;
      });

      const valoresReais = valoresDolares.map(valor => valor * cotacaoDolar);

      // Configuração do gráfico
      const ctx = document.getElementById('groupedBar').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: meses,
          datasets: [
            {
              label: 'Lucros em Dólares (USD)',
              data: valoresDolares,
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 1,
              hidden: true
            },
            {
              label: 'Lucros em Reais (BRL)',
              data: valoresReais,
              backgroundColor: 'rgba(255, 159, 64, 0.2)',
              borderColor: 'rgba(255, 159, 64, 1)',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            tooltip: {
              callbacks: {
                label: function (tooltipItem) {
                  const currency = tooltipItem.dataset.label.includes('Dólares') ? 'USD' : 'R$';
                  return currency + ' ' + tooltipItem.raw.toFixed(2).replace('.', ',');
                }
              }
            },
            legend: {
              labels: {
                color: '#76728e'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value) {
                  return 'R$ ' + value.toFixed(2).replace('.', ',');
                },
                color: '#76728e'
              }
            },
            x: {
              ticks: {
                color: '#76728e'
              }
            }
          }
        }
      });
    });
  </script>

</body>

</html>