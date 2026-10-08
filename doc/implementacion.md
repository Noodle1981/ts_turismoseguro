# Guía de Implementación: Turismo Seguro San Juan

Esta guía detalla los pasos exactos para inicializar, configurar y ejecutar el proyecto **Turismo Seguro San Juan** con arquitectura desacoplada en dos carpetas: **`server/`** (Laravel API) y **`client/`** (Vue 3 SPA).

---

## 1. Requisitos Previos

Asegúrate de contar con las siguientes herramientas en tu entorno:
- **PHP 8.2 o superior**
- **Composer** (gestor de paquetes PHP)
- **Node.js** (v18 o superior) y **npm**
- **Git**
- **SQLite** (integrado de forma nativa en PHP)

---

## 2. Paso 0: Resguardo del Código Histórico (Rama Git `legacy`)

Antes de crear las carpetas de código y comenzar la implementación limpia, se congela el estado original del prototipo en una rama dedicada:

```bash
# 1. Crear y cambiarse a la rama legacy
git checkout -b legacy

# 2. Confirmar que el contenido de legacy/ y la documentación inicial queden registrados
git add .
git commit -m "chore: resguardo del prototipo original legacy"

# 3. Regresar a la rama principal para estructurar server/ y client/
git checkout main
```

---

## 3. Paso 1: Inicialización del Backend API (`server/`)

### 1.1 Crear el proyecto Laravel
Desde la raíz del proyecto (`ts_turismoseguro/`):

```bash
# Crear la carpeta server con Laravel
composer create-project laravel/laravel server

# Ingresar a server
cd server
```

### 1.2 Configurar la Base de Datos (SQLite)
1. Crear el archivo físico de la base de datos:
   ```bash
   # En Windows (PowerShell)
   New-Item -Path "database/database.sqlite" -ItemType "file" -Force

   # En Linux / macOS
   touch database/database.sqlite
   ```

2. Configurar el archivo `server/.env`:
   ```env
   APP_NAME="Turismo Seguro San Juan API"
   APP_ENV=local
   APP_URL=http://localhost:8000
   FRONTEND_URL=http://localhost:5173

   DB_CONNECTION=sqlite
   # Comentar o dejar vacías las variables de MySQL:
   # DB_HOST=127.0.0.1
   # DB_PORT=3306

   SESSION_DRIVER=cookie
   SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
   ```

3. Instalar soporte de API y Sanctum:
   ```bash
   php artisan install:api
   ```

4. Configurar CORS (`server/config/cors.php`):
   Asegurar que `allowed_origins` admita el cliente de desarrollo y el dominio privado de producción:
   ```php
   'paths' => ['api/*', 'sanctum/csrf-cookie'],
   'allowed_methods' => ['*'],
   'allowed_origins' => [
       'http://localhost:5173',
       'http://127.0.0.1:5173',
       'https://turismoseguro.com.ar',
   ],
   'supports_credentials' => true,
   ```

### 1.3 Generar Modelos, Migraciones y Seeders
```bash
php artisan make:model Department -m
php artisan make:model Property -m
php artisan make:model PropertyImage -m
php artisan make:model PropertyAmenity -m
php artisan make:model PropertyRule -m
php artisan make:model PropertyAvailability -m
php artisan make:model HostSubscription -m
php artisan make:model ValidatorCommission -m
php artisan make:model Experience -m
php artisan make:model ExperienceImage -m
php artisan make:model ExperienceInclude -m
php artisan make:model ExperienceRecommendation -m
php artisan make:model VerificationRequest -m

# Crear controladores API y Form Requests
php artisan make:controller Api/Public/PropertyCatalogController
php artisan make:controller Api/Public/ExperienceCatalogController
php artisan make:controller Api/Public/DepartmentController
php artisan make:controller Api/Auth/AuthController
php artisan make:controller Api/Auth/GoogleAuthController
php artisan make:controller Api/Host/HostPropertyController
php artisan make:controller Api/Host/HostAvailabilityController
php artisan make:controller Api/Validator/ValidatorAuditController
php artisan make:controller Api/Validator/ValidatorCommissionController
php artisan make:controller Api/Supervisor/SupervisorReviewController
php artisan make:controller Api/Admin/AdminDashboardController
php artisan make:controller Api/Admin/AdminCommissionController
```

---

## 4. Paso 2: Inicialización del Frontend (`client/`)

### 2.1 Crear el proyecto Vue 3 con Vite y TypeScript
Desde la raíz del proyecto (`ts_turismoseguro/`):

```bash
# Crear la carpeta client con plantilla Vue + TypeScript
npm create vite@latest client -- --template vue-ts

# Ingresar a client e instalar dependencias
cd client
npm install
```

### 2.2 Instalar Librerías Clave (Tailwind, Router, Pinia, Axios)
```bash
# Tailwind CSS y utilidades
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p

# Enrutador, estado global y cliente HTTP
npm install vue-router pinia axios
```

### 2.3 Configurar Variables de Entorno (`client/.env`)
Crear el archivo `client/.env`:
```env
VITE_API_BASE_URL=http://localhost:8000/api/v1
VITE_APP_NAME="Turismo Seguro San Juan"
```

### 2.4 Configurar Tailwind CSS (`client/tailwind.config.js`)
Configurar rutas y colores institucionales definidos en `doc/visual.md`:
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          900: '#1e3a8a',
          800: '#1e40af',
        },
        safe: {
          600: '#059669',
        },
        warm: {
          600: '#ea580c',
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        display: ['Montserrat', 'sans-serif'],
      }
    },
  },
  plugins: [],
}
```

---

## 5. Paso 3: Ejecución en Entorno de Desarrollo

Para trabajar en el proyecto se inician ambos servicios en terminales independientes:

### Terminal 1 — Backend API (Laravel):
```bash
cd server
php artisan serve
# Escuchando en http://localhost:8000
```

### Terminal 2 — Frontend SPA (Vue 3 + Vite):
```bash
cd client
npm run dev
# Disponible en http://localhost:5173
```

---

## 6. Verificación de Seguridad y Salud del Entorno

1. **Prueba de Endpoint CSRF:**  
   Verificar en el navegador que `http://localhost:8000/sanctum/csrf-cookie` devuelva estado HTTP 204 y asigne la cookie `XSRF-TOKEN`.
2. **Prueba de Catálogo Público:**  
   Verificar que `GET http://localhost:8000/api/v1/properties` responda un JSON estructurado con la lista de propiedades certificadas.
3. **Persistencia SQLite:**  
   Confirmar que las migraciones corran correctamente ejecutando `php artisan migrate --status` en la carpeta `server/`.
