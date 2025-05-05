<?php
include 'conexao/conexao.php';

// Filtro por grupo
$grupoSelecionado = $_GET['grupo'] ?? '';

// Buscar grupos distintos
$stmtGrupos = $conn->query("SELECT DISTINCT nome FROM grupos ORDER BY nome");
$grupos = $stmtGrupos->fetchAll(PDO::FETCH_ASSOC);

// Buscar itens de montagem com JOIN
$sql = "SELECT im.id, im.codigo, im.nome, im.quantidade, g.nome AS grupo_nome 
        FROM itens_montagem im 
        JOIN grupos g ON im.id_grupo = g.id";

if ($grupoSelecionado) {
    $sql .= " WHERE g.nome = :grupo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':grupo', $grupoSelecionado);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'include/head.php';
?>

    <title>Itens de Montagem</title>
</head>
<body>
<!--SIDEBAR MENU LATERAL-->
    <?php include 'include/sidebar.php'; ?>

    <!-- Conteúdo -->
    <div class="content">
    <div class="container-fluid">
        <div class="container mt-5">
<div class="container">
    <h2 class="mb-4">Itens de Montagem</h2>

    <form method="get" class="mb-3">
        <div class="row g-2">
            <div class="col-auto">
                <select name="grupo" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos os Grupos</option>
                    <?php foreach ($grupos as $g): ?>
                        <option value="<?= htmlspecialchars($g['nome']) ?>" <?= $grupoSelecionado == $g['nome'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Nome do Item</th>
                <th>Grupo</th>
                <th>Quantidade Necessária</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($itens) > 0): ?>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['codigo']) ?></td>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= htmlspecialchars($item['grupo_nome']) ?></td>
                        <td><?= htmlspecialchars($item['quantidade']) ?></td>
                        <td>
                            <a href="editar_item_montagem.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="excluir_item_montagem.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este item?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">Nenhum item encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="cadastrar_item_montagem.php" class="btn btn-primary">+ Cadastrar Novo Item</a>
</div>

</body>
</html>
