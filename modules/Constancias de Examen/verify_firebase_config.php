<?php
/**
 * Verificación de configuración Firebase
 * Módulo: Constancias de Examen
 * Gibbon v29
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use Kreait\Firebase\Factory;

echo "<pre>";

/**
 * 1️⃣ Verificar existencia del archivo de credenciales
 */
$credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';

if (!file_exists($credentialsPath)) {
    die("❌ ERROR: No se encontró el archivo de credenciales:\n$credentialsPath\n");
}

echo "✅ Archivo de credenciales encontrado\n";

/**
 * 2️⃣ Inicializar Firebase
 */
try {
    $factory = (new Factory)
        ->withServiceAccount($credentialsPath);

    $storage = $factory->createStorage();

    echo "✅ Firebase inicializado correctamente\n";
} catch (Exception $e) {
    die("❌ ERROR al inicializar Firebase:\n" . $e->getMessage() . "\n");
}

/**
 * 3️⃣ Verificar acceso al bucket
 * IMPORTANTE: verificar que el nombre coincida con Firebase Console
 */
$bucketName = 'constancias-examen-aac92.firebasestorage.app';

try {
    $bucket = $storage->getBucket($bucketName);

    // Intentar listar un archivo (máx 1)
    foreach ($bucket->objects(['maxResults' => 1]) as $object) {
        echo "📂 Archivo encontrado en bucket: " . $object->name() . "\n";
        break;
    }

    echo "✅ Acceso al bucket verificado\n";
} catch (Exception $e) {
    die("❌ ERROR al acceder al bucket:\n" . $e->getMessage() . "\n");
}

echo "\n🎉 VERIFICACIÓN COMPLETA: Firebase Storage funcionando correctamente\n";
echo "</pre>";
