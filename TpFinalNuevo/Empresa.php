<?php
class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $activo;

    public function __construct() {
        $this->idempresa = null;
        $this->enombre = "";
        $this->edireccion = "";
        $this->activo = 1;
    }

    
    public function getIdEmpresa() { return $this->idempresa; }
    public function setIdEmpresa($idempresa) { $this->idempresa = $idempresa; }

    public function getEnombre() { return $this->enombre; }
    public function setEnombre($enombre) { $this->enombre = $enombre; }

    public function getEdireccion() { return $this->edireccion; }
    public function setEdireccion($edireccion) { $this->edireccion = $edireccion; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }

    
    public function __toString() {
        return "Empresa: {$this->enombre} - Dirección: {$this->edireccion}";
    }

    
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $query = "INSERT INTO empresa (enombre, edireccion, activo) 
                  VALUES ('{$this->enombre}', '{$this->edireccion}', {$this->activo})";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($query)) {
                $this->idempresa = $base->devuelveIDInsercion();
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;
        $query = "UPDATE empresa 
                  SET enombre = '{$this->enombre}', edireccion = '{$this->edireccion}', activo = {$this->activo}
                  WHERE idempresa = {$this->idempresa}";
        if ($base->Iniciar()) {
            $resp = $base->Ejecutar($query);
        }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $id = $this->getIdempresa();

        // Validación: verificar si hay viajes asociados a esta empresa
        $consulta = "SELECT COUNT(*) AS cantidad FROM viaje WHERE idempresa = $id";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $row = $base->Registro();
                if ($row['cantidad'] > 0) {
                    echo "No se puede eliminar la empresa porque tiene viajes asociados.\n";
                    return false;
                }
            }

            // Si no hay viajes asociados, se elimina la empresa
            $query = "DELETE FROM empresa WHERE idempresa = $id";
            $resp = $base->Ejecutar($query);
        }

        return $resp;
    }


    public static function listar() {
    $base = new BaseDatos();
    $lista = [];
    $consulta = "SELECT * FROM empresa WHERE activo = 1";

    if ($base->Iniciar()) {
        if ($base->Ejecutar($consulta)) {
            while ($row = $base->Registro()) {
                $e = new Empresa();
                $e->setIdempresa($row['idempresa']);
                $e->setEnombre($row['enombre']);
                $e->setEdireccion($row['edireccion']);
                $e->setActivo($row['activo']);
                $lista[] = $e;
            }
        }
    }
    return $lista;
}

}
?>
