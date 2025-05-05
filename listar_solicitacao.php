<?php
include_once ('conexao/conexao.php');


include 'include/head.php';
?>


	<title>Listar peça para produção</title>
</head>
<body>
	<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
    	<div class="container mt-5">
    		<h2 class="mb-4">Separar itens Peça</h2>

    	
    <table class="table table-bordered table-hover align-middle bg-white shadow-sm rounded">
            <thead class="table-dark">
              <tr>
              	<th scope="col">Código</th>
                <th scope="col-3">Nome</th>
                <th colspan>Ação</th>
              </tr>
            </thead>

            <tbody>
   
	<?php
		$query_reg = "SELECT sp.id AS id_solicitacao, sp.id_peca, p.codigo, p.nome
						FROM solicitacoes_producao sp
						INNER JOIN peca p
						ON sp.id_peca = p.id
						WHERE status='solicitado'";
		$result_reg = $conn->prepare($query_reg);
		$result_reg->execute();

		if (($result_reg) AND ($result_reg->rowCount() != 0)) {
			while ($row_reg = $result_reg->fetch(PDO::FETCH_ASSOC)) {
				// echo "<pre>";
				// var_dump($row_reg);
				// echo "</pre>";
				extract($row_reg);

			?>

              <tr>
              	<td><?php echo $codigo ?></td>           
                <td><?php echo $nome ?></td>
                <td class="row">
                	<div class="col-2 action-icon">
                			<a href="separar_itens.php?id_peca=<?php echo $id_peca ?>&id_solicitacao=<?php echo $id_solicitacao ?>" class="btn btn-primary">Separar Item</a>                		
                		</div>
                	   </td>
                	  

                </tr>
               
              </tr>
                <?php
			}
		}else{
			
		}

				?>
             
       <tr><td colspan="4">Nenhum item cadastrado neste grupo.</td></tr>
				</tbody>
		</table>
	

</div>
</div>
</body>
</html>