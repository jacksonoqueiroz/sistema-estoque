<?php
require_once 'conexao.php';

$id_peca = $_GET['id_peca'] ?? null;
$id_solicitacao = $_GET['id_solicitacao'] ?? null;

$msg = '';



if ($id_peca) {
    $stmt = $pdo->prepare("SELECT i.id, i.codigo, i.nome, i.qtd_itens, i.estoque_atual, li.local
        FROM itens i
        LEFT JOIN local_itens li ON li.id_item = i.id
        WHERE i.id_peca = ?");
    $stmt->execute([$id_peca]);
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
//Atualização para baixar novos itens
$ja_baixado = $pdo->prepare("SELECT status FROM solicitacoes_producao WHERE id_peca = ? ORDER BY id DESC LIMIT 1");
$ja_baixado->execute([$id_peca]);
$status = $ja_baixado->fetchColumn();

if ($status === 'baixado') {
    echo "<script>alert('Itens já foram baixados.'); history.back();</script>";
    exit;
}


include 'include/head.php';
?>


    <title>Baixar Itens da Peça</title>

</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-4">
    <h3 class="mb-3">Baixar Itens da Peça</h3>
    <?= $msg ?>

    <?php if ($itens): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Qtd. Necessária</th>
                    <th>Estoque Atual</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['codigo']) ?></td>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= $item['qtd_itens'] ?></td>
                        <td><?= $item['estoque_atual'] ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="id_item" value="<?= $item['id'] ?>">
                                <!-- <button class="btn btn-sm btn-danger" type="submit">Dar Baixa</button>-->
                                <a href="baixar_itens.php?id_solicitacao=<?= $id_solicitacao ?>" class="btn btn-primary">Dar Baixa nos Itens</a>

                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">Nenhum item encontrado para esta peça.</div>
    <?php endif; ?>

    <a href="listar_producao.php" class="btn btn-secondary mt-3">Voltar</a>
</div>

</body>
</html>


