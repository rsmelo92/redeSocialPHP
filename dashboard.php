<?php 

	error_reporting(1);

	session_start("rede_social");
	if ($_SESSION["logado"] != 'ok') {
		header("Location: index.php");
	}

	// Local
	// $servidor 	= "localhost";
	// $user 		= "root";
	// $senha 		= "";
	// $banco	 	= "andrecos_unifacs";

	// heroku
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	$servidor = $url["host"];
	$user 	  = $url["user"];
	$senha 	  = $url["pass"];
	$banco 	  = substr($url["path"], 1);

	$con = new mysqli($servidor, $user, $senha, $banco);

	if ($con->connect_errno) {
		echo "Erro ao conectar: " . $con->connect_error;
	}

	$seguir 	= $_GET["seguir"];
	$desseguir 	= $_GET["desseguir"];
	$seguirNome = $_GET["seguirNome"];
	$mensagemId = $_GET["mensagemId"];
	$idUser 	= $_SESSION["id_usuario"];

	// Curtir mensagem
	if ($mensagemId) {
		$sql = "SELECT * 
				FROM curtidas 
				WHERE mensagemId='$mensagemId' 
				AND curtidorId='$idUser'";
		$retornoCurtida = $con -> query($sql);
		$registroLike = $retornoCurtida -> fetch_array();
		if (!$registroLike[0]) {
			$sql = "INSERT INTO curtidas (mensagemId, curtidorId)
					VALUES ('$mensagemId', '$idUser')";
			$retorno = $con -> query( $sql );
			if (!$retorno) {
				echo "<script>";
				echo "alert('Erro na inserção!');";
				echo "</script>";
			}
			else{
				header("Location: dashboard.php");
			}
		}
		else{
			$sql = "DELETE 
					FROM curtidas 
					WHERE mensagemId='$mensagemId' 
					AND curtidorId='$idUser'";
			$retornoCurtida = $con -> query($sql);
			header("Location: dashboard.php");
		}
	}

	// Seguir user
	if ($seguir) {
		$sql = "SELECT * 
				FROM seguindo 
				WHERE seguidorId='$idUser' 
				AND seguindoId='$seguir'";
		$retornoSeguindo = $con -> query($sql);
		$registro = $retornoSeguindo -> fetch_array();
		if (!$registro[0]) {
			$sql = "INSERT INTO seguindo (seguidorId, seguindoId, seguindoNome)
					VALUES ('$idUser', '$seguir', '$seguirNome')";
			$retorno = $con -> query( $sql );
			if (!$retorno) {
				echo "<script>";
				echo "alert('Erro na inserção!');";
				echo "</script>";
			}
			else{
				header("Location: dashboard.php");
			}
		}
		else{
			echo "<script>alert('Você já segue esta pessoa')</script>";
		}
		
	}
	// Parar de seguir user
	if ($desseguir) {
		$idUser = $_SESSION["id_usuario"];
		$sql = "DELETE 
				FROM seguindo 
				WHERE seguidorId='$idUser' 
				AND seguindoId='$desseguir'";
		$retornoDesseguindo = $con -> query($sql);
		if (!$retornoDesseguindo) {
			echo "<script>";
			echo "alert('Erro no delete!');";
			echo "location.href = 'dashboard.php'; ";
			echo "</script>";
		}
		else{
			header("Location:dashboard.php");
		}
	}

	// Enviar mensagem
	if ($_POST != NULL) {

		$senderNome 	= $_SESSION["nome_usuario"];
		$senderId 		= $_SESSION["id_usuario"];
		$senderCurso 	= $_SESSION["curso_usuario"];
		$senderSem 		= $_SESSION["semestre_usuario"];
		$message 		= $_POST["message_feed"];
		date_default_timezone_set('America/Araguaina');
		$data 			= date('Y-m-d H:i:sa');

		if ($message == '') {
			header("Location:dashboard.php");
			return;
		}
		$sql = "INSERT INTO mensagens (texto, sender, idSender, cursoSender, semestreSender, data)
				VALUES ('$message', '$senderNome', '$senderId', '$senderCurso', '$senderSem', CURRENT_TIMESTAMP())";

		$retorno = $con -> query( $sql );

		if (!$retorno) {
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
			<a href="dashboard.php" class="brand-logo" style="font-size: 22px;">Rede Social</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a class="profile-figure valign-wrapper" href="sass.html"><?php echo $_SESSION['nome_usuario'] ?></a></li>
				<li><a href="logoff.php">Sair</a></li>
			</ul>
		</div>
	</nav>

	<main class="app-main row">
		<!-- <div class="grid-helper col xl1 show-on-large"></div> -->
		<section class="profile-card-section col s12 l3">

			<div class="card profile-card z-depth-1 card-content">
				<!-- <a class="edit-mobile-profile hide-on-large-only btn-floating btn-small waves-effect">
					<i class="small material-icons">mode_edit</i>
				</a> -->
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
				<div class="user-data-holder">
					<h4>
						<?php 
							$sqlUser = "SELECT * 
										FROM seguindo
										WHERE seguindoId='$idUser'";
							$retornoSeguindoThird = $con -> query($sqlUser);
							echo mysqli_num_rows($retornoSeguindoThird);
						?> 
					</h4>
					<span>
						Seguidores
					</span>
				</div>
				<div class="user-data-holder">
					<h4>
						<?php 
							$sqlUser = "SELECT * 
										FROM curtidas
										WHERE curtidorId='$idUser'";
							$retornoCurtidaSecond = $con -> query($sqlUser);
							echo mysqli_num_rows($retornoCurtidaSecond);
						?> 
					</h4>
					<span>
						Curtidas
					</span>
				</div>
				<div class="user-data-holder">
					<h4>
						<?php 
							$sqlUser = "SELECT * 
										FROM mensagens
										WHERE idSender='$idUser'";
							$retornoMsgSecond = $con -> query($sqlUser);
							echo mysqli_num_rows($retornoMsgSecond);
						?> 
					</h4>
					<span>
						Posts
					</span>
				</div>
			</div>
			</div>
		</section>

		<section class="feed-section container col s12 l6">
			<div class="feed-text-holder">
				<div class="row">
					<form method="POST" class="col s12">
						<div class="textarea-feed-holder z-depth-1 valign-wrapper">
							<textarea name="message_feed" class="col s11" placeholder="O que está pensando?"></textarea>	
							<span class="col s2">
								<button type="submit" class="btn-floating btn-large waves-effect waves-light"><i class="material-icons">send</i></button>		
							</span>		
						</div>
					</form>
				</div>
			</div>

			<div class="feed-content-holder">
			<?php 
				if ($_GET['filtrar']) {
					$idSender = $_GET['idSender'];
					$sql = "SELECT * FROM mensagens WHERE idSender='$idSender' ORDER BY data DESC";
				}
				else{
					$sql = "SELECT * FROM mensagens ORDER BY data DESC";
				}
				$retornoMsg = $con -> query($sql);
				while ($registro = $retornoMsg -> fetch_array()) {
					$msgId 		= $registro['id'];
					$nome 		= $registro['sender'];
					$curso 		= $registro['cursoSender'];
					$semestre 	= $registro['semestreSender'];
					$texto 		= $registro['texto'];
					$data 		= $registro['data'];
					$idSender	= $registro['idSender'];
			?>
				<article>
					<div class="article-header valign-wrapper">
						<span><?php echo $nome; ?></span>
						<span class="badge red"><?php echo $curso; ?></span>
  						<span class="badge blue"><?php echo $semestre; ?></span>
						<a class="add-person-icon" href="dashboard.php?seguir=<?php echo $idSender; ?>&seguirNome=<?php echo $nome; ?>"> <i class="small material-icons">person_add</i> </a>
					</div>
					<div class="article-body">
						<p><em><?php echo $texto; ?></em></p>
					</div>
					<div class="article-footer">
						<div class="article-do-stuff">
							<a href="dashboard.php?mensagemId=<?php echo $msgId; ?>&$userId=<?php echo $idUser; ?>">
								<i class="material-icons">thumb_up</i>
								<span>
									<?php  
										$sqlLiked = "SELECT id
													FROM curtidas 
													WHERE mensagemId='$msgId'";
										$retornoMsgLiked = $con -> query($sqlLiked);
										echo mysqli_num_rows($retornoMsgLiked);
									?>
								</span>
							</a>
							<a href=""><i class="material-icons">mode_comment</i></a>
						</div>
						<div class="article-date">
							<small><?php echo $data; ?></small>
						</div>
					</div>
				</article>
			<?php
				}
			?>
			</div>

		</section>

		<section class="other-section col s12 m3">
			<div class="card">
				<ul class="collection with-header">
					<li class="collection-header"><h5>Seguindo <a href="dashboard.php"><i class='material-icons'>home</i></a></h5></li>
					<?php 
						$idUserSeguidor = $_SESSION["id_usuario"];
						$sqlUser = "SELECT * 
									FROM seguindo
									WHERE seguidorId='$idUserSeguidor'";
						$retornoSeguindoSecond = $con -> query($sqlUser);
						while($registroSeguindo = $retornoSeguindoSecond -> fetch_array()){
							$seguindoNome = $registroSeguindo['seguindoNome'];
							$seguindoId = $registroSeguindo['seguindoId'];
					?>
					<li class='collection-item valign-wrapper'>
						<a href="dashboard.php?desseguir=<?php echo $seguindoId; ?>&seguirNome=<?php echo $seguindoNome; ?>"><i class='material-icons'>highlight_off</i></a>
						<a href="dashboard.php?filtrar=1&idSender=<?php echo $seguindoId; ?>"><?php echo $seguindoNome; ?></a>
					</li>

					<?php
						}
					?>
					
				</ul>
			</div>
		</section>
	</main>

	<footer>
		<div class="fixed-action-btn horizontal click-to-toggle hide-on-large-only">
			<a class="btn-floating btn-large red">
				<i class="material-icons">menu</i>
			</a>
			<ul>
				<li>
					<a class="btn-floating blue" href="logoff.php"><i class="material-icons">exit_to_app</i></a>
				</li>
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