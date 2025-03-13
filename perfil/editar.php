<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para a página inicial
if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

include '/var/www/html/class/class.usuario.php';

$userId = $_SESSION['usuario_id'];

$usuario = new usuario();

// Obter os dados do usuário para recuperar a imagem atual
$stmt = $usuario->getUsuariosID($userId);
$dados_usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = $_POST['user'];
  $nome = $_POST['nome'];
  $ver_rank = ($_POST['rankativo'] == 'on') ? 1 : 0;
  $avatar_path = $dados_usuario['avatar']; // Caminho da imagem atual

  // Verificar se uma imagem foi enviada
  if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = $_FILES['avatar']['name'];
    $fileSize = $_FILES['avatar']['size'];
    $fileType = $_FILES['avatar']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Validar tipo de arquivo permitido
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'JPG');
    if (in_array($fileExtension, $allowedfileExtensions)) {
      // Nome único para o arquivo
      $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

      // Diretório de upload
      $uploadFileDir = '/var/www/html/perfil/img/';
      $dest_path = $uploadFileDir . $newFileName;

      // Mover o arquivo enviado para o diretório de destino
      if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $avatar_path = '/perfil/img/' . $newFileName; // Novo caminho da imagem

        // Excluir a imagem anterior se existir
        if ($dados_usuario['avatar']) {
          $oldFilePath = $_SERVER['DOCUMENT_ROOT'] . $dados_usuario['avatar'];
          if (file_exists($oldFilePath)) {
            unlink($oldFilePath); // Exclui a imagem antiga
          }
        }
      } else {
        // Exibir mensagem de erro se o arquivo não puder ser movido
        echo 'Erro ao mover o arquivo para o diretório de upload.';
        echo 'Error details: ' . $_FILES['avatar']['error'];
      }
    } else {
      // Exibir mensagem de erro se o tipo de arquivo não for permitido
      echo 'Tipo de arquivo não permitido.';
    }
    $_SESSION['avatar'] = $avatar_path;
  }

  // Atualizar informações do usuário no banco de dados
  $usuario->atualizarPerfil($user, $avatar_path, $nome, $userId, $ver_rank);

  // Redirecionar para a página de perfil após a atualização
  header("Location: index.php");
  exit();
}