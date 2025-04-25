<?php
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
include 'conexao.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID inválido.";
    exit;
}

// Excluir fornecedor e vínculos
try {
    $pdo->beginTransaction();

    // Remove vínculos com materiais
    $stmt = $pdo->prepare("DELETE FROM fornecedor_materiais WHERE fornecedor_id = ?");
    $stmt->execute([$id]);

    // Remove o fornecedor
    $stmt = $pdo->prepare("DELETE FROM fornecedores WHERE id = ?");
    $stmt->execute([$id]);

    $pdo->commit();
    header("Location: listar_fornecedores.php?excluido=1");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    echo "Erro ao excluir: " . $e->getMessage();
}
