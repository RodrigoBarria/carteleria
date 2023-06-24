<?php
	header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
	header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos
	session_start();
	require_once("../db/ConexionMsqlCarteleria.php");

	class Usuario{

		private $db_;
		private $registroPorPagina_ = 5;
		private $hora;
		private $hoy;

		function __construct() {
       		$this->hora = date("H:i:s");
       		$this->hoy = date("d-m-Y");
   		}

		/*Obtiene todos los usuarios activos registrados*/
		public function getUsuarios($pagina){
			try{
				$cantidadUsuarios = $this->getCantidadUsuarios();
				$empezarDesde=($pagina-1)*$this->registroPorPagina_;
				$totalPaginas = ceil($cantidadUsuarios/$this->registroPorPagina_);

				$sql = "Select u.id_usuario, p.descripcion as perfil, u.login_usuario, u.id_perfil_usuario, u.nombre, u.apellido ";
				$sql .= "FROM usuarios AS u ";
				$sql .= "INNER JOIN perfil_usuario AS p ";
				$sql .= "ON u.id_perfil_usuario = p.id_perfil_usuario ";
				$sql .= "where u.eliminado=0 order by u.id_usuario Limit ". $empezarDesde .",". $this->registroPorPagina_;

				$usuarios=$this->getDatosUsuarios($sql);
				$usuarios["paginasUsuario"] = $totalPaginas;

				return $usuarios;
				
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene un usuario según su id*/
		public function getUnUsuario($idUsuario){
			try{
				$sql ="select login_usuario, nombre, apellido, id_perfil_usuario from usuarios ";
				$sql.="where id_usuario=".$idUsuario." and eliminado=0";

				$respuesta=$this->getDatosUsuarios($sql);
				return $respuesta;

			}catch(Exception $e){
				return $e;
			}
		}

		/*Ingresa un nuevo usuario a la BD*/
		public function addUsuario($jsonObj){
			try{
				$usuario = json_decode($jsonObj);
				if($this->isLoginUsuario($usuario->{"loginUsuario"})==0){
					$sql ="Insert into usuarios(id_perfil_usuario, login_usuario, clave_usuario, nombre, apellido) ";
					$sql.="values(".$usuario->{"idPerfilUsuario"}.", '".$usuario->{"loginUsuario"}."', '".$usuario->{"claveUsuario"}."', '".$usuario->{"nombreUsuario"}."', '";
					$sql.=$usuario->{"apellidoUsuario"}."')";

					$filas = $this->addDatosUsuarios($sql);

					if($filas!=0){
						$evento="Agregó el usuario ".$usuario->{"loginUsuario"};
						$this->addlogUsuario($_SESSION["idUsuario"], $evento);
						return "Datos ingresados correctamente";
					}else{
						return "Error";
					}
				}else{
					return "El nombre de usuario ya existe";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Actualiza un usuario existente en la BD*/
		public function updateUsuario($jsonObj){
			try{
				$usuario = json_decode($jsonObj);
				$sql ="update usuarios ";
				$sql.="set id_perfil_usuario=".$usuario->{"idPerfilUsuario"}.", ";
				$sql.="login_usuario='".$usuario->{"loginUsuario"}."', ";

				if($usuario->{"claveUsuario"}!=""){
					$sql.="clave_usuario='".$usuario->{"claveUsuario"}."', ";
				}

				$sql.="nombre='".$usuario->{"nombreUsuario"}."', ";
				$sql.="apellido='".$usuario->{"apellidoUsuario"}."' ";
				$sql.="where id_usuario=".$usuario->{"idUsuario"};

				$filas = $this->addDatosUsuarios($sql);

				if($filas!=0){
					$evento="Actualizó el usuario ".$usuario->{"loginUsuario"};
					$this->addlogUsuario($_SESSION["idUsuario"], $evento);
					return "Datos actualizados correctamente";
				}else{
					return "No se realizaron cambios";
				}
			}catch(Exception $e){
				return $e;
			}
		}

		/*Deshabilita la cuenta de un usuario cambiando el campo 'eliminado' de 0 a 1 de la tabla usuario*/
		public function deleteUsuario($idUsuario, $loginUsuario){
			try{
				$sql ="update usuarios ";
				$sql.="set eliminado=1 ";
				$sql.="where id_usuario=".$idUsuario;

				$filas = $this->addDatosUsuarios($sql);

				if($filas!=0){
					$evento="Eliminó el usuario ".$loginUsuario;
					$this->addlogUsuario($_SESSION["idUsuario"], $evento);
					return "Datos eliminados correctamente";
				}else{
					return "No se actualizaron los datos";
				}
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene la cantidad de usuarios activos*/
		private function getCantidadUsuarios(){
			try{
				$sql ="Select count(id_usuario) as cantidad_usuarios ";
				$sql.="from usuarios where eliminado = 0";

				$obj=$this->getDatosUsuarios($sql);
				return $obj[0]["cantidad_usuarios"];

			}catch(Exception $e){
				return $e;
			}
		}

		/*Comprueba si el nombre de usuario recibido existe*/
		private function isLoginUsuario($loginusuario){
			try{
				$sql="Select id_usuario from usuarios where login_usuario='".$loginusuario."'";
				$respuesta=$this->getDatosUsuarios($sql);

				if(isset($respuesta[0])){
					return 1;
				}else{
					return 0;
				}
			}catch(Exception $e){
				return $e;
			}
		}

		/*Registra una acción realizada en el menú usuarios*/
		private function addlogUsuario($idUsuario=0, $evento=""){
			try{
				$sql ="insert into log_eventos";
				$sql.="(evento, id_usuario, fecha) ";
				$sql.="values('".$evento."', ".$idUsuario.", '".$this->hoy."/".$this->hora."')";

				$this->addDatosUsuarios($sql);
				
			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y retorna el resultado*/
		private function getDatosUsuarios($sql){
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

		/*Agrega o actualiza una tabla de BD según la consulta recibida,
		devuelve el númeto de filas afectadas*/
		private function addDatosUsuarios($sql){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado = $this->db_->conectar("cliente")->prepare($sql);
				$resultado->execute(array());
				$filas = $resultado->rowCount();
				
				$resultado->closeCursor();
				$this->db_ = null;
				$resultado = null;

				return $filas;
			}catch(Exception $e){
				return $e;
			}
		}
	}
?>