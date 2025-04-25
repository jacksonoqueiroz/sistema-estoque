<?php
include 'conexao.php';

if (!isset($_GET['material_id'])) {
  die("Material não informado.");
}

$id = $_GET['material_id'];
$stmt = $pdo->prepare("SELECT * FROM itens WHERE id = ?");
$stmt->execute([$id]);
$material = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$material) {
  die("Material não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Etiqueta de Armazenamento</title>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
  <style>
    @media print {
      body {
        margin: 0;
      }
    }
    .etiqueta {
      width: 350px;
      padding: 15px;
      border: 1px solid #000;
      font-family: Arial, sans-serif;
    }
    .etiqueta h4 {
      margin: 0 0 10px;
    }
  </style>
</head>
<body onload="window.print()">
  <div class="etiqueta">
    <h4>📦 Etiqueta de Armazenamento</h4>
    <div><strong>Material:</strong> <?= $material['nome'] ?></div>
    <div><strong>Código:</strong> <?= $material['codigo'] ?></div>
    <div><strong>Local:</strong> <?= $material['localizacao'] ?></div>
    <svg id="barcode"></svg>
  </div>

  <script>
    JsBarcode("#barcode", "<?= $material['codigo'] ?>", {
      format: "CODE128",
      width: 2,
      height: 40,
      displayValue: true
    });
  </script>
</body>
</html>
