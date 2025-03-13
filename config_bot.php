<?php
session_start();

include '/var/www/html/class/class.botopcoes.php';

$bot = new BotOpcoes();

$tempFile = tempnam(sys_get_temp_dir(), 'pidfile');
$uptimeFile = '/var/www/html/uptime.json';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $status = $_POST['status'];

  if ($status == 'true') {
    $output = [];
    $return_var = 0;

    exec('nohup python3 /var/www/html/bot-python/buyitemall.py > /dev/null 2>&1 & echo $!', $output);
    file_put_contents($tempFile, $output[0] . PHP_EOL, FILE_APPEND);
    $pid_buy = $output[0]; 
    /* exec('nohup /var/www/html/bot-python/dmarket_bot/venv/bin/python3 /var/www/html/bot-python/dmarket_bot/main.py > /dev/null 2>&1 & echo $!', $output); */
    /* exec('nohup /var/www/html/bot-python/dmarket_bot/venv/bin/python3 /var/www/html/bot-python/dmarket_bot/venderallusers.py > /dev/null 2>&1 & echo $!', $output); */
    exec('nohup /var/www/html/bot-python/dmarket_bot/venv/bin/python3 /var/www/html/bot-python/dmarket_bot/venderallusers.py > /var/www/html/bot-python/dmarket_bot/venderallusers.log 2>&1 & echo $!', $output); 
    file_put_contents($tempFile, $output[1] . PHP_EOL, FILE_APPEND);
    $pid_sell = $output[1];
    
    // Armazena o timestamp de início no arquivo uptime.json
    $uptimeData = ['start_time' => time()];
    $jsonData = json_encode($uptimeData);
    
    if ($jsonData === false) {
        error_log("Erro ao codificar dados JSON: " . json_last_error_msg());
    } else {
        $result = file_put_contents($uptimeFile, $jsonData);
        if ($result === false) {
            error_log("Erro ao escrever no arquivo $uptimeFile");
        } else {
            error_log("Arquivo $uptimeFile criado com sucesso. Bytes escritos: $result");
        }
    }
    
    echo "started";

    // Comando executado com sucesso
    $bot->updatePerms(1, 1);
    echo "Comando executado com sucesso. Saída:<br>";
    echo "<pre>" . implode("\n", $output) . "</pre>";

  } else {
    $bot->updatePerms(1, 0);
    $bot->updatePerms(2, 0);
    echo "stopped";

    // Remove o arquivo de uptime
    if (file_exists($uptimeFile)) {
      if (unlink($uptimeFile)) {
        error_log("Arquivo $uptimeFile removido com sucesso");
      } else {
        error_log("Erro ao remover o arquivo $uptimeFile");
      }
    } else {
      error_log("Arquivo $uptimeFile não encontrado para remoção");
    }
    
    // Chama a função JavaScript 'ligarbot'
    exec("kill -9 " . intval($pid_buy));
    exec("kill -9 " . intval($pid_sell));
    unlink($tempFile); // Remove o arquivo temporário após o uso
  }
}