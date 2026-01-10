<?php
require_once 'moduleFunctions.php';
try {
$constancias = getAllConstancias();
echo "✅ Firestore OK. Cantidad: " . count($constancias);
$cred = json_decode(file_get_contents(__DIR__ .
'/credentials/firebase-credentials.json'), true);
echo "✅ Proyecto: " . $cred['project_id'];
} catch (Exception $e) {
echo "❌ Error: " . $e->getMessage();
}
?>