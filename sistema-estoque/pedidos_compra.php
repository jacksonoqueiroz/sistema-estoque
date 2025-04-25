<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
require 'conexao.php';

if (isset($_POST['item_id'])) {
    $item_id = (int)$_POST['item_id'];
    $quantidade = (int)$_POST['quantidade'];

    $stmt = $pdo->prepare("INSERT INTO pedidos_compra (item_id, quantidade) VALUES (?, ?)");
    $stmt->execute([$item_id, $quantidade]);
    
    header('Location: pedidos_compra.php?sucesso=1');
    exit;
}

$itens = $pdo->query("SELECT * FROM itens WHERE estoque_atual <= estoque_minimo")->fetchAll(PDO::FETCH_ASSOC);
$pedidos = $pdo->query("SELECT pc.*, i.nome FROM pedidos_compra pc JOIN itens i ON i.id = pc.item_id WHERE pc.status = 'pendente'")->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';


?>

    <title>Pedidos de Compra</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container my-5">
    <h1 class="mb-4">🛒 Pedidos de Compra</h1>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">Pedido gerado!</div>
    <?php endif; ?>

    <h3>Itens com Estoque Baixo</h3>
    <form method="post">
        <div class="mb-3">
            <select name="item_id" class="form-control" required>
                <?php foreach ($itens as $item): ?>
                    <option value="<?= $item['id'] ?>"><?= htmlspecialchars($item['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <input type="number" name="quantidade" placeholder="Quantidade" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger">Criar Pedido</button>
    </form>

    <h3 class="mt-5">Pedidos Pendentes</h3>
    <ul class="list-group">
        <?php foreach ($pedidos as $pedido): ?>
            <li class="list-group-item">
                Pedido #<?= $pedido['id'] ?> - <?= htmlspecialchars($pedido['nome']) ?> (<?= $pedido['quantidade'] ?> unidades)
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard.php" class="btn btn-secondary mt-3">🏠 Voltar</a>
</div>
</div>
</div>
</body>
</html>
