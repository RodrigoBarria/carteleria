<?php
	header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
	header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos
	session_start();
	require_once("../db/ConexionMsqlCarteleria.php");

	class Skin{

		private $db_;

		/*Obtiene todas las skins disponibles*/
		public function getSkinsPlayer(){
			try{
				$sql = "Select id_skin, descripcion, img from skins";
				$skins = $this->getDatosSkin($sql);
				return $skins;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosSkin($sql){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar("principal")->prepare($sql);
				$resultado->execute(array());
				$datos=$resultado->fetchAll(PDO::FETCH_ASSOC);

				$resultado->closeCursor();
				$resultado=null;
				$this->db_=null;

				return $datos;

			}catch(Exception $e){
				return $e;
			}
		}
	}
?>