<?php
	session_start();
	require_once("../db/ConexionMsqlCarteleria.php");

	class LoginUsuario{

		private $db_;

		/*Valida login de cliente*/
		public function validaLogin($objJson){
			try{
				$usuario = json_decode($objJson);
				$clave = htmlentities(addslashes($usuario->{'clave'}));

				$sql ="select id_cliente, nombre, id_perfil from clientes ";
				$sql.="where clave ='".$clave."' and eliminado=0";

				$respuesta = $this->getDatosLogin($sql);

				if(!empty($respuesta[0])){
					$_SESSION["idCliente"] = $respuesta[0]["id_cliente"];
					$_SESSION["nombreCliente"] = $respuesta[0]["nombre"];
					$_SESSION["idPerfilCliente"] = $respuesta[0]["id_perfil"];
					return "ok";
				}else{
					return "error";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Valida los datos de inicio de sesión ingresados por el usuario en appFtp*/
		public function validaLoginAppFtp($claveApp){
			try{
				$clave = htmlentities(addslashes($claveApp));

				$sql ="select id_cliente, nombre from clientes ";
				$sql.="where clave ='".$clave."' and eliminado=0";

				$respuesta = $this->getDatosLogin($sql);

				if(!empty($respuesta[0])){
					return $respuesta[0]["id_cliente"]."|".$respuesta[0]["nombre"];
				}else{
					return "error";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		//Valida login de usuario
		public function validaLoginUsuario($objJson){
			try{
				$usuario = json_decode($objJson);
				$login = htmlentities(addslashes($usuario->{'login'}));
				$clave = htmlentities(addslashes($usuario->{'clave'}));

				$sql ="select id_usuario, login_usuario, nombre, apellido, id_perfil_usuario from usuarios ";
				$sql.="where login_usuario='".$login."' and clave_usuario='".$clave."' and eliminado=0";

				$respuesta = $this->getDatosLogin($sql, true);

				if(!empty($respuesta[0])){
					$_SESSION["idUsuario"] = $respuesta[0]["id_usuario"];
					$_SESSION["loginUsuario"] = $respuesta[0]["login_usuario"];
					$_SESSION["nombreUsuario"] = $respuesta[0]["nombre"];
					$_SESSION["apellidousuario"] = $respuesta[0]["apellido"];
					$_SESSION["idPerfilUsuario"] = $respuesta[0]["id_perfil_usuario"];
					return "ok";
				}else{
					return "error";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Destruye la sesión actual*/
		public function logout(){
			try{
				if(session_destroy()){
					return "ok";
				}else{
					return "noOk";
				}
				
			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosLogin($sql, $cliente=false){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado="";

				if(!$cliente){
					$resultado = $this->db_->conectar("principal")->prepare($sql);	
				}else{
					$resultado = $this->db_->conectar("cliente")->prepare($sql);
				}

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