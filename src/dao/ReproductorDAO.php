<?php
header('Access-Control-Allow-Origin: *');
	require_once("../db/ConexionMsqlCarteleria.php");

	class Reproductor{

		private $db_;

		/*Obtiene la lista de archivos a reproducir*/
		public function getArchivosReproductor($jsonObj){
			try{
				$reproductor = json_decode($jsonObj);

				$sql ="Select ap.id_archivo_player, ap.duracion, ap.volumen, a.id_archivo, a.nombre_archivo, a.ruta_archivo FROM archivo_player AS ap ";
				$sql.="INNER JOIN archivos AS a ";
				$sql.="ON ap.id_archivo = a.id_archivo ";
				$sql.="WHERE ap.id_player=".$reproductor->{"idPlayer"}." ORDER BY ap.prioridad";

				$respuesta = $this->getDatosReproductor($sql, $reproductor->{"idCliente"});

				return $respuesta;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene la configuración de pantalla de un player*/
		public function getConfigPlayer($jsonObj){
			try{
				$reproductor = json_decode($jsonObj);
				$sql="Select id_skin, banner, modificado, color, img_fondo from config_player where id_player=".$reproductor->{"idPlayer"};
				$respuesta = $this->getDatosReproductor($sql, $reproductor->{"idCliente"});
				return $respuesta;

			}catch(Exception $e){
				return $e;
			}
		}

		/*actualiza el campo modificado de la tabla config_player*/
		public function updateConfigPlayer($jsonObj){
			try{

				$reproductor = json_decode($jsonObj);

				// if($reproductor->{"idCliente"} == "65"){
			  //   	$r = fopen("fecha.txt","a+");
				// 	$fecha = date("Y-m-d H:i:s");
			  //   	fputs($r, "Fecha: ".$fecha." envio: " .$jsonObj."\n");
			  //   	fclose($r);
			  //   }

				$sql="Select modificado from config_player where id_player=".$reproductor->{"idPlayer"};
				$respuesta = $this->getDatosReproductor($sql, $reproductor->{"idCliente"});

				if(!empty($respuesta[0])){

					if($respuesta[0]["modificado"]==1 || $respuesta[0]["modificado"]==2){
						$sql ="update config_player ";
						$sql.="set modificado=0 where id_player=".$reproductor->{"idPlayer"};

						$filas = $this->addDatosReproductor($sql, $reproductor->{"idCliente"});

						if($filas!=0){
							return $respuesta[0]["modificado"];
						}else{
							return "No se actualizó el campo 'modificado'";
						}
					}else{
						return "No hay cambios";
					}
				}else{
					return "El player no está configurado";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosReproductor($sql, $idCliente){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar($idCliente)->prepare($sql);
				$resultado->execute(array());
				$datos=$resultado->fetchAll(PDO::FETCH_ASSOC);

				$resultado->closeCursor();
				$resultado = null;
				$this->db_ = null;

				return $datos;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Ejecuta la consulta de inserción o actualización que reciba y devuelve la cantidad de filas afectadas*/
		private function addDatosReproductor($sql, $idCliente){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar($idCliente)->prepare($sql);
				$resultado->execute(array());
				$filas = $resultado->rowCount();

				$resultado->closeCursor();
				$resultado = null;
				$this->db_ = null;

				return $filas;
			}catch(Exception $e){
				return $e;
			}
		}
	}
?>
