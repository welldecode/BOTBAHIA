<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

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

$id_usuario = (int) $_GET['id'];

$obj = new usuario();
$usuario = $obj->getUsuariosID($id_usuario);
$usuarios = $usuario->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $obj2 = new usuario();
  $nome = $_POST['usuario'];
  $email = $_POST['email'];
  $nome_completo = $_POST['nome_completo'];
  $cargo = (int)$_POST['tipo_usuario'];
  $senha1 = $_POST['senha'];
  $senha = password_hash($senha1, PASSWORD_DEFAULT);
  $senha2 = $_POST['confirmsenha'];

  if ($senha1 != "" && $senha2 != "") {
    if ($senha1 == $senha2) {
      $obj2->editUsuarioSenha($id_usuario, $nome, $email, $senha, $nome_completo, $cargo);
    } else {
      $_SESSION['mensagem'] = "As senhas não coincidem!";
      $_SESSION['mensagem_tipo'] = "erro";
      header("Location: editar.php?id=$id_usuario");
      exit();
    }
  } else {
    $obj2->editUsuario($id_usuario, $nome, $email, $nome_completo, $cargo);
  }

  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/content.css">
  <link rel="stylesheet" href="../assets/css/effect-fade.css">
  <link rel="stylesheet" href="../assets/css/swiper.min.css">
  <link rel="stylesheet" href="../assets/css/app-dollar.css">
  <link rel="stylesheet" href="../assets/css/app-tournaments.css">
  <link rel="stylesheet" href="../assets/css/background.css">
  <link rel="stylesheet" href="../assets/css/botaoCollapsado.css">
  <link rel="stylesheet" href="../assets/css/botaoPadrao.css">
  <link rel="stylesheet" href="../assets/css/cup-violet.css">
  <link rel="stylesheet" href="../assets/css/tabela.css">
</head>

<body>
  <?php include '/var/www/html/includes/alert.php'; ?>
  <div class="authenticated-layout">
    <?php include '../includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <div class="app-background -top-position -dark"><img src="../assets/default.060da614.jpg" alt=""></div>
        <div class="dashboard-route__first-group">
          <form style="width: 100%" class="card-panel" action="editar.php?id=<?= $id_usuario ?>" method="POST">
            <div
              style="margin-bottom: 20px;border-bottom: 1px dashed gray;padding-bottom: 16px;display: flex;align-items: center;gap: 7px;">
              <a style="cursor: pointer;" onclick="window.history.back()"><img src="/assets/left-arrow.svg"
                  alt=""></a>EDITAR USUÁRIO
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px">
              <div style="margin-right: 10px;">
                <div class="form-group">
                  <label for="">Usuário</label>
                  <input type="text" value="<?= $usuarios['nome_usuario'] ?>" name="usuario">
                  <span>Insira um nome de usuário</span>
                </div>
                <div class="form-group">
                  <label for="">Email</label>
                  <input type="email" value="<?= $usuarios['email_usuario'] ?>" name="email">
                  <span>Email para logar e recuperar acesso</span>
                </div>
                <div class="form-group">
                  <label for="">Nova senha</label>
                  <input type="password" name="senha" placeholder="Caso não queira alterar deixe em branco">
                  <span>Senha de 8 dígitos</span>
                </div>
                <div class="form-group">
                  <label for="">Confirme sua nova senha</label>
                  <input type="password" name="confirmsenha" placeholder="Caso não queira alterar deixe em branco">
                  <span>Repita sua senha</span>
                </div>
                <div class="form-group">
                  <label for="">Nome Completo</label>
                  <input type="text" value="<?= $usuarios['nome_completo'] ?>" name="nome_completo">
                  <span>Nome completo</span>
                </div>
                <div class="form-group">
                  <label for="">Tipo de Usuário</label>
                  <select name="tipo_usuario">
                    <option value="1" <?= ($usuarios['cargo_usuario'] == 1) ? 'selected' : '' ?>>CEO</option>
                    <option value="2" <?= ($usuarios['cargo_usuario'] == 2) ? 'selected' : '' ?>>Assinante</option>
                  </select>
                  <span>Selecione o tipo de usuário</span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn-editar">Editar usuário</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</body>

</html>