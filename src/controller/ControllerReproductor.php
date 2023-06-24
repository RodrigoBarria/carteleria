<?php
	header('Access-Control-Allow-Origin: *');
	require("../dao/ReproductorDAO.php");

	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch ($_POST["accion"]){
		case 'getArchivosReproductor':
			$objReproductor = new Reproductor();
			$respuesta = $objReproductor->getArchivosReproductor($_POST["datos"]);
			unset($objReproductor);
			echo json_encode($respuesta);
			break;
		case 'getConfigPlayer':
			$objReproductor = new Reproductor();
			$respuesta = $objReproductor->getConfigPlayer($_POST["datos"]);
			unset($objReproductor);
			echo json_encode($respuesta);
			break;
		case 'updateConfigPlayer':
			$objReproductor = new Reproductor();
			$respuesta = $objReproductor->updateConfigPlayer($_POST["datos"]);
			unset($objReproductor);
			echo $respuesta;
			break;
		default:
			echo "La acción enviada no existe";
			break;
	}
?>