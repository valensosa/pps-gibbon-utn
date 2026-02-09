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
// CONSULTAS Y QUERIES (igual que en desarrollo)
// ============================================================================

namespace App\config;

class UTNApiQueries
{

    /**
     * Obtiene la URL completa para buscar personas por DNI
     * 
     * @param string $dni DNI de la persona
     * @return string URL completa del endpoint
     */
    public static function getPersonasByDNI($dni)
    {
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
    public static function getDatosAnalitico($personaId)
    {
        $baseUrl = UTNApiConfig::API_BASE_URL;
        $endpoint = str_replace('{persona_id}', $personaId, UTNApiConfig::ENDPOINTS['datos_analitico']);
        return $baseUrl . $endpoint;
    }

    /**
     * Obtiene los headers necesarios para las peticiones HTTP
     * 
     * @return array Array de headers
     */
    public static function getHeaders()
    {
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
