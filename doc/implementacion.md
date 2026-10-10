# Plan de Implementación: Turismo Seguro San Juan

Plan maestro, fase por fase, de todo el proyecto. Es un **documento vivo**: al terminar cada tarea se marca con `[x]`.

**Leyenda:** `[x]` completado · `[ ]` pendiente · `[~]` en curso

**Estrategia:** *Vertical Slices*. Cada fase entrega funcionalidad completa (backend + frontend conectados) y verificable. No se construye todo el front con datos falsos ni todo el back sin pantallas.

**Stack:** Laravel (API, `server/`) + Vue 3 Composition API con Composables (SPA, `client/`) + Tailwind CSS + SQLite (MVP) + Laravel Sanctum.

---

## Tablero de Progreso General

| Fase | Nombre | Estado |
| :--- | :--- | :---: |
| 0 | Fundaciones: documentación, skills y resguardo legacy | `[x]` Completada |
| 1 | Setup del esqueleto (`server/` y `client/`) | `[x]` Completada |
| 2 | Modelo de datos, migraciones y seeders desde legacy | `[x]` Completada |
| 3 | Autenticación, roles (5) y procedencia geográfica | `[ ]` Pendiente |
| 4 | Catálogo público (turistas y residentes locales - rol `rent`) | `[ ]` Pendiente |
| 5 | Panel del Cliente Oferente (`client`): ofertas, disponibilidad y validación | `[ ]` Pendiente |
| 6 | App del Validador: captación, auditoría in situ y comisiones | `[ ]` Pendiente |
| 7 | Panel del Supervisor: control de calidad y emisión del sello | `[ ]` Pendiente |
| 8 | Panel Super Admin: métricas, procedencia, usuarios y liquidación | `[ ]` Pendiente |
| 9 | Suscripciones y facturación del cliente oferente | `[ ]` Pendiente |
| 10 | Google OAuth, SEO y pulido UX | `[ ]` Pendiente |
| 11 | Testing, seguridad y hardening | `[ ]` Pendiente |
| 12 | Despliegue a producción | `[ ]` Pendiente |

---

## Requisitos Previos del Entorno

- [x] Git instalado
- [x] Node.js 18+ y npm
- [x] Python (requerido por el skill `ui-ux-pro-max`)
- [ ] PHP 8.2+ (verificar la versión mínima que exija la versión de Laravel elegida)
- [ ] Composer
- [ ] SQLite habilitado en PHP (`pdo_sqlite`)

> Verificar con: `php -v`, `composer -V`, `node -v`, `php -m | findstr sqlite`

---

## FASE 0 — Fundaciones `[x]`

Objetivo: dejar definido el *qué* y el *cómo* antes de escribir código.

- [x] Prototipo estático original resguardado en la rama `legacy`
- [x] Rama `main` con la documentación base
- [x] Documentación unificada en `doc/` (`PRD.md`, `context.md`, `arquitectura.md`, `visual.md`, `implementacion.md`, `README.md`)
- [x] Modelo de negocio definido: comisiones del validador (mes 1 alto, meses siguientes residual), regla de "no publicar sin validar"
- [x] **5 roles definidos:** `super_admin`, `supervisor`, `validator`, `client`, `rent`
- [x] **Dato de procedencia en rol `rent`:** captura de origen (turista exterior/nacional o habitante local de San Juan)
- [x] Módulo de disponibilidad del cliente oferente (`client`) incluido en el diseño
- [x] Dominios privados definidos (sin referencias gubernamentales)
- [x] Skills instaladas en `.agents/skills/`: `laravel-best-practices`, `laravel-security`, `laravel-tdd`, `api-design`, `database-migrations`, `vue-best-practices`, `ui-ux-pro-max`, `seo`, `visual`, `api-contract-spec`
- [x] Sistema de diseño maestro generado en `design-system/turismo-seguro-san-juan/MASTER.md`
- [x] Carpeta `.agent` eliminada; unificado exclusivamente en `.agents/`

---

## FASE 1 — Setup del Esqueleto y Contrato Base `[x]`

Objetivo: dos proyectos arrancando y comunicándose entre sí, gobernados por el contrato OpenAPI.

### 1.0 Contrato Inicial OpenAPI 3.1 (skill `api-contract-spec`)
- [x] Crear carpeta `contracts/` y archivo maestro `contracts/openapi.yaml` (versión 3.1.0)
- [x] Definir metadatos de la API, servidores local/producción y tags principales
- [x] Especificar endpoint `GET /api/v1/health` y schema `HealthCheckResponse`

### 1.1 Backend (`server/`)
- [x] Crear proyecto Laravel: `composer create-project laravel/laravel server`
- [x] Configurar `.env`: `DB_CONNECTION=sqlite`, `APP_URL`, `FRONTEND_URL`, `SANCTUM_STATEFUL_DOMAINS`
- [x] Crear `database/database.sqlite`
- [x] Instalar API + Sanctum: `php artisan install:api`
- [x] Configurar CORS (`config/cors.php`) con `supports_credentials => true` y orígenes permitidos
- [x] Crear prefijo de rutas `/api/v1` y endpoint de salud `GET /api/v1/health` (alineado a `contracts/openapi.yaml`)
- [x] Definir formato estándar de respuestas y errores (skill `api-design` y `api-contract-spec`)
- [x] Verificar: `php artisan serve` y suite de tests en verde con `php artisan test`

### 1.2 Frontend (`client/`)
- [x] Crear proyecto: `pnpm create vite@latest client --template vue-ts`
- [x] Instalar dependencias con pnpm: `vue-router`, `pinia`, `axios`, `tailwindcss`
- [x] Configurar Tailwind con los tokens de `doc/visual.md` y skill `visual` (colores `brand`, `safe`, `accent`; fuentes Montserrat e Inter)
- [x] Crear estructura: `composables/`, `components/`, `views/`, `stores/`, `router/`, `types/`
- [x] Crear `types/health.ts` derivado del contrato `contracts/openapi.yaml` (`pnpm run api:types`)
- [x] Crear `composables/useApi.ts` (cliente HTTP con cookies, CSRF de Sanctum e interceptor de errores)
- [x] Configurar `VITE_API_BASE_URL` en `client/.env`
- [x] Crear layout base (Navbar + Footer) y `router/index.ts` con vista de inicio
- [x] Verificar: `pnpm run build` sin errores de tipos ni advertencias de compilación

### 1.3 Calidad desde el día uno
- [x] Configurar PHPUnit en `server/` (skill `laravel-tdd`) con test de integración de salud
- [x] Configurar `vue-tsc` y compilar sin errores en `client/`
- [x] Crear `.gitignore` raíz (`server/vendor`, `client/node_modules`, `.env`, `database.sqlite`)
- [x] Crear `README.md` raíz con instrucciones de arranque
- [x] Commit: `feat: esqueleto server/ y client/ con contrato openapi inicial`

---

## FASE 2 — Modelo de Datos y Seeders `[x]`

Objetivo: base de datos completa y poblada con los datos reales del prototipo legacy.

### 2.1 Migraciones (skill `database-migrations`)
- [x] `users` (con `role` enum: `super_admin`, `supervisor`, `validator`, `client`, `rent`; campos de procedencia para `rent`: `origin_country`, `origin_province`, `origin_city`, `is_local_resident`)
- [x] `departments` (los 19 departamentos de San Juan con slug, hero_image y descripción)
- [x] `properties` (con `validator_id`, `publication_status`, `verification_status`, certificado y coordenadas)
- [x] `property_images`, `property_amenities`, `property_rules`
- [x] `property_availabilities` (calendario de fechas ocupadas/libres)
- [x] `experiences`, `experience_images`, `experience_includes`, `experience_recommendations`
- [x] `verification_requests` (actas y control de calidad)
- [x] `validator_commissions` (comisión mes 1 y recurrente)
- [x] Índices compuestos en `slug`, `department_id`, `verification_status`, `publication_status` y fechas
- [x] Claves foráneas con `onDelete` definido y *soft deletes* agnósticos a la base de datos

### 2.2 Modelos Eloquent (skill `laravel-best-practices`)
- [x] Modelos con `$fillable`, `casts(): array` estricto para fechas, tipos numéricos y booleanos
- [x] Relaciones fuertemente tipadas entre todos los modelos
- [x] *Global Scope* / *Scope Local* `Published`: solo propiedades verificadas y publicadas son visibles al público
- [x] Eloquent Strict Mode activado para prevenir N+1 y atributos no asignables

### 2.3 Seeders y Almacenamiento
- [x] `DepartmentSeeder` con los 19 departamentos de San Juan y fotografías oficiales
- [x] `UserSeeder`: usuarios de demostración para los 5 roles (incluyendo turistas y residentes locales)
- [x] `LegacyPropertySeeder`: importación de todas las propiedades auténticas de `legacy/detalle-alquiler.js` y `legacy/alquileres.html`
- [x] `LegacyExperienceSeeder`: importación de todas las excursiones auténticas de `legacy/detalle-experiencia.js`
- [x] Copiar imágenes de `legacy/img/` a `server/storage/app/public/` y `client/public/img/`, y ejecutar `php artisan storage:link`
- [x] Sincronización Contract-First: OpenAPI 3.1 actualizado y tipos generados con `pnpm run api:types`
- [x] Verificar: `php artisan migrate:fresh --seed` completado con éxito (Dept: 19 | Users: 6 | Props: 5 | Exps: 6)

### 2.4 Tests de la fase
- [x] Test del Scope `published` (solo propiedades publicadas y verificadas son visibles)
- [x] Test de integridad de relaciones Eloquent (Department, User, Amenities, Images) con suite 100% en verde

---

## FASE 3 — Autenticación, Roles, Procedencia y Recuperación de Contraseña `[x]`

Objetivo: suite completa de autenticación tradicional (registro por rol, login con credenciales, recuperación y reseteo de contraseña por email) y rutas protegidas por rol.

### 3.1 Backend
- [x] `POST /auth/login`, `POST /auth/logout`, `GET /auth/me`
- [x] `POST /auth/register/rent`: registro de usuario interesado con **procedencia geográfica** (`origin_country`, `origin_province`, `origin_city`, `is_local_resident`)
- [x] `POST /auth/register/client`: registro del cliente oferente (anfitrión)
- [x] `POST /auth/forgot-password`: solicitud de recuperación de contraseña con envío de correo y token seguro firmado
- [x] `POST /auth/reset-password`: restablecimiento de contraseña mediante token validado
- [x] `PUT /auth/password`: actualización de contraseña desde perfil autenticado
- [x] Middleware `CheckRole` y alias `role:` en el router para los 5 roles
- [x] Policies por recurso (propiedades, auditorías, comisiones)
- [x] *Rate limiting* estricto en login, registro y recuperación de contraseña (skill `laravel-security`)
- [x] Tests: login válido/inválido, flujo de olvido/reseteo de contraseña, validación de procedencia en `rent`, acceso denegado por rol

### 3.2 Frontend
- [x] `composables/useAuth.ts` (`login`, `logout`, `registerRent`, `registerClient`, `forgotPassword`, `resetPassword`, `user`, `isAuthenticated`)
- [x] `composables/usePermissions.ts` (`hasRole`, `isSuperAdmin`, `isSupervisor`, `isValidator`, `isClient`, `isRent`)
- [x] `stores/auth.ts` (Pinia)
- [x] Guards en Vue Router con `meta.roles` y redirección según rol
- [x] `LoginView.vue` (login común con enlace a "¿Olvidaste tu contraseña?")
- [x] `RegisterRentView.vue` (registro común con selector de procedencia) y `RegisterClientView.vue`
- [x] `ForgotPasswordView.vue` (formulario para ingresar email de recuperación)
- [x] `ResetPasswordView.vue` (formulario para definir nueva contraseña con token de URL)
- [x] Redirección post-login al panel que corresponde a cada rol
- [x] Vista 403 y vista 404

### 3.3 Mapa de rutas del frontend

| Zona | Prefijo | Roles permitidos |
| :--- | :--- | :--- |
| Público / Consulta | `/` , `/alquileres`, `/experiencias`, `/departamentos/:slug` | Todos |
| Autenticación Común | `/login`, `/registro/alquilar`, `/registro/anfitrion`, `/recuperar-password`, `/restablecer-password` | Invitados |
| Cliente Oferente | `/panel/cliente/*` | `client` |
| Validador | `/panel/validador/*` | `validator` |
| Supervisor | `/panel/supervisor/*` | `supervisor` |
| Administración | `/panel/admin/*` | `super_admin` |

---

## FASE 4 — Catálogo Público (Turistas y Habitantes Locales - Rol `rent`) `[ ]`

Objetivo: el usuario interesado navega, filtra, ve disponibilidad y contacta. Primer gran hito visible.

### 4.1 Backend
- [ ] `GET /properties` con filtros (departamento, precio, capacidad, comodidades, búsqueda de texto) y paginación
- [ ] `GET /properties/{slug}` con imágenes, comodidades y reglas
- [ ] `GET /properties/{id}/availability` (fechas ocupadas por rango)
- [ ] `GET /experiences` y `GET /experiences/{slug}`
- [ ] `GET /departments` con contador de ofertas verificadas
- [ ] API Resources para todas las respuestas
- [ ] Tests: solo aparecen propiedades verificadas y publicadas

### 4.2 Frontend — Composables
- [ ] `useProperties.ts` (listado, filtros reactivos sincronizados con la URL, paginación)
- [ ] `useExperiences.ts`
- [ ] `useDepartments.ts`
- [ ] `useAvailability.ts` (modo consulta pública para usuario `rent`)

### 4.3 Frontend — Componentes (guía `doc/visual.md` y `design-system/`)
- [ ] `BadgeVerified.vue` (sello con halo de resplandor y punto de pulso activo)
- [ ] `PropertyCard.vue` y `ExperienceCard.vue` (elevación 3D hover)
- [ ] `DepartmentCard.vue`
- [ ] `FilterSidebar.vue` (drawer táctil en móvil)
- [ ] `PropertyGallery.vue` (mosaico editorial + visualizador)
- [ ] `AvailabilityPicker.vue` (calendario visual de fechas libres/ocupadas)
- [ ] `ContactButtons.vue` (WhatsApp con mensaje preformateado antifraude + llamada)
- [ ] Skeletons de carga y estados vacíos / de error

### 4.4 Frontend — Vistas
- [ ] `HomeView.vue` (hero con buscador, departamentos de San Juan, destacados, garantía del sello)
- [ ] `PropertiesView.vue` y `PropertyDetailView.vue`
- [ ] `ExperiencesView.vue` y `ExperienceDetailView.vue`
- [ ] `DepartmentView.vue`
- [ ] Transiciones de página y respeto de `prefers-reduced-motion`
- [ ] Revisión responsive: 375, 768, 1024 y 1440 px

---

## FASE 5 — Panel del Cliente Oferente (`client`) `[ ]`

Objetivo: el anfitrión/cliente gestiona su oferta y su disponibilidad, y solicita la validación.

### 5.1 Backend
- [ ] CRUD de propiedades propias (estado inicial `draft`)
- [ ] Carga de imágenes con validación de tipo y tamaño y redimensionado
- [ ] `POST /client/verifications/request` (pasa a `pending_review`; no se publica)
- [ ] `GET/POST /client/properties/{id}/availability` y bloqueo/desbloqueo de rangos
- [ ] `AvailabilityService`: validación de rangos, solapamientos y fechas pasadas
- [ ] Policy: el cliente oferente solo accede a sus propias propiedades
- [ ] Tests: solapamiento de fechas, acceso a recursos ajenos, imposibilidad de auto-publicar

### 5.2 Frontend
- [ ] `useClientProperties.ts` y ampliación de `useAvailability.ts` (modo edición de calendario)
- [ ] `ClientDashboardView.vue` (estado de sello, membresía y consultas recibidas)
- [ ] `MyPropertiesView.vue` y `PropertyFormView.vue` (alta/edición de alojamiento)
- [ ] `AvailabilityCalendar.vue` (bloquear/liberar días y rangos interactivos)
- [ ] `VerificationStatusTimeline.vue` (estado: solicitado → en visita → en revisión → verificado)
- [ ] Botón "Solicitar validación física in situ"

---

## FASE 6 — App del Validador (Móvil) `[ ]`

Objetivo: el validador capta clientes en la calle, audita en terreno y ve sus comisiones.

### 6.1 Backend
- [ ] Alta de prospecto por el validador (cliente + propiedad) vinculando `validator_id` (base de la comisión)
- [ ] `GET /validator/tasks` (visitas asignadas)
- [ ] `POST /validator/audits/{id}/photos` (carga con validación)
- [ ] `POST /validator/audits/{id}/submit` (checklist, notas, GPS; estado `under_supervision`)
- [ ] `CommissionService`: tasas configurables (mes 1 = 70%, recurrente = 15%), con tests unitarios
- [ ] `GET /validator/commissions` (cartera de clientes, devengado, pendiente y pagado)
- [ ] Tests: un validador solo ve sus auditorías; la comisión del mes 1 y la recurrente se calculan bien

### 6.2 Frontend
- [ ] `useInspection.ts` (checklist, geolocalización, cola de fotos)
- [ ] `useCommissions.ts`
- [ ] `ValidatorHomeView.vue` (tareas y accesos rápidos, enfoque *mobile-first*)
- [ ] `ProspectFormView.vue` (alta rápida de clientes en calle)
- [ ] `InspectionView.vue` con `InspectionChecklist.vue` y `PhotoUploader.vue` (`capture="environment"`)
- [ ] `MyCommissionsView.vue` y `CommissionCard.vue`
- [ ] Objetivos táctiles ≥ 48 px y alto contraste para uso al aire libre
- [ ] Evaluar PWA instalable y modo offline para fotos en zonas sin señal

---

## FASE 7 — Panel del Supervisor `[ ]`

Objetivo: filtro antifraude interno y emisión del sello.

### 7.1 Backend
- [ ] `GET /supervisor/requests` (tabla de peticiones con filtros por departamento/zona, estado y ordenamiento por fecha)
- [ ] `POST /supervisor/requests/{id}/assign` (asignación de la petición a un validador zonal específico)
- [ ] `GET /supervisor/audits/pending` (bandeja de actas in situ terminadas listas para dictamen)
- [ ] `POST /supervisor/audits/{id}/approve` (emite Sello TS: estado `verified` + `published`, registra `verified_at`)
- [ ] `POST /supervisor/audits/{id}/reject` (motivo obligatorio de rechazo técnico)
- [ ] `PATCH /supervisor/properties/{id}/toggle-status` (baja/alta temporal de publicación)
- [ ] Registro de auditoría (quién asignó, quién aprobó, cuándo y qué)
- [ ] Regla de integridad: un supervisor no puede auditar como validador y aprobarse a sí mismo
- [ ] Tests: asignación zonal, aprobación publica la propiedad, rechazo no, y no se puede saltar el flujo

### 7.2 Frontend
- [ ] `RequestsTableView.vue` (tabla interactiva de peticiones agrupada por departamentos/zonas de San Juan, fechas y selector modal de validador)
- [ ] `QualityControlView.vue` (bandeja de actas in situ enviadas por validadores pendientes de revisión)
- [ ] `AuditReviewDetailView.vue` (muro de fotos de control, checklist técnico, GPS y datos del validador)
- [ ] `AuditReviewCard.vue` y `PhotoCompareModal.vue`
- [ ] Acciones de aprobar / rechazar con motivo fundamentado
- [ ] Notificación automática al cliente del resultado del sello TS

---

## FASE 8 — Panel Super Admin `[ ]`

Objetivo: control total del negocio, análisis de demanda y pago de comisiones.

### 8.1 Backend
- [ ] `GET /admin/metrics` (propiedades activas, solicitudes, ingresos, comisiones, conversión)
- [ ] `GET /admin/metrics/provenance` (**Analítica de Procedencia**: distribución de usuarios `rent` por origen: San Juan local, otras provincias argentinas, países extranjeros)
- [ ] CRUD de usuarios internos y asignación de roles
- [ ] Activación y desactivación de cuentas
- [ ] Asignación de validadores a solicitudes
- [ ] `GET /admin/commissions/payouts` y `POST .../mark-paid`
- [ ] Parámetros de negocio editables (porcentajes de comisión y precio de planes)
- [ ] Exportación de reportes (CSV/PDF)
- [ ] Tests: solo `super_admin` accede y la liquidación es idempotente

### 8.2 Frontend
- [ ] `SuperAdminDashboardView.vue` con gráficos
- [ ] `ProvenanceStatsView.vue` (mapa/gráfico de procedencia de los usuarios que buscan alquileres)
- [ ] `UsersManagementView.vue`
- [ ] `CommissionsPayoutView.vue`
- [ ] `BusinessSettingsView.vue`
- [ ] Botones de exportación de reportes

---

## FASE 9 — Suscripciones y Facturación `[ ]`

Objetivo: cobrar al cliente oferente y generar las comisiones de forma automática.

- [ ] Definir con el negocio: precio de validación inicial, cuota mensual, periodicidad y política de mora
- [ ] Definir método de cobro (MVP: registro manual por el Super Admin; luego pasarela de pagos)
- [ ] `client_subscriptions`: estados `active`, `past_due`, `cancelled`
- [ ] Al registrarse un pago: generar la comisión del validador (mes 1 o recurrente) automáticamente
- [ ] Tarea programada que marca mora y suspende la publicación si corresponde
- [ ] Vista de estado de suscripción para el cliente oferente
- [ ] Tests del ciclo completo: pago → comisión → liquidación
- [ ] (Opcional) Integración con pasarela de pagos

---

## FASE 10 — Google OAuth (Autenticación Social Complementaria), SEO y Pulido UX `[ ]`

Objetivo: sumar inicio rápido con Google conviviendo con el login común tradicional, optimizar SEO para WhatsApp y motores de búsqueda, y aplicar el acabado de diseño final.

### 10.1 Autenticación Dual (Google OAuth + Login Común Tradicional)
- [ ] Laravel Socialite + Google API: configuración de `client_id` y `client_secret`
- [ ] Endpoints backend: `GET /auth/google/redirect` y `GET /auth/google/callback`
- [ ] Lógica de vinculación: si el correo de Google ya existe en un usuario con login común, asociar cuenta de forma transparente
- [ ] Para usuarios nuevos que ingresan vía Google como `rent`: modal/flujo rápido de captura de procedencia (país, provincia, localidad)
- [ ] Interfaz frontend: botón "Continuar con Google" integrado en `LoginView.vue`, `RegisterRentView.vue` y `RegisterClientView.vue`, manteniendo 100% operativos los formularios comunes con email y contraseña, así como el flujo de recuperación de contraseña olvidada.

### 10.2 SEO y OpenGraph (skill `seo`)
- [ ] Metadatos dinámicos por ficha de alojamiento y experiencia
- [ ] Tarjetas OpenGraph (OG Title, OG Image con Sello TS, OG Description) optimizadas para enlaces compartidos en WhatsApp
- [ ] Generación automática de `sitemap.xml` y archivo `robots.txt`
- [ ] Microdatos estructurados Schema.org (`LodgingBusiness`)
- [ ] Evaluación de prerendering o Nuxt si los requerimientos de SEO en buscadores así lo exigen

### 10.3 Pulido Visual y Accesibilidad (skill `ui-ux-pro-max`)
- [ ] Auditoría de accesibilidad (cumplimiento WCAG AA)
- [ ] Medición de rendimiento Lighthouse (Performance, A11y, Best Practices, SEO)
- [ ] Optimización de imágenes en formato WebP con *lazy loading*
- [ ] Revisión de consistencia con `design-system/turismo-seguro-san-juan/MASTER.md`

---

## FASE 11 — Testing, Seguridad y Hardening `[ ]`

- [ ] Cobertura de tests backend ≥ 80% en lógica crítica (sello, comisiones, disponibilidad, roles)
- [ ] Tests de componentes y composables clave en el frontend
- [ ] Prueba E2E de los flujos críticos (captación → auditoría → aprobación → publicación)
- [ ] Revisión con checklist de `laravel-security`: CSRF, XSS, *mass assignment*, validación de archivos, cabeceras de seguridad
- [ ] Revisión de políticas de acceso de cada endpoint
- [ ] Revisión de logs y manejo de errores sin filtrar datos sensibles
- [ ] Backups de base de datos y de imágenes

---

## FASE 12 — Despliegue `[ ]`

- [ ] Registro de dominio comercial privado y DNS (`turismoseguro.com.ar`, `api.turismoseguro.com.ar`)
- [ ] Hosting para `server/` (PHP + HTTPS) y para `client/` (estático/CDN)
- [ ] Migración de SQLite a MySQL o PostgreSQL si la carga lo requiere
- [ ] Variables de entorno de producción (`APP_DEBUG=false`, cookies `Secure`, dominios de Sanctum)
- [ ] Almacenamiento de imágenes en disco persistente o en la nube
- [ ] Cola de trabajos y *scheduler* (cron)
- [ ] Pipeline de CI/CD
- [ ] Monitoreo y alertas
- [ ] Puesta en marcha controlada con un grupo piloto de clientes oferentes

---

## Decisiones Abiertas (requieren definición del negocio)

- [ ] Porcentajes definitivos de comisión (mes 1 y recurrente)
- [ ] Precio de la validación inicial y de la cuota mensual del cliente oferente
- [ ] Quién administra la fuerza de validadores (RR. HH. / relación comisionista independiente) y qué implica fiscal y legalmente
- [ ] Periodicidad de las re-verificaciones de propiedades ya aprobadas
- [ ] Alcance del convenio con Turismo y qué datos de procedencia o reportes se comparten
- [ ] Política ante validadores que abandonan: ¿quién hereda su cartera y su comisión recurrente?
- [ ] Versión exacta de Laravel a utilizar

---

## Cómo se actualiza este documento

1. Al terminar una tarea, cambiar `[ ]` por `[x]`.
2. Mientras una fase esté en curso, marcarla `[~]` en el tablero.
3. Al cerrar todas las tareas de una fase, marcarla `[x] Completada` en el tablero.
4. Cualquier cambio de alcance se refleja primero en `PRD.md` / `arquitectura.md` y luego aquí.
