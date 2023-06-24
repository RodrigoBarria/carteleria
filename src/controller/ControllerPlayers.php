<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/PlayersDAO.php");
	
	/*switch para controlar la función que se realizará según el valor de '$_POST["accion"]'*/
	switch ($_POST["accion"]) {
		case 'addSkinPlayer':
			$objPlayer = new Player();
			$respuesta="";

			if(isset($_FILES["imgFondo"])){
				$respuesta = $objPlayer->addSkinPlayer($_POST["datos"], $_FILES["imgFondo"]);
			}else{
				$respuesta = $objPlayer->addSkinPlayer($_POST["datos"], $_POST["imgFondo"]);
			}

			unset($objPlayer);
			echo $respuesta;
			break;
		case 'getPlayers':
			$objPlayer = new Player();
			$respuesta = $objPlayer->getPlayers();
			unset($objPlayer);
			echo json_encode($respuesta);
			break;
		case 'getSkinsPlayer':
			$objPlayer = new Player();
			$respuesta = $objPlayer->getSkinsPlayer();
			unset($objPlayer);
			echo json_encode($respuesta);
			break;
		case 'getUnSkinPlayer':
			$objPlayer = new Player();
			$respuesta = $objPlayer->getUnSkinPlayer($_POST["idPlayer"]);
			unset($objPlayer);
			echo json_encode($respuesta);
			break;
		default:
			echo "La acción no está registrada";
			break;
	}
?>