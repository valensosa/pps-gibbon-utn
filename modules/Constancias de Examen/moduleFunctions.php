<?php

function getStudentConstancias($dniAlumno) {
    $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $projectId = $credentials['project_id'];
    $accessToken = getGoogleAccessToken();
    if (!$accessToken) {
        throw new Exception('No se pudo obtener el access token de Google.');
    }
    $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents:runQuery";
    $query = [
        'structuredQuery' => [
            'from' => [['collectionId' => 'constancias']],
            'where' => [
                'fieldFilter' => [
                    'field' => ['fieldPath' => 'dniAlumno'],
                    'op' => 'EQUAL',
                    'value' => ['stringValue' => $dniAlumno]
                ]
            ]
        ]
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        throw new Exception('Error al obtener constancias: ' . ($error['error']['message'] ?? $response));
    }
    $results = json_decode($response, true);
    $constancias = [];
    foreach ($results as $item) {
        if (isset($item['document'])) {
            $constancias[] = $item['document'];
        }
    }
    return $constancias;
}

function getAllConstancias() {
    $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $projectId = $credentials['project_id'];
    $accessToken = getGoogleAccessToken();
    if (!$accessToken) {
        throw new Exception('No se pudo obtener el access token de Google.');
    }
    $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents:runQuery";
    $query = [
        'structuredQuery' => [
            'from' => [['collectionId' => 'constancias']],
            'orderBy' => [
                [
                    'field' => ['fieldPath' => 'fechaPedido'],
                    'direction' => 'DESCENDING'
                ]
            ]
        ]
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        throw new Exception('Error al obtener constancias: ' . ($error['error']['message'] ?? $response));
    }
    $results = json_decode($response, true);
    $constancias = [];
    foreach ($results as $item) {
        if (isset($item['document'])) {
            $constancias[] = $item['document'];
        }
    }
    return $constancias;
}

// Helper: obtener access token de Google usando JWT
function getGoogleAccessToken() {
    $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $now = time();
    $header = [
        'alg' => 'RS256',
        'typ' => 'JWT'
    ];
    $claimSet = [
        'iss' => $credentials['client_email'],
        'scope' => 'https://www.googleapis.com/auth/cloud-platform',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => $now + 3600,
        'iat' => $now
    ];
    $jwtHeader = str_replace('=', '', strtr(base64_encode(json_encode($header)), '+/', '-_'));
    $jwtClaim = str_replace('=', '', strtr(base64_encode(json_encode($claimSet)), '+/', '-_'));
    $jwtInput = $jwtHeader . '.' . $jwtClaim;
    openssl_sign($jwtInput, $jwtSig, $credentials['private_key'], 'sha256');
    $jwtSig = str_replace('=', '', strtr(base64_encode($jwtSig), '+/', '-_'));
    $jwt = $jwtInput . '.' . $jwtSig;
    $postFields = http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]);
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['access_token'] ?? null;
}

function createConstancia($data) {
    $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $projectId = $credentials['project_id'];
    $accessToken = getGoogleAccessToken();
    if (!$accessToken) {
        throw new Exception('No se pudo obtener el access token de Google.');
    }
    $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/constancias";
    $fechaPedido = $data['fechaPedido'] ?? date('Y-m-d');
    $fechaExamen = $data['fechaExamen'];
    $document = [
        'fields' => [
            'dniAlumno' => ['stringValue' => $data['dniAlumno']],
            'nombre' => ['stringValue' => $data['nombre']],
            'email' => ['stringValue' => $data['email']],
            'estado' => ['stringValue' => 'pendiente'],
            'fechaPedido' => ['stringValue' => $fechaPedido],
            'presentarAnte' => ['stringValue' => $data['presentarAnte']],
            'examen' => [
                'mapValue' => [
                    'fields' => [
                        'materia' => ['stringValue' => $data['materia']],
                        'fechaExamen' => ['stringValue' => $fechaExamen]
                    ]
                ]
            ],
            'pdfUrl' => ['stringValue' => '']
        ]
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($document));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        throw new Exception('Error al crear constancia: ' . ($error['error']['message'] ?? $response));
    }
    return json_decode($response, true);
}

function uploadConstanciaPDF($constanciaId, $dniAlumno, $materia, $filePath) {
    $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
    if (!file_exists($credentialsPath)) {
        throw new Exception('Archivo de credenciales no encontrado');
    }
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $filename = generatePdfFilename($dniAlumno, $materia);
    $token = getGoogleAccessToken();
    if (!$token) {
        throw new Exception('No se pudo obtener el token de acceso');
    }
    //modificar por bucket de Firebase storage
    $bucket = 'constancias-examen-aac92.firebasestorage.app';


    // Usar uploadType=media para enviar el archivo como binario
    $url = "https://storage.googleapis.com/upload/storage/v1/b/{$bucket}/o?uploadType=media&name=" . urlencode($filename);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/pdf"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($filePath));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        throw new Exception('Error al subir PDF: ' . $response);
    }

    $publicUrl = "https://firebasestorage.googleapis.com/v0/b/{$bucket}/o/" . urlencode($filename) . "?alt=media";
    // Actualizar documento en Firestore
    $docRef = "constancias/{$constanciaId}";
    $data = [
        'estado' => 'completado',
        'pdfUrl' => $publicUrl,
        'fechaSubida' => ['timestampValue' => date('c')]
    ];
    updateFirestoreDocument($docRef, $data);
    return $publicUrl;
}

// Función de notificación eliminada - no se envían notificaciones automáticas

// Helper para convertir documento Firestore REST a array plano
function parseFirestoreDocument($doc) {
    $fields = $doc['fields'] ?? [];
    $data = [];
    foreach ($fields as $key => $value) {
        // Detectar tipo de valor
        if (isset($value['stringValue'])) {
            $data[$key] = $value['stringValue'];
        } elseif (isset($value['timestampValue'])) {
            $data[$key] = $value['timestampValue'];
        } elseif (isset($value['mapValue'])) {
            $data[$key] = [];
            foreach (($value['mapValue']['fields'] ?? []) as $subKey => $subValue) {
                if (isset($subValue['stringValue'])) {
                    $data[$key][$subKey] = $subValue['stringValue'];
                } elseif (isset($subValue['timestampValue'])) {
                    $data[$key][$subKey] = $subValue['timestampValue'];
                }
            }
        }
    }
    return $data;
}

// Helper para extraer el ID del documento Firestore REST
function getFirestoreDocumentId($doc) {
    if (!isset($doc['name'])) return '';
    $parts = explode('/', $doc['name']);
    return end($parts);
}

// Helper para formatear timestamps de Firestore
function formatTimestamp($timestamp) {
    if (empty($timestamp)) return '';
    // Si es formato Y-m-d, mostrar como d/m/Y
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $timestamp)) {
        $dt = DateTime::createFromFormat('Y-m-d', $timestamp);
        return $dt ? $dt->format('d/m/Y') : $timestamp;
    }
    // Firestore REST puede devolver timestamps en formato RFC3339/ISO8601
    $dt = DateTime::createFromFormat(DateTime::ATOM, $timestamp);
    if (!$dt) {
        $dt = strtotime($timestamp);
        if ($dt === false) return $timestamp;
        return date('d/m/Y', $dt);
    }
    return $dt->format('d/m/Y');
}

function generatePdfFilename($dniAlumno, $materia) {
    $timestamp = time();
    $safeMateria = preg_replace('/[^a-zA-Z0-9]/', '_', $materia);
    return "constancia_{$dniAlumno}_{$safeMateria}_{$timestamp}.pdf";
}

function updateFirestoreDocument($docRef, $data) {
    $credentialsPath = __DIR__ . '/credentials/firebase-credentials.json';
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    $projectId = $credentials['project_id'];
    $accessToken = getGoogleAccessToken();
    if (!$accessToken) {
        throw new Exception('No se pudo obtener el access token de Google.');
    }
    $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/$docRef?updateMask.fieldPaths=estado&updateMask.fieldPaths=pdfUrl&updateMask.fieldPaths=fechaSubida";
    $fields = [];
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $fields[$key] = $value;
        } else {
            $fields[$key] = ['stringValue' => $value];
        }
    }
    $patchData = ['fields' => $fields];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($patchData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        throw new Exception('Error al actualizar constancia: ' . ($error['error']['message'] ?? $response));
    }
    return json_decode($response, true);
} 