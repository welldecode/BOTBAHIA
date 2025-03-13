<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';
$obj2 = new usuario();
if (!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))) {
  header("Location: /tela_bloqueado.php");
  exit();
}

$userId = $_SESSION['usuario_id'];

$stmt = $obj2->getUsuariosID($userId);
$dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meu perfil</title>
  <style>
    .avatar-image img {
      width: 150px;
      height: 150px;
      object-fit: cover;
    }
  </style>
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
  <link rel="stylesheet" href="/assets/css/switch.css">
</head>

<body>
  <div class="authenticated-layout">
    <?php include '/var/www/html/includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">

        <section class="profile-overview">
          <header class="card-header">
            <h1 class="--basement-grotesque-font">Informações do perfil</h1><br><br>
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
                  <p><span>Nome:</span> <?= $dados_usuario['nome_completo']; ?></p>
                  <p><span>Email:</span> <?= $dados_usuario['email_usuario']; ?></p><br><br>
                </div>
              </div>
            </div>
            <br><br>
          </div>
        </section><br><br><br>
        <a href="javascript:void(0);" class="btn-profile-card" onclick="showEditForm()" style="color:white">Editar
          perfil</a><br><br>

        <!-- Formulário de edição oculto inicialmente -->
        <section id="edit-form" style="display: none;">
          <div class="card">
            <header class="card-header">
              <br><br>
              <h1 class="--basement-grotesque-font">Editar Perfil</h1><br><br>
            </header>
            <div class="card-content">
              <form action="editar.php" method="POST" enctype="multipart/form-data">
                <div class="group">
                  <span>Mostrar</span>&nbsp;o meu perfil no ranking:
                  <div class="toggle-switch">
                    <input class="toggle-input" style="appearance: auto;" type="checkbox" id="rankativo"
                      name="rankativo" <?php if ($dados_usuario['rank_ativo'] == 1)
                        echo 'checked'; ?>>
                    <label class="toggle-label" for="rankativo"></label>
                  </div>
                </div><br>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                  <label for="user">Nome de Usuario:</label>
                  <div>
                    <input type="text" name="user" value="<?= $dados_usuario['nome_usuario']; ?>"
                      style="color: black; padding: 6px 10px; border-radius: 8px;">
                  </div>
                </div><br>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                  <label for="nome">Nome Completo:</label>
                  <div>
                    <input type="text" id="nome" name="nome" value="<?= $dados_usuario['nome_completo']; ?>"
                      style="color: black; padding: 6px 10px; border-radius: 8px;">
                  </div>
                </div><br>
                <div>
                  <label for="avatar">Upload de foto de perfil:</label>
                  <input type="file" name="avatar"
                    style="color: white; padding: 8px 15px; border-radius: 8px; cursor: pointer;">
                </div><br><br>
                <div style="display: flex; justify-content: space-between; max-width: 390px;">
                  <button type="submit" class="btn-profile-card" style="cursor: pointer;color: white;">Salvar
                    Edição</button><br>
                  <button type="button" class="btn-profile-card" onclick="hideEditForm()"
                    style="cursor: pointer; color: white;">Cancelar Edição</button>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <script>
    function showEditForm() {
      document.getElementById('edit-form').style.display = 'block';
    }
    function hideEditForm() {
      document.getElementById('edit-form').style.display = 'none';
    }
  </script>
</body>

</html>