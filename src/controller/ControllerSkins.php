<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/SkinsDAO.php");
	
	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch($_POST["accion"]){
		case 'getSkinsPlayer':
			$objSkin = new Skin();
			$respuesta = $objSkin->getSkinsPlayer();
			unset($objSkin);
			echo json_encode($respuesta);
			break;
		default:
			echo "La acción no está registrada";
			break;
	}
?>