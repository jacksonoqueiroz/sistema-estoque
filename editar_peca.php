<?php
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: login.php');
//     exit;
// }
require 'conexao.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: pecas.php');
    exit;
}

// Pega o ID da peça
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca dados da peça
$stmt = $pdo->prepare("
    SELECT peca.id, peca.nome, peca.id_grupo
    FROM peca
    WHERE peca.id = ?
");
$stmt->execute([$id]);
$peca = $stmt->fetch(PDO::FETCH_ASSOC);

// Busca todos os grupos para o select
$gruposStmt = $pdo->query("SELECT id, nome FROM grupos ORDER BY nome");
$grupos = $gruposStmt->fetchAll(PDO::FETCH_ASSOC);

// Se não encontrou peça, redireciona
if (!$peca) {
    header('Location: listar_pecas.php');
    exit;
}

// Atualiza a peça se postou o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $grupo_id = intval($_POST['id_grupo']);

    $update = $pdo->prepare("UPDATE peca SET nome = ?, id_grupo = ? WHERE id = ?");
    $update->execute([$nome, $grupo_id, $id]);

    // Redireciona com mensagem de sucesso
    header('Location: pecas_grupos.php?editado=1');
    exit;
}

include 'include/head.php';

?>

    <title>Editar Peça</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-5">
    <h2 class="mb-4">✏️ Editar Peça</h2>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Nome da Peça</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($peca['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Grupo</label>
            <select name="id_grupo" class="form-select" required>
                <option value="">Selecione o Grupo</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>" <?= $grupo['id'] == $peca['id_grupo'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($grupo['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="pecas_grupos.php" class="btn btn-secondary">← Voltar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>

</body>
</html>
