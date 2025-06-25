<?php
class ResponsableV{
    private $nroEmpleado;
    private $nroLicencia;
    private $nombre;
    private $apellido;


    public function __construct($nroEmpleado, $nroLicencia, $nombre, $apellido){
        $this->nroEmpleado = $nroEmpleado;
        $this->nroLicencia = $nroLicencia;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
    }


    // Getters
    public function getNumeroEmpleado() { return $this->nroEmpleado; }
    public function getNumeroLicencia() { return $this->nroLicencia; }
    public function getNombre() { return $this->nombre; }
    public function getApellido() { return $this->apellido; }
    

    // Setters
    public function setNumeroEmpleado($numEmp) { $this->nroEmpleado = $numEmp; }
    public function setNumeroLicencia($numLic) { $this->nroLicencia = $numLic; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellido($apellido) { $this->apellido = $apellido; }



    // toString
    public function __toString() {
    return "Responsable: " . $this->getNombre() . " " . $this->getApellido() . 
           " - Empleado: " . $this->getNumeroEmpleado() . 
           " - Licencia: " . $this->getNumeroLicencia() . "\n";
}

}


?>