<?php
session_start();

include '/var/www/html/class/class.historico_dmarket.php';
$historico = new historico_dmarket();

$transacoes = $historico->getTransacoes($_SESSION['usuario_id'])->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /index.php");
    exit();
}
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
    <style>
        .negative {
            color: red;
        }

        .positive {
            color: green;
        }

        .deposit {
            color: yellow;
        }

        .lucratividade::before {
            content: '+';
            color: green;
        }

        tr th, tr td {
            background: transparent!important;
            color: white!important;
            border: 1px solid #3333334f!important;
        }

        tr th:first-child {
            border-top-left-radius: 10px!important;
        }

        tr th:last-child {
            border-top-right-radius: 10px!important;
        }
    </style>
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
                            <!-- Removido o elemento img -->
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
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Item</th>
                                <th>Ação</th>
                                <th>Status</th>
                                <th>Preço USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transacoes as $transacao):
                                $balanceAmount = $transacao['valor_transacao'];
                                $balanceClass = '';

                                switch ($transacao['tipo_transacao']) {
                                    case 'Sell':
                                        $balanceClass = 'positive lucratividade';
                                        $balanceAmount = '+' . $balanceAmount;
                                        break;
                                    case 'Deposit':
                                        $balanceClass = 'deposit';
                                        break;
                                    case 'Purchase':
                                        $balanceAmount = -$balanceAmount;
                                        $balanceClass = 'negative';
                                        break;
                                    default:
                                        $balanceClass = '';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($transacao['data_transacao'])); ?></td>
                                    <td><?php echo htmlspecialchars($transacao['nome_item']); ?></td>
                                    <td><?php echo htmlspecialchars($transacao['tipo_transacao']); ?></td>
                                    <td><?php echo htmlspecialchars($transacao['status_transacao']); ?></td>
                                    <td class="<?php echo $balanceClass; ?>">
                                        <?php echo "$" . number_format($balanceAmount, 2, ',', '.'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                paging: true,
                searching: true,
                info: true,
                order: [[0, 'desc']], // Ordena pela primeira coluna (Data) em ordem decrescente
                language: {
                    zeroRecords: 'Nenhum registro encontrado',
                    paginate: {
                        previous: 'Anterior',
                        next: 'Próximo'
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>