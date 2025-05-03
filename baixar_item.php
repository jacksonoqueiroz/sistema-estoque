<?php
require 'conexao.php';

$id_item = $_POST['id_item'] ?? null;
$id_solicitacao = $_POST['id_solicitacao'] ?? null;
// $id_peca = $_POST['id_peca'] ?? null;

if ($id_item && $id_solicitacao) {
    // Verifica se já foi baixado
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM itens_baixados WHERE id_solicitacao = ? AND id_item = ?");
    $stmt->execute([$id_solicitacao, $id_item]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO itens_baixados (id_solicitacao, id_item) VALUES (?, ?)");
        $stmt->execute([$id_solicitacao, $id_item]);
    }
}

header("Location: baixar_itens.php?id_peca=$id_peca&id_solicitacao=$id_solicitacao");
exit;
