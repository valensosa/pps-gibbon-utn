# Instrucciones de Queries - Gibbon

## 📁 Estructura de Archivos

```
gibbonQueries/
├── gibbon_queries.php           # Queries principales de Gibbon
└── INSTRUCCIONES_QUERIES.md     # Este archivo
```

## 🔧 Funciones Disponibles

### Clase: `GibbonQueries`

#### 1. **getDocumentTypeID($connection2)**
Obtiene el ID del tipo de documento "Documento" en Gibbon.

**Parámetros:**
- `$connection2` (object) - Conexión a la base de datos

**Retorna:**
- `int|null` - ID del tipo de documento o null si no se encuentra

**Uso:**
```php
$tipoID = GibbonQueries::getDocumentTypeID($connection2);
```

#### 2. **getStudentDNI($connection2, $gibbonPersonID)**
Obtiene el DNI de un estudiante usando su gibbonPersonID.

**Parámetros:**
- `$connection2` (object) - Conexión a la base de datos
- `$gibbonPersonID` (int) - ID de la persona en Gibbon

**Retorna:**
- `string|null` - DNI del estudiante o null si no se encuentra

**Uso:**
```php
$dni = GibbonQueries::getStudentDNI($connection2, $gibbonPersonID);
```

#### 3. **getStudentNameByDNI($connection2, $dni)**
Obtiene el nombre y apellido de un estudiante usando su DNI.

**Parámetros:**
- `$connection2` (object) - Conexión a la base de datos
- `$dni` (string) - DNI del estudiante

**Retorna:**
- `array|null` - Array con 'firstName' y 'surname' o null si no se encuentra

**Uso:**
```php
$studentName = GibbonQueries::getStudentNameByDNI($connection2, '12345678');
if ($studentName) {
    echo $studentName['firstName'] . ' ' . $studentName['surname'];
}
```

#### 4. **searchStudents($connection2, $searchTerm, $limit = 10)**
Busca estudiantes que coincidan con un término de búsqueda.

**Parámetros:**
- `$connection2` (object) - Conexión a la base de datos
- `$searchTerm` (string) - Término de búsqueda
- `$limit` (int) - Límite de resultados (default: 10)

**Retorna:**
- `array` - Array de estudiantes encontrados

**Uso:**
```php
$students = GibbonQueries::searchStudents($connection2, 'Juan', 5);
foreach ($students as $student) {
    echo $student['display'] . "\n";
}
```

#### 5. **getUserRole($connection2, $gibbonPersonID)**
Obtiene el rol del usuario actual.

**Parámetros:**
- `$connection2` (object) - Conexión a la base de datos
- `$gibbonPersonID` (int) - ID de la persona

**Retorna:**
- `string|null` - Nombre del rol o null si no se encuentra

**Uso:**
```php
$role = GibbonQueries::getUserRole($connection2, $gibbonPersonID);
if ($role === 'Student') {
    // Lógica para estudiantes
}
```

#### 6. **getStudentInfoByDNI($connection2, $dni)**
Obtiene información completa de un estudiante por DNI.

**Parámetros:**
- `$connection2` (object) - Conexión a la base de datos
- `$dni` (string) - DNI del estudiante

**Retorna:**
- `array|null` - Array con información completa del estudiante

**Uso:**
```php
$studentInfo = GibbonQueries::getStudentInfoByDNI($connection2, '12345678');
if ($studentInfo) {
    echo "ID: " . $studentInfo['gibbonPersonID'];
    echo "Nombre: " . $studentInfo['firstName'] . ' ' . $studentInfo['surname'];
    echo "Email: " . $studentInfo['email'];
    echo "DNI: " . $studentInfo['dni'];
}
```

## 🚀 Cómo Usar

### 1. Incluir las queries
```php
require_once 'gibbonQueries/gibbon_queries.php';
```

### 2. Usar las funciones
```php
// Obtener DNI de un estudiante
$dni = GibbonQueries::getStudentDNI($connection2, $gibbonPersonID);

// Buscar estudiantes
$students = GibbonQueries::searchStudents($connection2, 'Juan');

// Obtener información completa
$studentInfo = GibbonQueries::getStudentInfoByDNI($connection2, $dni);
```

## 📊 Estructura de Datos

### Estudiante (searchStudents)
```php
[
    'id' => 123,
    'firstName' => 'Juan',
    'surname' => 'Pérez',
    'dni' => '12345678',
    'display' => 'Juan Pérez - 12345678'
]
```

### Información de Estudiante (getStudentInfoByDNI)
```php
[
    'gibbonPersonID' => 123,
    'firstName' => 'Juan',
    'surname' => 'Pérez',
    'email' => 'juan.perez@email.com',
    'dni' => '12345678'
]
```

### Nombre de Estudiante (getStudentNameByDNI)
```php
[
    'firstName' => 'Juan',
    'surname' => 'Pérez'
]
```

## 🔍 Tablas de Gibbon Utilizadas

### gibbonPersonalDocumentType
- **Propósito**: Tipos de documentos personales
- **Campo clave**: `name = 'Documento'`
- **Campo ID**: `gibbonPersonalDocumentTypeID`

### gibbonPersonalDocument
- **Propósito**: Documentos personales de los usuarios
- **Campos**: `foreignTable`, `foreignTableID`, `documentNumber`, `gibbonPersonalDocumentTypeID`
- **Relación**: Conecta personas con sus documentos

### gibbonPerson
- **Propósito**: Información de personas
- **Campos**: `gibbonPersonID`, `firstName`, `surname`, `email`, `gibbonRoleIDPrimary`
- **Relación**: Tabla principal de usuarios

### gibbonRole
- **Propósito**: Roles de usuarios
- **Campos**: `gibbonRoleID`, `name`
- **Relación**: Define el rol de cada persona

## ⚠️ Consideraciones Importantes

### 1. **Tipo de Documento**
- Las queries asumen que existe un tipo de documento llamado "Documento"
- Si el nombre es diferente, modificar en `getDocumentTypeID()`

### 2. **Manejo de Errores**
- Todas las funciones incluyen try-catch
- Los errores se registran en el log
- Las funciones retornan null o array vacío en caso de error

### 3. **Seguridad**
- Todas las queries usan prepared statements
- Los parámetros se escapan automáticamente
- No hay riesgo de SQL injection

### 4. **Performance**
- Las queries incluyen LIMIT para evitar resultados excesivos
- Se usan índices apropiados (gibbonPersonID, documentNumber)
- Las consultas están optimizadas para el uso específico

## 🔧 Personalización

### Agregar Nuevas Queries
```php
public static function nuevaQuery($connection2, $parametro) {
    try {
        $sql = "SELECT campo FROM tabla WHERE condicion = :parametro";
        $stmt = $connection2->prepare($sql);
        $stmt->execute(['parametro' => $parametro]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Error en nuevaQuery: " . $e->getMessage());
        return null;
    }
}
```

### Modificar Queries Existentes
- Todas las queries están en métodos estáticos
- Fácil de modificar sin afectar otros archivos
- Mantener el patrón de manejo de errores

## 📝 Notas de Desarrollo

- **Mantenibilidad**: Todas las queries centralizadas en un archivo
- **Reutilización**: Funciones modulares y reutilizables
- **Documentación**: Cada función tiene documentación completa
- **Testing**: Fácil de testear individualmente
- **Debugging**: Logs detallados para troubleshooting 