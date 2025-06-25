<?php
class BaseDatos {
    private $conexion;
    private $baseDatos = "bdviajes";
    private $host = "localhost";
    private $usuario = "root";
    private $clave = "";
    private $query;
    private $resultado;

    public function Iniciar() {
        $resp = false;
        try {
            $this->conexion = new mysqli($this->host, $this->usuario, $this->clave, $this->baseDatos);
            if ($this->conexion->connect_errno == 0) {
                $resp = true;
            } else {
                throw new Exception($this->conexion->connect_error);
            }
        } catch (Exception $e) {
            echo "Error al conectar: " . $e->getMessage();
        }
        return $resp;
    }

    public function Ejecutar($sql) {
        $this->query = $sql;
        $this->resultado = $this->conexion->query($sql);
        return $this->resultado != false;
    }

     public function Registro() {
        if ($this->resultado) {
            return $this->resultado->fetch_assoc();
        } else {
            return null;
        }
    }

    public function getUltimoIdInsertado() {
        return $this->conexion->insert_id;
    }

    public function getError() {
        return $this->conexion->error;
    }

    public function cerrar() {
        $this->conexion->close();
    }
}
?>
