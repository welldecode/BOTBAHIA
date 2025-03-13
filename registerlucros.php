<?php

include '/var/www/html/class/class.usuario.php';
include '/var/www/html/class/class.credenciais.php';
include '/var/www/html/class/class.config.php';
include '/var/www/html/credenciais/encryption_functions.php';
include '/var/www/html/class/class.historico_dmarket.php';
include '/var/www/html/class/class.infobot.php';

$obj_infobot = new infobot();
$ob_lucros = new historico_dmarket();
$ob_usuario = new usuario();
$usuarios = $ob_usuario->getUsuarios()->fetchAll(PDO::FETCH_ASSOC);

foreach ($usuarios as $usuario) {
    $ob_lucros->insertLucros($usuario['id_usuario'], 0);
}

foreach ($usuarios as $usuario) {
    $obj_config = new credenciais();
    $cred_info = $obj_config->getInfo($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC);

    if ($cred_info) {
        $historico = new historico_dmarket();
    
        $valor_depositado = $historico->getTotalDepositado($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
        if ($valor_depositado == null || !$valor_depositado) {
            $valor_depositado = 0;
        }

        $info_dmarket = $obj_infobot->getInfoUser($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC);

        // Armazena o valor total na variável
        $valorFinal = $historico->getInventarioJsonVendas($usuario['id_usuario'])->fetch(PDO::FETCH_ASSOC)['valor_inventario'];
        $lucro_total = (float) $valorFinal - $valor_depositado + $info_dmarket['usd'];

        $ob_lucros->insertLucros($usuario['id_usuario'], $lucro_total);
        // Exibe o valor final
        echo "O valor total é: $" . number_format($lucro_total, 2) . " do USUARIO DE ID : " . $usuario['id_usuario'] . "<br>";
    }
}
