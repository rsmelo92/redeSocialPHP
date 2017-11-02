<?php 

	session_start("rede_social");
	if ($_SESSION["logado"] != 'ok') {
		header("Location: index.php");
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
		<section class="profile-card-section col s12 m3">

			<div class="card profile-card">
				<div class="card-content">
					<div class="card-info-holder">
						<span class="profile-figure-second valign-wrapper"><?php echo $_SESSION['nome_usuario'] ?></span>
					</div>
					<div class="card-list-holder">
						<ul>
							
						</ul>
					</div>
				</div>
				<div class="card-action">
					<a href="#">Editar Perfil</a>
				</div>
			</div>
		</section>

		<section class="feed-section container col 6">
			
		</section>

		<section class="other-section col 3">
			
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