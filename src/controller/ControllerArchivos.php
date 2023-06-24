<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/ArchivosDAO.php");
	set_time_limit(600);

	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch($_POST["accion"]){
		case 'uploadArchivo':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->uploadArchivo($_FILES['archivo'], $_POST["preview"]);
			echo $respuesta;
			break;
		case 'getArchivos':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->getArchivos();
			echo json_encode($respuesta);
			break;
		case 'deleteArchivo':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->deleteArchivo($_POST["datos"]);
			echo $respuesta;
			break;
		case 'renombrarArchivo':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->renombrarArchivo($_POST["datos"]);
			echo $respuesta;
			break;
		case 'getSizeUpload':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->getSizeUpload();
			echo $respuesta;
			break;
		default:
			echo "La acción enviada no existe";
			break;
	}
?>