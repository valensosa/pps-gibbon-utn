<?php
// Include the student view
$studentViewFile = __DIR__ . '/studentView/student_constancias.php';
if (file_exists($studentViewFile)) {
    include $studentViewFile;
} else {
    echo "Error: No se pudo cargar la vista de estudiante.";
} 