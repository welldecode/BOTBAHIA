<?php
session_start();
include '/var/www/html/class/class.ranks.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /index.php");
    exit();
}

include '/var/www/html/class/class.usuario.php';
$obj2 = new usuario();
if (!($obj2->getUsuariosID($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC))) {
    header("Location: tela_bloqueado.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/content.css">
    <link rel="stylesheet" href="assets/css/effect-fade.css">
    <link rel="stylesheet" href="assets/css/swiper.min.css">
    <link rel="stylesheet" href="assets/css/app-dollar.css">
    <link rel="stylesheet" href="assets/css/app-tournaments.css">
    <link rel="stylesheet" href="assets/css/background.css">
    <link rel="stylesheet" href="assets/css/botaoCollapsado.css">
    <link rel="stylesheet" href="assets/css/botaoPadrao.css">
    <link rel="stylesheet" href="assets/css/cup-violet.css">
    <link rel="stylesheet" href="assets/css/temas.css">
    <link rel="stylesheet" href="assets/css/custom.css"> <!-- Inclua o seu CSS personalizado aqui -->
    <style>
        /* Estilo para o link de download */
        .download-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            background-color: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .download-link:hover,
        .download-link:focus {
            background-color: #007bff;
            color: #ffffff;
        }

        .download-link .icon {
            margin-right: 10px;
        }

        /* Estilo do ícone do PDF */
        .pdf-icon {
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <div class="authenticated-layout">
        <?php include 'includes/sidebar.php'; ?>
        <div class="authenticated-layout__content">
            <div class="dashboard-route">
                <!-- <div style="display: flex; flex-direction: column;">
                    <h2>Perguntas frequentes</h2><br>
                    <?php foreach (range(1, 5) as $numero): ?>
                        <button class="accordion">É possível fazer o saque a qualquer momento?</button>
                        <div class="panel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et malesuada ante. Nunc volutpat nunc dui, non feugiat nibh sollicitudin id. Vivamus vulputate fermentum libero</p>
                        </div>

                        <button class="accordion">Teste de pergunta aleatória oioi?</button>
                        <div class="panel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et malesuada ante. Nunc volutpat nunc dui, non feugiat nibh sollicitudin id. Vivamus vulputate fermentum libero, sit amet hendrerit mi ultricies nec. Nam sodales lectus eu mattis congue. Vestibulum eu elementum leo, at dictum justo. Nam in ex vel enim aliquet sagittis. Donec mattis mattis fermentum.</p>
                        </div>
                    <?php endforeach; ?>
                </div> -->
                <div style="margin-top: 20px;">
                    <p>Manual do Usuário: Encontre aqui as respostas para suas dúvidas.</p><br><br>
                    <a href="Manual_do_Usuário.pdf" class="download-link" download>
                        <img src="https://cdn.iconscout.com/icon/premium/png-256-thumb/pdf-117-695404.png" alt="PDF Icon" class="pdf-icon"> Baixar Manual do Usuário (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            var active = document.querySelector(".accordion.active");
            if (active && active != this) {
                active.classList.remove("active");
                active.nextElementSibling.style.maxHeight = null;
            }
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            } 
        });
    }
    </script>
</body>

</html>