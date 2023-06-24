<?php	
	class ConexionMsqlCarteleria{
	    private $db_;

	    /*Realiza la conexión con la base de datos*/
	    public function conectar($bd){
	    	try{
	    		$file_conexion = file_get_contents("../db/datosConexionCarteleria.json");
				$cnx = json_decode($file_conexion, true);
				$dsn = "";

				switch ($bd) {
					case 'global':	//se conecta al localhost
						$dsn = $cnx["mysql"];
						break;
					case 'principal': //se conecta a la BD 'dbcarteleria'
						$dsn = $cnx["mysql"]." ".$cnx["dbname"];
						break;
					case 'cliente': //se conecta a la BD asociada a un cliente logueado
						$dsn = $cnx["mysql"]." ".$cnx["dbname"].$_SESSION["idCliente"];
						break;	
					default: //se conecta a la BD asociada al cliente según el valor de '$bd'
						$dsn = $cnx["mysql"]." ".$cnx["dbname"].$bd;
						break;
				}	

				$this->db_=new PDO($dsn, $cnx["user"], $cnx["password"]);
				$this->db_->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db_->exec("SET CHARACTER SET UTF8");
				return $this->db_;

	    	} catch (Exception $e) {	
	    		die("Error" . $e->getMessage());
	    		echo "Line de error " . $e->getLine();
	    	}
	    }
	}
?>