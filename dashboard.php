<?php 

	session_start("rede_social");
	if ($_SESSION["logado"] != 'ok') {
		header("Location: index.php");
	}

	$con = new mysqli("localhost", "root", "", "andrecos_unifacs");
	
	if ($con->connect_errno) {
		echo "Erro ao conectar: " . $con->connect_error;
	}

	if ($_POST != NULL) {

		$senderNome 	= $_SESSION["nome_usuario"];
		$senderId 		= $_SESSION["id_usuario"];
		$senderCurso 	= $_SESSION["curso_usuario"];
		$senderSem 		= $_SESSION["semestre_usuario"];
		$message 		= $_POST["message_feed"];
		date_default_timezone_set('America/Araguaina');
		$data 			= date('Y-m-d H:i:sa');

		$sql = "INSERT INTO mensagens (texto, sender, idSender, cursoSender, semestreSender, data)
				VALUES ('$message', '$senderNome', '$senderId', '$senderCurso', '$senderSem', CURRENT_TIMESTAMP())";

		$retorno = $con -> query( $sql );

		if ($retorno) {
			echo "<script>";
			echo "alert('Inserido com sucesso!');";
			echo "location.href = 'dashboard.php'; ";
			echo "</script>";
		}
		else{
			echo "<script>";
			echo "alert('Erro na inserção!');";
			echo "</script>";
		}

	}

?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		include('head.html');
	?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/trianglify/1.1.0/trianglify.min.js"></script>
</head>
<body>
	<nav class="dashboard-nav">
		<div class="nav-wrapper">
			<a href="#" class="brand-logo">Rede Social</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a class="profile-figure valign-wrapper" href="sass.html"><?php echo $_SESSION['nome_usuario'] ?></a></li>
				<li><a href="logoff.php">Sair</a></li>
			</ul>
		</div>
	</nav>

	<main class="app-main row">
		<div class="grid-helper col m1 show-on-large"></div>
		<section class="profile-card-section col s12 m2">

			<div class="card profile-card z-depth-1">
				<div class="card-content">
					<div class="card-info-holder">
						<span class="profile-figure-second valign-wrapper"><?php echo $_SESSION['nome_usuario'] ?></span>
					</div>
					<div class="card-list-holder">
						
						<ul class="card-list">
							<li class="chip"><i class="tiny material-icons">flag</i> <?php echo $_SESSION["curso_usuario"]; ?></li>
							<li class="chip"><i class="tiny material-icons">description</i><?php echo $_SESSION["semestre_usuario"]; ?></li>
						</ul>
					</div>
				</div>
				<div class="card-action">
					<a href="#">Editar Perfil</a>
				</div>
			</div>
		</section>

		<section class="feed-section container col s12 m6">
			<div class="feed-text-holder">
				<div class="row">
					<form method="POST" class="col s12">
						<div class="textarea-feed-holder z-depth-1 valign-wrapper">
							<textarea name="message_feed" class="col s11"></textarea>	
							<span class="col s2">
								<button type="submit" class="btn-floating btn-large waves-effect waves-light"><i class="material-icons">send</i></button>		
							</span>		
						</div>
					</form>
				</div>
			</div>

			<div class="feed-content-holder">
			<?php 
				$sql = "SELECT * FROM mensagens ORDER BY data DESC";
				$retornoMsg = $con -> query($sql);
				while ($registro = $retornoMsg -> fetch_array()) {
					$texto = $registro['texto'];
			?>
				<article>
					<p><?php echo $texto; ?></p>
				</article>
			<?php
				}
			?>
			</div>

		</section>

		<section class="other-section col s12 m3">
			
		</section>
	</main>

	<footer>
		<div class="fixed-action-btn horizontal click-to-toggle hide-on-large-only">
			<a class="btn-floating btn-large red">
				<i class="material-icons">menu</i>
			</a>
			<ul>
				<li><a class="btn-floating red"><i class="material-icons">insert_chart</i></a></li>
				<li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
				<li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
				<li><a class="btn-floating blue" href="logoff.php"><i class="material-icons">exit_to_app</i></a></li>
			</ul>
		</div>
	</footer>
</body>
<script>
	let pattern = Trianglify({
			width: 40,
			height: 40,
			cell_size:10
		});
	$('.profile-figure').prepend(pattern.canvas());
	$('.profile-figure-second').prepend(pattern.canvas());
</script>
</html>