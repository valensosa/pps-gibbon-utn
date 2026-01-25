<?php
/**
 * EJEMPLO DE CONFIGURACIÓN PARA PRODUCCIÓN
 * 
 * Este archivo muestra cómo configurar la API UTN para producción.
 * Copia este archivo como utn_api_config.php y actualiza los valores.
 * 
 * IMPORTANTE: Nunca subas las credenciales reales al control de versiones.
 */

// ============================================================================
// CONFIGURACIÓN DE LA API PARA PRODUCCIÓN
// ============================================================================

class UTNApiConfig {
    
    // URLs de la API - ACTUALIZAR CON LA URL REAL
    const API_BASE_URL = 'https://api.utn.edu.ar/v1'; // URL real de la API UTN
    
    // Endpoints de la API - VERIFICAR QUE SEAN CORRECTOS
    const ENDPOINTS = [
        'personas' => '/personas',
        'datos_analitico' => '/personas/{persona_id}/datosanalitico'
    ];
    
    // Configuración de cURL para producción
    const CURL_OPTIONS = [
        'timeout' => 30,
        'ssl_verify' => true, // IMPORTANTE: true en producción
        'user_agent' => 'Gibbon-UTN-API/1.0'
    ];
    
    // Configuración de autenticación - ACTUALIZAR CON CREDENCIALES REALES
    const AUTH_CONFIG = [
        'enabled' => true, // Cambiar a true si la API requiere autenticación
        'type' => 'bearer', // 'bearer', 'basic', 'api_key'
        'token' => 'TU_TOKEN_AQUI', // Token real de la API
        'username' => '', // Usuario (para autenticación básica)
        'password' => ''  // Contraseña (para autenticación básica)
    ];
    
    // Configuración de caché para producción
    const CACHE_CONFIG = [
        'enabled' => true, // Habilitar caché en producción
        'duration' => 1800, // 30 minutos en segundos
        'directory' => __DIR__ . '/../cache/'
    ];
    
    // Configuración de logging para producción
    public static $LOG_CONFIG = [
        'enabled' => true,
        'level' => 'warning', // Solo warnings y errores en producción
        'file' => __DIR__ . '/../logs/api.log'
    ];
}

// ============================================================================
// CONSULTAS Y QUERIES (igual que en desarrollo)
// ============================================================================

class UTNApiQueries {
    
    /**
     * Obtiene la URL completa para buscar personas por DNI
     * 
     * @param string $dni DNI de la persona
     * @return string URL completa del endpoint
     */
    public static function getPersonasByDNI($dni) {
        $baseUrl = UTNApiConfig::API_BASE_URL;
        $endpoint = UTNApiConfig::ENDPOINTS['personas'];
        return $baseUrl . $endpoint . '?numero_documento=' . urlencode($dni);
    }
    
    /**
     * Obtiene la URL completa para obtener datos analíticos de una persona
     * 
     * @param string $personaId ID de la persona
     * @return string URL completa del endpoint
     */
    public static function getDatosAnalitico($personaId) {
        $baseUrl = UTNApiConfig::API_BASE_URL;
        $endpoint = str_replace('{persona_id}', $personaId, UTNApiConfig::ENDPOINTS['datos_analitico']);
        return $baseUrl . $endpoint;
    }
    
    /**
     * Obtiene los headers necesarios para las peticiones HTTP
     * 
     * @return array Array de headers
     */
    public static function getHeaders() {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'User-Agent: ' . UTNApiConfig::CURL_OPTIONS['user_agent']
        ];
        
        // Agregar headers de autenticación si está habilitada
        if (UTNApiConfig::AUTH_CONFIG['enabled']) {
            switch (UTNApiConfig::AUTH_CONFIG['type']) {
                case 'bearer':
                    $headers[] = 'Authorization: Bearer ' . UTNApiConfig::AUTH_CONFIG['token'];
                    break;
                case 'basic':
                    $auth = base64_encode(UTNApiConfig::AUTH_CONFIG['username'] . ':' . UTNApiConfig::AUTH_CONFIG['password']);
                    $headers[] = 'Authorization: Basic ' . $auth;
                    break;
                case 'api_key':
                    $headers[] = 'X-API-Key: ' . UTNApiConfig::AUTH_CONFIG['token'];
                    break;
            }
        }
        
        return $headers;
    }
}

// ============================================================================
// FUNCIONES DE UTILIDAD (igual que en desarrollo)
// ============================================================================

class UTNApiUtils {
    
    /**
     * Realiza una petición HTTP a la API UTN
     * 
     * @param string $url URL completa del endpoint
     * @param array $options Opciones adicionales para cURL
     * @return array Array con 'success', 'data', 'error' y 'http_code'
     */
    public static function makeRequest($url, $options = []) {
        $ch = curl_init();
        
        // Configuración básica de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, UTNApiConfig::CURL_OPTIONS['ssl_verify']);
        curl_setopt($ch, CURLOPT_TIMEOUT, UTNApiConfig::CURL_OPTIONS['timeout']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, UTNApiQueries::getHeaders());
        
        // Aplicar opciones adicionales
        foreach ($options as $option => $value) {
            curl_setopt($ch, $option, $value);
        }
        
        // Ejecutar la petición
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Log de la petición
        if (UTNApiConfig::$LOG_CONFIG['enabled']) {
            self::logRequest($url, $httpCode, $error, $response);
        }
        
        // Procesar respuesta
        if ($error) {
            return [
                'success' => false,
                'error' => 'cURL Error: ' . $error,
                'http_code' => $httpCode,
                'data' => null
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP Error: ' . $httpCode,
                'http_code' => $httpCode,
                'data' => $response
            ];
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'JSON Parse Error: ' . json_last_error_msg(),
                'http_code' => $httpCode,
                'data' => $response
            ];
        }
        
        return [
            'success' => true,
            'data' => $data,
            'http_code' => $httpCode,
            'error' => null
        ];
    }
    
    /**
     * Registra las peticiones en el log
     * 
     * @param string $url URL de la petición
     * @param int $httpCode Código de respuesta HTTP
     * @param string $error Error de cURL (si existe)
     * @param string $response Respuesta de la API
     */
    private static function logRequest($url, $httpCode, $error, $response) {
        $logDir = dirname(UTNApiConfig::$LOG_CONFIG['file']);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $logLevel = $error || $httpCode !== 200 ? 'ERROR' : 'INFO';
        
        $logMessage = "[{$timestamp}] [{$logLevel}] URL: {$url}, HTTP: {$httpCode}";
        if ($error) {
            $logMessage .= ", cURL Error: {$error}";
        }
        if (strlen($response) > 500) {
            $logMessage .= ", Response: " . substr($response, 0, 500) . "...";
        } else {
            $logMessage .= ", Response: {$response}";
        }
        
        file_put_contents(UTNApiConfig::$LOG_CONFIG['file'], $logMessage . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Valida si un DNI tiene el formato correcto
     * 
     * @param string $dni DNI a validar
     * @return bool True si el formato es válido
     */
    public static function validateDNI($dni) {
        return preg_match('/^\d{6,}$/', $dni);
    }
    
    /**
     * Formatea un DNI para asegurar que tenga el formato correcto
     * 
     * @param string $dni DNI a formatear
     * @return string DNI formateado
     */
    public static function formatDNI($dni) {
        return preg_replace('/[^0-9]/', '', $dni);
    }
}

// ============================================================================
// CONFIGURACIÓN PARA PRODUCCIÓN
// ============================================================================

// Configuración para producción
error_reporting(0);
ini_set('display_errors', 0);

// Logs menos detallados en producción
UTNApiConfig::$LOG_CONFIG['level'] = 'warning'; 