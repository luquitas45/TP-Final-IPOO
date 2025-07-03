<?php

class Viaje {
    private $idviaje;
    private $vdestino;
    private $vcantmaxpasajeros;
    private $empresa;
    private $responsable; 
    private $vimporte;
    private $activo;

    public function __construct() {
        $this->idviaje = null;
        $this->vdestino = "";
        $this->vcantmaxpasajeros = 0;
        $this->empresa = null;
        $this->responsable = null;
        $this->vimporte = 0.0;
        $this->activo = 1;
    }

    public function getIdviaje() { return $this->idviaje; }
    public function setIdviaje($idviaje) { $this->idviaje = $idviaje; }

    public function getVdestino() { return $this->vdestino; }
    public function setVdestino($vdestino) { $this->vdestino = $vdestino; }

    public function getVcantmaxpasajeros() { return $this->vcantmaxpasajeros; }
    public function setVcantmaxpasajeros($vcantmaxpasajeros) { $this->vcantmaxpasajeros = $vcantmaxpasajeros; }

    public function getEmpresa() { return $this->empresa; }
    
    public function setEmpresa($empresa) { $this->empresa = $empresa; }

    public function getResponsable() { return $this->responsable; }
    public function setResponsable($responsable) { $this->responsable = $responsable; }

    public function getVimporte() { return $this->vimporte; }
    public function setVimporte($vimporte) { $this->vimporte = $vimporte; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }

    public function __toString() {
        $empresaId = $this->empresa ? $this->empresa->getIdempresa() : 'null';
        $respId = $this->responsable ? $this->responsable->getRnumeroempleado() : 'null';
        return "Viaje a {$this->vdestino} - Empresa: {$empresaId}, Responsable: {$respId}, Cupo Máximo: {$this->vcantmaxpasajeros}, Importe: \${$this->vimporte}";
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $idempresa = $this->empresa->getIdempresa();
        $idresponsable = $this->responsable->getRnumeroempleado();
        $query = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte, activo)
                  VALUES ('{$this->vdestino}', {$this->vcantmaxpasajeros}, {$idempresa}, {$idresponsable}, {$this->vimporte}, {$this->activo})";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($query)) {
                $this->idviaje = $base->devuelveIDInsercion();
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $idempresa = $this->empresa->getIdempresa();
        $idresponsable = $this->responsable->getRnumeroempleado();
        $query = "UPDATE viaje 
                  SET vdestino = '{$this->vdestino}', 
                      vcantmaxpasajeros = {$this->vcantmaxpasajeros}, 
                      idempresa = {$idempresa}, 
                      rnumeroempleado = {$idresponsable}, 
                      vimporte = {$this->vimporte},
                      activo = {$this->activo}
                  WHERE idviaje = {$this->idviaje}";
        if ($base->Iniciar()) {
            $resp = $base->Ejecutar($query);
        }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $idViaje = $this->getIdviaje();

        // Verificar si hay pasajeros asociados
        $consulta = "SELECT COUNT(*) AS cantidad FROM viaje_pasajero WHERE idviaje = $idViaje";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $row = $base->Registro();
                if ($row['cantidad'] > 0) {
                    echo "No se puede eliminar el viaje porque tiene pasajeros asociados.\n";
                    return false;
                }
            }

            // Si no hay pasajeros asociados, eliminar
            $query = "DELETE FROM viaje WHERE idviaje = $idViaje";
            $resp = $base->Ejecutar($query);
    }

    return $resp;
}


    

    public static function listar() {
        $base = new BaseDatos();
        $lista = [];
        $consulta = "SELECT * FROM viaje WHERE activo = 1";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $v = new Viaje();
                    $v->setIdviaje($row['idviaje']);
                    $v->setVdestino($row['vdestino']);
                    $v->setVcantmaxpasajeros($row['vcantmaxpasajeros']);
                    $v->setVimporte($row['vimporte']);
                    $v->setActivo($row['activo']);

                    // Crear objeto Empresa
                    $empresa = new Empresa();
                    $empresa->setIdempresa($row['idempresa']);
                    $v->setEmpresa($empresa);

                    // Crear objeto Responsable
                    $responsable = new Responsable();
                    $responsable->setRnumeroempleado($row['rnumeroempleado']);
                    $v->setResponsable($responsable);

                    $lista[] = $v;
                }
            }
        }
        return $lista;
    }

}
