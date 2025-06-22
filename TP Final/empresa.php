<?php
class Empresa {
    private $idEmpresa;
    private $nombre;
    private $direccion;

    // Constructor 
     public function __construct($nombre, $direccion, $idEmpresa = null) {
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->idEmpresa = $idEmpresa;
    }

    // Getters
    public function getNombre() {
        return $this->nombre;
    }

    public function getDireccion() {
        return $this->direccion;
    }
        public function getIdEmpresa() {
        return $this->idEmpresa;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
        public function setIdEmpresa($id) {
        $this->idEmpresa = $id;
    }

    // __toString
    public function __toString() {
        return "Empresa: " . $this->getNombre() . " - Dirección: " . $this->getDireccion() . "\n";
    }
}