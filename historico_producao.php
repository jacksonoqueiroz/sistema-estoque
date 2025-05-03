<?php
require 'conexao.php';
session_start();

// Buscar histórico de produção
$stmt = $pdo->query("
    SELECT lp.id, p.nome AS nome_peca, u.nome AS nome_usuario, lp.quantidade, lp.data_fabricacao
    FROM log_producao lp
    JOIN peca p ON lp.id_peca = p.id
    JOIN usuarios u ON lp.usuario = u.id
    ORDER BY lp.data_fabricacao DESC
");
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Inclui o Head Cabeçalho
include 'include/head.php';

?>


    <title>Histórico de Produção</title>
</head>
<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<body class="container mt-5">

<h2>Histórico de Produção</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Peça Produzida</th>
            <th>Quantidade</th>
            <th>Produzido por</th>
            <th>Data/Hora</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($historico as $registro): ?>
            <tr>
                <td><?= $registro['id'] ?></td>
                <td><?= htmlspecialchars($registro['nome_peca']) ?></td>
                <td><?= $registro['quantidade'] ?></td>
                <td><?= htmlspecialchars($registro['nome_usuario']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($registro['data_fabricacao'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a href="fabricar.php" class="btn btn-primary">Nova Fabricação</a>
</div>
</div>

</body>
</html>
