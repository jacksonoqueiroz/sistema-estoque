<?php
require 'conexao.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $peca_id = $_POST['peca_id'];

    // Buscar itens necessários
    $stmt = $pdo->prepare("
        SELECT i.id, i.nome, pi.qtd_itens, i.estoque_atual
        FROM itens pi
        JOIN itens i ON pi.id = i.id
        WHERE pi.id_peca = ?
    ");
    $stmt->execute([$peca_id]);
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $erro = false;

    // Primeiro, checar se todos os itens têm estoque suficiente
    foreach ($itens as $item) {
        if ($item['estoque_atual'] < $item['qtd_itens']) {
            $erro = true;
            $mensagem = "Estoque insuficiente para o item: " . htmlspecialchars($item['nome']);
            break;
        }
    }

    if ($erro) {
        echo "<div class='alert alert-danger'>$mensagem</div>";
        echo "<a href='fabricar.php' class='btn btn-secondary mt-3'>Voltar</a>";
        exit;
    }

    // Se tudo certo, fazer as baixas
    foreach ($itens as $item) {
        $novo_estoque = $item['estoque_atual'] - $item['qtd_itens'];

        $update = $pdo->prepare("UPDATE itens SET estoque_atual = ? WHERE id = ?");
        $update->execute([$novo_estoque, $item['id']]);
    }

    // Registrar no log de produção
    $log = $pdo->prepare("INSERT INTO log_producao (id_peca, usuario, data_fabricacao) VALUES (?, ?, NOW())");
    $log->execute([$usuario, $_SESSION['usuario']]);

    echo "<div class='alert alert-success'>Peça fabricada com sucesso!</div>";
    echo "<a href='fabricar.php' class='btn btn-primary mt-3'>Fabricar outra</a>";
}
?>
