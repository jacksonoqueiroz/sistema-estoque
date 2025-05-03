<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

$stmt = $pdo->query("SELECT * FROM locais_estante ORDER BY coluna, prateleira");
$locais = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Estante de Armazenamento</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .slot {
      width: 70px;
      height: 70px;
      margin: 5px;
      border-radius: 10px;
      text-align: center;
      line-height: 70px;
      font-weight: bold;
      color: white;
    }
    .ocupado { background-color: #dc3545; }
    .livre { background-color: #28a745; }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h3 class="mb-3">📦 Estante de Armazenamento</h3>
    <div class="row">
      <?php foreach ($locais as $local): ?>
        <div class="col-auto">
          <div class="slot <?= $local['ocupado'] ? 'ocupado' : 'livre' ?>">
            <?= $local['coluna'] . $local['prateleira'] ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
