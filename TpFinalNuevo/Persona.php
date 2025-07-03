<?php
class Persona {
    protected $idpersona;
    protected $nombre;
    protected $apellido;
    protected $activo;

    public function __construct() {
        $this->nombre = "";
        $this->apellido = "";
        $this->activo = 1;
    }

    public function getIdpersona() { return $this->idpersona; }
    public function setIdpersona($id) { $this->idpersona = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getApellido() { return $this->apellido; }
    public function setApellido($apellido) { $this->apellido = $apellido; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $query = "INSERT INTO persona (nombre, apellido, activo) 
                VALUES ('" . $this->getNombre() . "', '" . $this->getApellido() . "', " . $this->getActivo() . ")";

        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($query)) {
                // Obtener el último ID insertado y asignarlo al objeto
                $queryId = "SELECT LAST_INSERT_ID() AS id";
                if ($base->Ejecutar($queryId)) {
                    if ($row = $base->Registro()) {
                        $this->setIdpersona($row['id']);
                        $resp = true;
                    }
                }
            } else {
                echo "Error al insertar persona: " . $base->getError();
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError();
        }

        return $resp;
    }

    public function modificar() {
        $base = new BaseDatos();
        $resp = false;

        $query = "UPDATE persona 
                SET nombre = '" . $this->getNombre() . "', 
                    apellido = '" . $this->getApellido() . "', 
                    activo = " . $this->getActivo() . "
                WHERE idpersona = " . $this->getIdpersona();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($query)) {
                $resp = true;
            } else {
                echo "Error al modificar persona: " . $base->getError();
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError();
        }

        return $resp;
    }

    public function eliminar($fisico = false) {
        $base = new BaseDatos();
        $resp = false;
        $id = $this->getIdpersona();

        if ($id === null) {
            echo "Error: No se ha establecido el ID de la persona.\n";
            return false;
        }

        if ($base->Iniciar()) {
            // Verifica relación con responsable
            $sqlResp = "SELECT * FROM responsable WHERE idresponsable = $id";
            $resResp = $base->Ejecutar($sqlResp);
            if ($resResp && $base->Registro()) {
                echo "No se puede eliminar la persona. Está asociada a un responsable.\n";
                return false;
            }

            // Verifica relación con pasajero
            $sqlPas = "SELECT * FROM pasajero WHERE idpasajero = $id";
            $resPas = $base->Ejecutar($sqlPas);
            if ($resPas && $base->Registro()) {
                echo "No se puede eliminar la persona. Está asociada a un pasajero.\n";
                return false;
            }

            // Arma la query según el tipo de eliminación
            if ($fisico) {
                $query = "DELETE FROM persona WHERE idpersona = $id";
            } else {
                $query = "UPDATE persona SET activo = 0 WHERE idpersona = $id";
            }

            if ($base->Ejecutar($query)) {
                $resp = true;
            } else {
                echo "Error al eliminar persona: " . $base->getError() . "\n";
            }
        } else {
            echo "Error al conectar con la base de datos: " . $base->getError() . "\n";
        }

        return $resp;
    }



    public static function listar($condicion = "") {
        $arreglo = [];
        $base = new BaseDatos();
        $consulta = "SELECT * FROM persona";

        if ($condicion != "") {
            $consulta .= " WHERE " . $condicion;
        }

        if ($condicion == "") {
            $consulta .= " WHERE activo = 1";
        }

        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                while ($row = $base->Registro()) {
                    $obj = new Persona();
                    $obj->setIdpersona($row['idpersona']);
                    $obj->setNombre($row['nombre']);
                    $obj->setApellido($row['apellido']);
                    $obj->setActivo($row['activo']);
                    array_push($arreglo, $obj);
                }
            } else {
                echo "Error al ejecutar la consulta: " . $base->getError() . "\n";
            }
        } else {
            echo "Error al iniciar la conexión: " . $base->getError() . "\n";
        }

        return $arreglo;
    }



}
?>
