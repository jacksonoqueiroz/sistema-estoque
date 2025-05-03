<?php
session_start();

require 'conexao.php';

// Buscar peças que ainda não têm locação
$stmt = $pdo->query("
    SELECT p.id AS id_peca, p.nome, g.id AS id_grupo, g.nome AS nome_grupo
    FROM peca p
    JOIN grupos g ON p.id_grupo = g.id
    WHERE p.id NOT IN (SELECT id_peca FROM local_pecas)
");
$pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lógica de locação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_peca'])) {
    $id_peca = $_POST['id_peca'];

    // Buscar id do grupo
    $stmt = $pdo->prepare("SELECT id_grupo FROM peca WHERE id = ?");
    $stmt->execute([$id_peca]);
    $id_grupo = $stmt->fetchColumn();

    if ($id_grupo) {
        $letra = idParaLetra($id_grupo);
        $local = $letra . $id_peca . "-P"; // Ex: A3-P

        // Gravar na tabela local_pecas
        $stmt = $pdo->prepare("INSERT INTO local_pecas (id_peca, local) VALUES (?, ?)");
        $stmt->execute([$id_peca, $local]);

        // $sucesso = true;
        $mensagem_sucesso = "Peça alocada com sucesso no local <strong>$local</strong>!";
    }
}

// Função auxiliar
function idParaLetra($id) {
    return chr(64 + $id);
}

include 'include/head.php';
?>

    <title>Locação de Peças</title>
</head>
<body>

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
    <div class="container">
        <h2 class="mb-4">Locar Peças</h2>

        <!-- <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success">Peça locada com sucesso!</div>
        <?php endif; ?> -->
        <?php if (isset($mensagem_sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <?= $mensagem_sucesso ?>
<?php endif; ?>
</div>
        <form method="post" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <select name="id_peca" class="form-select" required>
                        <option value="">Selecione uma peça</option>
                        <?php foreach ($pecas as $p): ?>
                            <option value="<?= $p['id_peca'] ?>">
                                <?= $p['nome'] ?> (Grupo: <?= idParaLetra($p['id_grupo']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Locar</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    // Espera 4 segundos e fecha o alerta automaticamente
    setTimeout(() => {
        const alerta = document.querySelector('.alert');
        if (alerta) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);
            bsAlert.close();
        }
    }, 4000);
</script>
</body>
</html>
