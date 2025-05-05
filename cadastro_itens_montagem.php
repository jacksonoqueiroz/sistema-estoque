<?php
include ('conexao/conexao.php');

// Busca os grupos existentes
$sql = "SELECT id, nome FROM grupos";
$stmt = $conn->query($sql);
$grupos = $stmt->fetchAll();

include 'include/head.php';

?>


    <title>Cadastro de Itens para Montagem</title>
    
</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
    <?php include 'include/sidebar.php'; ?>

    <!-- Conteúdo -->
    <div class="content">
    <div class="container-fluid">
    <h2>Cadastro de Itens para Montagem</h2>

    <form action="salvar_item_montagem.php" method="POST">
        <div class="mb-3">
            <label for="id_grupo" class="form-label">Grupo</label>
            <select name="id_grupo" class="form-select" required>
                <option value="">Selecione o grupo</option>
                <?php foreach ($grupos as $grupo): ?>
                    <option value="<?= $grupo['id'] ?>"><?= $grupo['nome'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="nome_item" class="form-label">Nome do Item</label>
            <input type="text" name="nome_item" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade Necessária</label>
            <input type="number" name="quantidade" class="form-control" required min="1">
        </div>

        <button type="submit" class="btn btn-success">Cadastrar Item</button>
    </form>
</body>
</html>
