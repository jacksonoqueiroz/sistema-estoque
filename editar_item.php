<?php
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: login.php');
//     exit;
// }
require 'conexao.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: itens.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM itens WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header('Location: itens.php');
    exit;
}

// Se enviou o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo']);
    $nome = trim($_POST['nome']);
    $quantidade = (int) $_POST['qtd_itens'];
    $estoque_minimo = (int) $_POST['estoque_minimo'];

    $update = $pdo->prepare("UPDATE itens SET codigo=?, nome=?, qtd_itens=?, estoque_minimo=? WHERE id=?");
    $update->execute([$codigo, $nome, $quantidade, $estoque_minimo, $id]);

    header('Location: itens.php?editado=1');
    exit;
}

//Cabeçalho para estilização
include 'include/head.php';

?>

    <title>Editar Item</title>
</head>
<body>

<?php include 'include/sidebar.php'; ?>

<div class="content container-fluid p-4">
    <h2>✏️ Editar Item</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required value="<?= htmlspecialchars($item['codigo']) ?>">
        </div>
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required value="<?= htmlspecialchars($item['nome']) ?>">
        </div>
        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" class="form-control" id="qtd_itens" name="qtd_itens" required value="<?= $item['qtd_itens'] ?>">
        </div>
        <div class="mb-3">
            <label for="estoque_minimo" class="form-label">Estoque Mínimo</label>
            <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo" required value="<?= $item['estoque_minimo'] ?>">
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="itens.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
