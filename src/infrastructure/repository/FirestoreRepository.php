<?php
namespace App\infrastructure\repository;

class FirestoreRepository
{
    private string $projectId;
    private string $credentialsPath;

    public function __construct(string $credentialsPath)
    {
        $this->credentialsPath = $credentialsPath;
        $credentials = json_decode(file_get_contents($credentialsPath), true);
        $this->projectId = $credentials['project_id'];
    }

    // ─────────────────────────────────────────
    // Queries
    // ─────────────────────────────────────────

    public function getByDni(string $dniAlumno): array
    {
        $query = [
            'structuredQuery' => [
                'from' => [['collectionId' => 'constancias']],
                'where' => [
                    'fieldFilter' => [
                        'field' => ['fieldPath' => 'dniAlumno'],
                        'op'    => 'EQUAL',
                        'value' => ['stringValue' => $dniAlumno]
                    ]
                ]
            ]
        ];

        return $this->runQuery($query);
    }

    public function getAll(): array
    {
        $query = [
            'structuredQuery' => [
                'from'    => [['collectionId' => 'constancias']],
                'orderBy' => [[
                    'field'     => ['fieldPath' => 'fechaPedido'],
                    'direction' => 'DESCENDING'
                ]]
            ]
        ];

        return $this->runQuery($query);
    }

    public function create(array $data): array
    {
        $url = $this->buildUrl('constancias');

        $document = [
            'fields' => [
                'dniAlumno'    => ['stringValue' => $data['dniAlumno']],
                'nombre'       => ['stringValue' => $data['nombre']],
                'email'        => ['stringValue' => $data['email']],
                'estado'       => ['stringValue' => 'pendiente'],
                'fechaPedido'  => ['stringValue' => $data['fechaPedido'] ?? date('Y-m-d')],
                'presentarAnte'=> ['stringValue' => $data['presentarAnte']],
                'examen'       => [
                    'mapValue' => [
                        'fields' => [
                            'materia'    => ['stringValue' => $data['materia']],
                            'fechaExamen'=> ['stringValue' => $data['fechaExamen']]
                        ]
                    ]
                ],
                'pdfUrl' => ['stringValue' => '']
            ]
        ];

        $response = $this->request($url, 'POST', $document);
        return $response;
    }

    public function update(string $docRef, array $data): array
    {
        $url = $this->buildUrl($docRef)
             . '?updateMask.fieldPaths=estado'
             . '&updateMask.fieldPaths=pdfUrl'
             . '&updateMask.fieldPaths=fechaSubida';

        $fields = [];
        foreach ($data as $key => $value) {
            $fields[$key] = is_array($value)
                ? $value
                : ['stringValue' => $value];
        }

        return $this->request($url, 'PATCH', ['fields' => $fields]);
    }

    public function uploadPdf(string $constanciaId, string $dniAlumno, string $materia, string $filePath): string
    {
        $filename = $this->generatePdfFilename($dniAlumno, $materia);
        $bucket   = 'constancias-examen-aac92.firebasestorage.app';
        $token    = $this->getAccessToken();

        $url = "https://storage.googleapis.com/upload/storage/v1/b/{$bucket}/o"
             . '?uploadType=media&name=' . urlencode($filename);

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
            throw new \Exception('Error al subir PDF: ' . $response);
        }

        $publicUrl = "https://firebasestorage.googleapis.com/v0/b/{$bucket}/o/"
                   . urlencode($filename) . '?alt=media';

        $this->update("constancias/{$constanciaId}", [
            'estado'      => 'completado',
            'pdfUrl'      => $publicUrl,
            'fechaSubida' => ['timestampValue' => date('c')]
        ]);

        return $publicUrl;
    }

    // ─────────────────────────────────────────
    // Helpers públicos (usados en vistas/controllers)
    // ─────────────────────────────────────────

    public static function parseDocument(array $doc): array
    {
        $data = [];

        foreach ($doc['fields'] as $key => $value) {
            if (isset($value['stringValue'])) {
                $data[$key] = $value['stringValue'];
            } elseif (isset($value['timestampValue'])) {
                $data[$key] = $value['timestampValue'];
            } elseif (isset($value['mapValue']['fields'])) {
                $nested = [];
                foreach ($value['mapValue']['fields'] as $nk => $nv) {
                    if (isset($nv['stringValue']))      $nested[$nk] = $nv['stringValue'];
                    elseif (isset($nv['timestampValue'])) $nested[$nk] = $nv['timestampValue'];
                }
                $data[$key] = $nested;
            }
        }

        if (!isset($data['alumno']) && isset($data['dniAlumno'])) {
            $data['alumno'] = [
                'dni'    => $data['dniAlumno'] ?? '',
                'nombre' => $data['nombre']    ?? ''
            ];
        }

        return $data;
    }

    public static function getDocumentId(array $doc): string
    {
        if (!isset($doc['name'])) return '';
        $parts = explode('/', $doc['name']);
        return end($parts);
    }

    public static function formatTimestamp(string $timestamp): string
    {
        if (empty($timestamp)) return '';

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $timestamp)) {
            $dt = \DateTime::createFromFormat('Y-m-d', $timestamp);
            return $dt ? $dt->format('d/m/Y') : $timestamp;
        }

        $dt = \DateTime::createFromFormat(\DateTime::ATOM, $timestamp);
        if (!$dt) {
            $ts = strtotime($timestamp);
            return $ts !== false ? date('d/m/Y', $ts) : $timestamp;
        }

        return $dt->format('d/m/Y');
    }

    // ─────────────────────────────────────────
    // Internos
    // ─────────────────────────────────────────

    private function runQuery(array $query): array
    {
        $url = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents:runQuery";

        $results = $this->request($url, 'POST', $query);

        $constancias = [];
        foreach ($results as $item) {
            if (isset($item['document'])) {
                $constancias[] = $item['document'];
            }
        }

        return $constancias;
    }

    private function request(string $url, string $method, array $body): array
    {
        $token = $this->getAccessToken();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        } elseif ($method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            $error = json_decode($response, true);
            throw new \Exception('Firestore error: ' . ($error['error']['message'] ?? $response));
        }

        return json_decode($response, true);
    }

    private function getAccessToken(): string
    {
        $credentials = json_decode(file_get_contents($this->credentialsPath), true);
        $now = time();

        $header   = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claimSet = [
            'iss'   => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'exp'   => $now + 3600,
            'iat'   => $now
        ];

        $encode = fn($arr) => str_replace('=', '', strtr(base64_encode(json_encode($arr)), '+/', '-_'));

        $jwtInput = $encode($header) . '.' . $encode($claimSet);
        openssl_sign($jwtInput, $sig, $credentials['private_key'], 'sha256');
        $jwt = $jwtInput . '.' . str_replace('=', '', strtr(base64_encode($sig), '+/', '-_'));

        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (empty($data['access_token'])) {
            throw new \Exception('No se pudo obtener el access token de Google.');
        }

        return $data['access_token'];
    }

    private function buildUrl(string $path): string
    {
        return "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/{$path}";
    }

    private function generatePdfFilename(string $dniAlumno, string $materia): string
    {
        $safeMateria = preg_replace('/[^a-zA-Z0-9]/', '_', $materia);
        return "constancia_{$dniAlumno}_{$safeMateria}_" . time() . '.pdf';
    }
}