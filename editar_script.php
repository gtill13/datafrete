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
		<div class="container">
			<div class="row">
				<div class="col">
					<?php
						include "conexao.php";
						include "auxiliar.php";
					
						try {
							$id = $_POST['id'];
							$cepOrigem = new Cep($_POST['cepOrigem']);
							$cepDestino = new Cep($_POST['cepDestino']);

							$cepOrigem->validaCep($conn);
							sleep(1);
							$cepDestino->validaCep($conn);
							
							$cepOrigem->insereBanco($conn);
							$cepDestino->insereBanco($conn);

							$cadastro = new Cadastro();
							$cadastro->setCeps($cepOrigem, $cepDestino);
							$cadastro->alterarBanco($id, $conn);
					?>
					
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col"> + </th>
								<th scope="col">CEP de Origem</th>
								<th scope="col">CEP de Destino</th>
								<th scope="col">Distancia Calculada</th>
								<th scope="col">Data da Criação</th>
								<th scope="col">Data da Alteração</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$cepOrigem = $cadastro->cepOrigem->cep;
								$cepDestino = $cadastro->cepDestino->cep;
								$distCalculada = $cadastro->distancia;
								$dtCriacao = formataDataHora($cadastro->dtCriacao);
								$dtAlteracao = formataDataHora($cadastro->dtAlteracao);
								
								$ORI_CEP = $cadastro->cepOrigem->cep;
								$ORI_ESTADO = $cadastro->cepOrigem->estado;
								$ORI_CIDADE = $cadastro->cepOrigem->cidade;
								$ORI_BAIRRO = $cadastro->cepOrigem->bairro;
								$ORI_LOGRADOURO = $cadastro->cepOrigem->logradouro;
								
								$DEST_CEP = $cadastro->cepDestino->cep;
								$DEST_ESTADO = $cadastro->cepDestino->estado;
								$DEST_CIDADE = $cadastro->cepDestino->cidade;
								$DEST_BAIRRO = $cadastro->cepDestino->bairro;
								$DEST_LOGRADOURO = $cadastro->cepDestino->logradouro;
								
								echo "
								<tr>
									<td><span class='clickable' data-toggle='collapse' id='row1' data-target='.row1'><i class='fas fa-plus' id='test'></i>&nbsp</span></td>
									<td>$cepOrigem</td>
									<td>$cepDestino</td>
									<td>$distCalculada Km</td>
									<td>$dtCriacao</td>
									<td>$dtAlteracao</td>
								</tr>
								<tr class='collapse show row1'>
									<td></td>
									<td>$ORI_CEP</td>
									<td>$ORI_ESTADO</td>
									<td>$ORI_CIDADE</td>
									<td>$ORI_BAIRRO</td>
									<td>$ORI_LOGRADOURO</td>
								</tr>
								<tr class='collapse show row1'>
									<td></td>
									<td>$DEST_CEP</td>
									<td>$DEST_ESTADO</td>
									<td>$DEST_CIDADE</td>
									<td>$DEST_BAIRRO</td>
									<td>$DEST_LOGRADOURO</td>
								</tr>
								";
							?>
						</tbody>
					</table>
					
					<?php
						} catch (Exception $e) {
							mensagem($e->getMessage(), "danger");
						}
					?>
					
					<hr><a href="index.php" class="btn btn-primary">Voltar</a>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	</body>
</html>