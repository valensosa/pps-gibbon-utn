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

namespace App\config;

class UTNApiConfig
{

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
// CONFIGURACIÓN PARA PRODUCCIÓN
// ============================================================================

// Configuración para producción
// error_reporting(0);
// ini_set('display_errors', 0);
