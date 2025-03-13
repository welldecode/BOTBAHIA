<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();

include '/var/www/html/class/class.credenciais.php';

$obj_credenciais = new credenciais();

$info_credenciais = $obj_credenciais->getInfo($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
</head>

<body>
  <sp class="authenticated-layout">
    <?php include '../includes/sidebar.php'; ?>
    <div class="authenticated-layout__content">
      <div class="dashboard-route">
        <h class="dashboard-route__second-group">
          <h3>CONFIGURAÇÕES DE CREDENCIAL</h3>
        </h>
      </div>
      <div class="dashboard-route__second-group">
        <!-- <form class="card-panel" action="/credenciais/credenciais_steam.php" method="POST" style="position: relative">
          <div class="overlay-breve">
            <h1>EM BREVE</h1>
          </div>
          <div style="margin-bottom: 20px; border-bottom: 1px dashed gray; padding-bottom: 16px;">STEAM</div>
          <div class="form-group">
            <label for="">Usuário Steam</label>
            <input type="text" name="usuariosteam" placeholder="*********" required>
            <span>Insira o usuário da steam</span>
          </div>
          <div class="form-group">
            <label for="">Senha da steam</label>
            <input type="password" name="senhasteam" placeholder="*********" required>
            <span>Sua senha da steam, ficará criptografada.</span>
          </div>
          <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="emailsteam" placeholder="*********" required>
            <span>Email do usuário steam</span>
          </div>
          <button class="btn-primary">Salvar</button>
        </form> -->

        <form class="card-panel" action="/credenciais/credenciais_dmarket.php" method="POST">
          <div style="margin-bottom: 20px; border-bottom: 1px dashed gray; padding-bottom: 16px;">DMARKET</div>
          <!-- <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="emaildmarket" placeholder="*********" required>
            <span>Email para criação/acesso da conta na plataforma</span>
          </div> -->
          <div class="form-group">
            <label for="">Chave Privada</label>
            <input type="password" name="senhadmarket" placeholder="*********" required>
            <span>Chave Privada da API</span>
          </div>
          <div class="form-group">
            <label for="">Chave Pública</label>
            <input type="password" name="keydmarket" placeholder="*********" required>
            <span>Chave pública da api</span>
          </div>
          <button class="btn-primary">Salvar</button>
        </form>

        <!-- <form class="card-panel" action="/credenciais/credenciais_buff.php" method="POST" style="position: relative">
          <div class="overlay-breve">
            <h1>EM BREVE</h1>
          </div>
          <div style="margin-bottom: 20px; border-bottom: 1px dashed gray; padding-bottom: 16px;">BUFF</div>
          <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="emailbuff" placeholder="*********" required>
            <span>Email para criação/acesso da conta na plataforma</span>
          </div>
          <div class="form-group">
            <label for="">Telefone</label>
            <input type="number" name="telbuff" placeholder="*********" required>
            <span>Telefone para criação/acesso da conta na plataforma</span>
          </div>
          <div class="form-group">
            <label for="">Senha</label>
            <input type="password" name="senhabuff" placeholder="*********" required>
            <span>Senha para acessar a plataforma BUFF</span>
          </div>
          <button class="btn-primary">Salvar</button>
        </form> -->

        <!-- <form class="card-panel" action="/credenciais/credenciais_paypal.php" method="POST" style="position: relative">
          <div class="overlay-breve">
            <h1>EM BREVE</h1>
          </div>
          <div style="margin-bottom: 20px; border-bottom: 1px dashed gray; padding-bottom: 16px;">Paypal</div>
          <div class="form-group">
            <label for="">Token</label>
            <input type="text" name="tokenpaypal" placeholder="*********" required>
            <span>Email para criação/acesso da conta na plataforma</span>
          </div>
          <div class="form-group">
            <label for="">Secretkey</label>
            <input type="password" name="secretpaypal" placeholder="*********" required>
            <span>Email para criação/acesso da conta na plataforma</span>
          </div>
          <button class="btn-primary">Salvar</button>
        </form> -->
      </div><!-- <br><br><br>
      <h4>Para editar qualquer campo, é necessário preencher todas as informações novamente por motivos de segurança.
        Por exemplo, se você deseja alterar a chave privada do DMarket, precisará fornecer a chave pública também para
        confirmação.</h4> -->
    </div>
  </sp>
</body>

</html>