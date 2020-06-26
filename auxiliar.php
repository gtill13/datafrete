<?php
	// api's
	define('TOKEN_CEPABERTO', 'f75747cca92759d0a56ca07351085487');
	
	//classes
	class Cep 
	{
		public string $cep;
		public string $latitude;
		public string $longitude;
		public string $altitude;
		public string $bairro;
		public string $logradouro;
		public string $cidade;
		public string $estado;
		public string $ddd;
		public string $ibge;
		
		private $included;
		
		public function __construct($cep)
		{
			$cep = trim($cep);
			$cep = str_replace("-", "", $cep);
			
			if(!preg_match('/^[0-9]{5,5}([- ]?[0-9]{3,3})?$/', $cep)) {
				throw new Exception('Cep: '.$cep.' inválido!');
			}
			
			$this->cep = $cep;
			$this->latitude = "";
			$this->longitude = "";
			$this->altitude = "";
			$this->bairro = "";
			$this->logradouro = "";
			$this->cidade = "";
			$this->estado = "";
			$this->ddd = "";
			$this->ibge = "";
			
			$this->included = false;
		}

		public function validaCep($conn)
		{
			if (!$this->validaPeloBanco($conn))
				if(!$this->validaPelaAPI())
					throw new Exception('Cep:'.$this->cep.' não encontrado!');
		}	

		private function validaPeloBanco($conn)
		{
			$sql = "select * from cep where cep = '$this->cep'";
			$dados = mysqli_query($conn, $sql);
			
			if ($d = mysqli_fetch_assoc ($dados))
			{
				$this->latitude = $d["latitude"];
				$this->longitude = $d["longitude"];
				$this->altitude = $d["altitude"];
				$this->bairro = $d["bairro"];
				$this->logradouro = $d["logradouro"];
				$this->cidade = $d["cidade"];
				$this->estado = $d["estado"];
				$this->ddd = $d["ddd"];
				$this->ibge = $d["ibge"];
				
				$this->included = true;
			}
			else
				return false;
			
			return true;
		}
		
		private function validaPelaAPI()
		{
			$token = TOKEN_CEPABERTO;
			
			$curl = curl_init( 'https://www.cepaberto.com/api/v3/cep?cep='.$this->cep );
			curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Token token="'.$token.'"' ) );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			$result = curl_exec( $curl );
			curl_close( $curl );
			$result = json_decode( $result );

			if (empty($result->cep)) 
			{
				return false;
			}

			if (isset($result->latitude))
				$this->latitude = $result->latitude;

			if (isset($result->longitude))
				$this->longitude = $result->longitude;
			
			if (isset($result->altitude))
				$this->altitude = $result->altitude;
			
			if (isset($result->bairro))
				$this->bairro = $result->bairro;
			
			if (isset($result->logradouro))
				$this->logradouro = $result->logradouro;
			
			if (isset($result->cidade->nome))
				$this->cidade = $result->cidade->nome;
			
			if (isset($result->estado->sigla))
				$this->estado = $result->estado->sigla;
			
			if (isset($result->cidade->ddd))
				$this->ddd = $result->cidade->ddd;
			
			if (isset($result->cidade->ibge))
				$this->ibge = $result->cidade->ibge;

			return true;
		}
		
		public function insereBanco($conn)
		{
			if (!$this->included)
			{
				$sql = "INSERT INTO `cep`
							(`cep`, `latitude`, `longitude`, `altitude`, `bairro`, `logradouro`, `cidade`, `estado`, `ddd`, `ibge`) 
						VALUES 
							('$this->cep','$this->latitude','$this->longitude','$this->altitude','$this->bairro','$this->logradouro','$this->cidade','$this->estado','$this->ddd','$this->ibge')";
						
				if (!mysqli_query($conn, $sql))
					throw new Exception('Cep:'.$this->cep.' houve uma falha ao inserir no banco!');
			}
		}
	}	 
	
	class Cadastro
	{
		public Cep $cepOrigem;
		public Cep $cepDestino;
		public $distancia;
		public $dtCriacao;
		public $dtAlteracao;
		
		private $included;
		
		public function __construct()
		{
			$this->included = false;
		}
		
		public function setCeps($cepOrigem, $cepDestino)
		{
			$this->cepOrigem = $cepOrigem;
			$this->cepDestino = $cepDestino;
			$this->distancia = $this->distancia2($this->cepOrigem->latitude, $this->cepOrigem->longitude, $this->cepDestino->latitude, $this->cepDestino->longitude);
		}
		
		public function distancia() 
		{
			$this->distancia = $this->distancia2($this->cepOrigem->latitude, $this->cepOrigem->longitude, $this->cepDestino->latitude, $this->cepDestino->longitude);
			return $this->distancia;
		}
		
		private function distancia2($lat1, $lon1, $lat2, $lon2) 
		{
			$lat1 = deg2rad($lat1);
			$lat2 = deg2rad($lat2);
			$lon1 = deg2rad($lon1);
			$lon2 = deg2rad($lon2);

			$latD = $lat2 - $lat1;
			$lonD = $lon2 - $lon1;

			$dist = 2 * asin(sqrt(pow(sin($latD / 2), 2) +
			cos($lat1) * cos($lat2) * pow(sin($lonD / 2), 2)));
			$dist = $dist * 6371;
			return number_format($dist, 2, '.', '');
		}
		
		public function insereBanco($conn)
		{
			$c1 = $this->cepOrigem->cep;
			$c2 = $this->cepDestino->cep;
			
			$sql = "select * from cadastro where cepOrigem = '$c1' and cepDestino = '$c2'";
			$dados = mysqli_query($conn, $sql);
			
			if ($d = mysqli_fetch_assoc ($dados))
			{
				$this->distancia = $d['distCalculada'];
				$this->dtCriacao = $d['dtCriacao'];
				$this->dtAlteracao = $d['dtAlteracao'];
				
				$this->included = true;
			}
			
			if (!$this->included)
			{
				$sql = "INSERT INTO `cadastro`
							(`cepOrigem`, `cepDestino`, `distCalculada`)
						VALUES 
							('$c1','$c2','$this->distancia')";
						
				if (!mysqli_query($conn, $sql))
					throw new Exception('Houve uma falha ao inserir no banco!');
				else
				{
					$sql = "select * from cadastro where cepOrigem = '$c1' and cepDestino = '$c2'";
					$dados = mysqli_query($conn, $sql);
					
					if ($d = mysqli_fetch_assoc ($dados))
					{
						$this->distancia = $d['distCalculada'];
						$this->dtCriacao = $d['dtCriacao'];
						$this->dtAlteracao = $d['dtAlteracao'];
					}
				}
			}
		}
		
		public function alterarBanco($id, $conn)
		{
			$c1 = $this->cepOrigem->cep;
			$c2 = $this->cepDestino->cep;
			
			$sql = "update `cadastro` set
						`cepOrigem` = '$c1', `cepDestino` = '$c2', `distCalculada` = '$this->distancia'
					where id = $id";
					
			if (!mysqli_query($conn, $sql))
				throw new Exception('Houve uma falha ao inserir no banco!');
			else
			{
				$sql = "select * from cadastro where id = $id";
				$dados = mysqli_query($conn, $sql);
				
				if ($d = mysqli_fetch_assoc ($dados))
				{
					$this->distancia = $d['distCalculada'];
					$this->dtCriacao = $d['dtCriacao'];
					$this->dtAlteracao = $d['dtAlteracao'];
				}
			}
		}
		
		public function excluirBanco($id, $conn)
		{
			$sql = "DELETE FROM `cadastro` WHERE id = $id";
					
			if (!mysqli_query($conn, $sql))
				throw new Exception('Houve uma falha ao inserir no banco!');
		}
	}
	
	//function's
	function mensagem($s, $t="success")
	{
		echo "<div class='alert alert-$t' role='alert'>
				$s
			</div>";
	}
	
	function formataDataHora($s)
	{
		if (empty($s))
			return "";
		$date = new DateTime($s);
		return $date->format('d/m/Y H:i');
	}
?>