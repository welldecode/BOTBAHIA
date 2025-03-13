<?php 

session_start();

include '/var/www/html/class/class.botopcoes.php';

$obj_opcoes = new BotOpcoes();
$info_opcoes = $obj_opcoes->getPerms()->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['usuario_id'])) {
  header("Location: /index.php");
  exit();
}

if($_SERVER['RESQUEST_METHOD'] == 'POST'){
    $id_permissao = $_POST['permissao'];
    $status_value = $_POST['status'];

    $obj_opcoes->updatePerms($id, $status_value);
}

