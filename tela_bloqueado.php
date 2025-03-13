<?php 
    // Iniciar a sessão
    session_start();

    // Obter parâmetros do cookie da sessão
    $parametros_cookie = session_get_cookie_params();

    // Definir o cookie da sessão para expirar
    setcookie(
        session_name(),
        '',
        time() - 3600,
        $parametros_cookie['path'],
        $parametros_cookie['domain'],
        $parametros_cookie['secure'],
        $parametros_cookie['httponly']
    );

    // Destruir a sessão
    session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Bloqueado</title>
    <style>
        body {
            background-color: rgb(34, 34, 34);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
        }
        h1 {
            color: white;
            padding: 20px;
        }
        a {
            display: inline-block;
            background-color: blueviolet;
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: rebeccapurple;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Você está banido ou temporariamente bloqueado de acessar este site!</h1>
        <a href="index.php">VOLTAR</a>
    </div>
</body>
</html>
