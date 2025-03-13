<?php
session_start();

include '/var/www/html/class/class.botopcoes.php';

$bot = new BotOpcoes();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $status = $_POST['status'];

  if ($status == 'true') {
    exec('python3 /var/www/html/bot-python/atualiza_saldo_conta.py > /dev/null 2>/dev/null &');
    exec('python3 /var/www/html/bot-python/atualiza_info_perfil.py > /dev/null 2>/dev/null &');
    exec('python3 /var/www/html/bot-python/atualiza_inventario_compra.py > /dev/null 2>/dev/null &');
    exec('python3 /var/www/html/bot-python/atualiza_historico_conta.py > /dev/null 2>/dev/null &');
    //exec('python3 /var/www/html/bot-python/verificar_credenciais.py > /dev/null 2>/dev/null &');
    exec('python3 /var/www/html/bot-python/atualiza_inventario_venda.py > /dev/null 2>/dev/null &');
    $bot->updatePerms(2, 1);
    echo "started";
  } else {
    exec('pkill -f atualiza_saldo_conta.py');
    exec('pkill -f atualiza_info_perfil.py');
    exec('pkill -f atualiza_inventario_compra.py');
    exec('pkill -f atualiza_historico_conta.py');
    //exec('pkill -f verificar_credenciais.py');
    exec('pkill -f atualiza_inventario_venda.py');
    $bot->updatePerms(2, 0);
    echo "stopped";
  }
}

// Desligar
/* exec('pkill -f balance.py');
exec('pkill -f userinfo.py');
exec('pkill -f inventory.py');
exec('pkill -f history.py');

echo 'Desligado';   */

/* exec('sudo pkill python3 2>&1', $output, $return_var); */