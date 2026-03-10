<?php

use App\DependencyFactory;

function getStudentDataFromAPI($studentID)
{
    global $connection2;
    return DependencyFactory::createAlumnoService($connection2)->getStudentDataFromAPI($studentID);
}

function formatStudentData($apiData, $studentID)
{
    global $connection2;
    return DependencyFactory::createAlumnoService($connection2)->formatStudentData($apiData, $studentID);
}

function getStudentDNI($gibbonPersonID)
{
    global $connection2;
    return DependencyFactory::createAlumnoService($connection2)->getStudentDNI($gibbonPersonID);
}
