<?php
require 'conexao.php';

$id_item = $_GET['id_item'] ?? null;

if ($id_item) {
    // Buscar dados da etiqueta com o local
    $stmt = $pdo->prepare("
        SELECT 
            i.nome AS nome_item,
            p.nome AS nome_peca,
            li.local,
            i.id
        FROM itens i
        JOIN peca p ON i.id_peca = p.id
        LEFT JOIN local_itens li ON li.id_item = i.id
        WHERE i.id = ?
    ");
    $stmt->execute([$id_item]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados):

include 'include/head.php';
?>

<<title>Etiquetas Locação</title>
<style>
        .etiqueta {
            width: 300px;
            padding: 15px;
            border: 2px dashed #000;
            margin: 20px auto;
            font-family: Arial, sans-serif;
        }
        .etiqueta h3 {
            margin: 0 0 5px;
        }
        .etiqueta .local {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .barcode {
            margin-top: 10px;
        }
    </style>
   
</head>
<body>
   

<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">    
</head>
<body>
    <div class="etiqueta">
        <h3>Item: <?= htmlspecialchars($dados['nome_item']) ?></h3>
        <p>Peça: <?= htmlspecialchars($dados['nome_peca']) ?></p>
        <div class="local">📍 Local: <?= htmlspecialchars($dados['local'] ?? 'Não alocado') ?></div>

        <!-- Código de barras -->
        <div class="barcode">
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= $dados['id'] ?>&code=Code128&dpi=96" alt="Código de Barras">
        </div>
    </div>
</body>
</html>

<?php
    else:
        echo "Item não encontrado.";
    endif;
} else {
    echo "ID do item não informado.";
}
?>
