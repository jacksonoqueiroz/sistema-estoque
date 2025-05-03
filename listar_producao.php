<?php
// listar_producao.php

// Inclui a conexão
require 'conexao.php';

// Busca todas as solicitações de produção na tabela itens_peca
$stmt = $pdo->prepare("SELECT 
                          ip.id, ip.id_peca, ip.status,
                          p.nome AS nome_peca, p.codigo AS codigo_peca,
                          g.nome AS nome_grupo
                        FROM solicitacoes_producao ip
                        INNER JOIN peca p ON ip.id_peca = p.id
                        LEFT JOIN grupos g ON p.id_grupo = g.id
                        WHERE ip.status = 'solicitado'
                        ORDER BY ip.id DESC");
$stmt->execute();
$solicitacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>

    <title>Solicitações de Produção</title>

</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container py-4">
    <h2 class="mb-4">📋 Solicitações de Produção</h2>

    <?php if (count($solicitacoes) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle bg-white shadow-sm rounded">
                <thead class="table-dark">
                    <tr>
                        <th>Código da Peça</th>
                        <th>Nome da Peça</th>
                        <th>Grupo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitacoes as $solicitacao): ?>
                        <tr>
                            <td><?= htmlspecialchars($solicitacao['codigo_peca']) ?></td>
                            <td><?= htmlspecialchars($solicitacao['nome_peca']) ?></td>
                            <td><?= htmlspecialchars($solicitacao['nome_grupo']) ?></td>
                            <td>
                                <?php if ($solicitacao['status'] == 'solicitado'): ?>
                                    <span class="badge bg-warning text-dark" data-bs-toggle="tooltip" data-bs-placement="top" title="Peça já solicitada!">
                                        ⚠️ Solicitado
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">Disponível</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="separar_itens.php?id_peca=<?= $solicitacao['id_peca'] ?>&" class="btn btn-success btn-sm">
                                    📦 Separar Itens
                                </a>
                                <a href="excluir_solicitacao.php?id_peca=<?= $solicitacao['id_peca'] ?>" 
   class="btn btn-danger btn-sm" 
   onclick="return confirm('Tem certeza que deseja cancelar a solicitação dessa peça?');">
   <i class="bi bi-x-circle"></i> Cancelar
</a>


                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Nenhuma solicitação de produção encontrada.</div>
    <?php endif; ?>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Ativa os tooltips do Bootstrap
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>

</body>
</html>
