<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

if ($_SESSION['cargo'] == 2) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';

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
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/effect-fade.css">
  <link rel="stylesheet" href="../assets/css/swiper.min.css">
  <link rel="stylesheet" href="../assets/css/app-dollar.css">
  <link rel="stylesheet" href="../assets/css/app-tournaments.css">
  <link rel="stylesheet" href="../assets/css/background.css">
  <link rel="stylesheet" href="../assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="../assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="../assets/css/cup-violet.css">
  <link rel="stylesheet" href="../assets/css/tabela.css">
  <link rel="stylesheet" href="../assets/css/temas.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include '../includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="dashboard-route__first-group">
          <table id="example" class="row-border" style="width:100%">
            <thead>
              <tr>
                <th>Cargo</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Bloqueado</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($usuarios as $user): ?>
                <tr>
                  <td>
                    <?php if($user['cargo_usuario'] == 1){echo "CEO";}else{echo "Assinante";} ?>
                  </td>
                  <td>
                    <?= $user['nome_usuario'] ?>
                  </td>
                  <td>
                    <?= $user['email_usuario'] ?>
                  </td>
                  <td>
                    <?= $user['bloqueado'] == 0 ? 'Não' : 'Sim' ?>
                  </td>
                  <td>
                    <div class="action-table">
                      <a href="editar.php?id=<?= $user['id_usuario']?>" class="btn-edit"><img src="../assets/edit-icon.svg" alt=""></a>
                      <?php if($user['bloqueado'] == 0){?>
                        <a href="bloquear.php?id=<?= $user['id_usuario']?>" class="btn-block"><img src="../assets/unblock-icon.svg" alt=""></a>
                        <?php }else{?>
                          <a href="desbloquear.php?id=<?= $user['id_usuario']?>" class="btn-unblock"><img src="../assets/unblock-icon.svg" alt=""></a>
                          <?php }?>
                      <a href="deletar.php?id=<?= $user['id_usuario']?>" class="btn-delete"><img src="../assets/delete-icon.svg" alt=""></a>
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
      }
    });

    document.querySelector('div.dt-layout-row.dt-layout-table').style.overflow = "auto";
  </script>
</body>

</html>