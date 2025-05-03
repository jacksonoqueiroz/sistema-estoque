<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

// Buscar fornecedores com seus materiais
$query = $pdo->query("
    SELECT f.id, f.nome, f.telefone, GROUP_CONCAT(m.nome SEPARATOR ', ') as materiais
    FROM fornecedores f
    LEFT JOIN fornecedor_materiais fm ON f.id = fm.fornecedor_id
    LEFT JOIN material m ON fm.material_id = m.id
    WHERE f.tipo = 'material'
    GROUP BY f.id
    ORDER BY f.nome
");
$fornecedores = $query->fetchAll(PDO::FETCH_ASSOC);

//Cabeçalho para estilos
include 'include/head.php';
?>
<title>Lista de Fornecedores</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>
<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">


<div class="container mt-5">
    <h2>📋 Fornecedores de Materiais</h2>
    

    <?php if (isset($_GET['excluido'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                Fornecedor excluído com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>


    <table class="table table-bordered table-hover mt-4 align-middle">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Materiais Fornecidos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fornecedores as $fornecedor): ?>
                <tr>
                    <td><?= htmlspecialchars($fornecedor['nome']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                    <td><?= htmlspecialchars($fornecedor['materiais'] ?? 'Nenhum material') ?></td>
                    <td>
                        <a href="editar_fornecedor.php?id=<?= $fornecedor['id'] ?>" class="btn btn-primary btn-sm">
                            Editar
                        </a>
                        <a href="excluir_fornecedor.php?id=<?= $fornecedor['id'] ?>" 
                                class="btn btn-danger btn-sm" 
                        onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                            Excluir
                            </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


