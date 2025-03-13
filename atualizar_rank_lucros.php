<?php
include '/var/www/html/class/class.historico_dmarket.php';
include '/var/www/html/class/class.usuario.php';

$historico = new historico_dmarket();
$usuario = new usuario();

$todosUsuarios = $usuario->getUsuarios()->fetchAll(PDO::FETCH_ASSOC);

foreach ($todosUsuarios as $user) {
    
    $todas_vendas = $historico->getTotalVendas($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    $todas_compras = $historico->getTotalCompas($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    
    $valor_depositado = $historico->getTotalDepositado($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];

    $lucro_semanals = $historico->totalVendasSemanal($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    $lucro_semanalp = $historico->totalComprasSemanal($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    
    $lucro_mensals = $historico->totalVendasMensal($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    $lucro_mensalp = $historico->totalComprasMensal($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    
    /* $lucro_diarios = $historico->totalVendasDiario($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total'];
    $lucro_diariop = $historico->totalComprasDiario($user['id_usuario'])->fetch(PDO::FETCH_ASSOC)['total']; */
    

    $lucro_total = (float) $todas_vendas + ($todas_compras * -1);
    /*$lucro_diario = $lucro_diarios + ($lucro_diariop * -1); */
    $lucro_semanal = $lucro_semanals + ($lucro_semanalp * -1);
    $lucro_mensal = $lucro_mensals + ($lucro_mensalp * -1);

    $usuario->updateLucrosUsuario($user['id_usuario'], $lucro_total, $lucro_mensal, $lucro_semanal);
}