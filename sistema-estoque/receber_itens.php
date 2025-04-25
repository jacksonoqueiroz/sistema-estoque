<?php
include 'conexao.php';

$mensagem = '';

// Receber via POST (atualiza estoque e marca como recebido)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];

    $stmt = $pdo->prepare("SELECT item_id, quantidade FROM pedidos_compra WHERE id = ? AND status = 'pendente'");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pedido) {
        // Atualiza estoque
        $stmt = $pdo->prepare("UPDATE itens SET estoque_atual = estoque_atual + ? WHERE id = ?");
        $stmt->execute([$pedido['quantidade'], $pedido['item_id']]);

       // Atualiza status do pedido
        $pdo->prepare("UPDATE pedidos_compra SET status = 'recebido', data_recebimento = NOW() WHERE id = ?")->execute([$pedido_id]);

        // $mensagem = "✅ Pedido #$pedido_id recebido com sucesso!";
        // Agora redireciona para a etiqueta
        header("Location: etiqueta_recebimento.php?pedido_id=" . $pedido_id);
        exit;
    } else {
        $mensagem = "⚠️ Pedido já recebido ou inválido.";
    }
}

// Buscar todos os pedidos pendentes
$pendentes = $pdo->query("
    SELECT pc.id, f.nome AS fornecedor, m.nome AS material, m.codigo, pc.quantidade, pc.preco_unitario, 
           (pc.quantidade * pc.preco_unitario) AS total, pc.data_pedido
    FROM pedidos_compra pc
    JOIN fornecedores f ON f.id = pc.fornecedor_id
    JOIN itens m ON m.id = pc.item_id
    WHERE pc.status = 'pendente'
    ORDER BY pc.data_pedido DESC
")->fetchAll();

include 'include/head.php';
?>

<title>Receber Pedido Itens</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-4">
    <h4>📦 Recebimento de Pedidos de Itens</h4>

    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <?php if ($pendentes): ?>
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th>#Pedido</th>
                    <th>Fornecedor</th>
                    <th>Código</th>
                    <th>Itens</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Data</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendentes as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['fornecedor']) ?></td>
                    <td><?= htmlspecialchars($p['codigo']) ?></td>
                    <td><?= htmlspecialchars($p['material']) ?></td>
                    <td><?= $p['quantidade'] ?></td>
                    <td>R$ <?= number_format($p['preco_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($p['total'], 2, ',', '.') ?></td>
                    <td><?= date('d/m/Y', strtotime($p['data_pedido'])) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="pedido_id" value="<?= $p['id'] ?>">
                            <button type="submit" class="btn btn-success btn-sm">✅ Receber</button>
                        </form>
                        <!-- <form action="recebimento_pedido.php" method="POST">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                        <button type="submit" class="btn btn-success">✅Confirmar</button> -->
                        
                    </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-success">🎉 Todos os pedidos já foram recebidos!</div>
    <?php endif; ?>
</div>
