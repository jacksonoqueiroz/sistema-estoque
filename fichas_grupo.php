<?php
include 'conexao.php';

$grupo = $_GET['id_grupo'] ?? '';
$sql = $pdo->prepare("SELECT * FROM itens_montagem WHERE id_grupo = ?");
$sql->execute([$grupo]);
$itens = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fichas de Separação - Grupo <?= htmlspecialchars($grupo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 10px;
        }

        .titulo-grupo {
            text-align: center;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .ficha-container {
            display: flex;
            border: 1px dashed #000;
            margin-bottom: 10px;
            padding: 5px;
            height: 240px;
            page-break-inside: avoid;
        }

        .ficha {
            width: 80%;
            border-right: 1px dashed #000;
            padding: 5px;
        }

        .etiqueta {
            width: 20%;
            padding: 5px;
            font-size: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .barcode {
            margin-top: -62px;
            margin-left: 180px;
            width: 80%;
        }


        @media print {
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <button onclick="window.print()" class="btn btn-success mb-3">🖨️ Imprimir Fichas</button>
    <a class="btn btn-secondary" href="listar_itens_montagem.php" style="margin-top: -15px;">Voltar</a>
    <div class="titulo-grupo">Fichas de Separação - Grupo: <?= htmlspecialchars($grupo) ?></div>

    <?php foreach ($itens as $item): ?>
        <div class="ficha-container">
            <div class="ficha">
                <h5>Ficha de Separação</h5>
                <p><strong>Código:</strong> <?= $item['codigo'] ?></p>
                <p><strong>Nome:</strong> <?= $item['nome'] ?></p>
                <p><strong>Local:</strong> <?= $item['localizacao'] ?></p>
                <p><strong>Quantidade:</strong> <?= $item['quantidade'] ?></p>
                <div class="barcode">
                    <svg id="barcode-<?= $item['id'] ?>"></svg>
                </div>
            </div>

            <div class="etiqueta">
                <div><strong><?= $item['codigo'] ?></strong></div>
                <div><?= $item['nome'] ?></div>
                <p><strong>Local:</strong> <?= $item['localizacao'] ?></p>
                <div><strong>Qtd: <?= $item['quantidade'] ?></strong></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
<?php foreach ($itens as $item): ?>
    JsBarcode("#barcode-<?= $item['id'] ?>", "<?= $item['codigo'] ?>", {
        format: "CODE128",
        width: 2,
        height: 40,
        displayValue: true,
        fontSize: 14
    });
<?php endforeach; ?>
</script>

</body>
</html>
