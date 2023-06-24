<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/LoginDAO.php");

	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch ($_POST["accion"]) {
		case 'validaLogin':
			$validaUsuario = new LoginUsuario();
			$respuesta = $validaUsuario->validaLogin($_POST["cliente"]);
			unset($validaUsuario);
			echo $respuesta;
			break;
		case 'validaLoginUsuario':
			$validaUsuario = new LoginUsuario();
			$respuesta = $validaUsuario->validaLoginUsuario($_POST["usuario"]);
			unset($validaUsuario);
			echo $respuesta;	
			break;
		case 'logout':
			$validaUsuario = new LoginUsuario();
			$respuesta = $validaUsuario->logout();
			echo $respuesta;
			break;
		default:
			echo "La acción no está registrada";
			break;
	}
?>