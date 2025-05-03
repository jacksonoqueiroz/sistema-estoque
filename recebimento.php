<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';

if (isset($_POST['pedido_id'])) {
    $pedido_id = (int)$_POST['pedido_id'];

    $stmt = $pdo->prepare("SELECT * FROM pedidos_compra WHERE id = ?");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pedido) {
        // Atualizar estoque
        // require 'funcoes.php';

        function checarEstoqueMinimo($pdo) {
    $alertas = [];

    $sql = "SELECT * FROM itens WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Item: {$item['nome']} com estoque crítico!";
    }

    $sql = "SELECT * FROM pecas WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($peca = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Peça: {$peca['nome']} com estoque crítico!";
    }

    return $alertas;
}





        function movimentarEstoque($pdo, $tipo, $tabela, $id, $quantidade, $descricao = '') {
        if ($tipo == 'saida') {
            $quantidade = -abs($quantidade);
        }
    
        $campo_estoque = 'estoque_atual';
        $sql = "UPDATE {$tabela} SET {$campo_estoque} = {$campo_estoque} + :quantidade WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':quantidade' => $quantidade,
        ':id' => $id
        ]);

        $sql = "INSERT INTO movimentacoes_estoque (tipo, tabela_referencia, referencia_id, quantidade, descricao)
            VALUES (:tipo, :tabela, :id, :quantidade, :descricao)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
        ':tipo' => $tipo,
        ':tabela' => $tabela,
        ':id' => $id,
        ':quantidade' => abs($quantidade),
        ':descricao' => $descricao
        ]);
    }


        movimentarEstoque($pdo, 'entrada', 'itens', $pedido['item_id'], $pedido['quantidade'], 'Recebimento de Pedido');

        // Atualizar pedido para 'recebido'
        $stmt = $pdo->prepare("UPDATE pedidos_compra SET status = 'recebido', data_recebimento = NOW() WHERE id = ?");
        $stmt->execute([$pedido_id]);
    }

    header('Location: recebimento.php?sucesso=1');
    exit;
}

$pedidos = $pdo->query("SELECT pc.*, i.nome FROM pedidos_compra pc JOIN itens i ON i.id = pc.item_id WHERE pc.status = 'pendente'")->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>

    <title>Recebimento</title>

</head>
<body>

    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container my-5">
    <h1 class="mb-4">📥 Recebimento de Pedidos</h1>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Pedido recebido e estoque atualizado!</div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <select name="pedido_id" class="form-control" required>
                <?php foreach ($pedidos as $pedido): ?>
                    <option value="<?= $pedido['id'] ?>">Pedido #<?= $pedido['id'] ?> - <?= htmlspecialchars($pedido['nome']) ?> (<?= $pedido['quantidade'] ?> unidades)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Confirmar Recebimento</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">🏠 Voltar</a>
</div>
</body>
</html>
