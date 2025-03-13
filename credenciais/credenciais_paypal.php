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

    $secretkey = $_POST['secretpaypal'];
    $paypal_token = $_POST['tokenpaypal'];

    $encryptionKey = $obj_config->getConfigEncrypt()->fetch(PDO::FETCH_ASSOC)['key'];
    
    $paypal_secretkey = encryptPassword($secretkey, $encryptionKey);
    $paypal_token = encryptPassword($paypal_token, $encryptionKey);

    if ($verificar) {
        $obj_credenciais->updateCredenciaisPaypal($paypal_secretkey, $paypal_token, $id_usuario);
    } else {
        $obj_credenciais->insertCredenciaisPaypal($paypal_secretkey, $paypal_token, $id_usuario);
    }
}

header('Location: index.php');
exit();
