<?php
header("Access-Control-Allow-Origin: *"); // Permite el acceso desde cualquier dominio
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos HTTP permitidos
header("Access-Control-Allow-Headers: Content-Type"); // Encabezados permitidos

	session_start();
	require_once("../db/ConexionMsqlCarteleria.php");

	class Archivo{

		private $db_;
		private $hora;
		private $hoy;

		function __construct(){
       		$this->hora = date("H:i:s");
       		$this->hoy = date("d-m-Y");
   		}

		/*Obtiene todos los archivos guardados*/
		public function getArchivos(){
			try{
				$sql ="Select id_archivo, nombre_archivo, ruta_archivo, ruta_preview from archivos";
				$respuesta = $this->getDatosArchivo($sql);

				return $respuesta;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene los archivos de un cliente*/
		public function getArchivosAppFtp($idCliente){
			try{
				$sql = "Select nombre_archivo from archivos";
				$respuesta = $this->getDatosArchivo($sql, $idCliente);

				$contador=0;
				foreach($respuesta as $val){
					$tamanioArchivo = filesize("../upload/".$idCliente."/".$val["nombre_archivo"]);

					if($tamanioArchivo<1048576){
						$tamanioArchivo = round(($tamanioArchivo/1024))." kb";
					}else if($tamanioArchivo>1048576 && $tamanioArchivo<1073741824){
						$tamanioArchivo = round(($tamanioArchivo/1048576))." mb";
					}else if($tamanioArchivo>1073741824){
						$tamanioArchivo = round(($tamanioArchivo/1073741824),2)." gb";
					}

					$respuesta[$contador]["tamanio"] = $tamanioArchivo;
					$contador++;
				}

				return $respuesta;

			}catch(Exception $e){
				return $e;
			}
		}

		/*Guarda un archivo en la carpeta upload*/
		public function uploadArchivo($archivo, $preview){
			try{
				if($this->isArchivo($archivo["name"])==0){
					$arrayArchivo = explode(".", $archivo['name']);
					array_pop($arrayArchivo);
					$nombreArchivo="";
						
					foreach($arrayArchivo as $value){
						$nombreArchivo.= $value;
					}

					$carpetaAchivos = '/upload/'.$_SESSION["idCliente"];
					$carpetaPreview = $carpetaAchivos.'/img';
					$rutaArchivo = $carpetaAchivos.'/'.$archivo['name'];
					$rutaPreview = $carpetaPreview.'/'.$nombreArchivo.".png";

					if(move_uploaded_file($archivo['tmp_name'], "..".$carpetaAchivos."/".$archivo['name'])){
						if($this->addArchivo($archivo['name'], $rutaArchivo, $rutaPreview)==1){
							$img = $preview;
							$img = str_replace('data:image/png;base64,', '', $img);
							$img = str_replace('data:image/jpeg;base64,', '', $img);
							$img = str_replace(' ', '+', $img);
							$dataImg = base64_decode($img);
							$img = imagecreatefromstring($dataImg);
							if($img !== false){
								imagejpeg($img, "..".$carpetaPreview.'/'.$nombreArchivo.".png");
								imagedestroy($img);
								$evento="Subió archivo ".$archivo["name"]." mediante sitio cartelería";
								$this->addlogArchivos($_SESSION["idUsuario"], $evento);
								return "Archivo subido correctamente";
							}else{
								return "Error al subir el archivo (3)";	 //No se creo la vista previa
							}
						}else{
							return "Error al subir el archivo (2)";	//No se creo el registro en BD
						}
					}else{
						return "Error al subir el archivo (1)"; //El archivo no se movió a la carpeta de destino
					}
				}else{
					return "El archivo ya existe";
				}

			}catch (Exception $e){
				return $e;
			}
		}

		/*Guarda las vistas previas de archivos subidos por AppFtp*/
		public function uploadArchivoAppFtp($archivo, $idCliente, $preview=""){
			try{

				if($this->isArchivo($archivo, $idCliente)==0){
					$arrayArchivo = explode(".", $archivo);
					array_pop($arrayArchivo);
					$nombreArchivo="";

					foreach($arrayArchivo as $value){
						$nombreArchivo.= $value;
					}

					$fuentePreview= "../../webContent/img/";
					$destinoPreview="/upload/".$idCliente."/img/";
					$destinoArchivo="/upload/".$idCliente."/".$archivo;

					$fuentePreview.="preview_video.png";
					$archivoPreview=$nombreArchivo.".png";

					if(copy($fuentePreview, "..".$destinoPreview.$archivoPreview)){
						if($this->addArchivo($archivo, $destinoArchivo, $destinoPreview.$archivoPreview, $idCliente)==1){
							$img = $preview;
							$img = str_replace('data:image/png;base64,', '', $img);
							$img = str_replace('data:image/jpeg;base64,', '', $img);
							$img = str_replace(' ', '+', $img);
							$dataImg = base64_decode($img);
							$img = imagecreatefromstring($dataImg);
							if($img !== false){
								imagejpeg($img, "..".$destinoPreview.'/'.$nombreArchivo.".png");
								imagedestroy($img);
								$evento="Subió archivo ".$archivo["name"]." mediante appFtp";
								$this->addlogArchivos(0, $evento, $idCliente);
								return "ok";
							}else{
								return "Error al subir el archivo (3)";	 //No se creo la vista previa
							}

						}else{
							return "No se subió el archivo (2)";
						}
					}else{
						return "No se subió el archivo (1)";
					}
				}else{
					return "El archivo ya existe (2)";
				}

			}catch(Exception $e){
				return $e->getMessage();
			}
		}

		/*Borra el archivo y el el registro en Bd de este*/
		public function deleteArchivo($jsonObj){
			try{
				$archivo = json_decode($jsonObj);
				$arrayArchivo = explode(".", $archivo->{"nombreArchivo"});
				array_pop($arrayArchivo);
				$nombreArchivo="";

				foreach($arrayArchivo as $value){
					$nombreArchivo.= $value;
				}

				if(unlink("../upload/".$_SESSION["idCliente"]."/img/".$nombreArchivo.".png")){
					if(unlink("../upload/".$_SESSION["idCliente"]."/".$archivo->{"nombreArchivo"})){

						/*borra registro archivo en player*/
						$sqlArchivoPLayer="delete from archivo_player where id_archivo=".$archivo->{"idArchivo"};
						$filasArchivoPlayer=$this->addDatosArchivo($sqlArchivoPLayer);

						/*query borra registro archivo*/
						$sqlArchivo="delete from archivos where id_archivo=".$archivo->{"idArchivo"};
						$filasArchivo = $this->addDatosArchivo($sqlArchivo);

						if($filasArchivoPlayer!=0 || $filasArchivo!=0){
							$evento="Eliminó el archivo ".$archivo->{"nombreArchivo"};
							$this->addlogArchivos($_SESSION["idUsuario"], $evento);
							return "Archivo eliminado correctamente";
						}else{
							return "Se produjo un error al eliminar el archivo (1)"; //No se borró el registro en BD
						}
					}else{
						return "Se produjo un error al eliminar el archivo (2)"; //No se borró el archivo
					}
				}else{
					return "Se produjo un error al eliminar el archivo (3)"; //No se borró la vista previa
				}
			}catch(Exception $e){
				return $e;
			}
		}

		/*Renombra un archivo*/
		public function renombrarArchivo($jsonObj){
			try{
				$archivo = json_decode($jsonObj);

				if($this->isArchivo($archivo->{"nombreNuevo"}.$archivo->{"ext"})==0){
					$carpetaArchivos = "/upload/".$_SESSION["idCliente"]."/";
					$carpetaPreview = $carpetaArchivos."img/";
					if(rename("..".$carpetaArchivos.$archivo->{"nombreAntiguo"}.$archivo->{"ext"}, "..".$carpetaArchivos.$archivo->{"nombreNuevo"}.$archivo->{"ext"})){
						if(rename("..".$carpetaPreview.$archivo->{"nombreAntiguo"}.".png", "..".$carpetaPreview.$archivo->{"nombreNuevo"}.".png")){
							$sql ="update archivos ";
							$sql.="set nombre_archivo='".$archivo->{"nombreNuevo"}.$archivo->{"ext"}."', ";
							$sql.="ruta_archivo='".$carpetaArchivos.$archivo->{"nombreNuevo"}.$archivo->{"ext"}."', ";
							$sql.="ruta_preview='".$carpetaPreview.$archivo->{"nombreNuevo"}.".png' ";
							$sql.="where id_archivo=".$archivo->{"idArchivo"};

							$filas=$this->addDatosArchivo($sql);

							if($filas!=0){
								$evento ="Renombró el archivo ".$archivo->{"nombreAntiguo"}.$archivo->{"ext"};
								$evento.=" a ".$archivo->{"nombreNuevo"}.$archivo->{"ext"};
								$this->addlogArchivos($_SESSION["idUsuario"], $evento);
								return "Nombre modificado correctamente";
							}else{
								return "Se produjo un error al renombrar el archivo (3)";
							}
						}else{
							return "Se produjo un error al renombrar el archivo (2)";
						}
					}else{
						return "Se produjo un error al renombrar el archivo (1)";
					}
				}else{
					return "El nombre ingresado ya existe";
				}
			}catch(Exception $e){
				return $e;
			}
		}

		/*Comprueba si el nombre de archivo recibido existe*/
		public function isArchivo($nombreArchivo, $idCliente=""){
			try{
				$sql="Select id_archivo from archivos where nombre_archivo='".$nombreArchivo."'";

				if($idCliente==""){
					$respuesta=$this->getDatosArchivo($sql);
				}else{
					$respuesta=$this->getDatosArchivo($sql, $idCliente);
				}

				if(isset($respuesta[0])){
					return 1;
				}else{
					return 0;
				}
			}catch(Exception $e){
				return $e;
			}
		}

		/*Obtiene el tamaño de la carpeta de archivos de un cliente*/
		public function getSizeUpload($idCliente=""){
			try{
				$carpetaArchivos='/upload/';
				if($idCliente!=""){
					$carpetaArchivos.=$idCliente;
				}else{
					$carpetaArchivos.=$_SESSION["idCliente"];
				}
				
				$carpetaPreview = $carpetaArchivos.'/img';
				$carpetaFondos = $carpetaArchivos.'/fondos';

				$archivos=$this->getCarpetaSize("..".$carpetaArchivos);
				$preview=$this->getCarpetaSize("..".$carpetaPreview);
				$fondos=$this->getCarpetaSize("..".$carpetaFondos);

				return $archivos-$preview-$fondos;

			}catch(Exception $e){
				return $e;
			}
		}

		/*Registra en base de datos un archivo subido*/
		private function addArchivo($nombre, $ruta_archivo, $ruta_preview, $idCliente=""){
			try{
				$sql ="insert into archivos";
				$sql.="(nombre_archivo, ruta_archivo, ruta_preview) ";
				$sql.="values('".$nombre."', '".$ruta_archivo."', '".$ruta_preview."')";

				$filas=0;

				if($idCliente==""){
					$filas = $this->addDatosArchivo($sql);
				}else{
					$filas = $this->addDatosArchivo($sql, $idCliente);
				}

				return $filas;

			}catch(Exception $e){
				return $e->getMessage();
			}
		}

		/*Obtiene el tamaño de la carpeta recibida*/
		private function getCarpetaSize($carpeta) {
			try{
				$size = 0;
			    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($carpeta)) as $file){
			        $size+=$file->getSize();
			    }
			    return $size;
			}catch(Exception $e){
				return $e;
			}
		}

		/*Registra una acción realizada en el menú de archivos*/
		private function addlogArchivos($idUsuario=0, $evento="", $idCliente=""){
			try{
				$sql ="insert into log_eventos";
				$sql.="(evento, id_usuario, fecha) ";
				$sql.="values('".$evento."', ".$idUsuario.", '".$this->hoy."/".$this->hora."')";

				if($idCliente==""){
					$this->addDatosArchivo($sql);
				}else{
					$this->addDatosArchivo($sql, $idCliente);
				}
				
			}catch(Exception $e){
				return $e;
			}
		}

		/*Realiza la consulta sql recibida y devuelve el resultado*/
		private function getDatosArchivo($sql, $bd=null){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado="";

				if($bd==null){
					$resultado = $this->db_->conectar("cliente")->prepare($sql);
				}else{
					$resultado = $this->db_->conectar($bd)->prepare($sql);
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

		/*Ejecuta la consulta de inserción, eliminación o actualización que reciba y devuelve la cantidad de filas afectadas*/
		private function addDatosArchivo($sql, $bd=null){
			try{
				$this->db_ = new ConexionMsqlCarteleria();
				$resultado="";

				if($bd==null){
					$resultado = $this->db_->conectar("cliente")->prepare($sql);
				}else{
					$resultado = $this->db_->conectar($bd)->prepare($sql);
				}

				$resultado->execute(array());
				$filas = $resultado->rowCount();

				$resultado->closeCursor();
				$resultado = null;
				$this->db_ = null;

				return $filas;
			}catch(Exception $e){
				return $e->getMessage();
			}
		}
	}
?>