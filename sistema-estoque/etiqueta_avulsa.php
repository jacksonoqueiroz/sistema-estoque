<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

// Busca os pedidos já recebidos
$stmt = $pdo->query("
    SELECT pc.id AS pedido_id, m.nome AS material, f.nome AS fornecedor
    FROM pedidos_compra pc
    JOIN itens m ON m.id = pc.item_id
    JOIN fornecedores f ON f.id = pc.fornecedor_id
    WHERE pc.status = 'recebido'
    ORDER BY pc.id DESC
");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'include/head.php';
?>

    <title>Etiqueta Avulsa</title>
    
</head>
<body>

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            <h5>🖨️ Impressão de Etiqueta Avulsa</h5>
        </div>
        <div class="card-body">
            <form action="etiqueta_recebimento.php" method="GET">
                <div class="mb-3">
                    <label for="pedido_id" class="form-label">Selecione o Pedido Recebido:</label>
                    <select name="pedido_id" id="pedido_id" class="form-select" required>
                        <option value="">-- Escolha um pedido --</option>
                        <?php foreach ($pedidos as $pedido): ?>
                            <option value="<?= $pedido['pedido_id'] ?>">
                                #<?= $pedido['pedido_id'] ?> - <?= $pedido['material'] ?> (<?= $pedido['fornecedor'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Gerar Etiqueta</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
