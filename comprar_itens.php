<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fornecedor_id = $_POST['fornecedor_id'];
    $materiais = $_POST['item_id'];
    $quantidades = $_POST['quantidade'];
    $precos = $_POST['preco_unitario'];

    $stmt = $pdo->prepare("INSERT INTO pedidos_compra (fornecedor_id, item_id, quantidade, preco_unitario, valor_total, data_pedido, status)
                           VALUES (?, ?, ?, ?, ?, NOW(), 'pendente')");

    for ($i = 0; $i < count($materiais); $i++) {
        $valor_total = $quantidades[$i] * $precos[$i];
        $stmt->execute([$fornecedor_id, $materiais[$i], $quantidades[$i], $precos[$i], $valor_total]);
    }

    echo '<div class="alert alert-success mt-3">Pedido realizado com sucesso!</div>';
}

include 'include/head.php';

?>

 <title>Comprar Itens</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-5">
    <h3>📦 Pedido de Compra - Itens</h3>
    <form method="POST" id="pedidoForm">
        <div class="mb-3">
            <label>Fornecedor:</label>
            <select name="fornecedor_id" class="form-select" required>
                <option value="">Selecione</option>
                <?php
                $fornecedores = $pdo->query("SELECT id, nome FROM fornecedores WHERE tipo = 'material' ORDER BY nome")->fetchAll();
                foreach ($fornecedores as $f):
                ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="materiaisContainer">
            <div class="row mb-3 material-row">
                <div class="col-md-5">
                    <label>Material:</label>
                    <select name="item_id[]" class="form-select" required>
                        <option value="">Selecione</option>
                        <?php
                        $materiais = $pdo->query("SELECT id, nome FROM itens ORDER BY nome")->fetchAll();
                        foreach ($materiais as $m):
                        ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Qtd:</label>
                    <input type="number" name="quantidade[]" class="form-control" min="1" required>
                </div>
                <div class="col-md-3">
                    <label>Preço Unitário (R$):</label>
                    <input type="number" step="0.01" name="preco_unitario[]" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-row">🗑️</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mt-2" id="addMaterial">➕ Adicionar Material</button>
        <button type="submit" class="btn btn-success mt-3">💾 Salvar Pedido</button>
    </form>
</div>

<script>
document.getElementById('addMaterial').addEventListener('click', () => {
    const container = document.getElementById('materiaisContainer');
    const row = container.querySelector('.material-row').cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = '');
    container.appendChild(row);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('.material-row');
        if (rows.length > 1) e.target.closest('.material-row').remove();
    }
});
</script>
