<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

		<title>DataFrete Teste</title>
		
	</head>
	<body>
		
		<?php
		
			include "conexao.php";
			include "auxiliar.php";
			
			$busca = "";
			if(isset($_POST['busca']))
			{
				$busca = $_POST['busca'];
			}
			
			$sql = "SELECT A.id, A.cepOrigem, A.cepDestino, A.distCalculada, A.dtCriacao, A.dtAlteracao, B.cep as ORI_CEP, B.estado as ORI_ESTADO, B.cidade as ORI_CIDADE, B.bairro as ORI_BAIRRO, B.logradouro as ORI_LOGRADOURO, C.cep as DEST_CEP, C.estado as DEST_ESTADO, C.cidade as DEST_CIDADE, C.bairro as DEST_BAIRRO, C.logradouro as DEST_LOGRADOURO FROM `cadastro` AS A join `cep` AS B on A.cepOrigem = B.cep join `cep` AS C on A.cepDestino = C.cep where A.cepOrigem like '%$busca%' or A.cepDestino like '%$busca%'";
			
			$dados = mysqli_query($conn, $sql);
		?>
	
		<div class="container">
			<div class="row">
				<div class="col">

					<nav class="navbar navbar-light bg-light">
						<form class="form-inline" action="pesquisar.php" method="POST">
							<input class="form-control mr-sm-2" type="search" placeholder="Insera o cep desejado" aria-label="Search" name="busca" autofocus>
							<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
						</form>
						<form class="form-inline" action="pesquisar.php" method="POST">
							<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Listar Todos</button>
						</form>
					</nav>
					

					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col"> + </th>
								<th scope="col">CEP de Origem</th>
								<th scope="col">CEP de Destino</th>
								<th scope="col">Distancia Calculada</th>
								<th scope="col">Data da Criação</th>
								<th scope="col">Data da Alteração</th>
								<th scope="col">Funções</th>
							</tr>
						</thead>
						<tbody>
							
							<?php
								$collapseRow = 0;
								while($d = mysqli_fetch_assoc ($dados) )
								{
									$collapseRow++;
									
									$id = $d['id'];
									$cepOrigem = $d['cepOrigem'];
									$cepDestino = $d['cepDestino'];
									$distCalculada = $d['distCalculada'];
									$dtCriacao = formataDataHora($d['dtCriacao']);
									$dtAlteracao = formataDataHora($d['dtAlteracao']);
									
									$ORI_CEP = $d['ORI_CEP'];
									$ORI_ESTADO = $d['ORI_ESTADO'];
									$ORI_CIDADE = $d['ORI_CIDADE'];
									$ORI_BAIRRO = $d['ORI_BAIRRO'];
									$ORI_LOGRADOURO = $d['ORI_LOGRADOURO'];
									
									$DEST_CEP = $d['DEST_CEP'];
									$DEST_ESTADO = $d['DEST_ESTADO'];
									$DEST_CIDADE = $d['DEST_CIDADE'];
									$DEST_BAIRRO = $d['DEST_BAIRRO'];
									$DEST_LOGRADOURO = $d['DEST_LOGRADOURO'];
									
									echo "
									<tr>
										<td><span class='clickable' data-toggle='collapse' id='row$collapseRow' data-target='.row$collapseRow'><i class='fas fa-plus' id='test'></i>&nbsp</span></td>
      									<td>$cepOrigem</td>
										<td>$cepDestino</td>
										<td>$distCalculada Km</td>
										<td>$dtCriacao</td>
										<td>$dtAlteracao</td>
										<td>
											<a href='editar.php?id=$id' class='btn btn-primary btn-sm'>Editar</a>
											<a href='#' class='btn btn-danger btn-sm' data-toggle='modal' 
												data-target='#modalConfirm' onclick=" .'"' ."setId($id)" .'"' ." >Excluir
											</a>
										</td>
									</tr>
									<tr class='collapse row$collapseRow'>
										<td></td>
      									<td>$ORI_CEP</td>
										<td>$ORI_ESTADO</td>
										<td>$ORI_CIDADE</td>
										<td>$ORI_BAIRRO</td>
										<td>$ORI_LOGRADOURO</td>
										<td></td>
									</tr>
									<tr class='collapse row$collapseRow'>
										<td></td>
      									<td>$DEST_CEP</td>
										<td>$DEST_ESTADO</td>
										<td>$DEST_CIDADE</td>
										<td>$DEST_BAIRRO</td>
										<td>$DEST_LOGRADOURO</td>
										<td></td>
									</tr>
									";
								}
							?>

						</tbody>
					</table>

					<hr><a href="index.php" class="btn btn-primary">Voltar</a>

				</div>
			</div>
		</div>

		<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Alerta!</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <form action="excluir_script.php" method="POST">
				<div class="modal-body">
					<p>Confirma a exclusção do registro?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
					<input type="submit" class="btn btn-primary" value="Sim">
					<input type="hidden" name="id" id="id" value="">
				</div>
			  </form>
			</div>
		  </div>
		</div>

		<script type="text/javascript">
			function setId(id) {
				document.getElementById('id').value = id;
			}
		</script>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	</body>
</html>