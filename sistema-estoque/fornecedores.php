<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['usuario_tipo'] != 'admin') {
    die('Acesso negado!');
}
include 'conexao.php'; // sua conexão PDO

$mensagem = '';

// Busca materiais cadastrados
$materiais = $pdo->query("SELECT id, nome FROM material ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

// Quando enviar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $selecionados = $_POST['selecionados'] ?? [];

    try {
        $pdo->beginTransaction();

        // Cadastra fornecedor
        $stmt = $pdo->prepare("
            INSERT INTO fornecedores (nome, cnpj, telefone, email, endereco, tipo)
            VALUES (:nome, :cnpj, :telefone, :email, :endereco, 'material')
        ");
        $stmt->execute([
            ':nome' => $nome,
            ':cnpj' => $cnpj,
            ':telefone' => $telefone,
            ':email' => $email,
            ':endereco' => $endereco
        ]);

        $fornecedorId = $pdo->lastInsertId();

        // Relaciona fornecedor aos materiais selecionados
        foreach ($selecionados as $materialId) {
            $stmtMat = $pdo->prepare("
                INSERT INTO fornecedor_materiais (fornecedor_id, material_id)
                VALUES (:fornecedor_id, :material_id)
            ");
            $stmtMat->execute([
                ':fornecedor_id' => $fornecedorId,
                ':material_id' => $materialId
            ]);
        }

        $pdo->commit();
        $mensagem = 'Fornecedor cadastrado com sucesso!';
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensagem = 'Erro ao cadastrar fornecedor: ' . $e->getMessage();
    }
}

include 'include/head.php';
?>

<title>Cadastro de Fornecedores</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>
<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<!-- HTML -->
<div class="container mt-5">
    <h2>📦 Cadastro de Fornecedores (Materiais)</h2>

    <?php if ($mensagem): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="row g-3">
            <!-- Dados do Fornecedor -->
            <div class="col-md-6">
                <label class="form-label">Nome *</label>
                <input type="text" name="nome" class="form-control" autocomplete="false" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">CNPJ</label>
                <input type="text" name="cnpj" class="form-control" autocomplete="false">
            </div>

            <div class="col-md-6">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control" autocomplete="false">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" autocomplete="false">
            </div>

            <div class="col-12">
                <label class="form-label">Endereço</label>
                <input type="text" name="endereco" class="form-control" autocomplete="false">
            </div>

            <div class="mb-3">
                <label class="form-label">Fornecimento</label>
                    <select name="selecionados[]" class="form-select" required>
                        <option value="">Selecione...</option>
                            <?php foreach ($materiais as $material): ?>
                        <option value="<?= $material['id'] ?>">
                            <?= htmlspecialchars($material['nome']) ?>
                        </option>
                            <?php endforeach; ?>
                    </select>
            </div>

            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-success">Salvar Fornecedor</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>


