<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
if ($_SESSION['usuario_tipo'] != 'admin') {
    die('Acesso negado!');
}


require 'conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, usuario, senha, tipo) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$nome, $usuario, $senha, $tipo])) {
            $mensagem = 'Usuário cadastrado com sucesso!';
        
    } else {
        $mensagem = 'Erro ao cadastrar usuário. Usuário já existe?';
    }
}

include 'include/head.php';
?>


    <title>Cadastrar Usuário</title>

</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container">
    <h1 class="mb-4">Cadastrar Novo Usuário</h1>

    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="usuario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Senha:</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tipo:</label>
                <select name="tipo" class="form-control" required>
                    <option value="usuario">Usuário</option>
                    <option value="admin">Administrador</option>
                </select>
        </div>


        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>

</body>
</html>
