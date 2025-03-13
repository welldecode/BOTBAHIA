<?php 

session_start();

include '/var/www/html/class/class.credenciais.php';
include '/var/www/html/class/class.config.php';
include '/var/www/html/credenciais/encryption_functions.php';

$obj_config = new config();
$obj_credenciais = new credenciais();

// Verifica se a sessão está definida
if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

// Define $id_usuario após verificar a sessão
$id_usuario = $_SESSION['usuario_id'];

$verificar = $obj_credenciais->buscarUsuario($id_usuario)->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_steam = $_POST['usuariosteam'];
    $password = $_POST['senhasteam'];
    $email_steam = $_POST['emailsteam'];

    $encryptionKey = $obj_config->getConfigEncrypt()->fetch(PDO::FETCH_ASSOC)['key'];
    
    $user_steam = encryptPassword($user_steam, $encryptionKey);
    $password_steam = encryptPassword($password, $encryptionKey);
    $email_steam = encryptPassword($email_steam, $encryptionKey);

    if ($verificar) {
        $obj_credenciais->updateCredenciaisSteam($user_steam, $password_steam, $email_steam, $id_usuario);
    } else {
        $obj_credenciais->insertCredenciaisSteam($user_steam , $password_steam, $email_steam, $id_usuario);
    }
}

header('Location: index.php');
exit();
