<?php 

	error_reporting(1);

	session_start("rede_social");
	// // local
	$servidor 	= "localhost";
	$user 		= "root";
	$senha 		= "";
	$banco	 	= "andrecos_unifacs";

	// // heroku
	// $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	// $servidor = $url["host"];
	// $user 	  = $url["user"];
	// $senha 	  = $url["pass"];
	// $banco 	  = substr($url["path"], 1);

	$con = new mysqli($servidor, $user, $senha, $banco);

	if ($con->connect_errno) {
		echo "Erro ao conectar: " . $con->connect_error;
	}

	$cadastro = $_GET["cadastrar"];

	if ($_POST != NULL) {
		if ($cadastro) {
			$nome 		= addslashes( $_POST["nome"]);
			$login 		= addslashes( $_POST["login"]);
			$senha 		= addslashes( $_POST["senha"]);
			$senha 		= md5($senha);
			$curso 		= addslashes( $_POST['curso'] );
			$semestre 	= addslashes( $_POST['semestre'] );

			$sql = "SELECT * 
					FROM usuario
					WHERE login = '$login' ";
			
			$retorno = $con -> query( $sql );
			$registro = $retorno -> fetch_array();

			if (!$registro[0]) {
				$sql = "INSERT INTO usuario(nome, login, senha, curso, semestre) 
						VALUES ('$nome', '$login', '$senha', '$curso', '$semestre')";

				$retorno = $con -> query( $sql );

				if ($retorno) {
					echo "<script>";
					echo "alert('Cadastrado com sucesso!');";
					echo "location.href = 'index.php'; ";
					echo "</script>";
				}
				else{
					echo "<script>";
					echo "alert('Erro na inserção!');";
					echo "</script>";
				}
			}
			else{
				echo "<script>";
				echo "alert('Já existe um user com esses dados!');";
				echo "location.href = 'index.php'; ";
				echo "</script>";
			}

		}
		else{
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
				$_SESSION["logado"] 			= "ok";
				$_SESSION["nome_usuario"] 		= $registro["nome"];
				$_SESSION["id_usuario"] 		= $registro["id"];
				$_SESSION["curso_usuario"] 		= $registro["curso"];
				$_SESSION["semestre_usuario"] 	= $registro["semestre"];

				header("Location: dashboard.php");
			}
			else{
				echo "<script>alert('Login ou senha inválidos')</script>";
			}
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
				<?php 
						if ($cadastro) {
						
					?>
				<script>
					$('.login-page-holder').css('padding-top','5px');
				</script>

				<form method="POST">
					<h5 style="text-align: center;">Faça seu cadastro</h5>
					<div class="input-field col s12">
						<select name="semestre">
							<option value="" disabled selected>Escolha seu semestre</option>
							<option value="1 Semestre">1 Semestre</option>
							<option value="2 Semestre">2 Semestre</option>
							<option value="3 Semestre">3 Semestre</option>
							<option value="4 Semestre">4 Semestre</option>
							<option value="5 Semestre">5 Semestre</option>
							<option value="6 Semestre">6 Semestre</option>
							<option value="7 Semestre">7 Semestre</option>
							<option value="8 Semestre">8 Semestre</option>
							<option value="9 Semestre">9 Semestre</option>
							<option value="10 Semestre">10 Semestre</option>
						</select>
					</div>
					<div class="input-field col s12">
						<select name="curso">
							<option value="" disabled selected>Escolha sua Engenharia</option>
							<option value="Ambiental">Ambiental</option>
							<option value="Civil">Civil</option>
							<option value="Computação">Computação</option>
							<option value="Produção">Produção</option>
							<option value="Mecatrônica">Mecatrônica</option>
							<option value="Mecânica">Mecânica</option>
							<option value="Elétrica">Elétrica</option>
							<option value="Petróleo">Petróleo</option>
							<option value="Química">Química</option>
						</select>
					</div>
					<div class="input-field col s12">
						<input id="nome" type="text" class="validate" name="nome">
						<label for="nome">Nome</label>
					</div>
					<div class="input-field col s12">
						<input id="login" type="text" class="validate" name="login">
						<label for="login">Login</label>
					</div>
					<div class="input-field col s12">
						<input id="senha" type="password" class="validate" name="senha">
						<label for="senha">Senha</label>
					</div>
					<button class="btn waves-effect waves-light col s12" type="submit" name="action" style="display: block;margin-bottom: 10px;">Cadastrar
					</button>
					<a class="col s12" href="index.php">Cancelar</a>


					<?php 
						}
						else{
					?>
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
					<p>Não tem uma conta? <a href="index.php?cadastrar=1">Cadastrar</a></p>
					<?php 
						}
					?>
				</form>
			</div>
		</section>
		
	</main>
</body>
</html>