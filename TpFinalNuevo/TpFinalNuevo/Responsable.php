<?php
include_once 'Persona.php';

class Responsable extends Persona {
    private $rnumeroempleado;
    private $rnumerolicencia;

    public function __construct() {
        parent::__construct();
        $this->rnumeroempleado = null;
        $this->rnumerolicencia = 0;
    }

   
    public function getRnumeroempleado() { return $this->rnumeroempleado; }
    public function setRnumeroempleado($rnumeroempleado) { $this->rnumeroempleado = $rnumeroempleado; }

    public function getRnumerolicencia() { return $this->rnumerolicencia; }
    public function setRnumerolicencia($rnumerolicencia) { $this->rnumerolicencia = $rnumerolicencia; }

    
    public function __toString() {
        return "Responsable: {$this->nombre} {$this->apellido} - Licencia: {$this->rnumerolicencia}";
    }

    
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $idPersona = $this->getIdpersona();

        if ($base->Iniciar()) {
            $consulta = "SELECT COUNT(*) AS cantidad FROM pasajero WHERE idpasajero = $idPersona";
            if ($base->Ejecutar($consulta)) {
                $row = $base->Registro();
                if ($row['cantidad'] > 0) {
                    echo "Error: La persona ya está asignada como pasajero.\n";
                    return false;
                }
            } else {
                echo "Error al verificar si es pasajero: " . $base->getError() . "\n";
                return false;
            }

            $query = "INSERT INTO responsable (idresponsable, rnumerolicencia, activo) 
                    VALUES ($idPersona, {$this->getRnumerolicencia()}, {$this->getActivo()})";

            if ($base->Ejecutar($query)) {
                $consultaId = "SELECT LAST_INSERT_ID() AS id";
                if ($base->Ejecutar($consultaId)) {
                    if ($row = $base->Registro()) {
                        $this->setRnumeroempleado($row['id']);
                        $resp = true;
                    }
                }
            } else {
                echo "Error al insertar responsable: " . $base->getError() . "\n";
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }





    public function modificar() {
        $base = new BaseDatos();
        $resp = false;

        if ($base->Iniciar()) {
            $queryPersona = "UPDATE persona 
                            SET nombre = '{$this->getNombre()}', apellido = '{$this->getApellido()}', activo = {$this->getActivo()}
                            WHERE idpersona = {$this->getIdpersona()}";

            $queryResponsable = "UPDATE responsable 
                                SET rnumerolicencia = {$this->getRnumerolicencia()}
                                WHERE rnumeroempleado = {$this->getRnumeroempleado()}";

            if ($base->Ejecutar($queryPersona)) {
                $resp = $base->Ejecutar($queryResponsable);
            } else {
                echo "Error al modificar los datos de la persona.\n";
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }


    public function eliminar($fisico = false) {
        $base = new BaseDatos();
        $resp = false;
        $idResponsable = $this->getRnumeroempleado();

        if ($base->Iniciar()) {
            $consulta = "SELECT COUNT(*) AS cantidad FROM viaje WHERE rnumeroempleado = $idResponsable AND activo = 1";
            if ($base->Ejecutar($consulta)) {
                $row = $base->Registro();
                if ($row['cantidad'] > 0) {
                    echo "No se puede eliminar el responsable porque tiene viajes asignados.\n";
                    return false;
                }
            } else {
                echo "Error al verificar viajes del responsable: " . $base->getError() . "\n";
                return false;
            }

            if ($fisico) {
                $query = "DELETE FROM responsable WHERE rnumeroempleado = $idResponsable";
            } else {
                $query = "UPDATE responsable SET activo = 0 WHERE rnumeroempleado = $idResponsable";
            }

            if ($base->Ejecutar($query)) {
                $resp = true;
            } else {
                echo "Error al eliminar responsable: " . $base->getError() . "\n";
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }



    public static function listar($condicion = "") {
        $base = new BaseDatos();
        $lista = [];

        $consulta = "SELECT r.*, p.nombre AS nombre, p.apellido AS apellido 
                    FROM responsable r 
                    INNER JOIN persona p ON r.idresponsable = p.idpersona 
                    WHERE r.activo = 1";

        if (!empty($condicion)) {
            $consulta .= " AND $condicion";
        }

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $r = new Responsable();
                    $r->setRnumeroempleado($row['rnumeroempleado']);
                    $r->setRnumerolicencia($row['rnumerolicencia']);
                    $r->setIdpersona($row['idresponsable']); 
                    $r->setNombre($row['nombre']); 
                    $r->setApellido($row['apellido']);
                    $r->setActivo($row['activo']);
                    $lista[] = $r;
                }
            }
        }

        return $lista;
    }


}
?>
