<?php
/*
Gibbon: Constancias de Examen
Copyright (C) 2024
*/

// Basic variables
$name = "Constancias de Examen";
$description = "Módulo para gestionar solicitudes y entrega de constancias de examen.";
$entryURL = "admin_constancias.php";
$type = "Additional";
$category = "Notas";
$version = "1.0.0";
$author = "Ignacio Garcia";
$url = "";

// No tables needed as we're using Firebase
$moduleTables = [];

// Main actions for the module
$actionRows[] = [
    'name'                      => 'Gestionar Constancias',
    'precedence'                => '0',
    'category'                  => 'Notas',
    'description'               => 'Ver y gestionar todas las solicitudes de constancias de examen',
    'URLList'                   => 'admin_constancias.php',
    'entryURL'                  => 'admin_constancias.php',
    'entrySidebar'              => 'Y',
    'menuShow'                  => 'Y',
    'defaultPermissionAdmin'    => 'Y',
    'defaultPermissionTeacher'  => 'N',
    'defaultPermissionStudent'  => 'N',
    'defaultPermissionParent'   => 'N',
    'defaultPermissionSupport'  => 'Y',
    'categoryPermissionStaff'   => 'Y',
    'categoryPermissionStudent' => 'N',
    'categoryPermissionParent'  => 'N',
    'categoryPermissionOther'   => 'N',
];

$actionRows[] = [
    'name'                      => 'Mis Constancias',
    'precedence'                => '1',
    'category'                  => 'Notas',
    'description'               => 'Solicitar y ver el estado de tus constancias de examen',
    'URLList'                   => 'student_constancias.php',
    'entryURL'                  => 'student_constancias.php',
    'entrySidebar'              => 'Y',
    'menuShow'                  => 'Y',
    'defaultPermissionAdmin'    => 'N',
    'defaultPermissionTeacher'  => 'N',
    'defaultPermissionStudent'  => 'Y',
    'defaultPermissionParent'   => 'N',
    'defaultPermissionSupport'  => 'N',
    'categoryPermissionStaff'   => 'N',
    'categoryPermissionStudent' => 'Y',
    'categoryPermissionParent'  => 'N',
    'categoryPermissionOther'   => 'N',
]; 