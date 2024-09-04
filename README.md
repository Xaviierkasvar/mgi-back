# Módulo de Gestión de Inventarios

## Descripción General

Este proyecto implementa un **módulo de gestión de inventarios** utilizando **Laravel** para el backend y **React** para el frontend. El sistema proporciona la funcionalidad de CRUD (Crear, Leer, Actualizar y Eliminar) para gestionar productos y permite la actualización en tiempo real del stock, con notificaciones simuladas a sistemas externos cuando cambian los niveles de inventario.

El proyecto también incluye un sistema de autenticación basado en **JWT** para proteger las rutas de la API, garantizando que solo los usuarios autenticados puedan realizar acciones críticas sobre los productos.

## Requerimientos

-   **Backend**: Laravel 8+.
-   **Frontend**: React 17+.
-   **Base de datos**: MySQL.
-   **Autenticación**: JSON Web Token (JWT).
-   **Contenerización** (opcional): Docker.
-   **Documentación de API**: Swagger/OpenAPI (opcional).

## Características Principales

1. **CRUD de Productos**:

    - Crear, leer, actualizar y eliminar productos en la base de datos mediante endpoints RESTful.
    - Campos del modelo de producto:
        - `nombre`: cadena de texto.
        - `descripcion`: texto largo.
        - `precio`: decimal.
        - `stock`: entero.

2. **Gestión de Inventario**:

    - Actualización del stock de productos en tiempo real.
    - Disminución del stock al vender productos.
    - Alertas si el stock es bajo.

3. **Notificaciones**:

    - Simulación de integración con sistemas externos (por ejemplo, Shopify o WooCommerce).
    - Envío de notificaciones cuando el stock cambia (mediante logs o mensajes HTTP).

4. **Autenticación y Autorización**:
    - Autenticación de usuarios usando JWT.
    - Solo los usuarios autenticados pueden realizar operaciones de creación, edición o eliminación de productos.
5. **Documentación de API**:
    - Documentación de las API utilizando Swagger/OpenAPI (opcional).

## Instalación

### Clonar el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
```

### Backend (Laravel)

1. Instalar dependencias:

    ```bash
    composer install
    ```

2. Copiar el archivo `.env.example` a `.env` y configurar las variables de entorno (base de datos, JWT, etc.).

3. Generar la clave de la aplicación:

    ```bash
    php artisan key:generate
    ```

4. Ejecutar migraciones para crear las tablas en la base de datos:

    ```bash
    php artisan migrate
    ```

5. Ejecutar el servidor de desarrollo de Laravel:
    ```bash
    php artisan serve
    ```

### Frontend (React)

1. Instalar dependencias:

    ```bash
    npm install
    ```

2. Iniciar el servidor de desarrollo de React:
    ```bash
    npm start
    ```

## Uso

-   **Listar productos**: Visitar la URL `/api/productos`.
-   **Crear productos**: Usar el endpoint `POST /api/productos`.
-   **Actualizar productos**: Usar el endpoint `PUT /api/productos/{id}`.
-   **Eliminar productos**: Usar el endpoint `DELETE /api/productos/{id}`.

## Notificaciones

El sistema simula el envío de notificaciones cuando se actualiza el stock de un producto. Estas notificaciones pueden ser logs o mensajes HTTP a un sistema externo.

## Autenticación

Este sistema utiliza JWT para la autenticación. Solo los usuarios autenticados pueden crear, actualizar o eliminar productos.

### Generar token JWT

1. Usar el endpoint de login `POST /api/login` para obtener un token.
2. Incluir el token en el header `Authorization` para acceder a rutas protegidas.

## Documentación de la API

Si activas la documentación de Swagger, puedes acceder a ella en la ruta `/api/documentation`.
