<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

		<title>DataFrete Teste</title>
		
	</head>
	<body>
		
		<?php
			
			include "conexao.php";

			$id = $_GET['id'] ?? '';			
			$sql = "SELECT A.cepOrigem, A.cepDestino FROM `cadastro` AS A where A.id = $id";
			
			$dados = mysqli_query($conn, $sql);
			$d = mysqli_fetch_assoc($dados)
		?>
	
		<div class="container">
			<div class="row">
				<div class="col">
					<h1>Alteração de Cadastro</h1>
					<form action="editar_script.php" method="POST">
						<div class="form-group">
							<label for="cepOrigem">Cep Origem</label>
							<input type="number_format" class="form-control" name="cepOrigem" id="cepOrigem" required value="<?php echo $d['cepOrigem']; ?>">
						</div>
						<div class="form-group">
							<label for="cepDestino">Cep Destino</label>
							<input type="number_format" class="form-control" name="cepDestino" id="cepDestino" required value="<?php echo $d['cepDestino']; ?>">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success">Salvar Alterações</button>
							<input type="hidden" name="id" value="<?php echo $id; ?>">
						</div>
					</form>
					<hr><a href="index.php" class="btn btn-primary">Voltar</a>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	</body>
</html>