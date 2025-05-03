<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
// Conexão PDO
include 'conexao.php';

// Consulta para buscar todas as peças e seus itens
$stmt = $pdo->query("
   SELECT item.id AS id_item, 
                item.nome,
                item.descricao, 
                item.qtd_itens AS qtd, 
                item.codigo,
                item.estoque_atual,
                peca.nome AS peca
          FROM itens AS item 
          INNER JOIN peca AS peca 
          ON peca.id=item.id_peca");

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>
<title>Itens por Peça</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-5">
    <h2 class="mb-4">📦 Itens por Peça</h2>

    <?php
    $pecaAtual = null;
    foreach ($dados as $linha):
        // Detecta mudança de peça
        if ($linha['peca'] != $pecaAtual):
            if ($pecaAtual !== null) {
                echo "</ul>"; // Fecha lista anterior
            }
            $pecaAtual = $linha['peca'];
            ?>
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <strong>⚙️   <?= htmlspecialchars($pecaAtual) ?></strong>
                </div>
                <ul class="list-group list-group-flush">
        <?php endif; ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($linha['nome']) ?></strong> 
                        (Código: <?= htmlspecialchars($linha['codigo']) ?>) 
                        - Estoque Atual: <?= htmlspecialchars($linha['estoque_atual']) ?>
                    </div>
                    <a href="editar_item.php?id=<?= $linha['id_item'] ?>" class="btn btn-sm btn-outline-secondary">
                        ✏️ Editar Item
                    </a>
                </li>
    <?php endforeach; ?>
    <?php if (!empty($dados)) echo "</ul></div>"; ?>
</div>
