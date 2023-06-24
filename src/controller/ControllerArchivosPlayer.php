<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/ArchivosPlayerDAO.php");

	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch ($_POST["accion"]) {
		case 'getPlayers':
			$objArchivoPlayer = new ArchivoPlayer();
			$respuesta = $objArchivoPlayer->getPlayers();
			unset($objArchivoPlayer);
			echo json_encode($respuesta);
			break;
		case 'getArchivoPorPlayer':
			$objArchivoPlayer = new ArchivoPlayer();
			$respuesta = $objArchivoPlayer->getArchivoPorPlayer($_POST["idPlayer"]);
			unset($objArchivoPlayer);
			echo json_encode($respuesta);
			break;
		case 'getArchivoNoPlayer':
			$objArchivoPlayer = new ArchivoPlayer();
			$respuesta = $objArchivoPlayer->getArchivoNoPlayer($_POST["archivosPlayer"]);
			unset($objArchivoPlayer);
			echo json_encode($respuesta);
			break;
		case 'addArchivoPlayer':
			$objArchivoPlayer = new ArchivoPlayer();
			$respuesta = $objArchivoPlayer->addArchivoPlayer($_POST["datos"]);
			unset($objArchivoPlayer);
			echo $respuesta;
			break;
		case 'addUnArchivoPlayer':
			$objArchivoPlayer = new ArchivoPlayer();
			$respuesta = $objArchivoPlayer->addUnArchivoPlayer($_POST["datos"]);
			unset($objArchivoPlayer);
			echo $respuesta;
			break;
		case 'deleteArchivoPlayer':
			$objArchivoPlayer = new ArchivoPlayer();
			$respuesta = $objArchivoPlayer->deleteArchivoPlayer($_POST["idArchivoPlayer"]);
			unset($objArchivoPlayer);
			echo $respuesta;
			break;
		default:
			echo "La acción no está registrada";
			break;
	}
?>