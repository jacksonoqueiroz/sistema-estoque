<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

// Buscar pedidos pendentes
$pedidos = $pdo->query("
    SELECT p.*, f.nome AS fornecedor, m.nome AS material,
    p.quantidade
    FROM pedidos_compra p
    JOIN fornecedores f ON p.fornecedor_id = f.id
    JOIN itens m ON p.item_id = m.id
    WHERE p.status = 'pendente'
    ORDER BY p.data_pedido
")->fetchAll(PDO::FETCH_ASSOC);

// Receber pedido
if (isset($_GET['receber'])) {
    $pedido_id = $_GET['receber'];

    // Buscar dados do pedido
    $stmt = $pdo->prepare("SELECT item_id, quantidade FROM pedidos_compra WHERE id = ?");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pedido) {
        // Atualiza estoque
        $stmt = $pdo->prepare("UPDATE itens SET estoque_atual = estoque_atual + ? WHERE id = ?");
        $stmt->execute([$pedido['quantidade'], $pedido['item_id']]);

        // Atualiza status do pedido
        $pdo->prepare("UPDATE pedidos_compra SET status = 'recebido' WHERE id = ?")->execute([$pedido_id]);

        echo "<script>alert('Material recebido com sucesso e estoque atualizado!');window.location='recebimento_material.php';</script>";
    }
}

include 'include/head.php';

?>
<title>Receber Pedido Materiais</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-5">
    <h2>📦 Recebimento de Materiais</h2>
    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Fornecedor</th>
                <th>Material</th>
                <th>Quantidade</th>
                <th>Preço Unitário (R$)</th>
                <th>Valor Total (R$)</th>
                <th>Data Pedido</th>
                <th>Prazo Entrega</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['fornecedor']) ?></td>
                    <td><?= htmlspecialchars($p['material']) ?></td>
                    <td><?= $p['quantidade'] ?></td>
                    <td>R$ <?= number_format($p['preco_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($p['valor_total'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['prazo_entrega'])) ?></td>
                    <td>
                        <a href="?receber=<?= $p['id'] ?>" class="btn btn-success btn-sm"
                           onclick="return confirm('Confirmar recebimento deste pedido?')">
                           Receber
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($pedidos) === 0): ?>
                <tr><td colspan="8" class="text-center">Nenhum pedido pendente.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
