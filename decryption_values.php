<?php

include '/var/www/html/class/class.config.php';
include '/var/www/html/credenciais/encryption_functions.php';

$public_key = $_POST['public_key']; 
$private_key = $_POST['private_key']; 

$obj_config = new config();
$encryptionKey = $obj_config->getConfigEncrypt()->fetch(PDO::FETCH_ASSOC)['key'];

$dmarket_keypublica = decryptPassword($public_key, $encryptionKey);
$dmarket_keyprivada = decryptPassword($private_key, $encryptionKey);

// Retornar os valores como um array JSON
echo json_encode(array(
    'public_key' => $dmarket_keypublica,
    'private_key' => $dmarket_keyprivada
));
