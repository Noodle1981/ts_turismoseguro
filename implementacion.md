# Guía de Implementación: Turismo Seguro San Juan

Esta guía detalla los pasos exactos para inicializar, configurar y ejecutar el proyecto **Turismo Seguro San Juan** desde cero en cualquier máquina.

---

## 1. Requisitos Previos
Asegúrate de tener instalado en tu máquina:
- **PHP 8.2 o superior**
- **Composer** (gestor de dependencias de PHP)
- **Node.js** (v18 o superior) y **npm**
- **SQLite** (generalmente viene preinstalado en macOS y Windows, o se instala fácilmente)

---

## 2. Paso 1: Inicialización del Proyecto Laravel

Si vas a crear el proyecto desde cero en una máquina nueva, ejecuta:

```bash
# 1. Crear el proyecto Laravel 11
composer create-project laravel/laravel ts_turismoseguro

# 2. Entrar al directorio
cd ts_turismoseguro
```

Si estás clonando el repositorio existente en otra máquina:

```bash
# 1. Instalar dependencias de PHP
composer install

# 2. Crear archivo de variables de entorno
copy .env.example .env     # En Windows
# cp .env.example .env     # En macOS/Linux

# 3. Generar la clave de la aplicación
php artisan key:generate
```

---

## 3. Paso 2: Configuración de la Base de Datos (SQLite)

1. Crea el archivo físico de la base de datos dentro de la carpeta `database`:
   ```bash
   # En Windows (PowerShell)
   New-Item -Path "database/database.sqlite" -ItemType "file"
   
   # En macOS/Linux/Git Bash
   touch database/database.sqlite
   ```

2. Abre el archivo `.env` en la raíz del proyecto y reemplaza la sección de la base de datos con lo siguiente:
   ```env
   DB_CONNECTION=sqlite
   # Deja las demás variables DB_* vacías o coméntalas
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=laravel
   # DB_USERNAME=root
   # DB_PASSWORD=
   ```

---

## 4. Paso 3: Configuración de Frontend (Vite + TypeScript + Tailwind CSS)

1. **Instalar dependencias de Node**:
   ```bash
   npm install
   ```

2. **Instalar Tailwind CSS y PostCSS**:
   ```bash
   npm install -D tailwindcss postcss autoprefixer
   npx tailwindcss init -p
   ```

3. **Instalar TypeScript**:
   ```bash
   npm install -D typescript
   npx tsc --init
   ```

4. **Configurar Tailwind (`tailwind.config.js`)**:
   Reemplaza el contenido de `tailwind.config.js` con:
   ```javascript
   /** @type {import('tailwindcss').Config} */
   export default {
     content: [
       "./resources/**/*.blade.php",
       "./resources/**/*.js",
       "./resources/**/*.ts",
       "./resources/**/*.vue",
     ],
     theme: {
       extend: {
         colors: {
           indigo: {
             primary: '#1e3a8a',
           },
           emerald: {
             safe: '#059669',
           },
           amber: {
             accent: '#d97706',
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

5. **Configurar Vite (`vite.config.js`)**:
   Modifica la configuración de Vite para soportar TypeScript (`app.ts`):
   ```javascript
   import { defineConfig } from 'vite';
   import laravel from 'laravel-vite-plugin';

   export default defineConfig({
       plugins: [
           laravel({
               input: ['resources/css/app.css', 'resources/js/app.ts'],
               refresh: true,
           }),
       ],
   });
   ```

6. **Renombrar y configurar archivos de Frontend**:
   - Renombra `resources/js/app.js` a `resources/js/app.ts`.
   - Agrega las directivas de Tailwind en `resources/css/app.css`:
     ```css
     @tailwind base;
     @tailwind components;
     @tailwind utilities;
     ```

---

## 5. Paso 4: Creación de Modelos, Migraciones y Seeders

Genera los archivos clave en el backend para migrar la base de datos legacy:

```bash
# Crear modelos con migraciones y controladores integrados
php artisan make:model Department -mc
php artisan make:model Property -mc
php artisan make:model PropertyImage -mc
php artisan make:model PropertyAmenity -mc
php artisan make:model Experience -mc
php artisan make:model ExperienceImage -mc
php artisan make:model VerificationRequest -mc
```

Una vez editadas las migraciones con la estructura definida en `arquitectura.md` y creados los seeders con los datos de `legacy/`:

```bash
# Correr migraciones y poblar la base de datos
php artisan migrate:fresh --seed
```

---

## 6. Paso 5: Ejecución del Proyecto en Desarrollo

Para ver el proyecto funcionando localmente, debes correr dos comandos en terminales separadas:

1. **Servidor Backend (Laravel)**:
   ```bash
   php artisan serve
   ```
   *Acceso por defecto en:* `http://127.0.0.1:8000`

2. **Compilador Frontend en tiempo real (Vite)**:
   ```bash
   npm run dev
   ```

---

## 7. Solución de Problemas Comunes

- **Error: "Database file does not exist"**:
  Verifica que el archivo `database/database.sqlite` esté creado y que la ruta en el `.env` esté configurada correctamente con `DB_CONNECTION=sqlite`.
- **Estilos de Tailwind no se aplican**:
  Asegúrate de que estás ejecutando `npm run dev` y que en tu vista principal de Blade (`layouts/app.blade.php`) hayas incluido la directiva `@vite(['resources/css/app.css', 'resources/js/app.ts'])` dentro del `<head>`.
