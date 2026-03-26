# CMS Headless Multi-Tenant

Sistema CMS headless multi-tenant construido con Laravel 13, diseñado para gestionar contenido de múltiples clientes desde una sola plataforma centralizada.

## Características

- **Multi-tenant**: Gestión de múltiples clientes desde un solo sistema
- **Roles de usuario**: Superadmin y usuarios cliente con permisos diferenciados
- **Secciones dinámicas**: Cada cliente puede tener secciones de tipo "single" o "collection"
- **Campos configurables**: Sistema de campos dinámicos con múltiples tipos (texto, número, imagen, etc.)
- **API REST pública**: Endpoints JSON por cliente para consumir contenido
- **Panel administrativo**: Interfaz limpia y profesional con TailwindCSS
- **Gestión de archivos**: Sistema de medios para subir imágenes y archivos

## Stack Tecnológico

- **Backend**: Laravel 13, PHP 8.4
- **Base de datos**: MySQL 8
- **Frontend**: Blade, TailwindCSS, Alpine.js
- **Entorno**: Docker, Docker Compose

## Instalación

### Requisitos

- Docker
- Docker Compose

### Pasos

1. Clonar el repositorio:
```bash
git clone <repositorio>
cd simplesitio
```

2. Iniciar los contenedores:
```bash
docker compose up -d --build
```

3. Ejecutar migraciones:
```bash
docker compose exec app php artisan migrate --force
```

4. Ejecutar seeders (datos de ejemplo):
```bash
docker compose exec app php artisan db:seed --force
```

5. Crear enlace de storage:
```bash
docker compose exec app php artisan storage:link
```

## Acceso

Una vez instalado, accede a:

- **Aplicación**: http://localhost:8090
- **phpMyAdmin**: http://localhost:8081

### Credenciales de prueba

**Superadmin:**
- Email: `admin@cms.local`
- Password: `password`

**Usuario Cliente 1 (Tech Solutions):**
- Email: `juan@techsolutions.com`
- Password: `password`

**Usuario Cliente 2 (Creative Studio):**
- Email: `maria@creativestudio.com`
- Password: `password`

## Estructura del Proyecto

```
src/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Controladores del panel admin
│   │   │   ├── Client/       # Controladores del panel cliente
│   │   │   ├── Api/          # Controladores de API pública
│   │   │   └── Auth/         # Controladores de autenticación
│   │   ├── Middleware/       # Middleware personalizados
│   │   ├── Requests/         # Form Requests
│   │   └── ...
│   ├── Models/               # Modelos Eloquent
│   ├── Policies/             # Policies de autorización
│   └── Services/             # Servicios de negocio
├── database/
│   ├── migrations/           # Migraciones
│   └── seeders/              # Seeders
├── resources/
│   └── views/                # Vistas Blade
└── routes/
    ├── web.php               # Rutas web
    └── api.php               # Rutas API
```

## API Pública

La API expone endpoints para cada cliente configurado:

### Endpoints disponibles

```
GET /api/public/{client}                    # Información del cliente
GET /api/public/{client}/sections           # Listado de secciones públicas
GET /api/public/{client}/{section}          # Contenido de una sección
GET /api/public/{client}/{section}/{slug}   # Detalle de entrada específica
GET /api/public/{client}/settings           # Configuración del cliente
```

### Ejemplos

```bash
# Información del cliente
curl http://localhost:8090/api/public/tech-solutions

# Secciones disponibles
curl http://localhost:8090/api/public/tech-solutions/sections

# Contenido de servicios
curl http://localhost:8090/api/public/tech-solutions/services

# Detalle de un servicio específico
curl http://localhost:8090/api/public/tech-solutions/services/desarrollo-web
```

## Gestión de Clientes

### Superadmin puede:

- Crear/editar/eliminar clientes
- Crear usuarios para cada cliente
- Definir estructura de secciones por cliente
- Configurar campos dinámicos
- Gestionar todo el contenido

### Usuario Cliente puede:

- Ver solo su panel
- Administrar contenido de sus secciones
- Subir archivos a su biblioteca de medios
- No puede modificar la estructura

## Tipos de Campos Soportados

- `text` - Campo de texto corto
- `textarea` - Área de texto multilinea
- `richtext` - Editor de texto enriquecido
- `number` - Número
- `boolean` - Sí/No (checkbox)
- `date` - Fecha
- `datetime` - Fecha y hora
- `email` - Correo electrónico
- `url` - URL
- `image` - Imagen
- `file` - Archivo
- `select` - Lista desplegable
- `json` - JSON
- `repeater` - Campo repetible
- `gallery` - Galería de imágenes

## Comandos Útiles

```bash
# Ver logs
docker compose logs -f app

# Ejecutar comandos artisan
docker compose exec app php artisan <comando>

# Instalar dependencias composer
docker compose exec app composer install

# Acceder a la consola del contenedor
docker compose exec app bash

# Detener contenedores
docker compose down

# Reconstruir contenedores
docker compose up -d --build
```

## Seguridad

- Autenticación basada en sesiones
- Middleware de verificación de roles
- Policies para control de acceso a recursos
- Scoping de consultas por tenant
- Soft deletes en modelos principales

## Licencia

MIT
# simplesitios
