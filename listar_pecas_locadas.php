<?php

require 'conexao.php';

// Buscar peças locadas
$stmt = $pdo->query("
    SELECT 
        lp.id,
        lp.local,
        p.id AS id_peca,
        p.codigo,
        p.nome AS nome_peca,
        g.nome AS nome_grupo
    FROM local_pecas lp
    JOIN peca p ON lp.id_peca = p.id
    JOIN grupos g ON p.id_grupo = g.id
    ORDER BY lp.id DESC
");
$locacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>

    <title>Peças Locadas</title>

</head>
<body><!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">Peças Locadas</h2>
        <table class="table table-bordered table-hover shadow-sm bg-white">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Peça</th>
                    <th>Grupo</th>
                    <th>Local</th>
                    <th>Etiqueta</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locacoes as $loc): ?>
                    <tr>
                        <td><?= $loc['codigo'] ?></td>
                        <td><?= htmlspecialchars($loc['nome_peca']) ?></td>
                        <td><?= htmlspecialchars($loc['nome_grupo']) ?></td>
                        <td><?= htmlspecialchars($loc['local']) ?></td>
                        <td>
                            <a href="etiqueta_peca.php?id_peca=<?= $loc['id_peca'] ?>" 
                               class="btn btn-outline-dark btn-sm" target="_blank">
                                <i class="bi bi-printer"></i> Imprimir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
