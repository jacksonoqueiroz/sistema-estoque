<?php

require 'conexao.php';

include 'include/head.php';


?>

    <title>Peças</title>
    
    
</head>
<body>

<?php include 'include/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">🔧 Peças Cadastradas</h2>

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
            $stmt = $pdo->query("SELECT id, codigo, nome, estoque_atual, estoque_minimo FROM peca ORDER BY nome ASC");
            $pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Código</th>
                        <th>Nome da Peça</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Mínimo</th>
                        <th>Status</th>
                        <th>⚙️</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pecas as $peca): ?>
                        <tr>
                            <td><?= htmlspecialchars($peca['codigo']) ?></td>
                            <td><?= htmlspecialchars($peca['nome']) ?></td>
                            <td><?= htmlspecialchars($peca['estoque_atual']) ?></td>
                            <td><?= htmlspecialchars($peca['estoque_minimo']) ?></td>
                            <td>
                                <?php if($peca['estoque_atual'] <= $peca['estoque_minimo']): ?>
                                    <span class="badge bg-danger">🚨 Baixo</span>
                                <?php else: ?>
                                    <span class="badge bg-success">✅ Ok</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar_peca.php?id=<?= $peca['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
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
