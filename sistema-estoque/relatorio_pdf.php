<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require 'conexao.php';
require 'vendor/autoload.php'; // Dompdf autoload

use Dompdf\Dompdf;

// Pegar dados do estoque
$itens = $pdo->query("SELECT nome, estoque_atual, estoque_minimo FROM itens")->fetchAll(PDO::FETCH_ASSOC);

$html = '<h1>Relatório de Estoque</h1>';
$html .= '<table border="1" width="100%" cellpadding="5" cellspacing="0">';
$html .= '<tr><th>Item</th><th>Estoque Atual</th><th>Estoque Mínimo</th></tr>';

foreach ($itens as $item) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($item['nome']) . '</td>';
    $html .= '<td>' . htmlspecialchars($item['estoque_atual']) . '</td>';
    $html .= '<td>' . htmlspecialchars($item['estoque_minimo']) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Gerar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Baixar PDF
$dompdf->stream('relatorio_estoque.pdf', ['Attachment' => false]);
exit;
?>
