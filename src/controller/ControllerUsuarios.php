<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/UsuariosDAO.php");

	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch ($_POST["accion"]) {
		case 'getUsuarios':
			$objUsuario = new Usuario();
			$respuesta = $objUsuario->getUsuarios($_POST["pagina"]);
			echo json_encode($respuesta);
			break;
		case 'getUnUsuario':
			$objUsuario = new Usuario();
			$respuesta = $objUsuario->getUnUsuario($_POST["idUsuario"]);
			echo json_encode($respuesta);
			break;
		case 'addUsuario':
			$objUsuario = new Usuario();
			$respuesta = $objUsuario->addUsuario($_POST["datos"]);
			echo $respuesta;
			break;
		case 'updateUsuario':
			$objUsuario = new Usuario();
			$respuesta = $objUsuario->updateUsuario($_POST["datos"]);
			echo $respuesta;
			break;
		case 'deleteUsuario':
			$objUsuario = new Usuario();
			$respuesta = $objUsuario->deleteUsuario($_POST["idUsuario"], $_POST["loginUsuario"]);
			echo $respuesta;
			break;	
		default:
			echo "La acción enviada no existe";
			break;
	}
?>