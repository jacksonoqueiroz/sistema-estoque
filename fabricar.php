<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';
session_start();

// Buscar todas as peças disponíveis
$pecas = $pdo->query("SELECT * FROM peca")->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';

?>


    <title>Fabricação de Peças</title>
</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container mt-5">
<h2>Fabricar Peça</h2>

<form action="processar_fabricacao.php" method="POST">
    <div class="mb-3">
        <label for="peca_id" class="form-label">Escolha a Peça:</label>
        <select name="peca_id" id="peca_id" class="form-select" required>
            <option value="">-- Selecione --</option>
            <?php foreach ($pecas as $peca): ?>
                <option value="<?= $peca['id'] ?>"><?= htmlspecialchars($peca['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Fabricar</button>
</form>
</div>
</div>

</body>
</html>
