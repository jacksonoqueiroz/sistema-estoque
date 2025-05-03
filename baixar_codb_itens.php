<?php
require_once 'conexao.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo_barra'] ?? '';

    if ($codigo) {
        // Verifica se o item existe e tem estoque
        $stmt = $pdo->prepare("SELECT * FROM itens WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item && $item['estoque_atual'] > 0) {
            // Dá baixa (decrementa 1)
            $stmt = $pdo->prepare("UPDATE itens SET estoque_atual = estoque_atual - 1 WHERE id = ?");
            $stmt->execute([$item['id']]);
            $msg = "<div class='alert alert-success'>Baixa realizada com sucesso para o item <strong>{$item['nome']}</strong>!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Item não encontrado ou sem estoque disponível.</div>";
        }
    } else {
        $msg = "<div class='alert alert-warning'>Informe o código do item.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Baixar Itens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Baixar Itens por Código</h2>

    <?= $msg ?>

    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" name="codigo_barra" id="codigo_barra" class="form-control" placeholder="Ex: ITM123" required>
            <button class="btn btn-primary" type="submit">Dar Baixa</button>
        </div>
    </form>

    <h5 class="mb-3">Simular Códigos</h5>
    <div class="d-flex flex-wrap gap-2">
        <button class="btn btn-outline-secondary" onclick="simularCodigo('ITM123')">ITM123</button>
        <button class="btn btn-outline-secondary" onclick="simularCodigo('ITM124')">ITM124</button>
        <button class="btn btn-outline-secondary" onclick="simularCodigo('ITM125')">ITM125</button>
    </div>

    <script>
        function simularCodigo(codigo) {
            document.getElementById('codigo_barra').value = codigo;
        }
    </script>
</div>

</body>
</html>
