<?php
require_once ('conexao/conexao.php');

$id_peca = $_GET['id_peca'] ?? null;
$id_solicitacao = $_GET['id_solicitacao'] ?? null;

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Obter a quantidade da tabela
        $query = "SELECT estoque_atual FROM peca";
        $stmt = $conn->query($query);
        $estoque_atual = $stmt->fetchColumn();
        
        if (!empty($dados['cad_reg'])) {

            $query_cad = "INSERT INTO entrada_pecas (id_solicitacao, id_peca, id_ordem, qtd_peca) VALUES (:id_solicitacao, :id_peca, :id_ordem, :qtd_peca)";
            $cad_reg = $conn->prepare($query_cad);
            $cad_reg->bindParam(':id_solicitacao', $id_solicitacao, PDO::PARAM_STR);
            $cad_reg->bindParam(':id_peca', $id_peca, PDO::PARAM_STR);
            $cad_reg->bindParam(':id_ordem', $dados['ordem'], PDO::PARAM_STR);
            $cad_reg->bindParam(':qtd_peca', $dados['qtd_peca'], PDO::PARAM_STR);
            $cad_reg->execute();

            $novo_estoque = $estoque_atual + $dados['qtd_peca'];
            
            if ($cad_reg->rowCount()) {
                $query_solicitacao = "UPDATE solicitacoes_producao SET status='estocado' WHERE id=$id_solicitacao";
                $edit_solicita = $conn->prepare($query_solicitacao);
                $edit_solicita->execute();

                //Atualizando estoque_atual tabela peca
                $query_estoque = "UPDATE peca SET estoque_atual = $novo_estoque WHERE id= $id_peca";
                $edit_estoque = $conn->prepare($query_estoque);
                $edit_estoque->execute();

                header("Location: entrada_pecas.php");
                            
            }
        }


        //Resgata peça já baixado da Tabela solicitaca_producao
        $query_reg = "SELECT sp.id AS id_solicitacao, sp.id_peca, p.codigo, p.nome
                        FROM solicitacoes_producao sp
                        INNER JOIN peca p
                        ON sp.id_peca = p.id
                        WHERE sp.id = $id_solicitacao";
        $result_reg = $conn->prepare($query_reg);
        $result_reg->execute();

        if (($result_reg) AND ($result_reg->rowCount() != 0)) {
            while ($row_reg = $result_reg->fetch(PDO::FETCH_ASSOC)) {
                // echo "<pre>";
                // var_dump($row_reg);
                // echo "</pre>";
                extract($row_reg);
    
                }
            }

include 'include/head.php';
?>


    <title>Confirmar entrada Peça</title>

</head>
<body>
    <!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">

<div class="container mt-4">
    <h3 class="mb-3">Confirmar Entrada Peça: <strong><?php echo $nome ?></strong></h3>

    <form name="cad_op" class="form-group" method="POST" action="">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="inputEmail4" class="form-label">Nome da Peça</label>
                <input type="text" name="nome" autocomplete="off" class="form-control" id="nome" value="<?php echo $nome ?>" disabled>   
          </div>
          <div class="form-group col-md-3" style="margin-top: 9px;">
            <label for="ordem">Ordem de Produção:</label>
                <select name="ordem" id="ordem" class="form-control" required>
                    <?php
                            $query = $conn->query("SELECT id, codigo FROM ordem_producao ORDER BY codigo ASC");
                            $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                <option value="">Selecione...</option>
                    <?php
      
                        foreach ($result as $option) {
                    ?>
                <option value="<?php echo $option['id']; ?>"><?php echo $option['codigo']; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            </div>
            <div class="col-md-2">
            <label for="inputText" class="form-label">Quantidade de Peça</label>
            <input type="number" name="qtd_peca" autocomplete="off" class="form-control" id="qtd_peca">
      </div>
            <br>
            <div class="col-3 but-op">
            <input type="submit" value="Dar entrada" name="cad_reg" class="btn btn-success">
        </div>

    </form>
</div>

</body>
</html>


