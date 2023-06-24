<?php
header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos

	require_once("../db/ConexionMsqlCarteleria.php");

	class Cliente{

		private $db_;
		private $registroPorPagina_ = 5;

		/*Obtiene una cantidad determinada de clientes, esto según el valor de $registroPorPagina_*/
		public function getClientes($pagina){
			try{
				$cantidadClientes = $this->getCantidadClientes();
				$empezarDesde=($pagina-1)*$this->registroPorPagina_;
				$totalPaginas = ceil($cantidadClientes/$this->registroPorPagina_);

				$sql ="Select id_cliente, nombre, clave from clientes ";
				$sql.="where eliminado=0 order by id_cliente Limit ".$empezarDesde.",".$this->registroPorPagina_;

				$clientes=$this->getDatosClientes($sql);
				$clientes["paginasCliente"] = $totalPaginas;

				return $clientes;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Agrega un cliente a la BD*/
		public function addCliente($jsonObj){
			try{
				$cliente = json_decode($jsonObj);

				$sql ="insert into clientes";
				$sql.="(id_cliente, nombre, clave) ";
				$sql.="values(".$cliente->{"idCliente"}.", '".$cliente->{"nombre"}."', '".$cliente->{"clave"}."')";

				$filas = $this->addDatosCliente($sql);

				if($filas!=0){
					$claveUsuario = $this->generaClaveCliente();
					if($this->addBDCliente($cliente->{"idCliente"}, $claveUsuario)){

						$carpetaAchivos = '/upload/'.$cliente->{"idCliente"};
						$carpetaPreview = $carpetaAchivos.'/img';
						$carpetaFondos = $carpetaAchivos.'/fondos';

						/*Si el directorio de archivos de cliente no existe, lo crea*/
						if(!file_exists("..".$carpetaAchivos)){
    						mkdir("..".$carpetaAchivos, 0777, true);
    						mkdir("..".$carpetaPreview, 0777, true);
    						mkdir("..".$carpetaFondos, 0777, true);
							chmod("..".$carpetaAchivos, 0777);
							chmod("..".$carpetaPreview, 0777);
							chmod("..".$carpetaFondos, 0777);
						}

						return "ok|Cliente creado correctamente.|Clave cliente: ".$cliente->{"clave"}.".|Usuario: admin.|Clave usuario: ".$claveUsuario.".";
					}else{
						$this->addDatosCliente("delete from clientes where id_cliente=".$cliente->{"idCliente"});
						return "error|No se creó la base de datos";
					}
				}else{
					return "error|No se agregó el cliente";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene todos los clientes*/
		public function getListaClientes(){
			try{
				$sql ="Select id_cliente, nombre from clientes ";
				$sql.="where eliminado=0 order by id_cliente";

				$clientes=$this->getDatosClientes($sql);

				return $clientes;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Genera una clave de forma aleatoria*/
		public function generaClaveCliente(){
			try{
				$cadena="[^A-Z0-9]";
				$clave = eregi_replace($cadena, "", md5(rand())).eregi_replace($cadena, "", md5(rand())).eregi_replace($cadena, "", md5(rand()));
				return substr($clave, 0, 8);

			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene un cliente según un id*/
		public function getUnCliente($idCliente){
			try{
				$sql ="Select nombre, clave from clientes ";
				$sql.="where id_cliente=".$idCliente." and eliminado=0";

				$cliente=$this->getDatosClientes($sql);
				return $cliente;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Actualiza un ciente existente*/
		public function updateCliente($jsonObj){
			try{
				$cliente = json_decode($jsonObj);

				$sql ="update clientes ";
				$sql.="set clave='".$cliente->{"clave"}."' ";
				$sql.="where id_cliente=".$cliente->{"idCliente"};

				$filas = $this->addDatosCliente($sql);

				if($filas!=0){
					return "Clave actualizada correctamente";
				}else{
					return "No se modificó la clave";
				}

			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene los usuarios correspondientes al idcliente recibido*/
		public function getUsuariosCliente($idCliente){
			try{
				$sql = "Select u.id_usuario, u.login_usuario, u.clave_usuario, u.nombre, u.apellido, p.descripcion as perfil ";
				$sql.= "from dbcarteleria".$idCliente.".usuarios as u ";
				$sql.= "Inner Join dbcarteleria".$idCliente.".perfil_usuario as p ";
				$sql.= "ON u.id_perfil_usuario = p.id_perfil_usuario ";
				$sql.= "order by id_usuario";

				$respuesta = $this->getDatosClientes($sql, "global");
				return $respuesta;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene la cantidad de clientes activos*/
		private function getCantidadClientes(){
			try{
				$sql ="Select count(id_cliente) as cantidad_clientes ";
				$sql.="from clientes where eliminado = 0";

				$obj=$this->getDatosClientes($sql);
				return $obj[0]["cantidad_clientes"];

			}catch(Exception $e){
				return $e;
			}
		}

		/*Crea una nueva base de datos de cliente*/
		private function addBDCliente($idCliente, $claveUsuario){
			try{
				$sql="Create database dbcarteleria".$idCliente;
				$this->addDatosCliente($sql, "global");
				$this->addTablaArchivos($idCliente);
				$this->addTablaArchivoPlayer($idCliente);
				$this->addTablaConfigPlayer($idCliente);
				$this->addTablaPerfilUsuario($idCliente);
				$this->addTablaUsuarios($idCliente, $claveUsuario);
				$this->addTablaLogEventos($idCliente);

				return true;
			}catch(Exception $e){
				return false;
			}
		}

		/*Crea la tabla archivos en BD*/
		private function addTablaArchivos($idCliente){
			try{
				$sql ="Create table archivos(";
				$sql.="id_archivo int(1) NOT NULL AUTO_INCREMENT, ";
				$sql.="nombre_archivo varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="ruta_archivo varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="ruta_preview varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="PRIMARY KEY (id_archivo)) ";
				$sql.="ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci";

				$this->addDatosCliente($sql, $idCliente);

			}catch (Exception $e){
				return $e;
			}
		}

		/*Crea la tabla archivo_player en BD*/
		private function addTablaArchivoPlayer($idCliente){
			try{
				$sql ="Create table archivo_player(";
				$sql.="id_archivo_player int(1) NOT NULL AUTO_INCREMENT, ";
				$sql.="id_archivo int(1) DEFAULT NULL, ";
				$sql.="id_player int(1) DEFAULT NULL, ";
				$sql.="duracion varchar(8) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="archivo_completo int(1) DEFAULT '0', ";
				$sql.="prioridad int(1) NOT NULL, ";
				$sql.="volumen int(1) DEFAULT 50, ";
				$sql.="PRIMARY KEY (id_archivo_player), ";
				$sql.="KEY id_archivo (id_archivo), ";
				$sql.="CONSTRAINT archivo_player_ibfk_1 FOREIGN KEY (id_archivo) REFERENCES archivos (id_archivo)) ";
				$sql.="ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci";

				$this->addDatosCliente($sql, $idCliente);

			}catch (Exception $e){
				return $e;
			}
		}

		/*Crea la tabla de config_player*/
		private function addTablaConfigPlayer($idCliente){
			try{
				$sql ="Create TABLE config_player (";
  				$sql.="id_config_player int(1) NOT NULL AUTO_INCREMENT, ";
  				$sql.="id_player int(1) DEFAULT NULL, ";
  				$sql.="id_skin int(1) DEFAULT NULL, ";
  				$sql.="banner varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL, ";
  				$sql.="modificado int(1) DEFAULT 0, ";
  				$sql.="color varchar(7) COLLATE utf8_spanish_ci DEFAULT NULL, ";
  				$sql.="img_fondo varchar(500) COLLATE utf8_spanish_ci DEFAULT NULL, ";
  				$sql.="PRIMARY KEY (id_config_player)) ";
  				$sql.="ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci";

  				$this->addDatosCliente($sql, $idCliente);

			}catch(Exception $e){
				return $e;
			}
		}

		/*Crea la tabla perfil_usuario*/
		private function addTablaPerfilUsuario($idCliente){
			try{
				$sql="Create TABLE perfil_usuario ( ";
				$sql.="id_perfil_usuario int(1) NOT NULL AUTO_INCREMENT, ";
				$sql.="descripcion varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="PRIMARY KEY (id_perfil_usuario)) ";
				$sql.="ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
				$sql.="insert into perfil_usuario(id_perfil_usuario,descripcion) values(1,'Administrador'),(2,'Ejecutivo');";

				$this->addDatosCliente($sql, $idCliente);

			}catch(Exception $e){
				return $e;
			}
		}

		/*Crea la tabla usuarios*/
		private function addTablaUsuarios($idCliente, $claveUsuario){
			try{
				$sql="Create TABLE usuarios ( ";
  				$sql.="id_usuario int(1) NOT NULL AUTO_INCREMENT, ";
				$sql.="login_usuario varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="clave_usuario varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="nombre varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="apellido varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL, ";
				$sql.="eliminado int(1) DEFAULT 0, ";
				$sql.="id_perfil_usuario int(1) DEFAULT NULL, ";
				$sql.="PRIMARY KEY (id_usuario), ";
				$sql.="KEY id_perfil_usuario (id_perfil_usuario), ";
				$sql.="CONSTRAINT usuarios_ibfk_1 FOREIGN KEY (id_perfil_usuario) REFERENCES perfil_usuario (id_perfil_usuario)) ";
				$sql.="ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;";
				$sql.="insert into usuarios(id_usuario,login_usuario,clave_usuario,nombre,apellido,eliminado,id_perfil_usuario) values(1,'admin','".$claveUsuario."','Admin','...',0,1)";

				$this->addDatosCliente($sql, $idCliente);

			}catch(Exception $e){
				return $e;
			}
		}

		/*Crea la tabla log_eventos*/
		private function addTablaLogEventos($idCliente){
			try{
				$sql ="Create TABLE log_eventos (";
  				$sql.="id_log_evento int(1) NOT NULL AUTO_INCREMENT, ";
  				$sql.="evento varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL, ";
  				$sql.="id_usuario int(1) DEFAULT NULL, ";
  				$sql.="fecha varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL, ";
  				$sql.="PRIMARY KEY (id_log_evento), ";
  				$sql.="KEY id_usuario (id_usuario), ";
  				$sql.="CONSTRAINT log_eventos_ibfk_1 FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)) ";
  				$sql.="ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci";

  				$this->addDatosCliente($sql, $idCliente);

			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosClientes($sql, $db=null){
			try{
				$this->db_ = new ConexionMsqlCarteleria();

				if($db==null){
					$resultado = $this->db_->conectar("principal")->prepare($sql);
				}else{
					$resultado = $this->db_->conectar($db)->prepare($sql);
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

		/*Ejecuta la consulta de inserción o actualización que reciba y devuelve la cantidad de filas afectadas*/
		private function addDatosCliente($sql, $db=null){
			try{
				$this->db_ = new ConexionMsqlCarteleria();

				if($db==null){
					$resultado = $this->db_->conectar("principal")->prepare($sql);
				}else{
					$resultado = $this->db_->conectar($db)->prepare($sql);
				}

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
