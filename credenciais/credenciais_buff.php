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
    $buff_email = $_POST['emailbuff'];
    $password = $_POST['senhabuff'];
    $buff_fone = $_POST['telbuff'];

    $encryptionKey = $obj_config->getConfigEncrypt()->fetch(PDO::FETCH_ASSOC)['key'];
    
    $buff_email = encryptPassword($buff_email, $encryptionKey);
    $buff_password = encryptPassword($password, $encryptionKey);
    $buff_fone = encryptPassword($buff_fone, $encryptionKey);

    if ($verificar) {
        $obj_credenciais->updateCredenciaisBuff($buff_email, $buff_password, $buff_fone, $id_usuario);
    } else {
        $obj_credenciais->insertCredenciaisBuff($buff_email, $buff_password, $buff_fone, $id_usuario);
    }
}

header('Location: index.php');
exit();
