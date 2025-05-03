<?php
require 'conexao.php';

$id_peca = $_GET['id_peca'] ?? null;

if ($id_peca) {
    // Excluir a solicitação da tabela itens_peca
    $stmt = $pdo->prepare("DELETE FROM itens_peca WHERE id_peca = ?");
    $stmt->execute([$id_peca]);

    // Redirecionar de volta com alerta
    header("Location: listar_producao.php?mensagem=cancelado");
    exit;
} else {
    echo "ID da peça não informado.";
}
