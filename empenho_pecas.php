<?php

require 'conexao.php';

include 'include/head.php';


?>

    <title>Empenho Peças</title>
    
    
</head>
<body>

<?php include 'include/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">🔧 Empenho Peças de para Montagem</h2>

        <!-- Alerta quando for editado -->
        <?php if (isset($_GET['editado']) && $_GET['editado'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Alteração salva com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>


        <!-- Campo de busca -->
        <div class="mb-4">
            <input type="text" id="busca" class="form-control" placeholder="🔍 Buscar por Código ou Nome da Peça...">
        </div>

        <!-- Tabela de Peças -->
        <div id="tabela-pecas">
            <?php
            $stmt = $pdo->query("SELECT e.id,
                                        e.id_ordem,
                                        e.id_peca,
                                        e.id_solicitacao,
                                        e.qtd_peca,
                                        e.status,
                                        od.codigo AS ordem,
                                        p.codigo AS codigo_peca,
                                        p.nome AS peca
                                FROM entrada_pecas e
                                INNER JOIN ordem_producao od ON e.id_ordem = od.id
                                INNER JOIN peca p ON e.id_peca = p.id");
            $pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Código</th>
                        <th>Peça</th>
                        <th>Qtd Peça</th>
                        <th>Status</th>
                        <th>Ordem</th>
                        <th>⚙️</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pecas as $peca): ?>
                        <tr>
                            <td><?= htmlspecialchars($peca['codigo_peca']) ?></td>
                            <td><?= htmlspecialchars($peca['peca']) ?></td>
                            <td><?= htmlspecialchars($peca['qtd_peca']) ?></td>
                            <td><?= htmlspecialchars($peca['status']) ?></td>
                            <td><?= htmlspecialchars($peca['ordem']) ?></td>
                            <!-- <td>
                                <?php if($peca['estoque_atual'] <= $peca['estoque_minimo']): ?>
                                    <span class="badge bg-danger">🚨 Baixo</span>
                                <?php else: ?>
                                    <span class="badge bg-success">✅ Ok</span>
                                <?php endif; ?>
                            </td> -->
                            <td>
                                <a href="editar_peca.php?id=<?= $peca['id_peca'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($pecas) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center">Nenhuma peça encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>


<script>
// Filtro de Busca em Tempo Real
document.getElementById('busca').addEventListener('keyup', function() {
    var filtro = this.value.toLowerCase();
    var linhas = document.querySelectorAll('tbody tr');

    linhas.forEach(function(linha) {
        var texto = linha.innerText.toLowerCase();
        linha.style.display = texto.includes(filtro) ? '' : 'none';
    });
});
</script>

</body>
</html>
