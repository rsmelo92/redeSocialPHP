<?php 

	session_start("rede_social");
	if ($_SESSION["logado"] != 'ok') {
		header("Location: index.php");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>DashBorad</title>
</head>
<body>
	<h1>Olá <?php echo $_SESSION['nome_usuario'] ?>, seja bem vindo</h1>
	<br>
	<a href="logoff.php">Sair</a>
</body>
</html>