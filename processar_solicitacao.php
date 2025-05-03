<?php
require 'conexao.php';

$id_peca = $_POST['id_peca'] ?? null;
$quantidade = $_POST['quantidade'] ?? 1;

if (!$id_peca || $quantidade <= 0) {
    die('Peça e quantidade são obrigatórios.');
}

$stmt = $pdo->prepare("INSERT INTO itens_peca (id_peca, quantidade_necessaria, status) VALUES (?, ?, 'PENDENTE')");
$stmt->execute([$id_peca, $quantidade]);

header("Location: solicitar_producao.php?mensagem=" . urlencode('Produção solicitada com sucesso!'));
exit;
