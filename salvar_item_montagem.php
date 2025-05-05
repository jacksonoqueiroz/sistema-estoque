<?php
include ('conexao/conexao.php');

$id_grupo = $_POST['id_grupo'];
$nome_item = $_POST['nome_item'];
$quantidade = $_POST['quantidade'];

// 1. Verifica a próxima ordem do item dentro do grupo
$sqlCount = "SELECT COUNT(*) AS total FROM itens_montagem WHERE id_grupo = ?";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->execute([$id_grupo]);
$total = $stmtCount->fetchColumn();
$ordem_item = $total + 1; // próxima posição

// 2. Inserir o item
$sqlInsert = "INSERT INTO itens_montagem (id_grupo, nome, quantidade) VALUES (?, ?, ?)";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->execute([$id_grupo, $nome_item, $quantidade]);

// 3. Pegar o ID do item recém-inserido
$id_item = $conn->lastInsertId();

// 4. Gerar o código no novo formato ITC-001-01
$codigo_formatado = 'ITC-' . str_pad($id_grupo, 3, '0', STR_PAD_LEFT) . '-' . str_pad($ordem_item, 2, '0', STR_PAD_LEFT);

// 5. Atualizar o item com o código gerado
$sqlUpdate = "UPDATE itens_montagem SET codigo = ? WHERE id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->execute([$codigo_formatado, $id_item]);

// 6. Redirecionar
header("Location: cadastro_itens_montagem.php");
exit;
