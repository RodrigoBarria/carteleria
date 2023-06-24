<?php
header('Access-Control-Allow-Origin: *');
	/*Usado para obtener los clientes de cartelería para mostrarlos en appFtp*/
	header('Access-Control-Allow-Origin: *');
	require("../dao/ClientesDAO.php");

	$objCliente = new Cliente();
	$respuesta = $objCliente->getListaClientes();
	unset($objCliente);

	$listaClientes = array('listaClientes' => $respuesta);

	echo json_encode($listaClientes);
?>