<?php
class Pasajero{
    private $nombre;
    private $apellido;
    private $nroDocumento;
    private $telefono;


    // Construct
    public function __construct($nombre, $apellido, $nroDocumento, $telefono) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->nroDocumento = $nroDocumento;
        $this->telefono = $telefono;
    }


    // Getters
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getNroDocumento(){
        return $this->nroDocumento;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    

    // Setters
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setApellido($apellido){
        $this->apellido = $apellido;
    }
    public function setNroDocumento($nroDocumento){
        $this->nroDocumento = $nroDocumento;
    }
    public function setTelefono($telefono){
        $this->telefono = $telefono;
    }


    // toString
    public function __toString(){
        return "\nnombre: ". $this->getNombre() .
        "\nApellido: ". $this->getApellido() .
        "\nNro Documento: ". $this->getNroDocumento() .
        "\nTelefono: ". $this->getTelefono() ."\n";
    }
}




?>