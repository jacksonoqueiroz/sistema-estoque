<?php
// Conexão e busca
include 'conexao.php';
$id = $_GET['id'];

$sql = $pdo->prepare("SELECT * FROM itens_montagem WHERE id = ?");
$sql->execute([$id]);
$item = $sql->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Separação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ficha-container {
            display: flex;
            padding: 5px;
        }

        .ficha {
            width: 90%;
            border: 1px dashed; #000;
            padding: 20px;
        }

        .ficha strong{
            font-size: 20px;
        }


        .etiqueta {
            width: 40%;
            border: 1px dashed #000;
            padding: 5px;
            font-size: 14px;/*
            writing-mode: vertical-rl;*/
            text-orientation: mixed;
            text-align: center;
        }
        .cod-etiqueta {
            margin-top: 100px;
        }

        .etiqueta strong{
            font-size: 20px;
        }
        .descricao {
            font-size: 20px;
            margin-top: 20px;
            font-weight: ;
        }
        
        #barcode{
            width: 200px;
        }

        @media print {
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container my-4">
    <button onclick="window.print()" class="btn btn-success mb-3">🖨️ Imprimir</button>
    <a class="btn btn-secondary" href="listar_itens_montagem.php" style="margin-top: -15px;">Voltar</a>
    <div class="ficha-container">
        <!-- Ficha Principal -->
        <div class="ficha">
            <h4>Ficha de Separação</h4>
            <p>Código: <strong><?= $item['codigo'] ?></strong></p>
            <p>Nome: <strong><?= $item['nome'] ?></strong></p>
            <p>Local: <strong><?= $item['localizacao'] ?></strong></p>
            <p>Quantidade: <strong><?= $item['quantidade'] ?></strong></p>

            <div class="barcode">
                <svg id="barcode"></svg>
            </div>
        </div>

        <!-- Etiqueta Lateral -->
        <div class="etiqueta">
            <div><strong><?= $item['codigo'] ?></strong></div>
            <div class="descricao"><?= $item['nome'] ?></div>
            <p>Local: <strong><?= $item['localizacao'] ?></strong></p>
            <div class="descricao">Qtd: <?= $item['quantidade'] ?></div>
            <div class="barcode cod-etiqueta">
                <svg id="barcode"></svg>
            </div>
        </div>
    </div>
</div>

<!-- JsBarcode -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    JsBarcode("#barcode", "<?= $item['codigo'] ?>", {
        format: "CODE128",
        width: 2,
        height: 60,
        displayValue: true
    });
</script>

</body>
</html>
