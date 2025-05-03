<?php

require 'conexao.php';

// Buscar todas as peças
$stmt = $pdo->query("SELECT id, nome FROM peca ORDER BY nome");
$pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../include/head.php';
?>


</head>
<body>

<!--SIDEBAR MENU LATERAL-->
<?php include '.include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
    <div class="container my-5">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4">Selecionar Peça para Produção</h3>

            <form action="verificar_producao.php" method="GET">
                <div class="mb-3">
                    <label for="id_peca" class="form-label">Peça:</label>
                    <select name="id_peca" id="id_peca" class="form-select" required>
                        <option value="" selected disabled>Selecione uma peça</option>
                        <?php foreach ($pecas as $peca): ?>
                            <option value="<?= $peca['id'] ?>">
                                <?= htmlspecialchars($peca['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Verificar Produção</button>
            </form>
        </div>
    </div>
</body>
</html>
