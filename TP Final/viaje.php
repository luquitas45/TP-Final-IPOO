<?php
class Viaje{
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $importe;
    private $empresa; 
    private $responsable; // Objeto ResponsableV
    private $pasajeros = []; 


    // Construct
    public function __construct($destino, $cantMaxPasajeros, $importe, $empresa, $responsable, $idViaje = null) {
        $this->destino = $destino;
        $this->cantMaxPasajeros = $cantMaxPasajeros;
        $this->importe = $importe;
        $this->empresa = $empresa;
        $this->responsable = $responsable;
        $this->pasajeros = [];
        $this->idViaje = $idViaje;
    }

    // Getters
    public function getDestino() {
        return $this->destino;
    }

    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function getEmpresa() {
        return $this->empresa;
    }

    public function getResponsable() {
        return $this->responsable;
    }

    public function getPasajeros() {
        return $this->pasajeros;
    }
        public function getIdViaje() {
        return $this->idViaje;
    }

    // Setters
    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function setCantMaxPasajeros($cantMaxPasajeros) {
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function setResponsable($responsable) {
        $this->responsable = $responsable;
    }

    public function setPasajeros($pasajeros) {
        $this->pasajeros = $pasajeros;
    }
    public function setIdViaje($id) {
        $this->idViaje = $id;
    }


    // Agregar pasajero
    public function agregarPasajero($pasajero) {
        if (count($this->getPasajeros()) < $this->getCantMaxPasajeros()) {
            $this->pasajeros[] = $pasajero;
            return true;
        } else {
            return false;
        }
    }

    


    
    public function __toString() {
        $cadena = "Destino: " . $this->getDestino() . 
                  " | Importe: $" . $this->getImporte() . 
                  " | Máx Pasajeros: " . $this->getCantMaxPasajeros() . "\n";

        if ($this->getEmpresa() instanceof Empresa) {
            $cadena .= $this->getEmpresa()->__toString();
        } else {
            $cadena .= "Empresa: " . $this->getEmpresa() . "\n";
        }

        $cadena .= $this->getResponsable()->__toString();
        $cadena .= "\nPasajeros:\n";

        foreach ($this->getPasajeros() as $p) {
            $cadena .= $p->__toString();
        }

        return $cadena;
    }
}


?>