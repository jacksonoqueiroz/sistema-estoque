<?php
require 'conexao.php';

$id_solicitacao = $_POST['id_solicitacao'];

$conn->prepare("UPDATE solicitacoes_producao SET status = 'baixado' WHERE id = ?")->execute([$id_solicitacao]);

header("Location: solicitar_producao.php?ok=1");
exit;
