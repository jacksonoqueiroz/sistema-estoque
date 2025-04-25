<?php

require 'conexao.php';
session_start();

include 'include/head.php';


$stmt = $pdo->query("
    SELECT 
    peca.id AS peca_id,
    peca.nome AS peca_nome,
    grupos.nome AS grupo_nome
FROM 
    peca
INNER JOIN 
    grupos ON peca.id_grupo = grupos.id
ORDER BY 
    grupos.nome, peca.nome;

");

$pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

    <title>Peças Por Grupos</title>
    
    
</head>
<body>

<?php include 'include/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">


<div class="container mt-5">
    <h2 class="mb-4">🔧 Peças Organizadas por Grupo</h2>
    <?php if (isset($_GET['editado']) && $_GET['editado'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Peça editada com sucesso! 🎯
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    <?php
    $grupoAtual = null;
    foreach ($pecas as $peca):
        // Detecta mudança de grupo
        if ($peca['grupo_nome'] != $grupoAtual):
            if ($grupoAtual !== null) {
                echo "</ul>"; // Fecha lista anterior
            }
            $grupoAtual = $peca['grupo_nome'];
            ?>
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <strong>⚙️ <?= htmlspecialchars($grupoAtual) ?></strong>
                </div>
                <ul class="list-group list-group-flush">
        <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($peca['peca_nome']) ?>
                    <a href="editar_peca.php?id=<?= $peca['peca_id'] ?>" class="btn btn-sm btn-outline-secondary">
                        ✏️ Editar
                    </a>
                </li>
    <?php endforeach; ?>
    <?php if (!empty($pecas)) echo "</ul></div>"; ?>
</div>
   