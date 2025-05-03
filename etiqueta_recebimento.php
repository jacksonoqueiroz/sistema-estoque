<?php
session_start();
include 'conexao.php';

$pedido_id = $_GET['pedido_id'] ?? null;

if ($pedido_id) {
    $stmt = $pdo->prepare("
        SELECT pc.id AS pedido_id, m.nome AS material, m.codigo AS codigo,
               f.nome AS fornecedor, pc.data_pedido
        FROM pedidos_compra pc
        JOIN itens m ON m.id = pc.item_id
        JOIN fornecedores f ON f.id = pc.fornecedor_id
        WHERE pc.id = ?
    ");
    $stmt->execute([$pedido_id]);
    $etiqueta = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Número do pedido não informado!";
    exit;
}

include 'include/head.php';

?>

    <title>Etiqueta de Recebimento</title>   
   
</head>
<style>
    .etiqueta {
            width: 600px;
            border: 2px dashed #333;
            padding: 15px;
            margin: 20px auto;
            font-size: 14px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .etiqueta h5 {
            background-color: #000;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            font-size: 19px;
            margin-bottom: 10px;
            color: #fff;
            height: 40px;
        }
        .etiqueta strong{
            font-size: 20px;
        }
        .linha {
            border-top: 1px dashed #aaa;
            margin: 10px 0;
        }
        .titulo-etiq h5{
            background-color: #000;
            color: #fff;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .etiqueta, .etiqueta * {
                visibility: visible;
            }
            .etiqueta {
                position: absolute;
                left: 0;
                top: 0;
            }
        }


</style>
<body>

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="etiqueta shadow">
    <h5>Etiqueta de Recebimento</h5>
    <div>Código: <strong><?= htmlspecialchars($etiqueta['codigo']) ?></strong></div>
    <div>Item: <strong><?= htmlspecialchars($etiqueta['material']) ?></strong></div>
    <div>Pedido:<strong> #<?= $etiqueta['pedido_id'] ?></strong></div>
    <div>Fornecedor: <strong><?= htmlspecialchars($etiqueta['fornecedor']) ?></strong></div>
    <div>Data: <strong><?= date('d/m/Y', strtotime($etiqueta['data_pedido'])) ?></strong></div>
    <div class="linha"></div>

    <div class="text-center">
        <svg id="barcode"></svg>
    </div>   
</div>

 <div class="text-center mt-2">
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm">🖨️ Imprimir Etiqueta</button>
    </div>

<script>
    JsBarcode("#barcode", "<?= $etiqueta['codigo'] ?>", {
        format: "CODE128",
        lineColor: "#000",
        width: 2,
        height: 40,
        displayValue: true
    });
</script>

</body>
</html>
