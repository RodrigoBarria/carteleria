<?php
header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos

	session_start();
	require_once("../db/ConexionMsqlCarteleria.php");

	class ArchivoPlayer{

		private $db_;
		private $hora;
		private $hoy;

		function __construct(){
       		$this->hora = date("H:i:s");
       		$this->hoy = date("d-m-Y");
   		}

		/*Obtiene todas los players*/
		public function getPlayers(){
			try{
				$sql ="Select id_player, descripcion FROM players where eliminado=0";

				$respuesta = $this->getDatosArchivoPlayer($sql);
				return $respuesta;

			}catch(Exception $e){
				return $e;
			}
		}

		/*Asocia archivos a un player determinado*/
		public function addUnArchivoPlayer($jsonObj=null){
			try{
				$archivoPlayer = json_decode($jsonObj);
				$player = $archivoPlayer->{"player"}[0];
				$archivo = $archivoPlayer->{"archivo"};

				$sql = "Delete from archivo_player where id_player=".$player->{"idPlayer"};
				$filas = $this->addDatosArchivoPlayer($sql);

				if(!empty($archivo)){
					return $this->addArchivoPlayer($jsonObj);
				}else{
					$this->updateConfigPlayer($player->{"idPlayer"});
					$evento="Eliminó todos los archivos asociados al player ".$player->{"idPlayer"};
					$this->addlogArchivosPlayer($_SESSION["idUsuario"], $evento);
					return "Configuración guardada correctamente";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Agrega archivos a uno o varios players*/
		public function addArchivoPlayer($jsonObj=null){
			try{
				$archivoPlayer = json_decode($jsonObj);
				$player = $archivoPlayer->{"player"};
				$archivo = $archivoPlayer->{"archivo"};
				$prioridad = $this->prioridadArchivo();

				$playerConfigurados="";

				foreach($player as $valPlayer){
					$addPlayer=0;
					foreach($archivo as $valArchivo){
						if(!$this->isArchivoPlayer($valArchivo->{"idArchivo"}, $valPlayer->{"idPlayer"})){
							$sql ="Insert into archivo_player";
							$sql.="(id_archivo, id_player, duracion, archivo_completo, prioridad, volumen) ";
							$sql.="values(".$valArchivo->{"idArchivo"}.", ".$valPlayer->{"idPlayer"}.", '";
							$sql.=$valArchivo->{"duracion"}."', ".$valArchivo->{"archivoCompleto"}.", ".$prioridad.", ".$valArchivo->{"volumen"}.")";

							$this->addDatosArchivoPlayer($sql);
							$this->updateConfigPlayer($valPlayer->{"idPlayer"});
							$prioridad++;

							if($addPlayer==0){
								$playerConfigurados.=$valPlayer->{"idPlayer"}.",";
								$addPlayer=1;
							}
						}
					}
				}

				$playerConfigurados = trim($playerConfigurados, ',');
				$evento="Realizó nueva configuración del o los players ".$playerConfigurados.".";
				$this->addlogArchivosPlayer($_SESSION["idUsuario"], $evento);
				return "Configuración guardada correctamente";

			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene los archivos asociados a un player*/
		public function getArchivoPorPlayer($idPlayer=0){
			try{
				$sql ="Select ap.id_archivo_player, ap.id_archivo, ap.duracion, ap.volumen, a.nombre_archivo, a.ruta_preview, ap.prioridad ";
				$sql.="FROM archivo_player AS ap ";
				$sql.="INNER JOIN archivos AS a ";
				$sql.="ON ap.id_archivo=a.id_archivo ";
				$sql.="WHERE ap.id_player=".$idPlayer." ";
				$sql.="ORDER BY ap.prioridad";

				$respuesta=$this->getDatosArchivoPlayer($sql);
				return $respuesta;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene todos los archivos, menos los recibidos en $archivosPlayer*/
		public function getArchivoNoPlayer($archivosPlayer=null){
			try{
				$sql ="Select id_archivo, nombre_archivo, ruta_archivo, ruta_preview from archivos ";
				$sql.="where id_archivo not in(";

				for($i=0;$i<=count($archivosPlayer);$i++){
    				if($i<count($archivosPlayer)-1){
    					$sql.=$archivosPlayer[$i].",";
    				}else{
    					$sql.=$archivosPlayer[$i];
    				}
				}

				$sql.=")";

				$respuesta = $this->getDatosArchivoPlayer($sql);
				return $respuesta;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Borra un archivo asociado a un player*/
		public function deleteArchivoPlayer($idArchivoPlayer){
			try{
				$sql="delete from archivo_player where id_archivo_player=".$idArchivoPlayer;
				$filas = $this->addDatosArchivoPlayer($sql);

				if($filas!=0){
					return "delete";
				}else{
					return "no delete";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Registra con un 1 en el campo 'modificado' de la tabla 'config_player'
		cuando la configuración del player es cambiada*/
		private function updateConfigPlayer($idPlayer){
			try{
				$sql ="update config_player ";
				$sql.="set modificado=1 where id_player=".$idPlayer;
				$filas = $this->addDatosArchivoPlayer($sql);
			}catch(Exception $e){
				return $e;
			}
		}

		/*Comprueba si el archivo recibido está asociado al player recibido*/
		private function isArchivoPlayer($idArchivo=0, $idPlayer=0){
			try{
				$sql="Select id_archivo_player from archivo_player where id_archivo=".$idArchivo." and id_player=".$idPlayer;
				$respuesta = $this->getDatosArchivoPlayer($sql);

				if(empty($respuesta[0])){
					return false;
				}else{
					return true;
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene el id mas alto de un archivoPlayer + 1*/
		private function prioridadArchivo(){
			try {
				$sql="Select max(id_archivo_player) as prioridad from archivo_player";
				$respuesta = $this->getDatosArchivoPlayer($sql);

				if($respuesta[0]["prioridad"]!=null){
					return $respuesta[0]["prioridad"]+1;
				}else{
					return 1;
				}
			} catch (Exception $e) {
				return $e;
			}
		}

		/*Registra una acción realizada en la configuración de archivos de uno o más players*/
		private function addlogArchivosPlayer($idUsuario=0, $evento=""){
			try{
				$sql ="insert into log_eventos";
				$sql.="(evento, id_usuario, fecha) ";
				$sql.="values('".$evento."', ".$idUsuario.", '".$this->hoy."/".$this->hora."')";
				$this->addDatosArchivoPlayer($sql);
				
			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosArchivoPlayer($sql){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar("cliente")->prepare($sql);
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

		/*Ejecuta la consulta de inserción o actualización que reciba y devuelve la cantidad de filas afectadas*/
		private function addDatosArchivoPlayer($sql){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar("cliente")->prepare($sql);
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