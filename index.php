<?php 

	error_reporting(1);

	session_start("rede_social");

	$servidor 	= "localhost";
	$user 		= "root";
	$senha 		= "";
	$banco	 	= "andrecos_unifacs";

	$con = new mysqli($servidor, $user, $senha, $banco);

	if ($con->connect_errno) {
		echo "Erro ao conectar: " . $con->connect_error;
	}
	if ($_POST != NULL) {
		$login = addslashes( $_POST["login"]);
		$senha = addslashes( $_POST["senha"]);
		$senha = md5($senha);

		$sql = "SELECT * 
				FROM 	usuario 
				WHERE 	login = '{$login}' 
				AND 	senha = '{$senha}'";

		$return = $con->query($sql);

		$registro = $return -> fetch_array();

		if ($registro) {
			$_SESSION["logado"] 		= "ok";
			$_SESSION["nome_usuario"] 	= $registro["nome"];
			$_SESSION["id_usuario"] 	= $registro["id"];

			header("Location: dashboard.php");
		}
		else{
			echo "<script>alert('Login ou senha inválidos')</script>";
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
<?php 
	include('head.html');
?>	
</head>
<body>
	<main class="login-page-holder">
			
		<section class="login-section container">
			<div class="login-holder z-depth-4">
				<h3>Rede Social</h3>
				<form method="POST">
					<div class="input-field col s12">
						<input id="login" type="text" class="validate" name="login">
						<label for="login">Login</label>
					</div>

					<div class="input-field col s12">
						<input id="senha" type="password" class="validate" name="senha">
						<label for="senha">Password</label>
					</div>
					<button class="btn waves-effect waves-light " type="submit" name="action">Entrar
						<i class="material-icons right">send</i>
					</button>
				</form>
			</div>
		</section>
		
	</main>
</body>
</html>