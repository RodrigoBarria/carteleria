<?php
header('Access-Control-Allow-Origin: *');
	require("../dao/ClientesDAO.php");

	/*switch para controlar el proceso que se realizará según el valor de '$_POST["accion"]'*/
	switch($_POST["accion"]){
		case 'getListaClientes':
			$objCliente = new Cliente();
			$respuesta = $objCliente->getListaClientes();
			unset($objCliente);
			echo json_encode($respuesta);
			break;
		case 'getClientes':
			$objCliente = new Cliente();
			$respuesta = $objCliente->getClientes($_POST["pagina"]);
			unset($objCliente);
			echo json_encode($respuesta);
			break;
		case 'getUnCliente':
			$objCliente = new Cliente();
			$respuesta = $objCliente->getUnCliente($_POST["idCliente"]);
			unset($objCliente);
			echo json_encode($respuesta);
			break;
		case 'getUsuariosCliente':
			$objCliente = new Cliente();
			$respuesta = $objCliente->getUsuariosCliente($_POST["idCliente"]);
			unset($objCliente);
			echo json_encode($respuesta);
			break;
		case 'addCliente':
			$objCliente = new Cliente();
			$respuesta = $objCliente->addCliente($_POST["datos"]);
			unset($objCliente);
			echo $respuesta;
			break;
		case 'updateCliente':
			$objCliente = new Cliente();
			$respuesta = $objCliente->updateCliente($_POST["datos"]);
			unset($objCliente);
			echo $respuesta;
			break;
		case 'generaClaveCliente':
			$objCliente = new Cliente();
			$respuesta = $objCliente->generaClaveCliente();
			unset($objCliente);
			echo $respuesta;
			break;
		default:
			echo "La acción no está registrada";
			break;
		}
?>
