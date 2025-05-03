<?php
require 'conexao.php';

// Consulta todas as peças cadastradas + se foram solicitadas
$stmt = $pdo->query("
    SELECT 
        peca.id, 
        peca.nome, 
        grupos.nome as nome_grupo,
        (SELECT status FROM itens_peca WHERE id_peca = peca.id ORDER BY id DESC LIMIT 1) as status_producao
    FROM peca
    JOIN grupos ON peca.id_grupo = grupos.id
");
$pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$id_solicitacao = $_GET['id_solicitacao'] ?? null;

if (!$id_solicitacao) {
    echo "<div class='alert alert-danger'>ID da solicitação não informado.</div>";
    exit;
}

// Busca a solicitação
$stmt = $pdo->prepare("SELECT * FROM solicitacoes_producao WHERE id = ?");
$stmt->execute([$id_solicitacao]);
$solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$solicitacao) {
    echo "<div class='alert alert-danger'>Solicitação não encontrada.</div>";
    exit;
}

$id_peca = $solicitacao['id_peca'];

// Busca os itens da peça
$stmtItens = $pdo->prepare("SELECT * FROM itens WHERE id_peca = ?");
$stmtItens->execute([$id_peca]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

// Conta quantos itens devem ser baixados
$qtd_itens = count($itens);

// Conta quantos itens já foram baixados
$stmtBaixados = $pdo->prepare("SELECT COUNT(*) as total FROM itens_baixados WHERE id_solicitacao = ?");
$stmtBaixados->execute([$id_solicitacao]);
$total_baixados = $stmtBaixados->fetch(PDO::FETCH_ASSOC)['total'];

// Ação: Baixar item
if (isset($_GET['baixar'])) {
    $id_item = $_GET['baixar'];

    // Verifica se já foi baixado
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM itens_baixados WHERE id_solicitacao = ? AND id_item = ?");
    $stmtCheck->execute([$id_solicitacao, $id_item]);
    $ja_baixado = $stmtCheck->fetchColumn();

    if (!$ja_baixado) {
        $stmtInsert = $pdo->prepare("INSERT INTO itens_baixados (id_solicitacao, id_item, data_baixa) VALUES (?, ?, NOW())");
        $stmtInsert->execute([$id_solicitacao, $id_item]);
        header("Location: baixar_itens.php?id=$id_solicitacao");
        exit;
    }
}

// Ação: Confirmar baixa total
if (isset($_GET['confirmar'])) {
    $stmtUpdate = $pdo->prepare("UPDATE solicitacoes_producao SET status = 'baixado' WHERE id = ?");
    $stmtUpdate->execute([$id_solicitacao]);
    header("Location: solicitar_producao.php");
    exit;
}

include 'include/head.php';
?>

    <title>Baixar Itens</title>
</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

    <div class="container">
        <h3 class="mb-4">Baixar Itens da Peça: <?= htmlspecialchars($id_peca) ?></h3>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Status</th>
                    <th>Código</th>
                    <th>Nome do Item</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <?php
                    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM itens_baixados WHERE id_solicitacao = ? AND id_item = ?");
                    $stmtCheck->execute([$id_solicitacao, $item['id']]);
                    $baixado = $stmtCheck->fetchColumn();
                    ?>
                    <tr>
                        <td>
                            <?php if ($baixado): ?>
                                <span class="badge bg-success">✔️ Baixado</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">⏳ Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['codigo']) ?></td>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td>
                            <?php if ($baixado): ?>
                                <button class="btn btn-secondary btn-sm" disabled>Dar Baixa</button>
                            <?php else: ?>
                                <!-- <a href="baixar_itens.php?id=<?= $id_solicitacao ?>&id_item=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Dar Baixa</a> -->
                                <form method="post" style="display:inline;" action="baixar_item_individual.php">
                            <input type="hidden" name="id_item" value="<?= $item['id'] ?>">
                            <input type="hidden" name="id_solicitacao" value="<?= $id_solicitacao ?>">
                            <button class="btn btn-warning btn-sm">Dar Baixa</button>
                        </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <p><strong>Itens baixados:</strong> <?= $total_baixados ?> de <?= $qtd_itens ?></p>

            <?php if ($total_baixados == $qtd_itens): ?>
                <a href="baixar_itens.php?id_solicitacao=<?= $id_solicitacao ?>&confirmar=1" class="btn btn-success">✅ Confirmar Baixa Total</a>
            <?php else: ?>
                <div class="alert alert-info">Baixe todos os itens para liberar a confirmação.</div>
            <?php endif; ?>
        </div>

        <a href="solicitar_producao.php" class="btn btn-secondary mt-3">⬅ Voltar</a>
    </div>
</body>
</html>
