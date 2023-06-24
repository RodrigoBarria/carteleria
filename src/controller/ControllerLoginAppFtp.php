<?php
header('Access-Control-Allow-Origin: *');
	/*Usado para iniciar sesión en appFtp*/

	header('Access-Control-Allow-Origin: *');
	require("../dao/LoginDAO.php");

	$validaUsuario = new LoginUsuario();
	$respuesta = $validaUsuario->validaLoginAppFtp($_POST["clave"]);
	unset($validaUsuario);
	echo $respuesta;
?>