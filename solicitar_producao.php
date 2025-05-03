<?php
require_once 'conexao.php'; // Sua conexão padrão

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



// Solicitar produção (caso postado)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_peca'])) {
    $id_peca = $_POST['id_peca'];

    // Verificar se já foi solicitada
    $stmt = $pdo->prepare("SELECT * FROM solicitacoes_producao WHERE id_peca = ? AND status = 'solicitado'");
    $stmt->execute([$id_peca]);
    if ($stmt->rowCount() === 0) {
        $stmt = $pdo->prepare("INSERT INTO solicitacoes_producao (id_peca, status) VALUES (?, 'solicitado')");
            $stmt->execute([$id_peca]);

            $id_solicitacao = $pdo->lastInsertId(); // <- Aqui você pega o ID gerado da solicitação

            header("Location: separar_itens.php?id_peca=$id_peca&id_solicitacao=$id_solicitacao");
            exit;            

        // echo "<script>alert('Solicitação registrada com sucesso!');</script>";
        $mensagem = "<div class='alert alert-success mt-3'>Solicitação de produção realizada com sucesso!</div>";
    } else {
        // echo "<script>alert('Esta peça já foi solicitada!');</script>";
        $mensagem = "<div class='alert alert-warning mt-3'>Esta peça já foi solicitada para produção!</div>";
    }
}

include 'include/head.php';
?>

    <title>Solicitar Produção</title>

</head>
<body>

    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container py-4">
    <h2 class="mb-4">Solicitar Produção de Peças</h2>

    <?php if (isset($mensagem)) echo $mensagem; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle bg-white shadow rounded">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome da Peça</th>
                    <th>Grupo</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($pecas as $peca): ?>
                    <?php
                $stmt = $pdo->prepare("SELECT status FROM solicitacoes_producao WHERE id_peca = ? ORDER BY id DESC LIMIT 1");
                $stmt->execute([$peca['id']]);
                $status = $stmt->fetchColumn();
            ?>
                    <tr>
                        <td><?= $peca['id']; ?></td>
                        <td><?= htmlspecialchars($peca['nome']); ?></td>
                        <td><?= htmlspecialchars($peca['nome_grupo']); ?></td>
                        <td>
                            <?php if ($status == 'solicitado'): ?>
                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Peça já solicitada!">
                                    ⚠️
                                </span>
                                
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($status != 'solicitado'): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id_peca" value="<?= $peca['id']; ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Solicitar Produção
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Já Solicitado</button>
                                      
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($pecas) == 0): ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhuma peça cadastrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Ativa todos os tooltips da página
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>

</body>
</html>
