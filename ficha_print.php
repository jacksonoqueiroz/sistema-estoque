<?php
require 'conexao.php';

$id_peca = $_GET['id_peca'] ?? null;
$peca = null;
$itens = [];

if ($id_peca) {
    // Buscar dados da peça
    $stmt = $pdo->prepare("SELECT * FROM peca WHERE id = ?");
    $stmt->execute([$id_peca]);
    $peca = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($peca) {
        // Buscar itens da peça com local
        $stmt = $pdo->prepare("
             SELECT i.id, i.codigo, i.nome, i.qtd_itens, li.local
        FROM itens i
        LEFT JOIN local_itens li ON li.id_item = i.id
        WHERE i.id_peca = ?
        ");
        $stmt->execute([$id_peca]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Separação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-area, .print-area * {
                visibility: visible;
            }
            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
        .ficha {
            border: 1px dashed #333;
            padding: 15px;
            margin-bottom: 0px;
            display: flex;
            justify-content: space-between;
        }
        .barcode-etiqueta {
            margin-left: -50px;
            margin-top: 140px;
            width: 200px;
        }
        .etiqueta {
            width: 30%;
            border-left: 1px solid #000;
            padding-left: 10px;
            font-size: 12px;
        }
        .etiqueta strong{
            font-size: 15px;
        }
        .etiqueta p{
            font-size: 15px;
        }
        .conteudo {
            width: 90%;
        }
        .conteudo img{
            width: 140px;
            margin-left: 40px;
        }
    </style>
</head>
<body>
    <div class="container mt-3 print-area">
        <h6 class="text-center mb-4">Fichas de Separação - Peça: <?= htmlspecialchars($peca['nome']) ?></h6>
        <?php foreach ($itens as $item): ?>
            <div class="ficha">
                <div class="conteudo">
                    <p><strong>Item:</strong> <?= htmlspecialchars($item['nome']) ?></p>
                    <p><strong>Código:</strong> <?= htmlspecialchars($item['codigo']) ?></p>
                    <p><strong>Peça:</strong> <?= htmlspecialchars($peca['nome']) ?></p>
                    <p><strong>Quantidade:</strong> <?= $item['qtd_itens'] ?></p>
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= urlencode($item['codigo']) ?>&code=Code128&translate-esc=off" alt="Código de Barras">
                </div>
                <div class="etiqueta">
                    <strong><?= $item['codigo'] ?></strong>
                    <p><?= $item['nome'] ?></p>
                    <p><?= $peca['nome'] ?></p>
                    <p>Qtd: <?= $item['qtd_itens'] ?></p>
                </div>
                <div class="barcode-etiqueta">
                        <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= urlencode($item['codigo']) ?>&code=Code128&dpi=96" alt="Código de Barras" style="width: 80%; margin-right: -20px;">
                    </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>
