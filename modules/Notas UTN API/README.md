# Notas UTN API - Módulo para Gibbon

## 📋 Descripción

El módulo **Notas UTN API** permite visualizar las notas de estudiantes de la Universidad Tecnológica Nacional (UTN) desde una API externa. Este módulo se integra con el sistema de gestión escolar Gibbon y proporciona una interfaz para consultar el historial académico de los estudiantes.

## ✨ Características Principales

- 🔍 **Búsqueda de Estudiantes**: Interfaz de búsqueda por nombre, apellido o DNI
- 📊 **Visualización de Notas**: Tabla detallada con todas las materias y calificaciones
- 👥 **Control de Acceso**: Diferentes permisos según el rol del usuario
- 📄 **Paginación**: Navegación por páginas para grandes volúmenes de datos
- 🔄 **Integración API**: Conexión con la API oficial de la UTN SIU Guaraní
- 🎯 **Filtros**: Filtrado por materias específicas
- 📱 **Responsive**: Interfaz adaptada para diferentes dispositivos

## 🏗️ Estructura del Módulo

```
Notas UTN API/
├── api/                           # Configuración y utilidades de la API
│   ├── config/                    # Configuración de la API UTN
│   │   ├── utn_api_config.php     # Configuración principal
│   │   └── utn_api_config.production.example.php
│   ├── logs/                      # Logs de la API
│   ├── .gitignore                 # Protección de archivos sensibles
│   └── INSTRUCCIONES_API.md       # Documentación de la API
├── gibbonQueries/                 # Queries personalizadas de Gibbon
│   ├── gibbon_queries.php         # Funciones de consulta a Gibbon
│   ├── .gitignore                 # Protección de archivos
│   └── INSTRUCCIONES_QUERIES.md   # Documentación de queries
├── css/                          # Estilos del módulo
├── js/                           # JavaScript del módulo
├── index.php                     # Página principal del módulo
├── buscarNotas.php               # Función de búsqueda de notas
├── search_students.php           # Endpoint de búsqueda de estudiantes
├── moduleFunctions.php           # Funciones principales del módulo
├── manifest.php                  # Configuración del módulo
└── README.md                     # Este archivo
```

## 🚀 Instalación

### 1. Requisitos Previos

- Gibbon v22.0 o superior
- PHP 7.4 o superior
- Acceso a la API de la UTN SIU Guaraní
- Permisos de administrador en Gibbon

### 2. Instalación del Módulo

1. **Copiar archivos**: Copia la carpeta `Notas UTN API` al directorio `modules/` de tu instalación de Gibbon

2. **Configurar permisos**: Asegúrate de que el servidor web tenga permisos de lectura en la carpeta del módulo

3. **Instalar en Gibbon**:
   - Accede al panel de administración de Gibbon
   - Ve a **System Admin** > **Manage Modules**
   - Busca "Notas UTN API" y haz clic en **Install**

### 3. Configuración de la API

#### Archivo de Configuración Principal

Edita el archivo `api/config/utn_api_config.php`:

```php
// URLs de la API - Google Cloud Functions
const API_BASE_URL = 'https://us-central1-siu-mock-api-2025.cloudfunctions.net/api';

// Endpoints
const ENDPOINTS = [
    'personas' => '/personas',
    'datos_analitico' => '/personas/{persona_id}/datosanalitico'
];

// Configuración de autenticación
const AUTH_CONFIG = [
    'enabled' => false,           // Habilitar si la API requiere autenticación
    'type' => 'bearer',          // Tipo de autenticación
    'token' => '',               // Token de acceso
    'username' => '',            // Usuario (si es necesario)
    'password' => ''             // Contraseña (si es necesario)
];
```

#### Configuración de Producción

La API ya está configurada para usar Google Cloud Functions. Si necesitas cambiar la configuración:

1. Edita directamente `api/config/utn_api_config.php`
2. Actualiza las credenciales y URLs según tu entorno
3. Asegúrate de que las credenciales estén protegidas (no en el control de versiones)
4. Verifica que SSL esté habilitado para HTTPS

### 4. Configuración de Permisos

El módulo incluye los siguientes permisos por defecto:

- **Admin**: Acceso completo
- **Student**: Solo puede ver sus propias notas

Para modificar permisos:
1. Ve a **System Admin** > **Manage Permissions**
2. Busca "Notas UTN API"
3. Configura los permisos según tus necesidades

## 📖 Uso del Módulo

### Para Administradores

1. **Acceder al módulo**: Navega a **Notas** > **Ver Notas API**

2. **Buscar estudiantes**:
   - Usa el campo de búsqueda para encontrar estudiantes por nombre, apellido o DNI
   - El sistema mostrará sugerencias automáticas mientras escribes
   - Haz clic en "Buscar" para ver los resultados

3. **Ver notas**:
   - Las notas se muestran en una tabla organizada
   - Cada fila representa una materia/actividad
   - Los datos incluyen: título, actividad, fecha, nota, resultado, promedio, etc.

4. **Navegación**:
   - Usa la paginación para navegar entre páginas
   - Cada página muestra hasta 10 materias por estudiante

### Para Estudiantes

1. **Acceso automático**: Los estudiantes ven automáticamente sus propias notas
2. **Información completa**: Ven su historial académico completo


## 🔧 Configuración Avanzada

### Personalización de la Interfaz

#### Estilos CSS

Edita `css/module.css` para personalizar la apariencia:

```css
/* Personalizar tabla de notas */
.grades-table {
    border-collapse: collapse;
    width: 100%;
}

.grades-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

/* Personalizar formulario de búsqueda */
.search-form {
    margin-bottom: 20px;
}

.search-input {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
```

#### JavaScript

Edita `js/module.js` para funcionalidades adicionales:

```javascript
// Ejemplo: Exportar datos a Excel
function exportToExcel() {
    // Implementar exportación
}

// Ejemplo: Filtros adicionales
function applyAdvancedFilters() {
    // Implementar filtros avanzados
}
```

### Configuración de Caché

El módulo incluye un sistema de caché para mejorar el rendimiento:

```php
// En utn_api_config.php
const CACHE_CONFIG = [
    'enabled' => true,
    'duration' => 3600,  // 1 hora en segundos
    'directory' => __DIR__ . '/cache'
];
```

### Configuración de Logs

Los logs se guardan en `api/logs/`:

```php
// Configurar nivel de logging
const LOG_LEVEL = 'INFO'; // DEBUG, INFO, WARNING, ERROR
```

## 🔍 API Reference

### Endpoints Utilizados

#### 1. Buscar Personas
```
GET https://us-central1-siu-mock-api-2025.cloudfunctions.net/api/personas?numero_documento={dni}
```

**Parámetros:**
- `numero_documento`: DNI del estudiante

**Respuesta:**
```json
[
  {
    "email": "estudiante@utn.edu.ar",
    "telefono": "1234567890",
    "persona": 12345
  }
]
```

#### 2. Datos Analíticos
```
GET https://us-central1-siu-mock-api-2025.cloudfunctions.net/api/personas/{persona_id}/datosanalitico
```

**Parámetros:**
- `persona_id`: ID de la persona obtenido del endpoint anterior

**Respuesta:**
```json
[
  {
    "titulo_araucano": "ING001",
    "titulo_nombre": "Ingeniería en Sistemas",
    "actividad_nombre": "Matemática I",
    "actividad_codigo": "MAT001",
    "fecha": "2024-01-15",
    "nota": "8",
    "resultado": "Aprobado",
    "promedio": "7.5",
    "forma_aprobacion": "Examen Final",
    "es_optativa": "No"
  }
]
```

### Funciones Principales

#### `getStudentDataFromAPI($studentID)`
Obtiene los datos completos de un estudiante desde la API.

**Parámetros:**
- `$studentID` (string): DNI del estudiante

**Retorna:**
- `array|null`: Datos del estudiante o null si hay error

#### `formatStudentData($apiData, $studentID)`
Formatea los datos de la API para mostrar en la interfaz.

**Parámetros:**
- `$apiData` (array): Datos crudos de la API
- `$studentID` (string): DNI del estudiante

**Retorna:**
- `array`: Datos formateados para la interfaz

#### `getStudentDNI($gibbonPersonID)`
Obtiene el DNI de un estudiante desde Gibbon.

**Parámetros:**
- `$gibbonPersonID` (int): ID de la persona en Gibbon

**Retorna:**
- `string|null`: DNI del estudiante o null si no se encuentra

## 🛠️ Solución de Problemas

### Errores Comunes

#### 1. "No se encontró un DNI registrado"
**Causa**: El estudiante no tiene un documento de tipo "Documento" registrado en Gibbon.

**Solución**:
1. Ve a **User Admin** > **Manage Users**
2. Busca el estudiante
3. Ve a **Personal Documents**
4. Agrega un documento de tipo "Documento" con el DNI

#### 2. "Error en llamada API"
**Causa**: Problemas de conectividad o configuración de la API.

**Solución**:
1. Verifica la configuración en `api/config/utn_api_config.php`
2. Confirma que la URL de Google Cloud Functions sea correcta
3. Revisa los logs en `api/logs/`
4. Confirma que la API esté disponible en: `https://us-central1-siu-mock-api-2025.cloudfunctions.net/api`
5. Verifica las credenciales de autenticación (si están habilitadas)
6. Asegúrate de que SSL esté habilitado para HTTPS

#### 3. "No se encontraron materias"
**Causa**: El estudiante no tiene materias registradas en la API.

**Solución**:
1. Verifica que el DNI sea correcto
2. Confirma que el estudiante tenga materias en el sistema de la UTN
3. Revisa los logs para más detalles

### Logs y Debugging

#### Habilitar Logs Detallados

```php
// En utn_api_config.php
const LOG_LEVEL = 'DEBUG';
const DEBUG_MODE = true;
```

#### Revisar Logs

Los logs se guardan en:
- `api/logs/api_errors.log` - Errores de la API
- `api/logs/debug.log` - Información de debug
- `api/logs/requests.log` - Historial de peticiones

### Verificación de Configuración

Ejecuta el script de verificación:

```bash
php modules/Notas\ UTN\ API/verify_config.php
```

## 🔒 Seguridad

### Consideraciones de Seguridad

1. **Protección de Credenciales**:
   - Nunca subas credenciales al control de versiones
   - Usa variables de entorno para credenciales sensibles
   - Mantén los archivos `.gitignore` actualizados

2. **Validación de Entrada**:
   - Todos los DNIs se validan antes de enviarse a la API
   - Se aplica escape HTML en todas las salidas
   - Se validan los permisos de usuario

3. **Control de Acceso**:
   - Los estudiantes solo ven sus propias notas
   - Los permisos se verifican en cada petición
   - Se registran todos los accesos

### Mejores Prácticas

1. **Configuración de Producción**:
   - Usa HTTPS para todas las comunicaciones
   - Configura timeouts apropiados
   - Implementa rate limiting

2. **Mantenimiento**:
   - Revisa los logs regularmente
   - Actualiza las credenciales de la API cuando sea necesario
   - Monitorea el rendimiento


## 👨‍💻 Autor

**Ignacio Garcia**
- Versión: 1.0.0
- Fecha: 2025

## 🔗 Enlaces Útiles

- [Documentación de la API UTN SIU](https://documentacion.siu.edu.ar/apis/?spec=guarani_v2)
- [Documentación de Gibbon](https://docs.gibbonedu.org/)
- [Foro de Gibbon](https://gibbonedu.org/community/)

## 📝 Changelog

### v1.0.0 (2024)
- ✅ Implementación inicial del módulo
- ✅ Integración con API UTN SIU Guaraní
- ✅ Interfaz de búsqueda y visualización
- ✅ Sistema de permisos y roles
- ✅ Documentación completa

---

**Nota**: Este módulo requiere acceso a la API oficial de la UTN SIU Guaraní. Asegúrate de tener las credenciales y permisos necesarios antes de la instalación. 