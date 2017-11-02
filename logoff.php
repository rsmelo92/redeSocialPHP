<?php 
	
	session_start("rede_social");

	session_destroy();

	header("Location: index.php");

?>