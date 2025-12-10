# Módulo Constancias de Examen

Un módulo completo para Gibbon que permite a los estudiantes solicitar constancias de examen y a los administradores gestionar y subir los documentos correspondientes.

## 🚀 Características

- **Solicitud de Constancias**: Los estudiantes pueden solicitar constancias de examen
- **Gestión Administrativa**: Panel de administración para gestionar solicitudes
- **Almacenamiento en la Nube**: Integración con Firebase Storage para archivos PDF
- **Base de Datos en Tiempo Real**: Firestore para almacenamiento de datos
- **Interfaz Moderna**: Diseño responsive y fácil de usar
- **Búsqueda y Filtros**: Funcionalidades avanzadas de búsqueda
- **Autocompletado**: Sugerencias de materias basadas en cursos existentes

## 📋 Requisitos del Sistema

### Software
- **PHP**: 7.4 o superior
- **Gibbon**: Cualquier versión con sistema de documentos personales
- **Extensiones PHP**: cURL, OpenSSL, JSON

### Base de Datos
- **Tabla `gibbonPersonalDocumentType`**: Debe existir un tipo llamado "Documento"
- **Tabla `gibbonPersonalDocument`**: Para almacenar DNIs de estudiantes
- **Tabla `gibbonCourse`**: Para el autocompletado de materias

### Firebase
- **Proyecto Firebase**: Configurado con Firestore y Storage
- **Credenciales de Servicio**: Archivo JSON con permisos adecuados

## 🛠️ Instalación

### 1. Copiar Archivos
```bash
# Copiar el módulo al directorio de Gibbon
cp -r "Constancias de Examen" /path/to/gibbon/modules/
```

### 2. Configurar Firebase
Editar `modules/Constancias de Examen/moduleFunctions.php`:

```php
// Configuración de Firebase
define('FIREBASE_PROJECT_ID', 'tu-proyecto-id');
define('FIREBASE_PRIVATE_KEY_ID', 'tu-private-key-id');
define('FIREBASE_PRIVATE_KEY', 'tu-private-key');
define('FIREBASE_CLIENT_EMAIL', 'tu-client-email');
define('FIREBASE_CLIENT_ID', 'tu-client-id');
```

### 3. Configurar Permisos
En el panel de administración de Gibbon:

**Para Estudiantes:**
- Acceso a `/modules/Constancias de Examen/student_constancias.php`

**Para Administradores:**
- Acceso a `/modules/Constancias de Examen/admin_constancias.php`

### 4. Activar el Módulo
- Ir a **System Admin** → **Manage Modules**
- Activar "Constancias de Examen"

## ⚙️ Configuración

### Configuración de Documentos Personales

El módulo requiere que los estudiantes tengan su DNI registrado en el sistema de documentos personales de Gibbon:

1. **Crear Tipo de Documento**:
   1️⃣ Admin → Administrar el usuario → Configuración de documentos personales
   2️⃣ Editar el primer campo:
      ➡️ Nombre: "Documento"
      ➡️ Descripción: "Documento Nacional de Identidad"
      ➡️ Activo: ✅
      ➡️ Requerido: ✅

### Configuración de Firebase

1. **Crear Proyecto Firebase**:
   - Ir a [Firebase Console](https://console.firebase.google.com/)
   - Crear nuevo proyecto
   - Habilitar Firestore Database y Storage

2. **Configurar Firestore**:
   - Crear colección `constancias`
   - Configurar reglas de seguridad

3. **Configurar Storage**:
   - Crear bucket para archivos PDF
   - Configurar reglas de acceso público

4. **Obtener Credenciales**:
   - Ir a Configuración del Proyecto → Cuentas de servicio
   - Generar nueva clave privada
   - Descargar archivo JSON
   - Colocar en `modules/Constancias de Examen/credentials/firebase-credentials.json`

## 📖 Uso del Módulo

### Para Estudiantes

#### Acceso
Los estudiantes acceden a través de su sesión normal de Gibbon:
```
https://tu-gibbon.com/index.php?q=/modules/Constancias de Examen/student_constancias.php
```

#### Solicitar Constancia
1. Hacer clic en **"Solicitar constancia"**
2. Completar el formulario:
   - **Materia**: Usar autocompletado o escribir libremente
   - **Fecha del Examen**: Seleccionar fecha
   - **Presentar Ante**: Especificar institución destino
3. Enviar solicitud

#### Seguimiento
- Ver estado de solicitudes en la tabla
- Recibir email cuando la constancia esté lista
- Descargar PDF desde la vista de solicitudes

### Para Administradores

1. **Acceder al Panel**:
   - Ir a **Constancias de Examen** → **Gestionar Constancias**
   - Solo accesible para administradores

2. **Gestionar Solicitudes**:
   - Ver todas las solicitudes pendientes y completadas
   - Usar filtros por estado y búsqueda por texto
   - Subir archivos PDF para solicitudes pendientes

3. **Subir Constancias**:
   - Seleccionar archivo PDF
   - El sistema actualiza automáticamente el estado
   - El PDF queda disponible para descarga

## 🗂️ Estructura de Archivos

```
modules/Constancias de Examen/
├── README.md                           # Esta documentación
├── moduleFunctions.php                 # Funciones principales
├── student_constancias.php             # Vista principal estudiantes
├── admin_constancias.php               # Vista principal administradores
├── studentView/
│   ├── css/
│   │   └── student.css                 # Estilos para estudiantes
│   ├── js/
│   │   └── student.js                  # JavaScript para estudiantes
│   └── includes/
│       ├── submit.php                  # Manejo de solicitudes
│       ├── table.php                   # Tabla de solicitudes
│       └── search_courses.php          # Endpoint autocompletado
├── adminView/
│   ├── css/
│   │   └── admin.css                   # Estilos para administradores
│   ├── js/
│   │   └── admin.js                    # JavaScript para administradores
│   └── includes/
│       ├── admin_functions.php         # Funciones administrativas
│       └── upload.php                  # Manejo de subida
└── credentials/
    └── firebase-credentials.json       # Credenciales Firebase (opcional)
```

## 🔧 Estructura de Datos

### Identificación de Estudiantes

El módulo usa el sistema de documentos personales de Gibbon:

```sql
-- Paso 1: Obtener ID del tipo de documento
SELECT gibbonPersonalDocumentTypeID 
FROM gibbonPersonalDocumentType 
WHERE name = 'Documento'

-- Paso 2: Buscar documento del estudiante
SELECT documentNumber 
FROM gibbonPersonalDocument 
WHERE foreignTable = 'gibbonPerson' 
  AND foreignTableID = :gibbonPersonID 
  AND gibbonPersonalDocumentTypeID = :tipoID
```

### Estructura en Firestore

```javascript
{
  dniAlumno: "12345678",
  nombre: "Juan Pérez",
  email: "juan.perez@escuela.edu",
  examen: {
    materia: "Matemáticas",
    fechaExamen: "2024-06-15"
  },
  presentarAnte: "Universidad Nacional",
  fechaPedido: "2024-06-10T10:30:00Z",
  estado: "pendiente|completado",
  pdfUrl: "https://storage.googleapis.com/...",
  uploadedBy: "admin@escuela.edu",
  uploadedAt: "2024-06-11T14:20:00Z"
}
```

### Autocompletado de Materias

```sql
SELECT gibbonCourseID, name, nameShort 
FROM gibbonCourse 
WHERE (name LIKE :searchTerm OR nameShort LIKE :searchTerm) 
ORDER BY name ASC 
LIMIT 10
```

## 🔄 Flujo de Trabajo

### 1. Solicitud de Estudiante
```
Estudiante → Completa formulario → Sistema crea documento en Firestore
```

### 2. Gestión Administrativa
```
Admin → Ve solicitudes pendientes → Sube PDF → Sistema actualiza estado
```

### 3. Notificación
```
Sistema → Envía email automático → Estudiante → Descarga constancia
```

## 🚨 Troubleshooting

### Problemas Comunes

#### Error: "No se encontró el tipo de documento"
**Causa**: No existe un tipo de documento llamado "Documento"
**Solución**: 
1. Ir a **User Admin** → **Personal Documents**
2. Verificar que tenga la opción de documento en su perfil

#### Error: "No se encontró el documento del estudiante"
**Causa**: El estudiante no tiene DNI registrado en el sistema
**Solución**: 
1. Ir a **User Admin** → **Personal Documents**
2. Verificar que tenga la opción de documento en su perfil
3. Ingresar el número de DNI

#### Error: "Error al subir PDF"
**Causa**: Problemas con Firebase Storage
**Solución**: 
1. Verificar credenciales de Firebase
2. Comprobar permisos del bucket de Storage
3. Verificar que las credenciales tengan permisos adecuados

#### Autocompletado no funciona
**Causa**: Problemas con la tabla `gibbonCourse`
**Solución**:
1. Verificar que la tabla tenga datos
2. Revisar permisos de acceso a la base de datos
3. Verificar que el endpoint `search_courses.php` sea accesible

#### Error de Firebase
**Causa**: Configuración incorrecta de credenciales
**Solución**:
1. Verificar configuración en `moduleFunctions.php`
2. Comprobar permisos del bucket de Storage
3. Verificar que las credenciales tengan permisos adecuados

## 🔒 Seguridad

### Validaciones Implementadas
- ✅ Verificación de permisos de usuario
- ✅ Validación de datos de entrada
- ✅ Sanitización de parámetros SQL
- ✅ Control de acceso por roles
- ✅ Validación de tipos de archivo (solo PDF)

### Recomendaciones
- Mantener credenciales de Firebase seguras
- Revisar logs regularmente
- Actualizar Gibbon y el módulo periódicamente
- Hacer backups regulares de Firestore

## 📞 Soporte

### Información del Módulo
- **Versión**: 1.0.0
- **Compatibilidad**: Gibbon 20.0+
- **Última Actualización**: Junio 2025

### Recursos Adicionales
- [Documentación de Gibbon](https://gibbonedu.org/docs/)
- [Firebase Documentation](https://firebase.google.com/docs)
---

**Desarrollado para Gibbon** - Sistema de Gestión Escolar 