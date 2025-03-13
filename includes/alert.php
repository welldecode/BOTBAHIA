<?php
$mensagem_tipo = '';
if (isset($_SESSION['mensagem']) && isset($_SESSION['mensagem_tipo'])) {
    $mensagem = $_SESSION['mensagem'];
    $mensagem_tipo = $_SESSION['mensagem_tipo'];

    unset($_SESSION['mensagem']);
    unset($_SESSION['mensagem_tipo']);
}
?>

<div style="position: absolute; right: 35px; top: 70px;">
    <?php if ($mensagem_tipo == 'success') {
        echo '<div id="mensagemDiv" style="z-index: 1;color: #7fff84;" class="alert alert-solid-success alert-dismissible" role="alert">' . $mensagem;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } elseif ($mensagem_tipo == 'erro') {
        echo '<div id="mensagemDiv" style="z-index: 1;color: #ff7f7f;" class="alert alert-solid-danger alert-dismissible" role="alert">' . $mensagem;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    } ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mensagemDiv = document.getElementById('mensagemDiv');

        // Função para fazer a mensagem desaparecer após 10 segundos
        function esconderMensagem() {
            mensagemDiv.style.display = 'none';
        }

        // Configurando o temporizador (10 segundos = 10000 milissegundos)
        setTimeout(esconderMensagem, 10000);

        // Adicionando a linha horizontal que diminui ao longo de 10 segundos
        var linhaHorizontal = document.createElement('hr');
        linhaHorizontal.style.transition = 'width 10s linear';
        linhaHorizontal.style.margin = '8px 0px -12px 0px';
        linhaHorizontal.style.borderRadius = '20px';
        linhaHorizontal.style.border = '2px solid #ffffff';
        linhaHorizontal.style.width = '100%';
        mensagemDiv.appendChild(linhaHorizontal);

        // Iniciando a animação da linha horizontal
        setTimeout(function() {
            linhaHorizontal.style.width = '0';
        }, 0);
    });
</script>