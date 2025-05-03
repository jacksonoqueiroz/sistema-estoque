<?php

require 'conexao.php';

// Verificar se foi passado o ID da peça para buscar
$id_peca = $_GET['id_peca'] ?? null;
$peca = null;
$itens = [];

if ($id_peca) {
    // Buscar os dados da peça
    $stmt = $pdo->prepare("SELECT * FROM peca WHERE id = ?");
    $stmt->execute([$id_peca]);
    $peca = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($peca) {
        // Buscar todos os itens associados à peça
        $stmt = $pdo->prepare("SELECT * FROM itens WHERE id_peca = ?");
        $stmt->execute([$id_peca]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$liberado = true;

include 'include/head.php';

?>

<title>Verificar Produção</title>
   
</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container">
    <div class="card shadow p-4">
        <h3 class="mb-4">Itens Necessários para Produzir</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código do Item</th>
                    <th>Nome do Item</th>
                    <th>Quantidade Necessária</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                                <td><?= htmlspecialchars($item['codigo']) ?></td>
                                <td><?= htmlspecialchars($item['nome']) ?></td>
                                <td><?= htmlspecialchars($item['qtd_itens']) ?></td>
                            </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        $pode_produzir = true;
        foreach ($itens as $item) {
            if ($item['estoque_atual'] < $item['qtd_itens']) {
                $pode_produzir = false;
                break;
            }
        }
        ?>

        <?php if ($pode_produzir): ?>
            <form action="produzir_peca.php" method="POST">
                <input type="hidden" name="id_peca" value="<?= $id_peca ?>">
                <button type="submit" class="btn btn-success mt-3">Produzir Peça</button>
            </form>
        <?php else: ?>
            <div class="alert alert-danger mt-3">Não há itens suficientes para produzir esta peça.</div>
        <?php endif; ?>

        <a href="selecionar_peca.php" class="btn btn-secondary mt-3">Voltar</a>
    </div>
</div>
</body>
</html>
