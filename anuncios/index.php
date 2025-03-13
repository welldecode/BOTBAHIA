<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.anuncio.php';

$obj = new Anuncio();

$stmt = $obj->getAnuncios();
$anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todos os Anúncios</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/main.css">
  <link rel="stylesheet" href="/assets/css/effect-fade.css">
  <link rel="stylesheet" href="/assets/css/swiper.min.css">
  <link rel="stylesheet" href="/assets/css/app-dollar.css">
  <link rel="stylesheet" href="/assets/css/app-tournaments.css">
  <link rel="stylesheet" href="/assets/css/background.css">
  <link rel="stylesheet" href="/assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="/assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="/assets/css/cup-violet.css">
  <link rel="stylesheet" href="/assets/css/tabela.css">
  <link rel="stylesheet" href="/assets/css/content.css">
  <link rel="stylesheet" href="/assets/css/temas.css">
</head>

<body>
  <?php include '/var/www/html/includes/alert.php'; ?>
  <div class="authenticated-layout">
    <?php include '/var/www/html/includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="dashboard-route__first-group" style="display: block;">
          <h3 style="margin: 50px 0px 17px 0px;">Todos os anúncios</h3>
          <a href="add_anuncio.php" class="btn-primary" style="display: block; width: fit-content; margin-bottom: 11px;">+ Novo anúncio</a>
          <table id="example" class="row-border" style="width:100%">
            <thead>
              <tr>
                <th>Id</th>
                <th>Título</th>
                <th>Subtítulo</th>
                <th>Ativo</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($anuncios as $anuncio): ?>
                <tr>
                  <td>
                    <?= $anuncio['id_anuncio'] ?>
                  </td>
                  <td>
                    <?= $anuncio['titulo'] ?>
                  </td>
                  <td>
                    <?= $anuncio['sub_titulo'] ?>
                  </td>
                  <td>
                    <?= $anuncio['ativo'] == "on" ? "Sim" : "Não" ?>
                  </td>
                  <td>
                    <div class="action-table">
                      <a href="editar.php?id=<?= $anuncio['id_anuncio']?>" class="btn-edit"><img src="/assets/edit-icon.svg" alt=""></a>
                      <a href="deletar.php?id=<?= $anuncio['id_anuncio']?>" class="btn-delete"><img src="/assets/delete-icon.svg" alt=""></a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
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
        sEmptyTable: 'Sem dados disponíveis na tabela'
      }
    });

    document.querySelector('div.dt-layout-row.dt-layout-table').style.overflow = "auto";
  </script>
</body>

</html>