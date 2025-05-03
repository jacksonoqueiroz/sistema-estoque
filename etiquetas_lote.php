<?php
require 'conexao.php';

// Buscar todos os itens com local alocado
$stmt = $pdo->query("
    SELECT 
        i.id, i.nome AS nome_item, 
        p.nome AS nome_peca, 
        li.local 
    FROM itens i
    JOIN peca p ON i.id_peca = p.id
    JOIN local_itens li ON li.id_item = i.id
    ORDER BY li.id ASC
");
$etiquetas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Impressão de Etiquetas</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
        }
        .pagina {
            width: 21cm;
            height: 29.7cm;
            padding: 1cm;
            display: flex;
            flex-wrap: wrap;
            gap: 0;
        }
        .etiqueta {
            width: 6.6cm;
            height: 2.5cm;
            border: 1px dashed #000;
            box-sizing: border-box;
            padding: 5px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .barcode {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="pagina">
    <?php foreach ($etiquetas as $et): ?>
        <div class="etiqueta">
            <div><strong>Item:</strong> <?= htmlspecialchars($et['nome_item']) ?></div>
            <div><strong>Peça:</strong> <?= htmlspecialchars($et['nome_peca']) ?></div>
            <div><strong>Local:</strong> <?= htmlspecialchars($et['local']) ?></div>
            <div class="barcode">
                <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $et['id'] ?>&code=Code128&dpi=96&translate-esc=false" height="30">
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
