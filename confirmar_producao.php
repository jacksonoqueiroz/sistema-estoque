<?php
require 'conexao.php';

// Recebe o ID da peça
$id_peca = $_GET['id_peca'] ?? null;
$peca = null;
$itens = [];

if ($id_peca) {
    // Buscar dados da peça
    $stmt = $pdo->prepare("SELECT * FROM peca WHERE id = ?");
    $stmt->execute([$id_peca]);
    $peca = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($peca) {
        // Buscar os itens necessários para a peça
        $stmt = $pdo->prepare("SELECT * FROM itens WHERE id_peca = ?");
        $stmt->execute([$id_peca]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Quando confirmar
if (isset($_POST['confirmar']) && $peca) {
    $pdo->beginTransaction();
    try {
        // 1. Dar baixa nos itens
        foreach ($itens as $item) {
            $stmt = $pdo->prepare("UPDATE itens SET quantidade_estoque = quantidade_estoque - ? WHERE id = ?");
            $stmt->execute([$item['quantidade'], $item['id']]);
        }

        // 2. Inserir peça no estoque
        $stmt = $pdo->prepare("INSERT INTO estoque_pecas (id_peca, quantidade) VALUES (?, 1)");
        $stmt->execute([$id_peca]);
        $id_estoque_peca = $pdo->lastInsertId();

        // 3. Fazer a locação da peça
        // Buscar o grupo da peça (supondo que tem coluna 'id_grupo' na tabela 'peca')
        $stmt = $pdo->prepare("SELECT grupo.letra FROM grupo INNER JOIN peca ON grupo.id = peca.id_grupo WHERE peca.id = ?");
        $stmt->execute([$id_peca]);
        $grupo = $stmt->fetch(PDO::FETCH_ASSOC);

        $letra_grupo = $grupo['letra'] ?? 'X'; // Se não tiver, coloca X
        $localizacao = $letra_grupo . $peca['id'] . "-P";

        $stmt = $pdo->prepare("INSERT INTO local_peca (id_peca, localizacao) VALUES (?, ?)");
        $stmt->execute([$id_peca, $localizacao]);

        $pdo->commit();

        // Redireciona com sucesso
        header("Location: confirmar_producao.php?id_peca=$id_peca&sucesso=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Produção</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">

<div class="container">
    <div class="card shadow p-4">
        <h3 class="mb-4">Confirmar Produção</h3>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Produção confirmada!</strong> A peça foi inserida no estoque e alocada com sucesso.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php if (!$peca): ?>
            <div class="alert alert-danger">
                Nenhuma peça selecionada ou peça não encontrada.
            </div>
        <?php else: ?>
            <div class="mb-4">
                <h5>Peça: <strong><?= htmlspecialchars($peca['nome']) ?></strong></h5>
                <p><strong>ID da Peça:</strong> <?= $peca['id'] ?></p>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Código do Item</th>
                            <th>Nome do Item</th>
                            <th>Quantidade a Baixar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['codigo']) ?></td>
                                <td><?= htmlspecialchars($item['nome']) ?></td>
                                <td><?= htmlspecialchars($item['quantidade']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <form method="post">
                <button type="submit" name="confirmar" class="btn btn-success btn-lg w-100">
                    Confirmar Produção e Alocar
                </button>
            </form>

            <div class="d-flex justify-content-end mt-3">
                <a href="verificar_producao.php?id_peca=<?= $peca['id'] ?>" class="btn btn-secondary">Voltar</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
