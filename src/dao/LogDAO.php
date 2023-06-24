<?php
	session_start();
	header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
	header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos

	require_once("../db/ConexionMsqlCarteleria.php");

	class Log{

		private $db_;
		private $registroPorPagina_ = 5;

		/*Obtiene los registros de la tabla log_eventos*/
		public function getLogEventos($pagina, $idCliente){
			try{
				$cantidadUsuarios = $this->getCantidadLog($idCliente);
				$empezarDesde=($pagina-1)*$this->registroPorPagina_;
				$totalPaginas = ceil($cantidadUsuarios/$this->registroPorPagina_);

				$sql ="Select l.id_log_evento, l.evento, u.nombre, u.apellido, l.fecha FROM log_eventos AS l ";
				$sql.="INNER JOIN usuarios AS u ON l.id_usuario=u.id_usuario ";
				$sql.="ORDER BY id_log_evento DESC Limit ".$empezarDesde.",".$this->registroPorPagina_;

				$respuesta = $this->getDatosLog($sql, $idCliente);
				$respuesta["paginasLog"] = $totalPaginas;
				return $respuesta;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene la cantidad de registros de la tabla log_eventos*/
		private function getCantidadLog($idCliente){
			try{
				$sql ="Select count(id_log_evento) as cantidad_log ";
				$sql.="from log_eventos";

				$obj=$this->getDatosLog($sql, $idCliente);
				return $obj[0]["cantidad_log"];

			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosLog($sql, $idCliente){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar($idCliente)->prepare($sql);
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