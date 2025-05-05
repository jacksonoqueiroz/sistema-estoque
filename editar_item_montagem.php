<?php
include 'conexao/conexao.php';

if (!isset($_GET['id'])) {
    echo "ID do item não informado.";
    exit;
}

$id = $_GET['id'];

$sql = "SELECT * FROM itens_montagem WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "Item não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];

    $update = "UPDATE itens_montagem SET nome = ?, quantidade = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update);
    $stmt_update->execute([$nome, $quantidade, $id]);

    echo "<script>alert('Item atualizado com sucesso!'); window.location.href='listar_itens_montagem.php';</script>";
    exit;
}
include 'include/head.php';
?>

    <title>Editar Itens para Montagem</title>
    
</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
    <?php include 'include/sidebar.php'; ?>

    <!-- Conteúdo -->
    <div class="content">
    <div class="container-fluid">
        <div class="container mt-5">
<h2>Editar Item de Montagem</h2>

<form method="post">
    <div class="mb-3">
    <label for="codigo" class="form-label">Nome:</label><br>
    <input class="form-control" type="text" name="nome" value="<?= htmlspecialchars($item['nome']) ?>" required><br><br>
    </div>
    <div class="mb-3">
    <label for="codigo" class="form-label">Quantidade:</label><br>
    <input class="form-control" type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="1" required><br><br>
    </div>
    <button type="submit" class="btn btn-success">Salvar Alterações</button>
    <a class="btn btn-primary" href="listar_itens_montagem.php">Cancelar</a>
</div>
</body>
</html>
