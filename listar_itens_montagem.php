<?php
include 'conexao/conexao.php';

$sql_grupos = "SELECT * FROM grupos";
$stmt_grupos = $conn->prepare($sql_grupos);
$stmt_grupos->execute();
$grupos = $stmt_grupos->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>

    <title>Lista de Itens para Montagem</title>
    
</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
    <?php include 'include/sidebar.php'; ?>

    <!-- Conteúdo -->
    <div class="content">
    <div class="container-fluid">
        <div class="container mt-5">

<h2>Itens de Montagem por Grupo</h2>

<?php foreach ($grupos as $grupo): ?>
    <div class="row" style="margin-bottom: 8px;">
        <div class="col-2">
            <h3><?= $grupo['nome'] ?></h3>
        </div>
        <div class="col-md-3">
             <a href="fichas_grupo.php?id_grupo=<?= $grupo['id'] ?>" class="btn btn-success">Gerar Fichas</a>
        </div> 
    </div>
    
    <table class="table table-bordered table-hover align-middle bg-white shadow-sm rounded">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Nome do Item</th>
                <th>Localização</th>
                <th>Quantidade Necessária</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql_itens = "SELECT * FROM itens_montagem WHERE id_grupo = ?";
        $stmt_itens = $conn->prepare($sql_itens);
        $stmt_itens->execute([$grupo['id']]);
        $itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

        if (count($itens) > 0):
            foreach ($itens as $item): ?>
                <tr>
                    <td><?= $item['codigo'] ?></td>
                    <td><?= $item['nome'] ?></td>
                    <td><?= $item['localizacao'] ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>
                        <a href="editar_item_montagem.php?id=<?= $item['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                        <a href="ficha_separacao.php?id=<?= $item['id'] ?>" class="btn btn-primary btn-sm">Separar</a>
                        
                    </td>

                </tr>
            <?php endforeach;
        else: ?>
            <tr><td colspan="4">Nenhum item cadastrado neste grupo.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
<?php endforeach; ?>
</div>
</div>
</div>