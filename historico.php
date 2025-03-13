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

$obj = new usuario();

$usuario = $obj->getUsuarios();
$usuarios = $usuario->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/effect-fade.css">
  <link rel="stylesheet" href="/assets/css/swiper.min.css">
  <link rel="stylesheet" href="/assets/css/app-dollar.css">
  <link rel="stylesheet" href="/assets/css/app-tournaments.css">
  <link rel="stylesheet" href="/assets/css/background.css">
  <link rel="stylesheet" href="/assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="/assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="/assets/css/cup-violet.css">
  <link rel="stylesheet" href="/assets/css/tabela.css">
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/temas.css">
</head>

<body>
  <div class="modal fade" id="visualizar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
      <div class="modal-content" style="color: black;">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Detalhes do item</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="details" id="modal-content">
            <div class="img-bg">
              <div class="skin_item" style="background-image: url(assets/images/awp-dragon-lore.png)"></div>
            </div>
            Detalhes
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="authenticated-layout">
    <?php include 'includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="dashboard-route__first-group">
          <table id="example" class="row-border" style="width:100%">
            <thead>
              <tr>
                <th>Item</th>
                <th>Raridade</th>
                <th>Comprado por</th>
                <th>Vendido por</th>
                <th>Lucro</th>
                <th>Status</th>
                <th>Data da compra</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/awp-dragon-lore.png" alt="" width="80"><span>AWP | Dragon Lore</span>
                </td>
                <td>Secreta</td>
                <td class="comprado">
                  R$23077,01
                </td>
                <td class="vendido">
                  R$27077,01
                </td>
                <td class="lucro"></td>
                <td>
                  Vendido
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <button type="button" class="btn-edit" data-bs-toggle="modal" data-bs-target="#visualizar" data-equipamento-id="1">Visualizar</button>
                </td>
              </tr>

              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/karambit-fade.png" alt="" width="80"><span>★ Karambit | Fade</span>
                </td>
                <td>Secreta</td>
                <td class="comprado">
                  R$13118,03
                </td>
                <td class="vendido">
                  à venda
                </td>
                <td class="lucro"></td>
                <td>
                  Disponível
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>

              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/ak-47-wild-lotus.png" alt="" width="80"><span>AK-47 | Wild Lotus</span>
                </td>
                <td>Secreta</td>
                <td class="comprado">
                  R$14612,68
                </td>
                <td class="vendido">
                  R$15612,68
                </td>
                <td class="lucro"></td>
                <td>
                  Vendido
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>

              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/sport-gloves-omega.png" alt="" width="80"><span>★ Sport Gloves | Omega</span>
                </td>
                <td>Extraordinária</td>
                <td class="comprado">
                  R$5684,66
                </td>
                <td class="vendido">
                  R$9684,66
                </td>
                <td class="lucro"></td>
                <td>
                  Vendido
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>

              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/glock-18-fade.png" alt="" width="80"><span>Glock-18 | Fade</span>
                </td>
                <td>Restrita</td>
                <td class="comprado">
                  R$7737,85
                </td>
                <td class="vendido">
                  à venda
                </td>
                <td class="lucro"></td>
                <td>
                  Disponível
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>
              </tr>
              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/m4a4-poseidon.png" alt="" width="80"><span>M4A4 | Poseidon</span>
                </td>
                <td>Classificada</td>
                <td class="comprado">
                  R$5476,22
                </td>
                <td class="vendido">
                  à venda
                </td>
                <td class="lucro"></td>
                <td>
                  Disponível
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>
              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/sport-gloves-vice.png" alt="" width="80"><span>★ Sport Gloves | Vice</span>
                </td>
                <td>Extraordinária</td>
                <td class="comprado">
                  R$4461,43
                </td>
                <td class="vendido">
                  R$5461,43
                </td>
                <td class="lucro"></td>
                <td>
                  Vendida
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>
              <tr>
                <td style="display: flex; align-items: center;gap: 10px">
                  <img src="assets/images/talon-knife-marble-fade.png" alt="" width="80"><span>★ Talon Knife | Marble
                    Fade</span>
                </td>
                <td>Secreta</td>
                <td class="comprado">
                  R$4238,31
                </td>
                <td class="vendido">
                  à venda
                </td>
                <td class="lucro"></td>
                <td>
                  Disponível
                </td>
                <td>
                  10/11/22
                </td>
                <td>
                  <a href="#" class="btn-edit">Visualizar</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
  <script>
    new DataTable('#example', {
      language: {
        info: 'Mostrando _PAGE_ de _PAGES_',
        infoEmpty: 'Nenhum dado disponível',
        infoFiltered: '(filtrado de _MAX_ registros)',
        lengthMenu: 'Mostrar _MENU_ registros por página',
        zeroRecords: 'Nenhum registro encontrado',
        search: 'Procurar: ',
      }
    });

    document.querySelector('div.dt-layout-row.dt-layout-table').style.overflow = "auto";
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const linhas = document.querySelectorAll('#example tbody tr');

      linhas.forEach(linha => {
        const comprado = linha.querySelector('.comprado');
        const vendido = linha.querySelector('.vendido');
        const lucro = linha.querySelector('.lucro');

        if (comprado && vendido && lucro) {
          const valorComprado = parseFloat(comprado.textContent.replace('R$', '').replace(',', '.'));
          const valorVendido = vendido.textContent.includes('à venda') ? 0 : parseFloat(vendido.textContent.replace('R$', '').replace(',', '.'));

          const calculoLucro = vendido.textContent.includes('à venda') ? 'Nenhum' : `R$${(valorVendido - valorComprado).toFixed(2).replace('.', ',')}`;
          if (valorVendido > valorComprado) {
            lucro.style.color = '#00af07';
          } else {
            lucro.style.color = '#ef6e6e';
          }

          if (calculoLucro === 'Nenhum') {
            lucro.style.color = '';
          }
          lucro.textContent = calculoLucro;
        }
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>