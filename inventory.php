<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include '/var/www/html/class/class.config.php';
include '/var/www/html/credenciais/encryption_functions.php';
include '/var/www/html/class/class.credenciais.php';
include '/var/www/html/class/class.historico_dmarket.php';

$obj_config = new config();
$obHistorico = new historico_dmarket();
$obj_credenciais = new credenciais();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /index.php");
    exit();
}

$cred_info = $obj_credenciais->getInfo($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
// Obtendo JSON de vendas e compras
$inv_compra = $obHistorico->getInventarioJsonCompras($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);
$inv_venda = $obHistorico->getInventarioJsonVendas($_SESSION['usuario_id'])->fetch(PDO::FETCH_ASSOC);

if ($inv_compra) {
    $inv_compra = $inv_compra['json'];
}
if ($inv_venda) {
    $inv_venda = $inv_venda['json'];
}

// Decodificando JSON
$data = json_decode($inv_venda, true);
$data2 = json_decode($inv_compra, true);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens da DMarket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/effect-fade.css">
    <link rel="stylesheet" href="/assets/css/swiper.min.css">
    <link rel="stylesheet" href="/assets/css/app-dollar.css">
    <link rel="stylesheet" href="/assets/css/app-tournaments.css">
    <link rel="stylesheet" href="/assets/css/background.css">
    <link rel="stylesheet" href="/assets/css/botaoCollapsado.css">
    <link rel="stylesheet" href="/assets/css/botaoPadrao.css">
    <link rel="stylesheet" href="/assets/css/cup-violet.css">
    <link rel="stylesheet" href="/assets/css/tabela.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/temas.css">
</head>

<body>
    <div class="modal fade" id="visualizar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content" style="color: black;">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Detalhes do item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="details" id="modal-content">
                        <div class="img-bg">
                            <div class="skin_item" style="background-image: url(assets/images/awp-dragon-lore.png)">
                            </div>
                        </div>
                        Detalhes
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="authenticated-layout">
        <?php include 'includes/sidebar.php'; ?>
        <div class="authenticated-layout__content">
            <div class="dashboard-route">
                <div class="dashboard-route__first-group">
                    <h2>Itens Comprados</h2>
                    <table id="example" class="row-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Tipo</th>
                                <th>Preço USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($data2['objects'])): ?>
                                <?php foreach ($data2['objects'] as $item): ?>
                                    <tr>
                                        <td style="display: flex; align-items: center; gap: 10px">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="" width="80">
                                            <span><?php echo htmlspecialchars($item['title']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['extra']['categoryPath']); ?></td>
                                        <td>
                                            <?php echo "$" . number_format($item['price']['USD'] / 100, 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <h2 style="margin-top: 40px; margin-bottom: 12px">Itens à Venda</h2>
                    <table id="forsale" class="row-border" style="width:100%">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>OfferID</th>
                                <th>Preço USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($data['Items'])): ?>
                                <?php foreach ($data['Items'] as $item2): ?>
                                    <tr>
                                        <td style="display: flex; align-items: center; gap: 10px">
                                            <img src="<?php echo htmlspecialchars($item2['ImageURL']); ?>" alt="" width="80">
                                            <span><?php echo htmlspecialchars($item2['Title']); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($item2['Offer']['OfferID']); ?></td>
                                        <td>
                                            <?php echo "$" . number_format($item2['Offer']['Price']['Amount'], 2, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script>
        new DataTable('#example', {
            language: {
                info: 'Mostrando _PAGE_ de _PAGES_',
                infoEmpty: 'Nenhum dado disponível',
                infoFiltered: '(filtrado de _MAX_ registros)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                zeroRecords: 'Nenhum registro encontrado',
                search: 'Procurar: ',
            },
            sorting: ''
        });

        new DataTable('#forsale', {
            language: {
                info: 'Mostrando _PAGE_ de _PAGES_',
                infoEmpty: 'Nenhum dado disponível',
                infoFiltered: '(filtrado de _MAX_ registros)',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                zeroRecords: 'Nenhum registro encontrado',
                search: 'Procurar: ',
            },
            sorting: ''
        });

        document.querySelector('div.dt-layout-row.dt-layout-table').style.overflow = "auto";
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const linhas = document.querySelectorAll('#example tbody tr');

            linhas.forEach(linha => {
                const comprado = linha.querySelector('.comprado');
                const vendido = linha.querySelector('.vendido');
                const lucro = linha.querySelector('.lucro');

                if (comprado && vendido && lucro) {
                    const valorComprado = parseFloat(comprado.textContent.replace('$', '').replace(',', '.'));
                    const valorVendido = vendido.textContent.includes('à venda') ? 0 : parseFloat(vendido.textContent.replace('$', '').replace(',', '.'));

                    const calculoLucro = vendido.textContent.includes('à venda') ? 'Nenhum' : `R$${(valorVendido - valorComprado).toFixed(2).replace('.', ',')}`;
                    if (valorVendido > valorComprado) {
                        lucro.style.color = '#00af07';
                    } else if (valorVendido < valorComprado) {
                        lucro.style.color = '#d84b49';
                    } else {
                        lucro.style.color = '#aaa';
                    }
                    lucro.textContent = calculoLucro;
                }
            });
        });
    </script>
</body>

</html>
<?php
?>