<?php

	/*Usado para registrar datos asociados a una subida de archivo por appFtp*/
	header('Access-Control-Allow-Origin: *');
	require("../dao/ArchivosDAO.php");

	switch ($_POST["accion"]) {
		case 'getArchivosAppFtp':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->getArchivosAppFtp($_POST["idCliente"]);
			unset($objArchivo);
			echo json_encode($respuesta);
			break;
		case 'isArchivo':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->isArchivo($_POST["archivo"],$_POST["idCliente"]);
			unset($objArchivo);
			echo $respuesta;
				break;
		case 'uploadArchivoAppFtp':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->uploadArchivoAppFtp($_POST["archivo"],$_POST["idCliente"], $_POST["preview"]);
			unset($objArchivo);
			echo $respuesta;
			break;
		case 'getSizeUpload':
			$objArchivo = new Archivo();
			$respuesta = $objArchivo->getSizeUpload($_POST["idCliente"]);
			unset($objArchivo);
			echo $respuesta;
			break;	
		default:
			echo "Error controller";
			break;
	}
?>