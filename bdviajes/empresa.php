<?php
include_once "bdviajes.php";

class Empresa {
    private $idEmpresa;
    private $nombre;
    private $direccion;
    private $mensajeOperacion;

    public function __construct(){
        $this->idEmpresa = "";
        $this->nombre = "";
        $this->direccion = "";
    }

    public function cargar($id, $nombre, $direccion){
        $this->idEmpresa = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }


    // Getters
    public function getIdEmpresa(){
        return $this->idEmpresa;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }


    // Setters
    public function setIdEmpresa($id){
        $this->idEmpresa = $id;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }
    public function setMensajeOperacion($mensaje){
        $this->mensajeOperacion = $mensaje;
    }

    
    // MOR
    public function insertar() {
        $base = new BaseDatos();
        $sql = "INSERT INTO empresa (enombre, edireccion) VALUES ('{$this->getNombre()}', '{$this->getDireccion()}')";
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->setIdEmpresa($base->getUltimoIdInsertado());
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $sql = "UPDATE empresa SET enombre='{$this->getNombre()}', edireccion='{$this->getDireccion()}' 
                WHERE idempresa = {$this->getIdEmpresa()}";
        $resp = false;
        if ($base->Iniciar()) {
            $resp = $base->Ejecutar($sql);
            if (!$resp) $this->setMensajeOperacion($base->getError());
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $sql = "DELETE FROM empresa WHERE idempresa = {$this->getIdEmpresa()}";
        $resp = false;
        if ($base->Iniciar()) {
            $resp = $base->Ejecutar($sql);
            if (!$resp) $this->setMensajeOperacion($base->getError());
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    
    // __toString
    public function __toString() {
        return "Empresa: {$this->getNombre()} - Dirección: {$this->getDireccion()}\n";
    }
}