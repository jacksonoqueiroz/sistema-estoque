<?php
session_start();
require 'conexao.php';


// Buscar todos os itens
$stmt = $pdo->query("SELECT id, codigo, nome, qtd_itens, estoque_minimo FROM itens ORDER BY nome ASC");
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Inclui o Head Cabeçalho
include 'include/head.php';

?>

    <title>Itens em Estoque</title>
    
</head>
<body>

<?php include 'include/sidebar.php'; ?>

<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">📦 Itens em Estoque</h2>

        <!-- Alerta quando for editado -->
        <?php if (isset($_GET['editado']) && $_GET['editado'] == 1): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Alteração salva com sucesso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>


        <!-- Campo de Busca JAVASCRIPT -->
            <!-- <div class="mb-4">
                <input type="text" id="busca" class="form-control" placeholder="🔍 Buscar por Código ou Nome...">
            </div> -->

            <!-- PESQUISAR EM AJAX-->
            <div class="mb-4">
                <input type="text" id="busca" class="form-control" placeholder="🔍 Buscar Itens...">
            </div>

    <div id="tabela-itens">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped table-sm align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Código</th>
                        <th>Nome do Item</th>
                        <th>Estoque Atual</th>
                        <th>Estoque Mínimo</th>
                        <th>Status</th>
                        <th>⚙️</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($itens as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['codigo']) ?></td>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td><?= htmlspecialchars($item['qtd_itens']) ?></td>
                            <td><?= htmlspecialchars($item['estoque_minimo']) ?></td>
                            <td>
                                <?php if($item['qtd_itens'] <= $item['estoque_minimo']): ?>
                                    <span class="badge badge-baixo">🚨 Baixo</span>
                                <?php else: ?>
                                    <span class="badge badge-ok">✅ Ok</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar_item.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($itens) == 0): ?>
                        <tr>
                            <td colspan="5" class="text-center">Nenhum item encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>



<!-- JAVASCRIPT CUSTOMIZADO DAS FUNÇÕES DO PESQUISAR-->
<script src="js/custom.js"></script>
</body>
</html>
