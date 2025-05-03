


<style>
    .rotate-icon {
        transition: transform 0.3s ease;
        display: inline-block;
    }
    .rotate-icon.rotated {
        transform: rotate(180deg);
    }
</style>

<ul class="nav nav-pills flex-column mb-auto sidebar">

    <!-- Dashboard -->
    <li class="nav-item">
        <a href="dashboard.php" class="nav-link text-white">
            🏠 Dashboard
        </a>
    </li>

    <!-- Itens -->    
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuItens" role="button" aria-expanded="false" aria-controls="submenuItens" id="linkConsultaPedidos">
            <span>📦 Itens</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuItens">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <a href="itens.php" class="nav-link text-white">📦 Itens
                </a>
                <li><a href="locar_itens.php" class="nav-link text-white">🗄️ Locar Itens</a></li>
                <li><a href="itens_alocados.php" class="nav-link text-white">⚙️  Itens Alocados</a></li>
            </ul>
        </div>
    </li>

    <!-- Peças -->
    
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuPeca" role="button" aria-expanded="false" aria-controls="submenuPeca" id="linkConsultaPedidos">
            <span>⚙️ Peças</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuPeca">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <a href="pecas.php" class="nav-link text-white">📦 Peças
                </a>
                <li><a href="locar_pecas.php" class="nav-link text-white">🗄️ Locar Peças</a></li>
                <li><a href="listar_pecas_locadas.php" class="nav-link text-white">🗄️ Pecas Locadas</a></li>
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
                <!-- li><a href="pedidos_compra.php" class="nav-link text-white">📝 Compar itens estoque baixo</a></li>
                <li><a href="recebimento.php" class="nav-link text-white">📥 Recebimento do Pedido estoque baixo</a></li>
                <li><a href="pedido_fornecedor.php" class="nav-link text-white">📝  Comprar Itens</a></li>-->
                <li><a href="comprar_itens.php" class="nav-link text-white">📝  Comprar Itens</a></li>
                <li><a href="receber_itens.php" class="nav-link text-white">✅  Receber Itens</a></li>
                <li><a href="relatorio_compra_itens.php" class="nav-link text-white">📁 Relatório de Pedido Itens</a></li>
            </ul>
        </div>
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
                <li><a href="listar_fornecedores.php" class="nav-link text-white">⚙️ Fornecedores</a></li>
                <li><a href="relatorio_compra_itens.php" class="nav-link text-white">⚙️  Itens por fornecedor</a></li>
                <li><a href="itens_alocados.php" class="nav-link text-white">⚙️  Itens Alocados</a></li>
            </ul>
        </div>
    </li>
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
    <!-- Etiquetas-->
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

    <!-- Produção -->
    <li>
        <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuProducao" role="button" aria-expanded="false" aria-controls="submenuProducao" id="linkConsultaPedidos">
            <span>📈 Produção</span> 
            <span id="iconPedidos" class="rotate-icon">🔽</span>
        </a>
        <div class="collapse" id="submenuProducao">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                <li><a href="solicitar_producao.php" class="nav-link text-white">⚙️ Solicitar Produção</a></li>
                <!-- <li><a href="selecionar_peca.php" class="nav-link text-white">⚙️ Itens para produção</a></li>                
                <li><a href="produzir_peca.php" class="nav-link text-white">⚙️ Produzir Peça</a></li> -->
                <li><a href="listar_producao.php" class="nav-link text-white">⚙️ Listar Produção</a></li>
                <li><a href="entrada_pecas.php" class="nav-link text-white">⚙️ Entrada de Peça</a></li>
                <li><a href="listar_solicitacao.php" class="nav-link text-white">⚙️ Separar Itens</a></li>
            </ul>
        </div>
    </li>
    <!-- Saída de Peça -->
    <li>
        <a href="saida_pecas.php" class="nav-link text-white">
            🏭 Saída de Peças
        </a>
    </li>

    <!-- Fornecedores -->
    <li>
        <a href="fornecedores.php" class="nav-link text-white">
            🚚 Fornecedores
        </a>
    </li>
    <!-- Logout-->
    <li>
        <a href="logout.php">🔒 Sair</a>        
    </li>
    

</ul>

