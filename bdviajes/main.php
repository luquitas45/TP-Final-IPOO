<?php
include_once "TestViajes.php";

$test = new TestViajes();

// Insertar una nueva empresa
$test->testInsertarEmpresa("Viajes del Sur", "Av. Libertador 1000");

// Modificar (reemplazar con un ID real)
$test->testModificarEmpresa(1, "Viajes del Norte", "Av. Colón 123");

// Eliminar (reemplazar con un ID real)
$test->testEliminarEmpresa(1);