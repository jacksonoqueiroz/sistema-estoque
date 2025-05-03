<?php
require 'conexao.php';

$id_item = $_POST['id_item'];
$id_solicitacao = $_POST['id_solicitacao'];

$stmt = $pdo->prepare("INSERT INTO itens_baixados (id_solicitacao, id_item) VALUES (?, ?)");
$stmt->execute([$id_solicitacao, $id_item]);

header("Location: baixar_itens.php?id_solicitacao=$id_solicitacao");
exit;
