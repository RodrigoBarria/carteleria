<?php
	header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
	header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos

	session_start();
	require_once("../db/ConexionMsqlCarteleria.php");
	
	class Player{

		private $db_;
		private $hora;
		private $hoy;

		function __construct(){
       		$this->hora = date("H:i:s");
       		$this->hoy = date("d-m-Y");
   		}

		/*Obtiene todos los players*/
		public function getPlayers(){
			try{
				$sql = "Select id_player, descripcion, integrado_stack from players";
				$players = $this->getDatosPlayer($sql);
				return $players;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene todas las skins disponibles*/
		public function getSkinsPlayer()
		{
			try{
				$sql = "Select id_skin, descripcion from skins";
				$skins = $this->getDatosPlayer($sql);
				return $skins;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene la configuración de un player*/
		public function getUnSkinPlayer($idPlayer){
			try{
				$sql="Select id_skin, banner, color, img_fondo from config_player where id_player=".$idPlayer;
				$respuesta = $this->getDatosPlayer($sql);
				return $respuesta;

			}catch(Exception $e){
				return $e;
			}
		}

		/*Agrega la configuración de un player*/
		public function addSkinPlayer($jsonObj, $imgFondo){
			try{
				$skinPlayer = json_decode($jsonObj);
				$isSkinPlayer = $this->isSkinPlayer($skinPlayer->{"idPlayer"});
				$urlFondo = "";

				$modificado=0;

				if($skinPlayer->{"idSkin"}==1 || $skinPlayer->{"idSkin"}==2 || $skinPlayer->{"idSkin"}==3){
					$modificado=2;
				}else{
					$modificado=1;
				}

				if($imgFondo!=""){
					if($imgFondo!="na"){
						$urlFondo="/upload/".$_SESSION["idCliente"]."/fondos/".$imgFondo['name'];
						move_uploaded_file($imgFondo['tmp_name'], "..".$urlFondo);
					}
				}

				if($isSkinPlayer==0){
					$sql ="insert into config_player";
					$sql.="(id_player, id_skin, banner, modificado, color, img_fondo) ";
					$sql.="values(".$skinPlayer->{"idPlayer"}.", ".$skinPlayer->{"idSkin"}.", '".$skinPlayer->{"banner"}."', ".$modificado.", '".$skinPlayer->{"color"}."', '".$urlFondo."')";

					$filas = $this->addDatosPlayer($sql);
			
					if($filas!=0){
						$evento="Realizó configuración de skin del player ".$skinPlayer->{"idPlayer"};
						$this->addlogPlayers($_SESSION["idUsuario"], $evento);
						return "Configuración guardada correctamente";
					}else{
						return "Se produjo un error";
					}
				}else{
					$sql ="update config_player ";
					$sql.="set id_skin=".$skinPlayer->{"idSkin"}.", banner='".$skinPlayer->{"banner"}."', modificado=".$modificado." , color='".$skinPlayer->{"color"}."' ";

					if($imgFondo!="na"){
						$sql.=", img_fondo='".$urlFondo."' ";
					}

					$sql.="where id_player=".$skinPlayer->{"idPlayer"};

					$this->addDatosPlayer($sql);
					$evento="Realizó configuración de skin del player ".$skinPlayer->{"idPlayer"};
					$this->addlogPlayers($_SESSION["idUsuario"], $evento);
					return "Configuración guardada correctamente";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Comprueba si el player esta configurado*/
		private function isSkinPlayer($idPlayer){
			try{
				$sql ="Select id_config_player from config_player where id_player=".$idPlayer;

				$respuesta = $this->getDatosPlayer($sql);

				if(isset($respuesta[0])){
					return 1;
				}else{
					return 0;
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Registra una acción realizada en el menú de players*/
		private function addlogPlayers($idUsuario=0, $evento=""){
			try{
				$sql ="insert into log_eventos";
				$sql.="(evento, id_usuario, fecha) ";
				$sql.="values('".$evento."', ".$idUsuario.", '".$this->hoy."/".$this->hora."')";

				$this->addDatosPlayer($sql);
				
			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosPlayer($sql){
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
		private function addDatosPlayer($sql){
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