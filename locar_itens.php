<?php
session_start();

require 'conexao.php';

// Quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_item'])) {
    $id_item = intval($_POST['id_item']);

    // Obter o item selecionado
    $stmt = $pdo->prepare("SELECT id_peca FROM itens WHERE id = ?");
    $stmt->execute([$id_item]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $id_peca = $item['id_peca'];

        // Obter a letra correspondente à ordem da peça
        $pecas = $pdo->query("SELECT id FROM peca ORDER BY id ASC")->fetchAll(PDO::FETCH_COLUMN);
        $letra_index = array_search($id_peca, $pecas);
        $letra = chr(65 + $letra_index); // 65 = A

        // Contar quantos itens da mesma peça já foram alocados
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM local_itens li 
            JOIN itens i ON li.id_item = i.id 
            WHERE i.id_peca = ?
        ");
        $stmt->execute([$id_peca]);
        $quantidade = $stmt->fetchColumn();

        $letra_final = "I";
        $numero = $quantidade + 1; // próxima posição
        $local = $letra . $numero . ' - ' . $letra_final;

        // Inserir no local_itens
        $stmt = $pdo->prepare("INSERT INTO local_itens (id_item, local) VALUES (?, ?)");
        $stmt->execute([$id_item, $local]);

        // echo "<p>Item alocado no local: <strong>$local</strong></p>";
        $mensagem_sucesso = "Item alocado com sucesso no local <strong>$local</strong>!";
    } else {
        echo "<p>Item não encontrado.</p>";
    }
}
include 'include/head.php';
?>
 <title>Alocar Itens</title>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
        <h2>Alocar Itens</h2>

        <?php if (isset($mensagem_sucesso)): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <?= $mensagem_sucesso ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    <?php endif; ?>

<!-- Formulário de seleção -->
<form method="POST">
    <label for="id_item">Selecione um item:</label>
    <select class="form-select" name="id_item" id="id_item" required>
        <option value="">-- Selecione --</option>
        <?php
        // Buscar itens para o select
        $stmt = $pdo->query("
            SELECT i.id, i.nome, p.nome AS peca_nome 
            FROM itens i 
            JOIN peca p ON i.id_peca = p.id 
            WHERE i.id NOT IN (SELECT id_item FROM local_itens)
            ORDER BY i.id ASC
            ");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$row['id']}'>[{$row['peca_nome']}] {$row['nome']}</option>";
        }
        ?>
    </select>
    <br>
    <button class="btn btn-primary" type="submit">Alocar Item</button>
</form>
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
