<?php
include_once "pasajero.php";
include_once "responsableV.php";
include_once "viaje.php";
include_once "empresa.php";
include_once "TestViajes.php";

$test = new TestViajes();

// Esto agrega una empresa
$test->agregarEmpresa("Transporte La Matanza", "Avenida los gallegos 888");

// Esto cambia la empresa con id 1
$test->modificarEmpresa(1, "Transporte Actualizado", "Avenida Siempre Viva 742");

// Esto elimina la empresa de id 2
$test->eliminarEmpresa(2);

?>