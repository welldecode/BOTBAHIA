<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Verifica se a sessão está definida
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /index.php");
    exit();
}

include '/var/www/html/class/class.usuario.php';

$usuario = new usuario();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $max = $_POST['max-value'];
    $min = $_POST['min-value'];

    $usuario->updateMin_price_dmarket($min, $_SESSION['usuario_id']);
    $usuario->updateMax_price_dmarket($max, $_SESSION['usuario_id']);
}

header('Location: ../configuracoes.php');
exit();
