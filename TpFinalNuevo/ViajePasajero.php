<?php

class ViajePasajero {
    private $viaje;       
    private $pasajero;    

    public function __construct($viaje = null, $pasajero = null) {
        $this->viaje = $viaje;
        $this->pasajero = $pasajero;
    }

    public function getViaje() {
        return $this->viaje;
    }

    public function setViaje($viaje) {
        $this->viaje = $viaje;
    }

    public function getPasajero() {
        return $this->pasajero;
    }

    public function setPasajero($pasajero) {
        $this->pasajero = $pasajero;
    }

    public function insertar() {
        $base = new BaseDatos();
        $idviaje = $this->viaje->getIdviaje();
        $pdocumento = $this->pasajero->getPdocumento();

        $sql = "INSERT INTO viaje_pasajero (idviaje, pdocumento) VALUES ('$idviaje', '$pdocumento')";
        if ($base->Iniciar()) {
            return $base->Ejecutar($sql);
        }
        return false;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $idviaje = $this->viaje->getIdviaje();
        $pdocumento = $this->pasajero->getPdocumento();

        $sql = "DELETE FROM viaje_pasajero WHERE idviaje = '$idviaje' AND pdocumento = '$pdocumento'";
        if ($base->Iniciar()) {
            return $base->Ejecutar($sql);
        }
        return false;
    }

    public function modificar($idviajeOld, $pdocumentoOld) {
    $base = new BaseDatos();
    $idviaje = $this->viaje->getIdviaje();
    $pdocumento = $this->pasajero->getPdocumento();

    $sql = "UPDATE viaje_pasajero 
            SET idviaje = '$idviaje', pdocumento = '$pdocumento'
            WHERE idviaje = '$idviajeOld' AND pdocumento = '$pdocumentoOld'";

    if ($base->Iniciar()) {
        return $base->Ejecutar($sql);
    }
    return false;
}


    public static function listarPorViaje($idviaje) {
        $base = new BaseDatos();
        $lista = [];
        $consulta = "SELECT p.pdocumento, p.ptelefono, p.activo, pe.nombre AS pnombre, pe.apellido AS papellido
                    FROM viaje_pasajero vp
                    JOIN pasajero p ON vp.pdocumento = p.pdocumento
                    JOIN persona pe ON p.idpasajero = pe.idpersona
                    WHERE vp.idviaje = $idviaje";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $pasajero = new Pasajero();
                    $pasajero->setPdocumento($row['pdocumento']);
                    $pasajero->setNombre($row['pnombre']);
                    $pasajero->setApellido($row['papellido']);
                    $pasajero->setPtelefono($row['ptelefono']);
                    $pasajero->setActivo($row['activo']);

                    $viaje = new Viaje();
                    $viaje->setIdviaje($idviaje);

                    $vp = new ViajePasajero($viaje, $pasajero);
                    $lista[] = $vp;
                }
            }
        }
        return $lista;
    }

}

