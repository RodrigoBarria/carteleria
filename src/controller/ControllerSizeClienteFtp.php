<?php
	header('Access-Control-Allow-Origin: *');
	require("../dao/ArchivosDAO.php");

	$objArchivo = new Archivo();
	$respuesta = $objArchivo->getSizeUploadFtp($_POST["idCliente"]);
	unset($objArchivo);
	echo $respuesta;
?>