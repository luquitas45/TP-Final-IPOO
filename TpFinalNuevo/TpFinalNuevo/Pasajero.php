<?php
include_once 'Persona.php';

class Pasajero extends Persona {
    private $pdocumento;
    private $ptelefono;

    public function __construct() {
        parent::__construct();
        $this->pdocumento = "";
        $this->ptelefono = 0;
    }

    
    public function getPdocumento() { return $this->pdocumento; }
    public function setPdocumento($pdocumento) { $this->pdocumento = $pdocumento; }

    public function getPtelefono() { return $this->ptelefono; }
    public function setPtelefono($ptelefono) { $this->ptelefono = $ptelefono; }

    
    public function __toString() {
        return "Pasajero: {$this->nombre} {$this->apellido} - Documento: {$this->pdocumento} - Teléfono: {$this->ptelefono}";
    }

    
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;

        $consultaExiste = "SELECT idpersona FROM persona WHERE nombre = '{$this->nombre}' AND apellido = '{$this->apellido}' LIMIT 1";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaExiste)) {
                if ($row = $base->Registro()) {
                    $this->setIdpersona($row['idpersona']);
                } else {
                    if (parent::insertar()) {
                    } else {
                        echo "Error al insertar persona.\n";
                        return false;
                    }
                }
            } else {
                echo "Error al buscar persona: " . $base->getError() . "\n";
                return false;
            }

            $idPersona = $this->getIdpersona();
            $consultaResp = "SELECT COUNT(*) AS cantidad FROM responsable WHERE idresponsable = $idPersona";

            if ($base->Ejecutar($consultaResp)) {
                $rowResp = $base->Registro();
                if ($rowResp['cantidad'] > 0) {
                    echo "Error: La persona ya está asignada como responsable.\n";
                    return false;
                }
            } else {
                echo "Error en la consulta para verificar responsable: " . $base->getError() . "\n";
                return false;
            }

            $query = "INSERT INTO pasajero (idpasajero, pdocumento, ptelefono, activo)
                    VALUES ($idPersona, '{$this->pdocumento}', {$this->ptelefono}, {$this->activo})";

            if ($base->Ejecutar($query)) {
                $resp = true;
            } else {
                echo "Error al insertar pasajero: " . $base->getError() . "\n";
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }





    public function modificar() {
        $base = new BaseDatos();
        $resp = false;

        $idPersona = $this->getIdpersona();
        $queryPersona = "UPDATE persona 
                        SET nombre = '{$this->getNombre()}', apellido = '{$this->getApellido()}' 
                        WHERE idpersona = $idPersona";

        $queryPasajero = "UPDATE pasajero
                        SET ptelefono = {$this->getPtelefono()}, activo = {$this->getActivo()}
                        WHERE pdocumento = '{$this->getPdocumento()}'";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($queryPersona)) {
                if ($base->Ejecutar($queryPasajero)) {
                    $resp = true;
                } else {
                    echo "Error al modificar pasajero: " . $base->getError() . "\n";
                }
            } else {
                echo "Error al modificar persona: " . $base->getError() . "\n";
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }


    public function eliminar($fisico = false) {
        $base = new BaseDatos();
        $resp = false;
        $documento = $this->getPdocumento();

        if ($base->Iniciar()) {
            if ($fisico) {
                $consulta = "SELECT COUNT(*) AS cantidad 
                            FROM viaje_pasajero vp
                            INNER JOIN viaje v ON vp.idviaje = v.idviaje
                            WHERE vp.pdocumento = '$documento' AND v.activo = 1";

                if ($base->Ejecutar($consulta)) {
                    $row = $base->Registro();
                    if ($row['cantidad'] > 0) {
                        echo "No se puede eliminar físicamente al pasajero porque tiene viajes activos asociados.\n";
                        return false;
                    }
                } else {
                    echo "Error en la consulta para verificar viajes asociados: " . $base->getError() . "\n";
                    return false;
                }

                $query = "DELETE FROM pasajero WHERE pdocumento = '$documento'";
                if ($base->Ejecutar($query)) {
                    $resp = true;
                } else {
                    echo "Error al eliminar pasajero: " . $base->getError() . "\n";
                }
            } else {
                $query = "UPDATE pasajero SET activo = 0 WHERE pdocumento = '$documento'";
                if ($base->Ejecutar($query)) {
                    $resp = true;
                } else {
                    echo "Error al dar de baja pasajero: " . $base->getError() . "\n";
                }
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }

    public static function listar($condicion = "") {
        $base = new BaseDatos();
        $lista = [];
        $consulta = "SELECT p.idpasajero, p.pdocumento, p.ptelefono, p.activo, pe.nombre, pe.apellido
                    FROM pasajero p
                    INNER JOIN persona pe ON p.idpasajero = pe.idpersona
                    WHERE p.activo = 1";

        if ($condicion != "") {
            $consulta .= " AND " . $condicion;
        }

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $pasajero = new Pasajero();
                    $pasajero->setIdpersona($row['idpasajero']);
                    $pasajero->setPdocumento($row['pdocumento']);
                    $pasajero->setPtelefono($row['ptelefono']);
                    $pasajero->setActivo($row['activo']);
                    $pasajero->setNombre($row['nombre']);
                    $pasajero->setApellido($row['apellido']);
                    $lista[] = $pasajero;
                }
            }
        }
        return $lista;
    }



}
?>
