<?php
	// conexao
	$server = "localhost";
	$user = "root";
	$pass = "";
	$bd = "datafrete";
	
	if ($conn = mysqli_connect($server, $user, $pass, $bd)) {
		//echo "Conectado!";
	} else 
		echo "Erro!";
?>