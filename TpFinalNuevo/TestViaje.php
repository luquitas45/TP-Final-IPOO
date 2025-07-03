<?php
require_once 'BaseDatos.php';
require_once 'Empresa.php';
require_once 'Responsable.php';
require_once 'Viaje.php';
require_once 'Pasajero.php';
require_once 'ViajePasajero.php';
require_once 'Persona.php';

//pasajeros
function menuPasajero() {
    echo "\n===== PASAJEROS =====\n";
    echo "1. Insertar pasajero\n";
    echo "2. Modificar pasajero\n";
    echo "3. Eliminar pasajero\n";
    echo "4. Ver todos los pasajeros cargados\n";
    echo "5. Salir\n";

    echo "Seleccione una opción: ";
}

function leerEntrada($mensaje) {
    echo $mensaje;
    return trim(fgets(STDIN));
}

function mostrarMenuPasajero() {
    

do {
    menuPasajero();
    $opcion = leerEntrada("");

    switch ($opcion) {
        case 1:
            echo "\n== INSERTAR PASAJERO ==\n";

        // Primero listar personas disponibles para asignar como pasajero (solo activas)
        $personas = Persona::listar();
        if (empty($personas)) {
            echo "No hay personas cargadas para asignar como pasajero.\n";
            break;
        }

        // Mostrar personas
        foreach ($personas as $i => $persona) {
            echo "$i. ID {$persona->getIdpersona()} - {$persona->getNombre()} {$persona->getApellido()}\n";
        }

        $indice = leerEntrada("Seleccione el número de la persona para asignar como pasajero: ");
        if (!isset($personas[$indice])) {
            echo "Índice inválido.\n";
            break;
        }

        $persona = $personas[$indice];

        // Pedir documento y teléfono
        $pdocumento = leerEntrada("Documento del pasajero: ");
        $ptelefono = leerEntrada("Teléfono del pasajero: ");

        $pasajero = new Pasajero();
        $pasajero->setIdpersona($persona->getIdpersona());
        $pasajero->setNombre($persona->getNombre());
        $pasajero->setApellido($persona->getApellido());
        $pasajero->setPdocumento($pdocumento);
        $pasajero->setPtelefono($ptelefono);
        $pasajero->setActivo(1);

        if ($pasajero->insertar()) {
            echo "Pasajero insertado correctamente.\n";
        } else {
            echo "Error al insertar pasajero.\n";
        }
        break;


        case 2:
            echo "\n== MODIFICAR PASAJERO ==\n";
            $pasajeros = Pasajero::listar();
            
            if (empty($pasajeros)) {
                echo "No hay pasajeros activos para modificar.\n";
                break;
            }

            foreach ($pasajeros as $i => $p) {
                echo "$i. {$p->getPdocumento()} - {$p->getNombre()} {$p->getApellido()} ({$p->getPtelefono()})\n";
            }

            $indice = leerEntrada("Seleccione el número del pasajero a modificar: ");
            if (!isset($pasajeros[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $pasajero = $pasajeros[$indice];

            $nombre = leerEntrada("Nuevo nombre [{$pasajero->getNombre()}]: ");
            $apellido = leerEntrada("Nuevo apellido [{$pasajero->getApellido()}]: ");
            $telefono = leerEntrada("Nuevo teléfono [{$pasajero->getPtelefono()}]: ");
            

            $pasajero->setNombre($nombre ?: $pasajero->getNombre());
            $pasajero->setApellido($apellido ?: $pasajero->getApellido());
            $pasajero->setPtelefono($telefono ?: $pasajero->getPtelefono());
            

            if ($pasajero->modificar()) {
                echo "Pasajero modificado correctamente.\n";
            } else {
                echo "Error al modificar pasajero.\n";
            }
            break;


        case 3:
            echo "\n== ELIMINAR PASAJERO ==\n";
            echo "1. Baja lógica\n";
            echo "2. Baja física\n";
            $tipoEliminacion = leerEntrada("Seleccione el tipo de eliminación: ");

            $pasajeros = Pasajero::listar();

            if (empty($pasajeros)) {
                echo "No hay pasajeros activos para eliminar.\n";
                break;
            }

            foreach ($pasajeros as $i => $p) {
                echo "$i. Documento: {$p->getPdocumento()} - Nombre: {$p->getNombre()} {$p->getApellido()}\n";
            }

            $indice = leerEntrada("Seleccione el número del pasajero a eliminar: ");
            if (!isset($pasajeros[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $pasajero = $pasajeros[$indice];

            if ($tipoEliminacion == '1') {
                // Baja lógica
                if ($pasajero->eliminar(false)) {
                    echo "Pasajero dado de baja lógicamente correctamente.\n";
                } else {
                    echo "No se pudo dar de baja lógicamente al pasajero.\n";
                }
            } elseif ($tipoEliminacion == '2') {
                // Baja física
                if ($pasajero->eliminar(true)) {
                    echo "Pasajero eliminado físicamente correctamente.\n";
                } else {
                    echo "No se pudo eliminar físicamente al pasajero (¿tiene viajes activos asociados?).\n";
                }
            } else {
                echo "Opción inválida para tipo de eliminación.\n";
            }
            break;


        case 4:
            echo "\n== LISTA DE PASAJEROS CARGADOS ==\n";
            $pasajeros = Pasajero::listar();

            if (empty($pasajeros)) {
                echo "No hay pasajeros activos en el sistema.\n";
            } else {
                foreach ($pasajeros as $i => $p) {
                    echo ($i + 1) . ". " . $p->__toString() . "\n";
                }
            }
            break;

        case 5:
            echo "Saliendo del menú de pasajeros...\n";
            break;

        default:
            echo "Opción inválida. Intentá de nuevo.\n";
    }
} while ($opcion != 5);
}

function menuResponsable() {
    echo "\n===== MENÚ DE RESPONSABLES =====\n";
    echo "1. Insertar responsable\n";
    echo "2. Modificar responsable\n";
    echo "3. Eliminar responsable físicamente\n";
    echo "4. Eliminar responsable lógicamente (activo = 0)\n";
    echo "5. Ver todos los responsables cargados\n";
    echo "6. Salir\n";
    echo "Seleccione una opción: ";
}


function mostrarMenuResponsable() {
    

do {
    menuResponsable();
    $opcion = leerEntrada("");

    switch ($opcion) {
        case 1:
             case 1:
                echo "\n== INSERTAR RESPONSABLE ==\n";

            // Listar personas activas
            $personas = Persona::listar("activo = 1");

            if (empty($personas)) {
                echo "No hay personas activas para asignar como responsable.\n";
                break;
            }

            echo "Personas disponibles:\n";
            foreach ($personas as $i => $p) {
                echo "$i. ID: {$p->getIdpersona()} - {$p->getNombre()} {$p->getApellido()}\n";
            }

            $indice = leerEntrada("Seleccione el número de la persona a asociar: ");
            if (!isset($personas[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $persona = $personas[$indice];
            $licencia = leerEntrada("Número de licencia: ");

            $responsable = new Responsable();
            $responsable->setIdpersona($persona->getIdpersona());
            $responsable->setNombre($persona->getNombre());
            $responsable->setApellido($persona->getApellido());
            $responsable->setRnumerolicencia($licencia);
            $responsable->setActivo(1);

            if ($responsable->insertar()) {
                echo "Responsable insertado correctamente.\n";
                echo "ID Persona: " . $responsable->getIdpersona() . "\n";
                echo "Número empleado: " . $responsable->getRnumeroempleado() . "\n";
            } else {
                echo "Error al insertar responsable.\n";
            }
            break;


        case 2:
            echo "\n== MODIFICAR RESPONSABLE ==\n";
            $responsables = Responsable::listar();

            if (empty($responsables)) {
                echo "No hay responsables activos para modificar.\n";
                break;
            }

            foreach ($responsables as $i => $r) {
                echo "$i. Empleado Nº {$r->getRnumeroempleado()} - {$r->getNombre()} {$r->getApellido()} | Licencia: {$r->getRnumerolicencia()}\n";
            }

            $indice = leerEntrada("Seleccione el número del responsable a modificar: ");
            if (!isset($responsables[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $responsable = $responsables[$indice];

            // Mostrar valores actuales y pedir nuevos (con opción de mantener)
            $nuevoNombre = leerEntrada("Nuevo nombre [{$responsable->getNombre()}]: ");
            $nuevoApellido = leerEntrada("Nuevo apellido [{$responsable->getApellido()}]: ");
            $nuevaLicencia = leerEntrada("Nueva licencia [{$responsable->getRnumerolicencia()}]: ");

            // Asignar nuevos valores (si se ingresaron)
            $responsable->setNombre($nuevoNombre ?: $responsable->getNombre());
            $responsable->setApellido($nuevoApellido ?: $responsable->getApellido());
            $responsable->setRnumerolicencia($nuevaLicencia !== "" ? $nuevaLicencia : $responsable->getRnumerolicencia());

            if ($responsable->modificar()) {
                echo "Responsable modificado correctamente.\n";
            } else {
                echo "Error al modificar responsable.\n";
            }

            break;


        case 3:
            echo "\n== ELIMINAR RESPONSABLE FÍSICAMENTE ==\n";
            $fisico = $opcion == 3;
                echo "\n== ELIMINAR RESPONSABLE " . ($fisico ? "FÍSICAMENTE" : "LÓGICAMENTE") . " ==\n";

            $responsables = Responsable::listar();
            if (empty($responsables)) {
                echo "No hay responsables activos para eliminar.\n";
                break;
            }

            foreach ($responsables as $i => $r) {
                echo "$i. Empleado Nº {$r->getRnumeroempleado()} - {$r->getNombre()} {$r->getApellido()}\n";
            }

            $indice = leerEntrada("Seleccione el número del responsable a eliminar: ");
            if (!isset($responsables[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $responsable = $responsables[$indice];
            if ($responsable->eliminar($fisico)) {
                echo "Responsable eliminado correctamente.\n";
            } else {
                echo "No se pudo eliminar el responsable.\n";
            }
            break;


        case 4:
             "\n== ELIMINAR RESPONSABLE LÓGICAMENTE ==\n";

            $responsables = Responsable::listar(); // Solo responsables activos
            if (empty($responsables)) {
                echo "No hay responsables activos para eliminar.\n";
                break;
            }

            foreach ($responsables as $i => $r) {
                echo "$i. Empleado Nº {$r->getRnumeroempleado()} - {$r->getNombre()} {$r->getApellido()}\n";
            }

            $indice = leerEntrada("Seleccione el número del responsable a dar de baja: ");
            if (!isset($responsables[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $responsable = $responsables[$indice];

            // Se intenta eliminar lógicamente (activo = 0)
            if ($responsable->eliminar(false)) {
                echo "Responsable dado de baja lógicamente.\n";
            } else {
                echo "No se pudo dar de baja el responsable.\n";
            }
            break;
        case 5:
            echo "\n== LISTA DE RESPONSABLES CARGADOS ==\n";
            $responsables = Responsable::listar();

            if (empty($responsables)) {
                echo "No hay responsables activos en el sistema.\n";
            } else {
                foreach ($responsables as $i => $r) {
                    echo ($i + 1) . ". " . $r->__toString() . "\n";
                }
            }
            break;

        case 6:
            echo "Saliendo del menú de responsables...\n";
            break;

        default:
            echo "Opción inválida. Intentá de nuevo.\n";
    }
} while ($opcion != 6);

}

//empresas

function menuEmpresa() {
    echo "\n===== MENÚ DE EMPRESAS =====\n";
    echo "1. Insertar empresa\n";
    echo "2. Modificar empresa\n";
    echo "3. Eliminar empresa físicamente\n";
    echo "4. Eliminar empresa lógicamente (activo = 0)\n";
    echo "5. Ver todas las empresas cargadas\n";
    echo "6. Salir\n";
    echo "Seleccione una opción: ";
}


function mostrarMenuEmpresa() {
    

do {
    menuEmpresa();
    $opcion = leerEntrada("");

    switch ($opcion) {
        case 1:
            echo "\n== INSERTAR EMPRESA ==\n";
            $nombre = leerEntrada("Nombre de la empresa: ");
            $direccion = leerEntrada("Dirección: ");

            $empresa = new Empresa();
            $empresa->setEnombre($nombre);
            $empresa->setEdireccion($direccion);
            $empresa->setActivo(1);

            if ($empresa->insertar()) {
                echo "Empresa insertada correctamente.\n";
            } else {
                echo "Error al insertar empresa.\n";
            }
            break;

        case 2:
            echo "\n== MODIFICAR EMPRESA ==\n";
            $empresas = Empresa::listar();

            if (empty($empresas)) {
                echo "No hay empresas activas para modificar.\n";
                break;
            }

            foreach ($empresas as $i => $e) {
                echo "$i. {$e->getIdempresa()} - {$e->getEnombre()} ({$e->getEdireccion()})\n";
            }

            $indice = leerEntrada("Seleccione el número de la empresa a modificar: ");
            if (!isset($empresas[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $empresa = $empresas[$indice];

            $nombre = leerEntrada("Nuevo nombre [{$empresa->getEnombre()}]: ");
            $direccion = leerEntrada("Nueva dirección [{$empresa->getEdireccion()}]: ");
            

            $empresa->setEnombre($nombre ?: $empresa->getEnombre());
            $empresa->setEdireccion($direccion ?: $empresa->getEdireccion());
            

            if ($empresa->modificar()) {
                echo "Empresa modificada correctamente.\n";
            } else {
                echo "Error al modificar empresa.\n";
            }
            break;

        case 3:
            echo "\n== ELIMINAR EMPRESA FÍSICAMENTE ==\n";
            $empresas = Empresa::listar();

            if (empty($empresas)) {
                echo "No hay empresas activas para eliminar.\n";
                break;
            }

            foreach ($empresas as $i => $e) {
                echo "$i. {$e->getIdempresa()} - {$e->getEnombre()} ({$e->getEdireccion()})\n";
            }

            $indice = leerEntrada("Seleccione el número de la empresa a eliminar: ");
            if (!isset($empresas[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $empresa = $empresas[$indice];

            if ($empresa->eliminar()) {
                echo "Empresa eliminada de la base de datos.\n";
            } else {
                echo "Error al eliminar empresa.\n";
            }
            break;

        case 4:
            echo "\n== ELIMINAR EMPRESA LÓGICAMENTE ==\n";
            $empresas = Empresa::listar();

            if (empty($empresas)) {
                echo "No hay empresas activas para dar de baja.\n";
                break;
            }

            foreach ($empresas as $i => $e) {
                echo "$i. {$e->getIdempresa()} - {$e->getEnombre()} ({$e->getEdireccion()})\n";
            }

            $indice = leerEntrada("Seleccione el número de la empresa a dar de baja lógicamente: ");
            if (!isset($empresas[$indice])) {
                echo "Índice inválido.\n";
                break;
            }

            $empresa = $empresas[$indice];
            $empresa->setActivo(0);

            if ($empresa->modificar()) {
                echo "Empresa desactivada correctamente (baja lógica).\n";
            } else {
                echo "Error al dar de baja la empresa.\n";
            }
            break;

        case 5:
            echo "\n== LISTA DE EMPRESAS CARGADAS ==\n";
            $empresas = Empresa::listar();

            if (empty($empresas)) {
                echo "No hay empresas activas en el sistema.\n";
            } else {
                foreach ($empresas as $i => $e) {
                    echo ($i + 1) . ". " . $e->__toString() . "\n";
                }
            }
            break;

        case 6:
            echo "Saliendo del menú de empresas...\n";
            break;

        default:
            echo "Opción inválida. Intentá de nuevo.\n";
    }
} while ($opcion != 6);
}

//viajes


function menuViaje() {
    echo "\n--- MENÚ VIAJE ---\n";
    echo "1. Listar viajes\n";
    echo "2. Insertar viaje\n";
    echo "3. Modificar viaje\n";
    echo "4. Eliminar viaje\n";
    echo "5. Salir\n";
    echo "Elige una opción: ";
}


function MostrarMenuViaje() {
    do {
        menuViaje();
        $opcion = leerEntrada("");

        switch ($opcion) {
            case '1':
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes cargados.\n";
                } else {
                    foreach ($viajes as $v) {
                        echo $v . "\n";
                    }
                }
                break;
            case '2':
                $v = new Viaje();
                $v->setVdestino(leerEntrada("Destino: "));
                $v->setVcantmaxpasajeros((int)leerEntrada("Cantidad máxima de pasajeros: "));
                
                echo "\n--- EMPRESAS DISPONIBLES ---\n";
                $empresas = Empresa::listar(); 
                if (empty($empresas)) {
                    echo "No hay empresas cargadas. No se puede continuar.\n";
                    break;
                }
                foreach ($empresas as $e) {
                    echo "ID: {$e->getIdempresa()} - Nombre: {$e->getEnombre()} - Dirección: {$e->getEdireccion()}\n";
    }
                
                $idempresa = (int)leerEntrada("ID Empresa: ");
                $empresa = new Empresa();
                $empresa->setIdempresa($idempresa);
                $v->setEmpresa($empresa);

                 echo "\n--- RESPONSABLES DISPONIBLES ---\n";
                $responsables = Responsable::listar(); 
                if (empty($responsables)) {
                    echo "No hay responsables cargados. No se puede continuar.\n";
                    break;
                }
                foreach ($responsables as $r) {
                    echo "N° Empleado: {$r->getRnumeroempleado()} - Nombre: {$r->getNombre()} {$r->getApellido()} - Licencia: {$r->getRnumerolicencia()}\n";
    }

                $idresponsable = (int)leerEntrada("Número de empleado responsable: ");
                $responsable = new Responsable();
                $responsable->setRnumeroempleado($idresponsable);
                $v->setResponsable($responsable);

                $v->setVimporte((float)leerEntrada("Importe: "));
                $v->setActivo(1);

                if ($v->insertar()) {
                    echo "Viaje insertado correctamente.\n";
                } else {
                    echo "Error al insertar viaje.\n";
                }
                break;
            case '3':
                echo "\n--- VIAJES DISPONIBLES ---\n";
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes cargados actualmente.\n";
                    break;
                }
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()} - Empresa ID: {$v->getEmpresa()->getIdempresa()} - Responsable N°: {$v->getResponsable()->getRnumeroempleado()} - Cupo: {$v->getVcantmaxpasajeros()} - Importe: {$v->getVimporte()}\n";
                }
                $idviaje = (int)leerEntrada("ID del viaje a modificar: ");

                $viajes = Viaje::listar();
                $viajeAModificar = null;

                foreach ($viajes as $v) {
                    if ($v->getIdviaje() == $idviaje) {
                        $viajeAModificar = $v;
                        break;
                    }
                }

                if (!$viajeAModificar) {
                    echo "No se encontró el viaje con ID $idviaje.\n";
                    break;
                }

                echo "\n--- Modificando viaje a {$viajeAModificar->getVdestino()} ---\n";

                $viajeAModificar->setVdestino(leerEntrada("Nuevo destino (actual: {$viajeAModificar->getVdestino()}): "));
                $viajeAModificar->setVcantmaxpasajeros((int)leerEntrada("Nueva cantidad máxima de pasajeros (actual: {$viajeAModificar->getVcantmaxpasajeros()}): "));

                
                echo "\n--- EMPRESAS DISPONIBLES ---\n";
                $empresas = Empresa::listar();
                if (empty($empresas)) {
                    echo "No hay empresas disponibles. No se puede continuar.\n";
                    break;
                }
                foreach ($empresas as $e) {
                    echo "ID: {$e->getIdempresa()} - Nombre: {$e->getEnombre()} - Dirección: {$e->getEdireccion()}\n";
                }
                $idempresa = (int)leerEntrada("Nuevo ID Empresa (actual: {$viajeAModificar->getEmpresa()->getIdempresa()}): ");
                $empresa = new Empresa();
                $empresa->setIdempresa($idempresa);
                $viajeAModificar->setEmpresa($empresa);

                
                echo "\n--- RESPONSABLES DISPONIBLES ---\n";
                $responsables = Responsable::listar();
                if (empty($responsables)) {
                    echo "No hay responsables disponibles. No se puede continuar.\n";
                    break;
                }
                foreach ($responsables as $r) {
                    echo "N° Empleado: {$r->getRnumeroempleado()} - Nombre: {$r->getNombre()} {$r->getApellido()} - Licencia: {$r->getRnumerolicencia()}\n";
                }
                $idresponsable = (int)leerEntrada("Nuevo número empleado responsable (actual: {$viajeAModificar->getResponsable()->getRnumeroempleado()}): ");
                $responsable = new Responsable();
                $responsable->setRnumeroempleado($idresponsable);
                $viajeAModificar->setResponsable($responsable);

                $viajeAModificar->setVimporte((float)leerEntrada("Nuevo importe (actual: {$viajeAModificar->getVimporte()}): "));
                $viajeAModificar->setActivo((int)leerEntrada("¿Activo? 1=Sí, 0=No (actual: {$viajeAModificar->getActivo()}): "));

                if ($viajeAModificar->modificar()) {
                    echo "Viaje modificado correctamente.\n";
                } else {
                    echo "Error al modificar viaje.\n";
                }
                break;

            case '4':
                echo "\n--- VIAJES DISPONIBLES ---\n";
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes cargados actualmente.\n";
                    break;
                }
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()} - Empresa ID: {$v->getEmpresa()->getIdempresa()} - Responsable N°: {$v->getResponsable()->getRnumeroempleado()} - Cupo: {$v->getVcantmaxpasajeros()} - Importe: {$v->getVimporte()}\n";
                }
                $idviaje = (int)leerEntrada("ID del viaje a eliminar: ");

                $viajes = Viaje::listar();
                $viajeAEliminar = null;
                foreach ($viajes as $v) {
                    if ($v->getIdviaje() == $idviaje) {
                        $viajeAEliminar = $v;
                        break;
                    }
                }
                if (!$viajeAEliminar) {
                    echo "No se encontró el viaje con ID $idviaje.\n";
                    break;
                }
                if ($viajeAEliminar->eliminar()) {
                    echo "Viaje eliminado correctamente.\n";
                } else {
                    echo "Error al eliminar viaje.\n";
                }
                break;
            case '5':
                echo "Saliendo de menú Viaje...\n";
                break;
            default:
                echo "Opción inválida.\n";
        }

    } while ($opcion != '5');
}


//tabla intermedia viajePasajero


function menuViajePasajero() {
    echo "\n--- MENÚ VIAJE-PASAJERO ---\n";
    echo "1. Listar pasajeros por viaje\n";
    echo "2. Insertar pasajero en viaje\n";
    echo "3. Modificar relación viaje-pasajero\n";
    echo "4. Eliminar pasajero de viaje\n";
    echo "5. Salir\n";
    echo "Elige una opción: ";
}



function mostrarMenuViajePasajero() {
    do {
        menuViajePasajero();
        $opcion = leerEntrada("");

        switch ($opcion) {
            case '1':
                echo "\n--- VIAJES DISPONIBLES ---\n";
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes disponibles.\n";
                    break;
                }
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()} - Importe: {$v->getVimporte()} - Cupo: {$v->getVcantmaxpasajeros()}\n";
                }
                $idviaje = (int)leerEntrada("ID del viaje para listar pasajeros: ");
                $lista = ViajePasajero::listarPorViaje($idviaje);
                if (empty($lista)) {
                    echo "No hay pasajeros para el viaje con ID $idviaje.\n";
                } else {
                    foreach ($lista as $vp) {
                        $p = $vp->getPasajero();
                        echo "Pasajero: {$p->getPdocumento()} - {$p->getNombre()} {$p->getApellido()}\n";
                    }
                }
                break;
            case '2':
                echo "\n--- VIAJES DISPONIBLES ---\n";
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes disponibles.\n";
                    break;
                }
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()} - Cupo: {$v->getVcantmaxpasajeros()} - Importe: {$v->getVimporte()}\n";
                }
                $viajeId = (int)leerEntrada("ID del viaje: ");

               
                $viaje = null;
                foreach ($viajes as $v) {
                    if ($v->getIdviaje() == $viajeId) {
                        $viaje = $v;
                        break;
                    }
                }

                if (!$viaje) {
                    echo "No se encontró el viaje.\n";
                    break;
                }

                echo "\n--- PASAJEROS DISPONIBLES ---\n";
                $pasajeros = Pasajero::listar();
                if (empty($pasajeros)) {
                    echo "No hay pasajeros disponibles.\n";
                    break;
                }
                foreach ($pasajeros as $p) {
                    echo "Documento: {$p->getPdocumento()} - Nombre: {$p->getNombre()} {$p->getApellido()}\n";
                }
                $pasajeroDoc = leerEntrada("Documento del pasajero: ");

               
                $pasajero = null;
                foreach ($pasajeros as $p) {
                    if ($p->getPdocumento() == $pasajeroDoc) {
                        $pasajero = $p;
                        break;
                    }
                }

                if (!$pasajero) {
                    echo "No se encontró el pasajero.\n";
                    break;
                }

               
                $yaRegistrado = false;
                $pasajerosEnViaje = ViajePasajero::listarPorViaje($viajeId);
                foreach ($pasajerosEnViaje as $vp) {
                    if ($vp->getPasajero()->getPdocumento() == $pasajeroDoc) {
                        $yaRegistrado = true;
                        break;
                    }
                }

                if ($yaRegistrado) {
                    echo "Ese pasajero ya está registrado en ese viaje.\n";
                    break;
                }

                
                if (count($pasajerosEnViaje) >= $viaje->getVcantmaxpasajeros()) {
                    echo "El viaje ya está lleno. No se puede agregar más pasajeros.\n";
                    break;
                }

                
                $vp = new ViajePasajero($viaje, $pasajero);
                if ($vp->insertar()) {
                    echo "Pasajero agregado al viaje correctamente.\n";
                } else {
                    echo "Error al agregar pasajero al viaje.\n";
                }
                break;

            case '3':
                

                echo "\n--- VIAJES DISPONIBLES ---\n";
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes disponibles.\n";
                    break;
                }
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()}\n";
                }

                $idviajeOld = (int)leerEntrada("ID del viaje actual: ");

                echo "\n--- PASAJEROS EN ESE VIAJE ---\n";
                $pasajerosEnViaje = ViajePasajero::listarPorViaje($idviajeOld);
                if (empty($pasajerosEnViaje)) {
                    echo "No hay pasajeros en ese viaje.\n";
                    break;
                }
                foreach ($pasajerosEnViaje as $vp) {
                    $p = $vp->getPasajero();
                    echo "Documento: {$p->getPdocumento()} - Nombre: {$p->getNombre()} {$p->getApellido()}\n";
                }

                $pdocumentoOld = leerEntrada("Documento del pasajero actual: ");

               
                echo "\n--- VIAJES DISPONIBLES PARA CAMBIO ---\n";
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()}\n";
                }
                $idviajeNew = (int)leerEntrada("Nuevo ID del viaje: ");

                echo "\n--- PASAJEROS DISPONIBLES PARA CAMBIO ---\n";
                $pasajeros = Pasajero::listar();
                foreach ($pasajeros as $p) {
                    echo "Documento: {$p->getPdocumento()} - Nombre: {$p->getNombre()} {$p->getApellido()}\n";
                }
                $pdocumentoNew = leerEntrada("Nuevo documento del pasajero: ");

                $viajeNew = new Viaje();
                $viajeNew->setIdviaje($idviajeNew);

                $pasajeroNew = new Pasajero();
                $pasajeroNew->setPdocumento($pdocumentoNew);

                $vp = new ViajePasajero($viajeNew, $pasajeroNew);

                if ($vp->modificar($idviajeOld, $pdocumentoOld)) {
                    echo "Relación modificada correctamente.\n";
                } else {
                    echo "Error al modificar relación.\n";
                }
                break;

           case '4':
                echo "\n--- VIAJES DISPONIBLES ---\n";
                $viajes = Viaje::listar();
                if (empty($viajes)) {
                    echo "No hay viajes cargados.\n";
                    break;
                }
                foreach ($viajes as $v) {
                    echo "ID: {$v->getIdviaje()} - Destino: {$v->getVdestino()}\n";
                }
                $idviaje = (int)leerEntrada("ID del viaje: ");

                echo "\n--- PASAJEROS EN EL VIAJE ---\n";
                $pasajerosEnViaje = ViajePasajero::listarPorViaje($idviaje);
                if (empty($pasajerosEnViaje)) {
                    echo "No hay pasajeros registrados en este viaje.\n";
                    break;
                }
                foreach ($pasajerosEnViaje as $vp) {
                    $p = $vp->getPasajero();
                    echo "Documento: {$p->getPdocumento()} - Nombre: {$p->getNombre()} {$p->getApellido()}\n";
                }
                $pdocumento = leerEntrada("Documento del pasajero a eliminar: ");

                $viaje = new Viaje();
                $viaje->setIdviaje($idviaje);

                $pasajero = new Pasajero();
                $pasajero->setPdocumento($pdocumento);

                $vp = new ViajePasajero($viaje, $pasajero);
                if ($vp->eliminar()) {
                    echo "Pasajero eliminado del viaje correctamente.\n";
                } else {
                    echo "Error al eliminar pasajero del viaje.\n";
                }
                break;

            case '5':
                echo "Saliendo de menú Viaje-Pasajero...\n";
                break;
            default:
                echo "Opción inválida.\n";
        }

    } while ($opcion != '5');
}

function menuPersona() {
    echo "\n--- MENÚ PERSONAS ---\n";
    echo "1. Insertar persona\n";
    echo "2. Modificar persona\n";
    echo "3. Eliminar persona\n";
    echo "4. Listar personas\n";
    echo "5. Salir\n";
    echo "Elige una opción: ";
}

    function mostrarMenuPersona() {
        do {
            menuPersona();
            $opcion = trim(fgets(STDIN));

            switch ($opcion) {
                case 1:
                    echo "\n== INSERTAR PERSONA ==\n";
                $nombre = leerEntrada("Nombre: ");
                $apellido = leerEntrada("Apellido: ");

                $persona = new Persona();
                $persona->setNombre($nombre);
                $persona->setApellido($apellido);
                $persona->setActivo(1);

                if ($persona->insertar()) {
                    echo "Persona insertada correctamente con ID: " . $persona->getIdpersona() . "\n";
                } else {
                    echo "Error al insertar persona.\n";
                }
                break;
                case 2:
                    echo "\n== MODIFICAR PERSONA ==\n";
                $personas = Persona::listar();

                if (empty($personas)) {
                    echo "No hay personas activas para modificar.\n";
                    break;
                }

                foreach ($personas as $i => $p) {
                    echo "$i. {$p->getIdpersona()} - {$p->getNombre()} {$p->getApellido()}\n";
                }

                $indice = leerEntrada("Seleccione el número de la persona a modificar: ");
                if (!isset($personas[$indice])) {
                    echo "Índice inválido.\n";
                    break;
                }

                $persona = $personas[$indice];

                $nombre = leerEntrada("Nuevo nombre [{$persona->getNombre()}]: ");
                $apellido = leerEntrada("Nuevo apellido [{$persona->getApellido()}]: ");

                $persona->setNombre($nombre ?: $persona->getNombre());
                $persona->setApellido($apellido ?: $persona->getApellido());

                if ($persona->modificar()) {
                    echo "Persona modificada correctamente.\n";
                } else {
                    echo "Error al modificar persona.\n";
                }
                break;
                case 3:
                    echo "\n=== TEST ELIMINAR PERSONA ===\n";

                $personas = Persona::listar();

                if (empty($personas)) {
                    echo "No hay personas cargadas para eliminar.\n";
                    return;
                }

                foreach ($personas as $i => $p) {
                    $status = $p->getActivo() ? "Activo" : "Inactivo";
                    echo "$i. ID: {$p->getIdpersona()} - {$p->getNombre()} {$p->getApellido()} - $status\n";
                }

                $indice = (int) leerEntrada("Seleccione el número de la persona a eliminar: ");

                if (!isset($personas[$indice])) {
                    echo "Índice inválido.\n";
                    return;
                }

                $persona = $personas[$indice];

                echo "¿Tipo de eliminación?\n";
                echo "1. Baja lógica (activo = 0)\n";
                echo "2. Eliminación física (borrado de base de datos)\n";
                $tipo = (int) leerEntrada("Seleccione opción: ");

                if ($tipo === 1) {
                    $resultado = $persona->eliminar(false);
                    if ($resultado) {
                        echo "Persona dada de baja (borrado lógico) correctamente.\n";
                    } else {
                        echo "No se pudo dar de baja a la persona.\n";
                    }
                } elseif ($tipo === 2) {
                    $resultado = $persona->eliminar(true);
                    if ($resultado) {
                        echo "Persona eliminada físicamente correctamente.\n";
                    } else {
                        echo "No se pudo eliminar físicamente a la persona.\n";
                    }
                } else {
                    echo "Opción inválida.\n";
                }

                case 4:
                    echo "\n== LISTA DE PERSONAS CARGADAS ==\n";
                $personas = Persona::listar();

                if (empty($personas)) {
                    echo "No hay personas activas en el sistema.\n";
                } else {
                    foreach ($personas as $i => $p) {
                        echo ($i + 1) . ". " . $p->getIdpersona() . " - " . $p->getNombre() . " " . $p->getApellido() . " (Activo: " . ($p->getActivo() ? "Sí" : "No") . ")\n";
                    }
                }
                break;
                case 5:
                    echo "Saliendo...\n";
                    break;

                default:
                    echo "Opcion Invalida.\n";
                }
            echo "\n";
            } while ($opcion != 5);
        }



function menuPrincipal() {
    echo "\n===== MENÚ PRINCIPAL =====\n";
    echo "1. Gestionar Pasajeros\n";
    echo "2. Gestionar Responsables\n";
    echo "3. Gestionar Empresas\n";
    echo "4. Gestionar Viajes\n";
    echo "5. Gestionar Pasajeros por Viaje\n";
    echo "6. Gestionar Personas\n";
    echo "7. Salir\n";
    echo "Seleccione una opción: ";
}


do {
    menuPrincipal();
    $opcion = leerEntrada("");

    switch ($opcion) {
        case 1:
            mostrarMenuPasajero();
            break;
        case 2:
            mostrarMenuResponsable();
            break;
        case 3:
            mostrarMenuEmpresa();
            break;
        case 4:
            mostrarMenuViaje();
            break;
        case 5:
            mostrarMenuViajePasajero();
            break;
        case 6:
            mostrarMenuPersona();
            break;
        case 7:
            echo "Cerrando sistema.\n";
            break;
        default:
            echo "Opción inválida. Intente de nuevo.\n";
    }
} while ($opcion != 7);
?>
