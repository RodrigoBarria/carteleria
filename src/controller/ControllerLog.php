<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/LogDAO.php");
	
	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch ($_POST["accion"]) {
		case 'getLogEventos':
			$objLog = new Log();
			$respuesta = $objLog->getLogEventos($_POST["pagina"], $_POST["idCliente"]);
			unset($objLog);
			echo json_encode($respuesta);
			break;
		default:
			echo "La acción no está registrada";
			break;
	}
?>