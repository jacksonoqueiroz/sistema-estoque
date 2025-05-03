<?php

require 'conexao.php';

// Buscar locações
$stmt = $pdo->query("
    SELECT li.id,
    li.id_item, 
    li.local, 
    i.nome AS nome_item, 
    i.codigo, 
    p.nome AS nome_peca
    FROM local_itens li
    JOIN itens i ON li.id_item = i.id
    JOIN peca p ON i.id_peca = p.id
    ORDER BY li.id DESC
");
$locacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);


include 'include/head.php';
?>

    <title>Itens Alocados</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container mt-5">
    <h2 class="mb-4">📦 Lista de Locação de Itens</h2>

    <?php if (count($locacoes) > 0): ?>
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Item</th>
                    <th>Peça</th>
                    <th>Local</th>
                    <th>Etiqueta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locacoes as $loc): ?>
                    <tr>
                        <td><?= $loc['codigo'] ?></td>
                        <td><i class="bi bi-box-seam"></i> <?= htmlspecialchars($loc['nome_item']) ?></td>
                        <td><i class="bi bi-gear-fill"></i> <?= htmlspecialchars($loc['nome_peca']) ?></td>
                        <td><span class="badge bg-primary fs-6"><?= $loc['local'] ?></span></td>
                        <td>
                    <a href="etiqueta_individual.php?id_item=<?= $loc['id_item'] ?>" 
                       class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="bi bi-printer"></i> Imprimir
                    </a>
                </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-warning">Nenhum item alocado ainda.</div>
    <?php endif; ?>
</div>

<!-- Ícones Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
