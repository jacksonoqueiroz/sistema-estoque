<?php
require_once 'conexao.php';

$sql = "SELECT sp.id, 
              sp.id_peca,
              sp.id_ordem,
              p.codigo, 
              p.nome,
              od.codigo AS ordem
            FROM entrada_pecas sp 
            INNER JOIN peca p ON sp.id_peca = p.id
            INNER JOIN ordem_producao od ON sp.id_peca = p.id
            WHERE status='estocado'";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>


    <title>Separar Peças para Montagem</title>

</head>
<body>
  <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
      <div class="container mt-5">
    <div class="container mt-4">
        <h2 class="mb-4">Separar Peças para Montagem</h2>

        <?php if (count($pecas) > 0): ?>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código da Peça</th>
                        <th>Nome da Peça</th>
                        <th>Nº Ordem</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pecas as $peca): ?>
                        <tr>
                            <td><?= htmlspecialchars($peca['codigo']) ?></td>
                            <td><?= htmlspecialchars($peca['nome']) ?></td>
                            <td><?= htmlspecialchars($peca['ordem']) ?></td>
                            <td>
                                <a href="separar_montagem.php?id_entrada=<?= $peca['id'] ?>&codigo_peca=<?= $peca['id_peca'] ?>&ordem=<?= $peca['ordem'] ?>" class="btn btn-primary btn-sm">
                                    Separar para Montagem
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Nenhuma peça estocada disponível para montagem.</div>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-3">Voltar ao Menu</a>
    </div>
</body>
</html>
