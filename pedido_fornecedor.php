<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

$fornecedores = $pdo->query("SELECT id, nome FROM fornecedores WHERE tipo = 'material'")->fetchAll(PDO::FETCH_ASSOC);
$materiais = $pdo->query("SELECT id, nome FROM itens ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fornecedor_id = $_POST['fornecedor_id'];
    $material_id = $_POST['material_id'];
    $quantidade = $_POST['quantidade'];
    $preco_unitario = $_POST['preco_unitario'];
    $valor_total = $quantidade * $preco_unitario;
    $prazo_entrega = $_POST['prazo_entrega'];
    $data = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO pedidos_compra (fornecedor_id, item_id, quantidade, preco_unitario, valor_total, data_pedido, prazo_entrega, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pendente')");
    $stmt->execute([$fornecedor_id, $material_id, $quantidade, $preco_unitario, $valor_total, $data, $prazo_entrega]);

    echo "<script>alert('Pedido cadastrado com sucesso!');window.location='pedido_fornecedor.php';</script>";
}

include 'include/head.php';

?>

 <title>Pedidos de Compra fornecedor</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-5">
    <h2>🛒 Comprar de Material</h2>
    <form method="POST" class="mt-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label>Fornecedor</label>
                <select class="form-select" name="fornecedor_id" required>
                    <option value="">Selecione</option>
                    <?php foreach ($fornecedores as $f): ?>
                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label>Material</label>
                <select class="form-select" name="material_id" required>
                    <option value="">Selecione</option>
                    <?php foreach ($materiais as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Quantidade</label>
                <input type="number" name="quantidade" class="form-control" min="1" required>
            </div>
            <div class="col-md-3">
                <label>Preço Unitário (R$)</label>
                <input type="number" step="0.01" name="preco_unitario" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Prazo de Entrega</label>
                <input type="date" name="prazo_entrega" class="form-control" required>
            </div>
            <div class="col-12 mt-3">
                <button class="btn btn-primary">Salvar Pedido</button>
            </div>
        </div>
    </form>
</div>
