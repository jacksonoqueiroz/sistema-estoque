<?php
require 'conexao.php';
session_start();

// Buscar dados para os cards
$total_itens = $pdo->query("SELECT COUNT(*) FROM itens")->fetchColumn();
$total_pecas = $pdo->query("SELECT COUNT(*) FROM peca")->fetchColumn();
$pedidos_pendentes = $pdo->query("SELECT COUNT(*) FROM pedidos_compra WHERE status = 'pendente'")->fetchColumn();

// Verificar estoque crítico
$estoque_critico = $pdo->query("SELECT COUNT(*) FROM itens WHERE qtd_itens <= estoque_minimo")->fetchColumn();


function checarEstoqueMinimo($pdo) {
    $alertas = [];

    $sql = "SELECT * FROM itens WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($item = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Item: {$item['nome']} com estoque crítico!";
    }

    $sql = "SELECT * FROM peca WHERE estoque_atual <= estoque_minimo";
    $stmt = $pdo->query($sql);
    while ($peca = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $alertas[] = "Peça: {$peca['nome']} com estoque crítico!";
    }

    return $alertas;
}

$alertas = checarEstoqueMinimo($pdo);

//Inclui o Head Cabeçalho
include 'include/head.php';


?>

    <title>Dashboard Produção</title>



</head>
<body>

<!-- Sidebar -->
<!-- <div class="sidebar">
    <h4 class="text-center">🛠️ Produção</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="itens.php">📦 Itens
        <?php if($estoque_critico > 0): ?>
            <span class="badge badge-alerta">🚨 <?= $estoque_critico ?></span>
        <?php endif; ?>
    </a>
    <a href="pecas.php">🏭 Peças</a>
    <a href="pedidos_compra.php">🛒 Pedidos de Compra</a>
    <a href="recebimento.php">📥 Recebimento</a>
    <a href="historico_producao.php">📈 Histórico</a>
    <a href="logout.php">🔒 Sair</a>
    
</div> -->

<ul class="nav nav-pills flex-column mb-auto sidebar">

    <!-- Dashboard -->
    <li class="nav-item">
        <a href="dashboard.php" class="nav-link text-white">
            🏠 Dashboard
        </a>
    </li>

    <!-- Itens -->
    <li>
        <!-- <a href="itens.php" class="nav-link text-white">
            📦 Itens
        </a> -->
        <a href="itens.php">📦 Itens
        <?php if($estoque_critico > 0): ?>
            <span class="badge badge-alerta">🚨 <?= $estoque_critico ?></span>
        <?php endif; ?>
    </a>
    </li>

    <!-- Peças -->
    <li>
        <a href="pecas.php" class="nav-link text-white">
            ⚙️ Peças
        </a>
    </li>

    <!-- Consultar com submenu animado -->
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuConsulta" role="button" aria-expanded="false" aria-controls="submenuConsulta" id="linkConsultaPedidos">
            <span>🔍 Consultar</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuConsulta">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <li><a href="pecas_grupos.php" class="nav-link text-white">⚙️ Peças Por Grupos</a></li>
                <li><a href="itens_por_peca.php" class="nav-link text-white">⚙️ Itens Por Peça</a></li>
                <li><a href="relatorio_compra_itens.php" class="nav-link text-white">📥  Itens por fornecedor</a></li>
            </ul>
        </div>
    </li>

    
    <!-- Pedidos com submenu animado -->
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuPedidos" role="button" aria-expanded="false" aria-controls="submenuPedidos" id="linkPedidos">
            <span>🛒 Pedidos</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuPedidos">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <!-- <li><a href="pedidos_compra.php" class="nav-link text-white">📝 Pedido de Compra</a></li>
                <li><a href="recebimento.php" class="nav-link text-white">📥 Recebimento do Pedido</a></li>
                <li><a href="pedido_fornecedor.php" class="nav-link text-white">📝  Comprar Material</a></li>
                <li><a href="recebimento_material.php" class="nav-link text-white">📥  Receber Material</a></li> -->
                <li><a href="comprar_itens.php" class="nav-link text-white">📝  Comprar Itens</a></li>
                <li><a href="receber_itens.php" class="nav-link text-white">✅  Receber Itens</a></li>
                <li><a href="relatorio_compra_itens.php" class="nav-link text-white">📁 Relatório de Pedido Itens</a></li>
            </ul>
        </div>
    </li>
    <!--Cadatros-->
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuCadastros" role="button" aria-expanded="false" aria-controls="submenuCadastros" id="linkConsultaPedidos">
            <span>📝Cadastros</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuCadastros">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <li><a href="cadastro_usuario.php" class="nav-link text-white">👤 Usuários</a></li>
                <li><a href="fornecedores.php" class="nav-link text-white">🚚 Fornecedores</a></li>
            </ul>
        </div>
    </li>
    <!---------ETIQUETAS--------->
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuEtiqueta" role="button" aria-expanded="false" aria-controls="submenuEtiqueta" id="linkConsultaPedidos">
            <span>🏷️Etiquetas</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuEtiqueta">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <li><a href="etiqueta_avulsa.php" class="nav-link text-white">🏷️ Etiqueta Avulsa</a></li>
                <li><a href="etiqueta_locacao.php" class="nav-link text-white">🖨️ Gerar etiquetas Itens</a></li>
            </ul>
        </div>
    </li>

    <!-- Fornecedores -->
    <li>
        <a href="historico_producao.php" class="nav-link text-white">📈 Histórico</a>
    </li>
    <!-- Fornecedores -->
    <li>
        <a href="fornecedores.php" class="nav-link text-white">
            🏭 Fornecedores
        </a>
    </li>
    <!-- Logout-->
    <li>
        <a href="logout.php">🔒 Sair</a>        
    </li>

</ul>



<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
        <h2 class="mb-4">🏠 Dashboard Principal</h2>

         <?php
// Buscar alguns dados para os cards
$total_itens = $pdo->query("SELECT COUNT(*) FROM itens")->fetchColumn();
$total_pecas = $pdo->query("SELECT COUNT(*) FROM peca")->fetchColumn();
$pedidos_pendentes = $pdo->query("SELECT COUNT(*) FROM pedidos_compra WHERE status = 'pendente'")->fetchColumn();
?>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card bg-primary text-white shadow p-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">📦 Itens no Estoque</h5>
                        <h2 id="contador-itens">0</h2>
                        <p class="card-text">Total de itens cadastrados</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white shadow p-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">🏭 Peças Cadastradas</h5>
                        <h2 id="contador-pecas">0</h2>
                        <p class="card-text">Total de peças cadastradas</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-dark shadow p-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">🛒 Pedidos Pendentes</h5>
                        <h2 id="contador-pedidos">0</h2>
                        <p class="card-text">Pedidos aguardando aprovação</p>
                    </div>
                </div>
            </div>

             <?php if ($alertas): ?>
        <div class="alert alert-danger">
            <h4>⚠️ Alertas de Estoque:</h4>
            <ul>
                <?php foreach ($alertas as $alerta): ?>
                    <li><?= htmlspecialchars($alerta) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="alert alert-success">Tudo certo no estoque!</div>
    <?php endif; ?>

        </div>
    </div>
</div>


<!-- Script para animar os contadores -->
<script>
    function animarContador(id, valorFinal, duracao = 1500) {
        const elemento = document.getElementById(id);
        let inicio = 0;
        const incremento = valorFinal / (duracao / 50);

        const contador = setInterval(() => {
            inicio += incremento;
            if (inicio >= valorFinal) {
                inicio = valorFinal;
                clearInterval(contador);
            }
            elemento.innerText = Math.floor(inicio);
        }, 50);
    }

    window.onload = function() {
        animarContador('contador-itens', <?= $total_itens ?>);
        animarContador('contador-pecas', <?= $total_pecas ?>);
        animarContador('contador-pedidos', <?= $pedidos_pendentes ?>);
    };
</script>
<!--  -->

</body>
</html>
