<?php

require 'conexao.php';

$id_peca = $_GET['id_peca'] ?? null;

if ($id_peca) {
    $stmt = $pdo->prepare("
        SELECT 
            p.id, p.nome AS nome_peca,
            p.codigo,
            g.nome AS nome_grupo,
            lp.local
        FROM peca p
        JOIN grupos g ON p.id_grupo = g.id
        JOIN local_pecas lp ON lp.id_peca = p.id
        WHERE p.id = ?
    ");
    $stmt->execute([$id_peca]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados):
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta da Peça</title>
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
            font-size: 23px;
        }
        .etiqueta .info {
            font-size: 14px;
            margin-top: 8px;
        }
        .etiqueta strong{
            font-size: 23px;
        }
        .barcode {
            margin-top: 10px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="etiqueta">
        <h2><?= htmlspecialchars($dados['nome_peca']) ?></h2>
        <div class="info">Grupo: <?= htmlspecialchars($dados['nome_grupo']) ?></div>
        <div class="info">Local: <strong><?= htmlspecialchars($dados['local']) ?></strong></div>
        <div class="barcode">
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $dados['codigo'] ?>&code=Code128&dpi=96" alt="Código de barras">
        </div>
        <!-- <div style="font-size:10px;">Código: <?= $dados['id'] ?></div> -->
    </div>
</body>
</html>

<?php else: echo "Peça não encontrada."; endif; } else { echo "ID não informado."; } ?>
