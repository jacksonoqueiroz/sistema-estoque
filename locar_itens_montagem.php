<?php
require 'conexao.php'; // conexão com PDO

// Gera letra do grupo
function letraGrupo($id_grupo) {
    return chr(64 + intval($id_grupo)); // 1 => A, 2 => B ...
}

// Alocar item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Buscar grupo do item
    $stmt = $pdo->prepare("SELECT id_grupo FROM itens_montagem WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $id_grupo = $item['id_grupo'];
        $letra = letraGrupo($id_grupo);

        // Contar quantos já existem nesse grupo
        $stmt = $pdo->prepare("SELECT COUNT(*) + 1 AS ordem FROM itens_montagem WHERE id_grupo = ? AND localizacao IS NOT NULL");
        $stmt->execute([$id_grupo]);
        $ordem = $stmt->fetchColumn();

        $local = $letra . $ordem . "-IM";

        // Atualizar item com localização
        $stmt = $pdo->prepare("UPDATE itens_montagem SET localizacao = ? WHERE id = ?");
        $stmt->execute([$local, $id]);

        header("Location: locar_itens_montagem.php");
        exit;
    }
}

// Buscar itens não alocados
$stmt = $pdo->query("SELECT im.*, g.nome AS grupo_nome 
    FROM itens_montagem im 
    JOIN grupos g ON im.id_grupo = g.id 
    WHERE im.localizacao IS NULL 
    ORDER BY im.id_grupo, im.id");
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'include/head.php';
?>

    <title>Alocar Itens de Montagem</title>
    
    <style>
        .card {
            margin-bottom: 15px;
            border-left: 5px solid #0d6efd;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .alocar-btn {
            width: 100%;
        }
    </style>
</head>
<body>

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
<div class="container py-4">
    <h2 class="mb-4">📦 Alocação de Itens de Montagem</h2>

    <?php if (count($itens) === 0): ?>
        <div class="alert alert-success">✅ Todos os itens já foram alocados!</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($itens as $item): ?>
                <div class="col-md-6">
                    <div class="card p-3">
                        <h5 class="card-title"><?= htmlspecialchars($item['nome']) ?></h5>
                        <p class="card-text mb-1">🔧 Grupo: <strong><?= htmlspecialchars($item['grupo_nome']) ?></strong></p>
                        <p class="card-text mb-2">📦 Quantidade: <?= $item['quantidade'] ?></p>
                        <form method="POST" class="d-grid">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-primary alocar-btn">📍 Alocar</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</div>
</div>
</div>
</body>
</html>
