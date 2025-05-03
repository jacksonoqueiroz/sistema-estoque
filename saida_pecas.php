<?php
include_once ('conexao/conexao.php');


include 'include/head.php';
?>


	<title>Saída de peças</title>
</head>
<body>
	<!--SIDEBAR MENU LATERAL-->
<?php include 'include/sidebar.php'; ?>

<!-- Conteúdo -->
<div class="content">
    <div class="container-fluid">
    	<div class="container mt-5">
    		<h2 class="mb-4">Saída de Peça Montagem	</h2>

    	
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
		$query_reg = "SELECT id AS id_peca, codigo, nome FROM peca";
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
                			<a href="confirmar_saida_peca.php?id_peca=<?php echo $id_peca ?>" class="btn btn-success">Saída</a>                		
                		</div>
                </td>
                	    <?php
			}
		}else{
			echo "Nenhuma peça em estoque!";
		}
				?>
                </tr>         
       
				</tbody>
		</table>
	

</div>
</div>
</body>
</html>