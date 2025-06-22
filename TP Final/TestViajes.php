<?php
include_once "pasajero.php";
include_once "responsableV.php";
include_once "viaje.php";
include_once "empresa.php";

class TestViajes {
    private $conexion;


    // Constructor
    public function __construct(){
        try {
            $this->conexion = new PDO("mysql:host=localhost;dbname=bdviajes", "root", "");
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }


    // Funciones
    public function agregarEmpresa($nombre, $direccion){
        $sql = "INSERT INTO empresa (enombre, edireccion) VALUES (:nombre, :direccion)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        return $stmt->execute();
    }

    public function modificarEmpresa($idEmpresa, $nuevoNombre, $nuevaDireccion){
        $sql = "UPDATE empresa SET enombre = :nombre, edireccion = :direccion WHERE idempresa = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre', $nuevoNombre);
        $stmt->bindParam(':direccion', $nuevaDireccion);
        $stmt->bindParam(':id', $idEmpresa);
        return $stmt->execute();
    }

    public function eliminarEmpresa($idEmpresa){
        $sql = "DELETE FROM empresa WHERE idempresa = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $idEmpresa);
        return $stmt->execute();
    }
}