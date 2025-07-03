<?php
class BaseDatos {
    private $HOSTNAME;
    private $BASEDATOS;
    private $USUARIO;
    private $CLAVE;
    private $CONEXION;
    private $QUERY;
    private $RESULT;
    private $ERROR;

    public function __construct(){
        $this->HOSTNAME = "127.0.0.1"; 
        $this->BASEDATOS = "bdviajes"; 
        $this->USUARIO = "root";       
        $this->CLAVE = "";            
        $this->RESULT = null;
        $this->QUERY = "";
        $this->ERROR = "";
    }

    public function getError(){
        return $this->ERROR;
    }

    public function Iniciar(){
        $this->CONEXION = mysqli_connect($this->HOSTNAME, $this->USUARIO, $this->CLAVE, $this->BASEDATOS);
        if (!$this->CONEXION) {
            $this->ERROR = mysqli_connect_error();
            return false;
        }
        return true;
    }

    public function Ejecutar($consulta){
        $this->QUERY = $consulta;
        $this->RESULT = mysqli_query($this->CONEXION, $consulta);
        if (!$this->RESULT) {
            $this->ERROR = mysqli_error($this->CONEXION);
            return false;
        }
        return true;
    }

    public function Registro(){
        if ($this->RESULT) {
            $registro = mysqli_fetch_assoc($this->RESULT);
            if ($registro) {
                return $registro;
            } else {
                mysqli_free_result($this->RESULT);
                return null;
            }
        } else {
            $this->ERROR = "No hay resultado para obtener registros";
            return null;
        }
    }

    public function devuelveIDInsercion(){
            return mysqli_insert_id($this->CONEXION);
        }
    

    public function cerrarConexion(){
        if ($this->CONEXION) {
            mysqli_close($this->CONEXION);
        }
    }
}
?>
