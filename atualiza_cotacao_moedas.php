<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '/var/www/html/class/class.cotacao.moedas.php';
include '/var/www/html/class/class.config.php';

$obCotacao = new Cotacao;
$obj_config = new config();

$cotacao_dolar = (float) $obCotacao->consultarCotacao('USD', 'BRL')["USDBRL"]["bid"];
$cotacao_euro = (float) $obCotacao->consultarCotacao('EUR', 'BRL')["EURBRL"]["bid"];

$obj_config->updateConfig($cotacao_dolar, 2);
$obj_config->updateConfig($cotacao_euro, 3);

echo $cotacao_dolar . "---" . $cotacao_euro;