<?php
include_once "empresa.php";

class TestViajes {

    public function testInsertarEmpresa($nombre, $direccion) {
        $empresa = new Empresa();
        $empresa->setNombre($nombre);
        $empresa->setDireccion($direccion);
        if ($empresa->insertar()) {
            echo "Empresa insertada con ID: " . $empresa->getIdEmpresa() . "\n";
        } else {
            echo "Error al insertar: " . $empresa->getMensajeOperacion() . "\n";
        }
    }

    public function testModificarEmpresa($id, $nuevoNombre, $nuevaDireccion) {
        $empresa = new Empresa();
        $empresa->setIdEmpresa($id);
        $empresa->setNombre($nuevoNombre);
        $empresa->setDireccion($nuevaDireccion);
        if ($empresa->modificar()) {
            echo "Empresa modificada.\n";
        } else {
            echo "Error al modificar: " . $empresa->getMensajeOperacion() . "\n";
        }
    }

    public function testEliminarEmpresa($id) {
        $empresa = new Empresa();
        $empresa->setIdEmpresa($id);
        if ($empresa->eliminar()) {
            echo "Empresa eliminada.\n";
        } else {
            echo "Error al eliminar: " . $empresa->getMensajeOperacion() . "\n";
        }
    }
}