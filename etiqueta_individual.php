<?php
require 'conexao.php';

$id_item = $_GET['id_item'] ?? null;

if ($id_item) {
    $stmt = $pdo->prepare("
        SELECT 
            i.id, i.nome AS nome_item,
            i.codigo,
            p.nome AS nome_peca,
            li.local
        FROM itens i
        JOIN peca p ON i.id_peca = p.id
        LEFT JOIN local_itens li ON li.id_item = i.id
        WHERE i.id = ?
    ");
    $stmt->execute([$id_item]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados):
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta do Item</title>
    <style>
        .etiqueta {
            width: 500px;
            border: 2px dashed #000;
            padding: 10px;
            font-family: Arial, sans-serif;
            margin: 30px auto;
            text-align: center;
        }
        .etiqueta h2 {
            margin: 0;
            font-size: 20px;
        }
        .etiqueta .info {
            font-size: 14px;
            margin-top: 8px;
        }
        .info strong{
            font-size: 23px;
        }
        .barcode {
            margin-top: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="etiqueta">
        <h2><?= htmlspecialchars($dados['codigo']) ?></h2>
        <div class="info">Item: <?= htmlspecialchars($dados['nome_item']) ?></div>
        <div class="info">Local: <strong><?= htmlspecialchars($dados['local']) ?></strong></div>
        <br>
        <div class="barcode">
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $dados['codigo'] ?>&code=Code128&dpi=96" alt="Código de barras">
        </div>
        <!-- <div style="font-size:10px;">Código: <?= $dados['id'] ?></div> -->
    </div>
</body>
</html>

<?php else: echo "Item não encontrado."; endif; } else { echo "ID não informado."; } ?>
