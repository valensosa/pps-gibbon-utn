# Instrucciones de Configuración - API UTN

## 📁 Estructura de Archivos

```
api/
├── config/
│   ├── utn_api_config.php              # Configuración actual (desarrollo)
│   └── utn_api_config.production.example.php  # Ejemplo para producción
├── cache/                              # Directorio para caché (se crea automáticamente)
├── logs/                               # Directorio para logs (se crea automáticamente)
├── .gitignore                          # Protege credenciales y logs
└── INSTRUCCIONES_API.md                # Este archivo
```

## 🔧 Configuración de la API UTN

### Archivo Principal: `config/utn_api_config.php`

Este archivo contiene toda la configuración necesaria para conectar con la API UTN. **Cuando tengas la API real, solo necesitarás actualizar las siguientes secciones:**

#### 1. URLs de la API
```php
const API_BASE_URL = 'http://127.0.0.1:8000'; // Cambiar por la URL real
```

#### 2. Endpoints
```php
const ENDPOINTS = [
    'personas' => '/personas',
    'datos_analitico' => '/personas/{persona_id}/datosanalitico'
];
```

#### 3. Autenticación (si es necesaria)
```php
const AUTH_CONFIG = [
    'enabled' => false,        // Cambiar a true si requiere autenticación
    'type' => 'bearer',        // 'bearer', 'basic', 'api_key'
    'token' => '',             // Token de autenticación
    'username' => '',          // Usuario (para auth básica)
    'password' => ''           // Contraseña (para auth básica)
];
```

## 📚 Documentación de la API de Producción - UTN SIU

### Fuente de Documentación
- **URL**: [https://documentacion.siu.edu.ar/apis/?spec=guarani_v2](https://documentacion.siu.edu.ar/apis/?spec=guarani_v2)
- **Especificación**: Guaraní v2

### Endpoints Disponibles

#### 1. Buscar Personas
**Endpoint**: `GET /personas`

**Descripción**: Devuelve una lista de personas. Buscar por país, tipo y número de documento, o usuario.

**Parámetros de Consulta (Query Parameters)**:
- `usuario` (string) - Usuario
- `pais` (string) - País
- `tipo_documento` (integer) - Tipo de documento de la persona
- `numero_documento` (string) - Número de documento de la persona

**Respuestas**:
- `200` - Éxito
- `400` - Error en los parámetros
- `404` - La persona no existe

**Ejemplo de Respuesta (200)**:
```json
[
  {
    "email": "string",
    "telefono": "string",
    "persona": 0
  }
]
```

#### 2. Historia Académica
**Endpoint**: `GET /personas/{persona}/datosanalitico`

**Descripción**: Devuelve la historia académica de una persona.

**Parámetros de Ruta**:
- `persona` (string, required) - ID del recurso personas

**Parámetros de Consulta**:
- `codigo_titulo_araucano` (string) - Código del título Araucano

**Respuestas**:
- `200` - Éxito
- `400` - Error en los parámetros

**Ejemplo de Respuesta (200)**:
```json
[
  {
    "titulo_araucano": "string",
    "titulo_nombre": "string",
    "responsable_academica": "string",
    "propuesta": "string",
    "propuesta_nombre": "string",
    "plan_alumno": "string",
    "titulo_esta_cumplido": "string",
    "nro_resolucion_ministerial": "string",
    "nro_resolucion_coneau": "string",
    "nro_resolucion_spu": "string",
    "nro_disposicion_spu": "string",
    "nro_resolucion_institucion": "string",
    "fecha_ingreso": "string",
    "fecha_egreso": "string",
    "tiene_sanciones": "string",
    "titulo_anterior_nivel": "string",
    "titulo_anterior_origen": "string",
    "titulo_anterior_nacionalidad": "string",
    "titulo_anterior_institucion": "string",
    "titulo_anterior_denominacion": "string",
    "titulo_anterior_revalidado": "string",
    "titulo_anterior_nro_resolucion": "string",
    "titulo_apto_ejercicio": "string",
    "plan_vigente": "string",
    "tipo": "string",
    "actividad_nombre": "string",
    "actividad_codigo": "string",
    "creditos": "string",
    "fecha": "string",
    "nota": "string",
    "resultado": "string",
    "folio_fisico": "string",
    "acta_resolucion": "string",
    "promedio": "string",
    "promedio_sin_aplazos": "string",
    "forma_aprobacion": "string",
    "es_optativa": "string",
    "fecha_inicio_tramite": "string",
    "nro_expediente": "string"
  }
]
```

### Campos Importantes para el Módulo

Los siguientes campos son los que utiliza actualmente el módulo "Notas UTN API":

#### Campos de Identificación:
- `persona` - ID único de la persona
- `numero_documento` - DNI del estudiante

#### Campos de Materias/Actividades:
- `titulo_araucano` - Código del título
- `titulo_nombre` - Nombre del título
- `actividad_nombre` - Nombre de la actividad/materia
- `actividad_codigo` - Código de la actividad
- `fecha` - Fecha del examen/actividad
- `nota` - Nota obtenida
- `resultado` - Resultado (Aprobado/Desaprobado)
- `promedio` - Promedio general
- `forma_aprobacion` - Forma de aprobación
- `es_optativa` - Si es materia optativa
- `creditos` - Créditos de la materia

#### Campos de Plan de Estudios:
- `plan_vigente` - Si el plan está vigente
- `plan_alumno` - Plan específico del alumno
- `tipo` - Tipo de estudiante (Regular, etc.)

## 🚀 Cómo Usar la API

### 1. Incluir la configuración
```php
require_once 'api/config/utn_api_config.php';
```

### 2. Hacer peticiones a la API
```php
// Buscar persona por DNI
$url = UTNApiQueries::getPersonasByDNI('12345678');
$result = UTNApiUtils::makeRequest($url);

if ($result['success']) {
    $personaData = $result['data'];
    // Procesar datos...
} else {
    error_log('Error: ' . $result['error']);
}

// Obtener datos analíticos
$url = UTNApiQueries::getDatosAnalitico('persona_id');
$result = UTNApiUtils::makeRequest($url);
```

### 3. Validar DNI
```php
if (UTNApiUtils::validateDNI($dni)) {
    // DNI válido
    $formattedDNI = UTNApiUtils::formatDNI($dni);
}
```

## 📊 Logging Automático

La API registra automáticamente todas las peticiones en `logs/api.log`:

```
[2025-01-21 19:30:15] [INFO] URL: http://127.0.0.1:8000/personas?numero_documento=12345678, HTTP: 200, Response: {...}
[2025-01-21 19:30:16] [ERROR] URL: http://127.0.0.1:8000/personas/123/datosanalitico, HTTP: 404, Response: Not found
```

## 🔄 Migración a API Real

### Pasos para cambiar a la API real:

1. **Actualizar URL base** en `UTNApiConfig::API_BASE_URL`
2. **Verificar endpoints** en `UTNApiConfig::ENDPOINTS`
3. **Configurar autenticación** si es necesaria
4. **Cambiar SSL verification** a `true` en producción
5. **Probar conectividad** con la nueva API

### Ejemplo de configuración para producción:
```php
const API_BASE_URL = 'https://api.utn.edu.ar/v1';
const AUTH_CONFIG = [
    'enabled' => true,
    'type' => 'bearer',
    'token' => 'tu_token_aqui'
];
const CURL_OPTIONS = [
    'ssl_verify' => true  // Importante en producción
];
```

### Configuración Específica para UTN SIU:
```php
const API_BASE_URL = 'https://api.utn.edu.ar/guarani/v2';
const ENDPOINTS = [
    'personas' => '/personas',
    'datos_analitico' => '/personas/{persona_id}/datosanalitico'
];
const AUTH_CONFIG = [
    'enabled' => true,
    'type' => 'bearer', // o el tipo que use la UTN
    'token' => 'token_de_la_utn'
];
```

## 🛠️ Funciones Disponibles

### UTNApiQueries
- `getPersonasByDNI($dni)` - URL para buscar por DNI
- `getDatosAnalitico($personaId)` - URL para datos analíticos
- `getHeaders()` - Headers HTTP necesarios

### UTNApiUtils
- `makeRequest($url, $options)` - Realizar petición HTTP
- `validateDNI($dni)` - Validar formato de DNI
- `formatDNI($dni)` - Formatear DNI

## 🔍 Troubleshooting

### Error de conexión
- Verificar que la URL base sea correcta
- Comprobar que el servidor esté accesible
- Revisar logs en `logs/api.log`

### Error de autenticación
- Verificar que las credenciales sean correctas
- Comprobar el tipo de autenticación configurado
- Revisar headers en la respuesta

### Error de formato de datos
- Verificar que la respuesta sea JSON válido
- Comprobar la estructura de datos esperada
- Revisar logs para ver la respuesta completa

## 📝 Notas Importantes

- **Desarrollo**: Los logs son más detallados y se muestran errores
- **Producción**: Los logs son menos detallados y se ocultan errores
- **Caché**: Opcional, puede mejorar el rendimiento
- **SSL**: Siempre habilitar en producción
- **Timeouts**: Configurar según la latencia de la API real

## 🔒 Seguridad

- Las credenciales están protegidas por `.gitignore`
- Nunca subir `utn_api_config.php` con credenciales reales
- Usar `utn_api_config.production.example.php` como plantilla
- Cambiar `ssl_verify` a `true` en producción

## 📞 Soporte

Para problemas con la configuración de la API:
1. Revisar los logs en `logs/api.log`
2. Verificar la conectividad con la API
3. Comprobar la configuración en `utn_api_config.php`
4. Validar que los endpoints sean correctos
5. Consultar la documentación oficial: [https://documentacion.siu.edu.ar/apis/?spec=guarani_v2](https://documentacion.siu.edu.ar/apis/?spec=guarani_v2) 