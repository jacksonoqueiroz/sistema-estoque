<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Fornecedor não encontrado.";
    exit;
}

// Buscar dados do fornecedor
$stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id = ?");
$stmt->execute([$id]);
$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fornecedor) {
    echo "Fornecedor não encontrado.";
    exit;
}

// Buscar materiais cadastrados
$materiais = $pdo->query("SELECT id, nome FROM material ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Buscar materiais já associados a este fornecedor
$stmt = $pdo->prepare("
    SELECT material_id FROM fornecedor_materiais WHERE fornecedor_id = ?
");
$stmt->execute([$id]);
$materiaisSelecionados = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Atualizar fornecedor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $selecionados = $_POST['selecionados'] ?? [];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            UPDATE fornecedores SET
            nome = :nome,
            cnpj = :cnpj,
            telefone = :telefone,
            email = :email,
            endereco = :endereco
            WHERE id = :id
        ");
        $stmt->execute([
            ':nome' => $nome,
            ':cnpj' => $cnpj,
            ':telefone' => $telefone,
            ':email' => $email,
            ':endereco' => $endereco,
            ':id' => $id
        ]);

        // Deleta associações antigas
        $pdo->prepare("DELETE FROM fornecedor_materiais WHERE fornecedor_id = ?")->execute([$id]);

        // Insere novas associações
        foreach ($selecionados as $materialId) {
            $stmtMat = $pdo->prepare("
                INSERT INTO fornecedor_materiais (fornecedor_id, material_id)
                VALUES (:fornecedor_id, :material_id)
            ");
            $stmtMat->execute([
                ':fornecedor_id' => $id,
                ':material_id' => $materialId
            ]);
        }

        $pdo->commit();

        header("Location: listar_fornecedores.php?success=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao atualizar: " . $e->getMessage();
    }
}

//Cabeçalho para estilos
include 'include/head.php';

?>
<title>Editar Fornecedor</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>
<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">



<!-- HTML -->
<div class="container mt-5">
    <h2>✏️ Editar Fornecedor</h2>

    <form method="POST" class="mt-4">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nome *</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($fornecedor['nome']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">CNPJ</label>
                <input type="text" name="cnpj" class="form-control" value="<?= htmlspecialchars($fornecedor['cnpj']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($fornecedor['telefone']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($fornecedor['email']) ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Endereço</label>
                <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($fornecedor['endereco']) ?>">
            </div>

           <!--  <div class="col-12 mt-3">
                <label class="form-label">Materiais Fornecidos:</label>
                <select class="form-select" name="selecionados[]" multiple required>
                    <?php foreach ($materiais as $material): ?>
                        <option value="<?= $material['id'] ?>" <?= in_array($material['id'], $materiaisSelecionados) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($material['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div> -->

            <div class="mb-3">
                <label class="form-label">Fornecimento</label>
                    <select name="selecionados[]" class="form-select" required>
                        <option value="">Selecione...</option>
                            <?php foreach ($materiais as $material): ?>
                        <option value="<?= $material['id'] ?>" <?= in_array($material['id'], $materiaisSelecionados) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($material['nome']) ?>
                        </option>
                            <?php endforeach; ?>
                    </select>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <a href="listar_fornecedores.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>
