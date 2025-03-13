<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

    //$dmarket_email = $_POST['emaildmarket'];
    $password = $_POST['senhadmarket'];
    $keyapi = $_POST['keydmarket'];

    $encryptionKey = $obj_config->getConfigEncrypt()->fetch(PDO::FETCH_ASSOC)['key'];

    $dmarket_password = encryptPassword($password, $encryptionKey); //PRIVATE
    $dmarket_keyapi = encryptPassword($keyapi, $encryptionKey); // PUBLIC

    if ($verificar) {
        $obj_credenciais->updateCredenciaisDmarket($dmarket_password, $dmarket_keyapi, $id_usuario);
    } else {
        $obj_credenciais->insertCredenciaisDmarket($dmarket_password, $dmarket_keyapi, $id_usuario);
    }
}
header('Location: index.php');
exit();
