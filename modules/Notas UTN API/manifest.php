<?php
/*
Gibbon: Notas UTN API
Copyright (C) 2024, TuNombre
*/

// Basic variables
$name = "Notas UTN API";
$description = "Módulo para visualizar notas de estudiantes de la UTN desde una API externa.";
$entryURL = "index.php";
$type = "Additional";
$category = "Notas";
$version = "1.0.0";
$author = "Ignacio Garcia";
$url = "";

// No tablas necesarias
$moduleTables = [];

// Acción principal (esto lo hace aparecer en el menú)
$actionRows[] = [
    'name'                      => 'Ver Notas API',
    'precedence'                => '30',
    'category'                  => 'Notas',
    'description'               => 'Visualizar notas de estudiantes de la UTN desde API',
    'URLList'                   => 'index.php',
    'entryURL'                  => 'index.php',
    'entrySidebar'              => 'Y',
    'menuShow'                  => 'Y',
    'defaultPermissionAdmin'    => 'Y',
    'defaultPermissionTeacher'  => 'Y',
    'defaultPermissionStudent'  => 'Y',
    'defaultPermissionParent'   => 'Y',
    'defaultPermissionSupport'  => 'Y',
    'categoryPermissionStaff'   => 'Y',
    'categoryPermissionStudent' => 'Y',
    'categoryPermissionParent'  => 'Y',
    'categoryPermissionOther'   => 'Y',
]; 