---
name: api-contract-spec
description: Gobernanza Contract-First mediante OpenAPI 3.1 en contracts/openapi.yaml. Sincronización estricta entre contratos YAML, API Resources de Laravel y tipos TypeScript en Vue 3.
license: Proprietary
metadata:
  project: "Turismo Seguro San Juan"
  version: "1.0.0"
  role: "Gobernanza de Contratos y APIs"
---

# 📜 Skill: API Contract Specification (OpenAPI 3.1)

> **Principio Rector: Contract-First**  
> Ningún endpoint, parámetro de consulta, cuerpo de petición ni respuesta JSON se codifica en Laravel o se consume en Vue 3 sin haber sido definido primero en [`contracts/openapi.yaml`](file:///d:/turismo_seguro/contracts/openapi.yaml).
> El contrato OpenAPI 3.1 es la **Única Fuente de Verdad (Single Source of Truth)** técnica del sistema.

---

## 1. Flujo de Trabajo Contract-First

```
+--------------------------------------------------------+
| 1. Definición / Edición en contracts/openapi.yaml      |
+--------------------------------------------------------+
                           |
        +------------------+------------------+
        v                                     v
+-------------------------------+ +--------------------------------+
| 2. Backend (Laravel API)      | | 3. Frontend (Vue 3 / TS)       |
| - Form Request (reglas)       | | - TypeScript types (schemas)   |
| - Controller (Thin)           | | - Composables (useApi / fetch) |
| - API Resource (serialización)| | - Vistas y Componentes         |
+-------------------------------+ +--------------------------------+
        \                                     /
         +-----------------+-----------------+
                           v
        +-------------------------------------+
        | 4. Verificación y Testing           |
        | - php artisan test (status & shape) |
        | - vue-tsc --noEmit (tipos estrictos)|
        +-------------------------------------+
```

---

## 2. Ubicación y Estructura del Archivo de Contrato

El archivo canónico reside en:
```
contracts/openapi.yaml
```

### Cabecera Estándar (OpenAPI 3.1.0):
```yaml
openapi: 3.1.0
info:
  title: Turismo Seguro San Juan - API
  version: 1.0.0
  description: API REST oficial para la plataforma de validación y alquileres seguros de San Juan.
servers:
  - url: http://localhost:8000/api/v1
    description: Servidor de desarrollo local
  - url: https://api.turismoseguro.com.ar/api/v1
    description: Servidor de producción
tags:
  - name: Health
    description: Verificación de estado del servicio
  - name: Auth
    description: Autenticación, roles y recuperación de contraseña
  - name: Properties
    description: Catálogo y gestión de alojamientos
  - name: Experiences
    description: Experiencias y operadores turísticos
  - name: Departments
    description: Departamentos de San Juan
  - name: Validations
    description: Auditorías in situ y flujo del sello TS
  - name: Commissions
    description: Comisiones y liquidaciones
```

---

## 3. Mapeo Trilateral Obligatorio

Cada entidad debe tener representación idéntica en los tres niveles:

| Entidad | OpenAPI (`components/schemas`) | Laravel (`app/Http/Resources/`) | Vue 3 (`client/src/types/`) |
| :--- | :--- | :--- | :--- |
| Propiedad | `PropertyResource` | `PropertyResource.php` | `interface Property` |
| Departamento | `DepartmentResource` | `DepartmentResource.php` | `interface Department` |
| Usuario | `UserResource` | `UserResource.php` | `interface User` |
| Inspección | `AuditResource` | `AuditResource.php` | `interface Audit` |
| Comisión | `CommissionResource` | `CommissionResource.php` | `interface Commission` |

---

## 4. Estandarización de Respuestas y Errores

### 4.1 Recursos Simples
```yaml
type: object
properties:
  message:
    type: string
    example: "Operación realizada con éxito."
  data:
    $ref: '#/components/schemas/NombreDelRecurso'
required:
  - data
```

### 4.2 Listados Paginados (Estándar Laravel)
```yaml
type: object
properties:
  data:
    type: array
    items:
      $ref: '#/components/schemas/NombreDelRecurso'
  links:
    type: object
    properties:
      first: { type: string, nullable: true }
      last: { type: string, nullable: true }
      prev: { type: string, nullable: true }
      next: { type: string, nullable: true }
  meta:
    type: object
    properties:
      current_page: { type: integer }
      from: { type: integer, nullable: true }
      last_page: { type: integer }
      per_page: { type: integer }
      to: { type: integer, nullable: true }
      total: { type: integer }
required:
  - data
  - links
  - meta
```

### 4.3 Errores de Validación (HTTP 422 Unprocessable Content)
```yaml
type: object
properties:
  message:
    type: string
    example: "Los datos proporcionados no son válidos."
  errors:
    type: object
    additionalProperties:
      type: array
      items:
        type: string
    example:
      email:
        - "El campo correo electrónico es obligatorio."
      password:
        - "La contraseña debe tener al menos 8 caracteres."
required:
  - message
  - errors
```

---

## 5. Códigos HTTP Semánticos

- `200 OK`: Petición exitosa con datos de retorno (`GET`, `PUT`, `PATCH`).
- `201 Created`: Recurso creado exitosamente (`POST`).
- `204 No Content`: Eliminación o acción exitosa sin cuerpo de retorno (`DELETE`).
- `400 Bad Request`: Parámetros malformados o inválidos en la petición.
- `401 Unauthorized`: No autenticado o token expirado/ausente.
- `403 Forbidden`: Autenticado pero sin permisos para el rol del usuario.
- `404 Not Found`: Recurso no encontrado.
- `422 Unprocessable Content`: Fallo de validación en Form Request.
- `500 Internal Server Error`: Error no controlado del servidor.

---

## 6. Autenticación y Seguridad en el Contrato

```yaml
components:
  securitySchemes:
    sanctumCookie:
      type: apiKey
      in: cookie
      name: XSRF-TOKEN
      description: Autenticación por cookie de sesión SPA con Laravel Sanctum.
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT
      description: Token Bearer personal para clientes API móviles.
```

---

## 7. Regla de Oro de Actualización

Al implementar una nueva funcionalidad (por ejemplo, `Fase 2: Catálogo de Departamentos`):
1. **Paso 1:** Abrir `contracts/openapi.yaml` y declarar `GET /departments` con su schema `DepartmentResource`.
2. **Paso 2:** Crear la migración, modelo, `DepartmentResource.php` y controlador en Laravel.
3. **Paso 3:** Exportar o sincronizar la `interface Department` en `client/src/types/department.ts`.
4. **Paso 4:** Conectar el composable `useDepartments.ts` y verificar que no existan errores de `vue-tsc`.
