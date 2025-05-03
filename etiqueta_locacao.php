<?php
session_start();

include 'conexao.php';

// Busca materiais com localização
$stmt = $pdo->query("SELECT id, nome, codigo, localizacao FROM itens ORDER BY nome ASC");
$materiais = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';

?>


  <title>Etiqueta de Armazenamento</title>
  </head>
<body>
 <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h5>🏷️ Imprimir Etiqueta de Armazenamento</h5>
    </div>
    <div class="card-body">
      <!-- <form method="GET">
        <div class="mb-3">
          <label for="material_id" class="form-label">Selecione o material:</label>
          <select name="material_id" id="material_id" class="form-select" required>
            <option value="">-- Escolha --</option>
            <?php foreach ($materiais as $m): ?>
              <option value="<?= $m['id'] ?>" <?= ($_GET['material_id'] ?? '') == $m['id'] ? 'selected' : '' ?>>
                <?= $m['nome'] ?> (<?= $m['codigo'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-success">Gerar Etiqueta</button>
      </form> -->

      <form action="etiqueta_print.php" method="GET" target="_blank">
  <div class="mb-3">
    <label for="material_id" class="form-label">Selecione o material:</label>
    <select name="material_id" id="material_id" class="form-select" required>
      <option value="">-- Escolha --</option>
      <?php foreach ($materiais as $m): ?>
        <option value="<?= $m['id'] ?>"><?= $m['nome'] ?> (<?= $m['codigo'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-success">🖨️ Gerar Etiqueta</button>
</form>


    </div>
  </div>

<?php if (isset($_GET['material_id'])):
  $id = $_GET['material_id'];
  $stmt = $pdo->prepare("SELECT * FROM itens WHERE id = ?");
  $stmt->execute([$id]);
  $material = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($material):
?>
  <div class="card shadow mt-4">
    <div class="card-body text-center">
      <div class="border p-3 bg-white" style="width: 350px; margin: auto;">
        <h5 class="mb-2">📦 Etiqueta de Armazenamento</h5>
        <div><strong>Material:</strong> <?= $material['nome'] ?></div>
        <div><strong>Código:</strong> <?= $material['codigo'] ?></div>
        <div><strong>Local:</strong> <?= $material['localizacao'] ?></div>
        <svg id="barcode"></svg>
        <button onclick="window.print()" class="btn btn-dark btn-sm mt-2">🖨️ Imprimir</button>
      </div>
    </div>
  </div>

  <script>
    JsBarcode("#barcode", "<?= $material['codigo'] ?>", {
      format: "CODE128",
      lineColor: "#000",
      width: 2,
      height: 40,
      displayValue: true
    });
  </script>
<?php endif; endif; ?>
</div>
</body>
</html>
