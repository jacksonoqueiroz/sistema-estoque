<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

$fornecedor_id = $_GET['fornecedor_id'] ?? '';
$data = $_GET['data'] ?? '';

// Consulta principal agrupada por fornecedor
$query = "SELECT f.id AS fornecedor_id, f.nome AS fornecedor, 
                 m.nome AS material, pc.quantidade, pc.preco_unitario, 
                 (pc.quantidade * pc.preco_unitario) AS total_item, pc.data_pedido
          FROM pedidos_compra pc
          JOIN fornecedores f ON f.id = pc.fornecedor_id
          JOIN itens m ON m.id = pc.item_id
          WHERE f.tipo = 'material'";

$params = [];

if ($fornecedor_id) {
    $query .= " AND f.id = ?";
    $params[] = $fornecedor_id;
}
if ($data) {
    $query .= " AND pc.data_pedido = ?";
    $params[] = $data;
}

$query .= " ORDER BY f.nome, pc.data_pedido";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupar os dados por fornecedor
$porFornecedor = [];
foreach ($dados as $d) {
    $fid = $d['fornecedor_id'];
    $porFornecedor[$fid]['nome'] = $d['fornecedor'];
    $porFornecedor[$fid]['itens'][] = $d;
}

// Carrega todos os fornecedores para o filtro
$fornecedores = $pdo->query("SELECT id, nome FROM fornecedores WHERE tipo = 'material' ORDER BY nome")->fetchAll();

include 'include/head.php';

?>


    <title>Pedidos de Compra por Fornecedor</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-4">
    <h4>📑 Relatório de Pedidos de Itens por Fornecedor</h4>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Fornecedor:</label>
            <select name="fornecedor_id" class="form-select">
                <option value="">Todos</option>
                <?php foreach ($fornecedores as $f): ?>
                    <option value="<?= $f['id'] ?>" <?= $f['id'] == $fornecedor_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Data do Pedido:</label>
            <input type="date" name="data" class="form-control" value="<?= htmlspecialchars($data) ?>">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary">🔍 Filtrar</button>
        </div>
    </form>

    <?php if ($porFornecedor): ?>
        <?php foreach ($porFornecedor as $fornecedor): ?>
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    Fornecedor: <?= htmlspecialchars($fornecedor['nome']) ?>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped m-0">
                        <thead class="table-light">
                            <tr>
                                <th>Material</th>
                                <th>Quantidade</th>
                                <th>Preço Unitário</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $subtotal = 0;
                            foreach ($fornecedor['itens'] as $item):
                                $subtotal += $item['total_item'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['material']) ?></td>
                                <td><?= $item['quantidade'] ?></td>
                                <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($item['total_item'], 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="3" class="text-end">Total do Fornecedor:</th>
                                <th>R$ <?= number_format($subtotal, 2, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">Nenhum pedido encontrado com os filtros selecionados.</div>
    <?php endif; ?>
</div>
