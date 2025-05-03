<?php

require 'conexao.php';

// Recebe o id da peça
$id_peca = $_POST['id_peca'] ?? null;

if (!$id_peca) {
    die('ID da peça não informado.');
}
// Começar transação
$pdo->beginTransaction();

try {
    // Buscar os itens necessários
    $stmt = $pdo->prepare("
        SELECT i.id, i.estoque_atual, ip.quantidade_necessaria
        FROM itens_peca ip
        JOIN itens i ON i.id = ip.id_item
        WHERE ip.id_peca = ?
    ");
    $stmt->execute([$id_peca]);
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dar baixa nos itens
    foreach ($itens as $item) {
        $novo_estoque = $item['estoque_atual'] - $item['quantidade_necessaria'];
        if ($novo_estoque < 0) {
            throw new Exception('Estoque insuficiente para item ID: ' . $item['id']);
        }
        $update = $pdo->prepare("UPDATE itens SET estoque_atual = ? WHERE id = ?");
        $update->execute([$novo_estoque, $item['id']]);
    }

    // Atualizar estoque da peça (+1)
    $update_peca = $pdo->prepare("UPDATE peca SET estoque_atual = estoque_atual + 1 WHERE id = ?");
    $update_peca->execute([$id_peca]);

    // Definir local automático (A1-P, A2-P, etc)
    $stmt_last = $pdo->query("SELECT COUNT(*) + 1 AS proximo FROM local_pecas");
    $proximo = $stmt_last->fetch(PDO::FETCH_ASSOC)['proximo'];

    $letra = chr(64 + ceil($proximo / 10)); // A cada 10 peças, troca a letra (A, B, C...)
    $numero = ($proximo % 10) ?: 10; // Se múltiplo de 10, fica 10
    $localizacao = strtoupper($letra) . $numero . "-P";

    // Inserir na tabela local_peca
    $insert = $pdo->prepare("INSERT INTO local_pecas (id_peca, local) VALUES (?, ?)");
    $insert->execute([$id_peca, $localizacao]);

    // Commit
    $pdo->commit();
    $sucesso = true;
    $mensagem = "Peça produzida e armazenada com sucesso no local: " . $localizacao;

} catch (Exception $e) {
    $pdo->rollBack();
    $sucesso = false;
    $mensagem = "Erro ao produzir peça: " . $e->getMessage();
}

include 'include/head.php';

?>

<title>Produzir Peça</title>

<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
    <div class="container">
        <?php if ($sucesso): ?>
            <div class="alert alert-success shadow">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-danger shadow">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <a href="selecionar_peca.php" class="btn btn-primary mt-3">Produzir Outra Peça</a>
    </div>
</body>
</html>
