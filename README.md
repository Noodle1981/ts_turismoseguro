# 🛡️ Turismo Seguro San Juan

Plataforma oficial de certificación técnica *in situ*, catálogo de alquileres temporarios y experiencias turísticas seguras para la provincia de San Juan, Argentina.

---

## 🏛️ Arquitectura del Repositorio (Monorepo Desacoplado)

```
turismo_seguro/
├── server/          # Backend Laravel (API REST, SQLite, Sanctum, PHP 8.4)
├── client/          # Frontend Vue 3 (Vite, TypeScript, Pinia, Tailwind CSS, pnpm)
├── contracts/       # OpenAPI 3.1 (Single Source of Truth de la API)
├── design-system/   # Tokens y guías maestras de diseño
├── doc/             # Documentación técnica y plan maestro de implementación
└── legacy/          # Prototipo y datos de referencia originales
```

---

## 🚀 Requisitos de Entorno

- **PHP 8.4+** con extensión `pdo_sqlite` habilitada
- **Composer 2.8+**
- **Node.js 20+**
- **pnpm 10+** (`pnpm -v`)

---

## ⚡ Guía de Arranque Rápido

### 1. Backend (`server/`)
```bash
cd server
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
El backend escuchará en `http://localhost:8000`.

### 2. Frontend (`client/`)
```bash
cd client
cp .env.example .env
pnpm install
pnpm dev
```
El cliente Vite escuchará en `http://localhost:5173`.

### 3. Documentación Interactiva de la API (Scalar)
Desde la raíz del monorepo:
```bash
npm run docs
```
Abre la interfaz interactiva de Scalar en `http://localhost:5054` con recarga automática al modificar [`contracts/openapi.yaml`](file:///d:/turismo_seguro/contracts/openapi.yaml).

### 4. Generación de Tipos TypeScript para Vue 3
```bash
pnpm run api:types
```
Genera automáticamente las interfaces y schemas en `client/src/types/schema.d.ts`.

---

## 🧪 Pruebas de Calidad

- **Backend:** `cd server && php artisan test`
- **Frontend (Typecheck & Build):** `cd client && pnpm run build`
