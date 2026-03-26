Actúa como un arquitecto de software y desarrollador senior con 15 años de experiencia, especializado en Laravel, sistemas CMS headless, arquitectura multi-tenant, paneles administrativos y APIs REST escalables.

Necesito que construyas un proyecto completo desde cero, con enfoque profesional, mantenible, escalable y bien estructurado.

Objetivo del proyecto

Quiero desarrollar un CMS headless multi-tenant, centralizado, que me permita administrar múltiples clientes desde un solo sistema.

Este sistema no debe construir el frontend público de cada cliente.
El objetivo del sistema termina en:

panel superadmin
panel cliente
administración de contenido
definición de secciones y campos
exposición de endpoints REST por cliente

Luego, con esos endpoints, yo desarrollaré externamente el frontend personalizado de cada cliente.

Idea general del sistema

El sistema debe funcionar como un CMS/API generator multi-cliente.

Habrá dos tipos principales de usuario:

1. Superadmin

Es el administrador global del sistema. Desde aquí debo poder:

crear clientes
editar clientes
eliminar o desactivar clientes
crear usuarios para cada cliente
definir la estructura de contenido de cada cliente
crear secciones para cada cliente
definir los campos de cada sección
decidir si una sección tendrá endpoint público
gestionar el contenido de cualquier cliente si es necesario
visualizar todos los clientes y sus configuraciones
administrar el estado general del sistema
2. Usuario cliente

Cada cliente debe tener su propio dashboard privado. Desde ahí podrá:

iniciar sesión
acceder solo a su información
ver únicamente las secciones que fueron configuradas para su cuenta
crear, editar y eliminar contenido dentro de esas secciones
subir imágenes y archivos según corresponda
editar textos, enlaces, galerías, datos y contenido general
nunca modificar la estructura del CMS
nunca crear nuevas secciones o campos, salvo que eso se permita explícitamente en una futura evolución
Lo que debe hacer el sistema

El sistema debe permitirme, como superadmin, definir para cada cliente una estructura de contenido personalizada.

Ejemplo:
para un cliente puedo definir las secciones:

servicios
equipo
blog
galería
testimonios
contacto

Para otro cliente puedo definir:

programas
podcasts
auspiciadores
noticias

Cada sección debe tener una estructura de campos configurable.

Ejemplo:

Sección "Servicios"

Campos:

título
descripción
imagen
icono
link
orden
activo
Sección "Equipo"

Campos:

nombre
cargo
foto
biografía
redes sociales
Sección "Blog"

Campos:

título
slug
imagen destacada
extracto
contenido
fecha de publicación
autor
estado

El cliente luego entra a su dashboard y administra los contenidos según la estructura que ya fue definida.

Importante

Este sistema no es un constructor visual de sitios.
No debe existir un módulo para crear páginas visuales ni plantillas públicas.

El sistema debe ser estrictamente:

panel administrativo
motor de contenido
motor de secciones
motor de campos dinámicos
API REST pública por cliente
Stack requerido

Debes construir este proyecto utilizando exclusivamente este stack base:

Backend
Laravel 13
PHP 8.4 o compatible con Laravel 13
Base de datos
MySQL 8
Panel administrativo
Laravel nativo
Blade
TailwindCSS
Alpine.js solo donde sea necesario
Livewire solo si realmente aporta valor en módulos complejos
Entorno de desarrollo

Debemos usar Docker para todo el entorno de desarrollo, incluyendo:

aplicación Laravel
MySQL
phpMyAdmin opcional
Node/Vite si corresponde
cualquier servicio auxiliar necesario

No quiero instalación local manual fuera de Docker.
Todo debe correr mediante contenedores.

Requerimientos técnicos obligatorios
Arquitectura

Debes construir el proyecto con una arquitectura clara, mantenible y escalable.

Aplica buenas prácticas de:

separación de responsabilidades
controladores delgados
lógica de negocio en servicios o actions cuando corresponda
validación mediante Form Requests
serialización API mediante API Resources
uso correcto de Policies / Gates / Middleware
código limpio y bien nombrado
estructura fácil de mantener por otros desarrolladores
Multi-tenant

El sistema debe ser multi-tenant a nivel lógico.

No necesito multi-base-de-datos por tenant en esta etapa.

Cada cliente debe estar aislado por:

client_id
permisos
middleware
scoping de consultas
políticas de acceso

Cada usuario cliente solo puede acceder a los datos de su propio cliente.

El superadmin puede acceder a todos.

Módulos que debe incluir el sistema
1. Autenticación
login
logout
recuperación básica si la implementas
protección de rutas
sesiones seguras
2. Gestión de usuarios

Debe existir:

usuarios superadmin
usuarios cliente

Campos sugeridos:

nombre
email
contraseña
rol
estado
cliente asociado si aplica
3. Gestión de clientes

Debo poder:

crear clientes
editar clientes
activar / desactivar clientes
definir nombre comercial
slug
dominio opcional
logo
configuración general
estado

Campos sugeridos:

id
name
slug
domain
logo
status
settings json
timestamps
4. Gestión de secciones

Debo poder crear secciones por cliente.

Cada sección debe tener:

nombre
slug
descripción
tipo
orden
visible
endpoint público sí/no
endpoint slug
configuración adicional

Tipos sugeridos de sección:

single: una sola entrada
collection: múltiples registros

Ejemplos:

“Nosotros” = single
“Servicios” = collection
“Blog” = collection
“Contacto” = single
5. Gestión de campos dinámicos por sección

Debo poder definir campos para cada sección.

Tipos de campo soportados, como mínimo:

text
textarea
richtext
number
boolean
date
datetime
email
url
image
file
select
json
repeater si decides soportarlo
gallery si decides soportarlo

Cada campo debe poder tener configuración como:

nombre interno
label visible
slug / key
tipo
requerido
valor por defecto
placeholder
ayuda
orden
visible
ancho o layout opcional
opciones en caso de select
si aparece en listados
si aparece en endpoint
reglas de validación
6. Gestión de contenido

Cada sección debe permitir gestionar contenido según su tipo:

Sección tipo single

Debe permitir editar una sola entrada.

Sección tipo collection

Debe permitir:

listar registros
crear registro
editar registro
eliminar registro
ordenar si corresponde
filtrar por estado si aplica

Cada registro puede tener:

título opcional
slug opcional
estado
published_at opcional
timestamps
valores dinámicos asociados a los campos de la sección
7. Gestión de archivos y medios

Debe existir soporte para:

carga de imágenes
carga de archivos
asociación de medios a registros
vista previa básica
almacenamiento organizado

Usa almacenamiento local inicialmente, preparado para migrar a S3 compatible en el futuro.

8. API pública

Este es uno de los puntos más importantes.

El sistema debe exponer endpoints REST públicos por cliente y por sección.

Ejemplos esperados:

/api/public/{cliente}/{seccion}
/api/public/{cliente}/{seccion}/{slug}

También puedes agregar endpoints complementarios, por ejemplo:

/api/public/{cliente}/sections
/api/public/{cliente}/settings
/api/public/{cliente}/menu

La API debe:

responder en JSON
estar bien estructurada
ser consistente
usar API Resources
ocultar información interna innecesaria
filtrar solo contenido publicado o visible públicamente
respetar si una sección fue marcada como pública o no
permitir detalle por slug en secciones collection si corresponde
9. Dashboard cliente

Cada cliente debe ver solo:

sus secciones
sus contenidos
su configuración básica, si la habilito
su panel principal

No debe ver:

otros clientes
configuración global
estructura interna de otros tenants
administración avanzada del CMS
10. Dashboard superadmin

Debe permitir:

ver todos los clientes
administrar usuarios
administrar secciones
administrar campos
administrar contenido
revisar endpoints
entrar a gestionar contenido de un cliente
visualizar estado general del sistema
Requerimientos de base de datos

Diseña una base de datos sólida y coherente.

Propón y crea migraciones para entidades como mínimo similares a:

users
clients
client_users o relación equivalente si lo resuelves distinto
sections
section_fields
content_entries
content_entry_values
media o tabla equivalente
password_reset_tokens si aplica
sessions si aplica

Puedes ajustar nombres si propones una estructura mejor, pero debes mantener el objetivo funcional.

Propuesta técnica esperada para el almacenamiento de campos dinámicos

Quiero una solución seria y mantenible para campos dinámicos.

Puedes usar una estructura tipo:

content_entries
content_entry_values

Donde:

content_entries representa el registro principal
content_entry_values almacena los valores por campo dinámico

Evalúa también si ciertos campos estructurales deben vivir directamente en content_entries, por ejemplo:

title
slug
status
published_at
sort_order

Y deja los demás campos personalizados en la tabla de valores.

Quiero una implementación que sea mantenible, clara y escalable, no una solución improvisada.

Reglas de permisos
Superadmin

Puede:

todo
Cliente

Puede:

ver solo su panel
ver solo sus secciones
editar solo sus contenidos
crear registros en secciones habilitadas
subir archivos si tiene permisos
nunca modificar estructura global

Implementa middleware y policies correctamente.

Interfaz administrativa

No necesito un diseño extravagante, pero sí una interfaz:

limpia
profesional
clara
usable
ordenada
responsive en lo básico

El foco debe estar en funcionalidad, claridad y mantenibilidad.

API y frontend público

Reitero algo crítico:

No debes construir ningún frontend público de cliente.

No debes crear:

templates web públicas
landing pages públicas por cliente
generadores visuales de sitio

Solo debes construir:

el backend
el dashboard
la estructura CMS
los endpoints REST

Yo luego consumiré esos endpoints desde proyectos externos personalizados.

Entorno Docker

Debes dejar el proyecto listo para desarrollo con Docker.

Quiero como mínimo:

contenedor de Laravel / PHP
contenedor de MySQL
contenedor de Nginx o Apache según tu propuesta
contenedor para Node/Vite si hace falta
volúmenes bien configurados
variables de entorno
archivo docker-compose.yml o equivalente actual

Debes entregar configuración funcional para levantar todo con algo como:

docker compose up -d --build

El proyecto debe quedar corriendo en Docker de forma simple.

Seeder y datos de ejemplo

Necesito seeders para probar el sistema.

Crea como mínimo:

un usuario superadmin por defecto
uno o dos clientes de ejemplo
un usuario cliente para cada ejemplo
algunas secciones de ejemplo
algunos campos de ejemplo
algunos registros de ejemplo
algunos endpoints funcionales para probar
Calidad del código

El código debe ser:

limpio
modular
bien nombrado
documentado donde valga la pena
fácil de mantener
sin duplicación innecesaria
sin sobreingeniería absurda
pensado para crecer
Entregables esperados

Quiero que construyas el proyecto completo incluyendo:

estructura base Laravel
configuración Docker completa
migraciones
modelos
relaciones
seeders
factories si aportan
autenticación
middleware
policies o gates
controladores web
controladores API
requests de validación
services/actions donde se necesiten
vistas Blade
rutas web
rutas API
recursos API
gestión de archivos
dashboard superadmin
dashboard cliente
CRUD de clientes
CRUD de secciones
CRUD de campos
CRUD de contenidos
endpoints públicos funcionales
Forma de trabajo

Quiero que construyas esto con criterio de producto real, no como demo incompleta.

Antes de escribir código, define y respeta:

arquitectura
entidades
relaciones
flujo de permisos
estrategia para campos dinámicos
estrategia para endpoints
Restricciones importantes
No usar Filament
No usar WordPress
No usar GraphQL
No construir frontend público de clientes
No improvisar estructura sin justificarla
No meter complejidad innecesaria
No romper separación entre panel administrativo y API pública
Resultado esperado

El resultado final debe ser una plataforma donde:

yo creo un cliente
yo le configuro sus secciones y campos
el cliente entra a su dashboard y administra su contenido
el sistema expone endpoints JSON por cliente
yo consumo esos endpoints en un frontend externo y personalizado
Extra deseable

Si lo consideras conveniente, puedes incorporar además:

versión inicial de menú configurable por cliente
settings globales por cliente
estado draft/published en contenidos
ordenamiento manual
slugs automáticos
filtros en listados administrativos
soft deletes donde tenga sentido
Instrucción final

Construye este proyecto como lo haría un equipo senior en un entorno profesional real.

Prioriza:

claridad
robustez
mantenibilidad
escalabilidad
buena arquitectura
experiencia de uso del panel
consistencia de la API

Si debes tomar decisiones técnicas, tómalas como un desarrollador senior, explicándolas y aplicándolas de manera coherente con el objetivo del proyecto.
