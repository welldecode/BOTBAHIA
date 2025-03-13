<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '/var/www/html/class/class.credenciais.php';

$obj_credenciais = new credenciais();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $status = $_POST['status'];

    if($status == "false"){
        $status = 0;
    }else if($status == "true"){
        $status = 1;
    }else{
        $status = 0;
    }
    
    $obj_credenciais->updateLigarBot($status, $_SESSION['usuario_id']);

}

header('Location: ../configuracoes.php');
exit();