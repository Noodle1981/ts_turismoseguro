# Plan Maestro de Implementación: Turismo Seguro San Juan

> **ESTADO DEL PROYECTO AL 09/10/2026:**
> - **Fases 0 a 5:** 100% COMPLETADAS Y OPERATIVAS en Backend y Frontend.
> - **Hito de Rediseño Visual Stitch 2.0:** EJECUTADO (Purga de estilos legacy, migración a tokens oficiales 'San Juan Verificado', Plus Jakarta Sans, Landing diurna y Ficha de propiedad con Bento Grid y WhatsApp verificado).
> - **Fases 6 a 12:** PENDIENTES DE DESARROLLO (Agendadas ordenadamente a continuación).

---

## 📌 GUÍA EXPLÍCITA DE EJECUCIÓN PARA EL AGENTE DE IA (DIRECTRICES TÉCNICAS)

Si eres un asistente de Inteligencia Artificial que retoma o continúa este proyecto, **DEBES SEGUIR ESTAS REGLAS OBLIGATORIAS**:

1. **Estrategia Vertical Slices (De Punta a Punta):**
   - NUNCA crees interfaces Vue 3 con datos falsos hardcodeados (`mock data`) que queden aisladas del backend.
   - NUNCA crees endpoints de Laravel que no se conecten de inmediato a su vista correspondiente.
   - Cada funcionalidad se entrega completa: Migración -> Modelo/Policy -> Controller/Resource -> Endpoint OpenAPI -> Composable Vue -> Vista/Componente -> Test PHPUnit/Vite build en verde.

2. **Gobierno Visual Oficial Stitch (Skill `visual`):**
   - La autoridad de diseño es el **Design System Stitch "San Juan Verificado"** (`stitch_portal_alquileres_temporarios_vue/san_juan_verificado/DESIGN.md` y `.agents/skills/visual/SKILL.md`).
   - **Tipografía:** Exclusivamente `Plus Jakarta Sans`.
   - **Colores:** Primario Deep Mineral Navy (`#001428`, container `#0f2942`), Secundario Verde Esmeralda Seguridad (`#006c4a`, container `#82f5c1`), Terciario Ámbar Sol Cuyano (`#1f1000`, container `#3b2200`), Fondo `#f8f9ff`.
   - La skill `ui-ux-pro-max` tiene ESTRICTAMENTE PROHIBIDO alterar colores, componentes o decisiones de diseño; solo se consulta si el usuario lo pide expresamente.

3. **Arquitectura del Stack Tecnológico:**
   - **Backend:** Laravel 11/12 en `server/` (SQLite en local, Sanctum Stateful Cookies, API Resources, FormRequests con validación estricta, Eloquent Strict Mode).
   - **Frontend:** Vue 3 con Composition API `<script setup lang="ts">`, Pinia, Vue Router y Tailwind CSS en `client/`.
   - **Contratos:** Gobernanza Contract-First en `contracts/openapi.yaml`. Si modificas una ruta o recurso, actualiza el YAML y regenera tipos.

4. **Documento Vivo:**
   - Cada tarea completada DEBE marcarse con `[x]` en este archivo `d:\turismo_seguro\doc\implementacion.md`.

---

## 📊 Tablero General de Control de Progreso

| Fase | Nombre del Módulo | Estado | Tests / Verificación |
| :--- | :--- | :---: | :---: |
| **0** | Fundaciones, reglas de negocio y resguardo legacy | `[x]` Completada | Documentación y Skills sincronizadas |
| **1** | Setup del Esqueleto (`server/` Laravel + `client/` Vue 3 Vite) | `[x]` Completada | Endpoint `/api/v1/health` en verde |
| **2** | Modelo de datos, migraciones (19 Deptos, Propiedades, Experiencias) | `[x]` Completada | Base SQLite poblada vía Seeders |
| **3** | Autenticación, 5 Roles, Procedencia de Huésped y Reseteo Password | `[x]` Completada | Guards de Vue Router y Sanctum OK |
| **4** | Catálogo Público de Alquileres y Experiencias Turísticas | `[x]` Completada | Filtros, Slugs y API Resources OK |
| **5** | Panel del Cliente Oferente (`client`): Carga de Propiedad y Disponibilidad | `[x]` Completada | 26 tests PHPUnit pasando (100% verde) |
| **HITO**| **Rediseño Visual Stitch 2.0 (Quintas Diurnas + Purga de Estilos)** | `[x]` Completada | Build Vite 100% limpio sin errores |
| **6** | App Móvil del Validador: Captación callejera, Actas In Situ y Comisiones | `[ ]` PENDIENTE | Próxima fase a implementar |
| **7** | Panel del Supervisor: Control de Calidad antifraude y Emisión del Sello TS | `[ ]` PENDIENTE | Posterior a Fase 6 |
| **8** | Panel Super Admin: Métricas, Analítica de Procedencia y Liquidación | `[ ]` PENDIENTE | Posterior a Fase 7 |
| **9** | Suscripciones mensuales de anfitriones y facturación automática | `[ ]` PENDIENTE | Posterior a Fase 8 |
| **10**| Autenticación Dual Google OAuth + SEO OpenGraph para WhatsApp | `[ ]` PENDIENTE | Posterior a Fase 9 |
| **11**| Hardening de Seguridad, Auditoría OWASP y Tests E2E | `[ ]` PENDIENTE | Posterior a Fase 10 |
| **12**| Despliegue a Producción (Dominio comercial y SSL) | `[ ]` PENDIENTE | Fase de Cierre |

---

## 🔍 DETALLE DE FASES YA COMPLETADAS (REFERENCIA HISTÓRICA)

### FASE 0 a 5 — Cimiento Funcional y Negocio `[x]`
- [x] Resguardo del código previo en rama `legacy`.
- [x] Creación de migraciones y modelos: `User` (roles: `super_admin`, `supervisor`, `validator`, `client`, `rent`), `Department` (19 departamentos), `Property`, `PropertyImage`, `PropertyAvailability`, `Experience`, `VerificationRequest`, `ValidatorCommission`.
- [x] Registro diferenciado con procedencia geográfica para turistas (`origin_country`, `origin_province`, `origin_city`, `is_local_resident`).
- [x] Panel de anfitrión en `/panel/cliente`: borrador de propiedades, gestión interactiva de calendario libre/ocupado y solicitud formal de inspección in situ.
- [x] 26 tests automatizados en Laravel pasando al 100%.

### HITO DE REDISEÑO VISUAL STITCH 2.0 `[x]`
- [x] Purga total de clases obsoletas de Tailwind y estilos legacy incompatibles.
- [x] Adopción integral de la paleta Stitch en `client/tailwind.config.js`: Mineral Navy (`#001428`), Emerald Assurance (`#006c4a`), San Juan Amber (`#1f1000`).
- [x] Incorporación de fuente `Plus Jakarta Sans` y set de iconos Google `Material Symbols Outlined`.
- [x] `HomeView.vue`: Landing page enfocada en alquileres diurnos (quintas y quinchos), tarjeta en vivo de auditoría técnica, buscador por turnos y calculadora estimativa por asistente.
- [x] `TheNavbar.vue` y `TheFooter.vue`: Cintas de garantía antifraude, promoción de lanzamiento (6 meses gratis para las primeras 50 ofertas) y enlaces departamentales.
- [x] `PropertyDetailView.vue`: Galería Bento Grid de 5 fotos, informe oficial del auditor técnico y calculador dinámico con reserva directa vía WhatsApp.
- [x] `vue-tsc -b && vite build` verificado con 0 errores de tipado.

---

## 🚀 FASES PENDIENTES DE DESARROLLO (GUÍA DETALLADA PASO A PASO)

---

### FASE 6 — App Móvil del Validador Técnico `[ ]`
**Objetivo:** Permitir al auditor/validador matricularse en el territorio, captar propiedades en la calle, completar el acta técnica presencial (pileta, cerco, CBU, fotos) y consultar sus comisiones ganadas.

#### 6.1 Backend (`server/`)
- [ ] Endpoint `POST /api/v1/validator/prospects`: Registro exprés de un cliente captado en calle, asignándole automáticamente el `validator_id` para devengar comisiones futuras.
- [ ] Endpoint `GET /api/v1/validator/tasks`: Listado de propiedades pendientes de inspección asignadas a ese validador.
- [ ] Endpoint `POST /api/v1/validator/audits/{id}/photos`: Subida múltiple de fotografías de control (pileta con cerco, parrillero, disyuntor eléctrico, fachada).
- [ ] Endpoint `POST /api/v1/validator/audits/{id}/submit`: Cierre del acta in situ con checklist técnico, coordenadas GPS reales de la visita y notas. Pasa el estado a `under_supervision`.
- [ ] `CommissionService`: Cálculo de comisión comercial del validador (Mes 1: 70% de la suscripción/validación; Meses siguientes: 15% recurrente de la cartera activa).
- [ ] Endpoint `GET /api/v1/validator/commissions`: Resumen de comisiones devengadas, pendientes de cobro y liquidadas.
- [ ] Tests unitarios y de integración de cálculo de comisiones y control de acceso.

#### 6.2 Frontend (`client/`)
- [ ] Basarse en la plantilla oficial: `stitch_portal_alquileres_temporarios_vue/panel_de_validador_acta_de_inspecci_n_in_situ/code.html`.
- [ ] Composable `useValidator.ts` (tareas, subida de acta con fotos y métricas de comisiones).
- [ ] Vista `ValidatorTasksView.vue`: Lista de inspecciones agendadas con acceso directo a GPS/Google Maps.
- [ ] Vista `InspectionActView.vue`: Acta digital con switches interactivos (Agua de pileta clorada/filtrada, Cerco para niños homologado, Disyuntor eléctrico, CBU del dueño cotejado en mano con DNI, Matafuego con carga vigente).
- [ ] Componente `CameraUploader.vue`: Captura directa de cámara del celular con soporte de previsualización.
- [ ] Vista `ValidatorCommissionsView.vue`: Billetera del validador con desglose de cartera activa y saldo a cobrar.

---

### FASE 7 — Panel del Supervisor (Control de Calidad y Emisión de Sello) `[ ]`
**Objetivo:** Filtro antifraude interno donde el supervisor coteja las pruebas aportadas por el validador, aprueba o rechaza técnicamente la propiedad y emite el certificado oficial QR.

#### 7.1 Backend (`server/`)
- [ ] Endpoint `GET /api/v1/supervisor/requests`: Bandeja de solicitudes de validación pendientes en la provincia.
- [ ] Endpoint `POST /api/v1/supervisor/requests/{id}/assign`: Asignación de solicitud al validador zonal más cercano.
- [ ] Endpoint `GET /api/v1/supervisor/audits/pending`: Bandeja de actas cerradas por validadores pendientes de revisión.
- [ ] Endpoint `POST /api/v1/supervisor/audits/{id}/approve`: Aprobación final. Genera número de certificado oficial único (ej: `SJ-POC-2026-089`), cambia estado a `verified` y `published`, y estampa fecha `verified_at`.
- [ ] Endpoint `POST /api/v1/supervisor/audits/{id}/reject`: Rechazo fundado con observaciones técnicas obligatorias devueltas al cliente y validador.
- [ ] Regla de integridad: Un usuario con rol supervisor NO puede aprobar una auditoría realizada por sí mismo.
- [ ] Tests de aprobación, emisión de certificado y bloqueo de publicación no autorizada.

#### 7.2 Frontend (`client/`)
- [ ] Composable `useSupervisor.ts`.
- [ ] Vista `SupervisorRequestsView.vue`: Tabla de solicitudes con mapa zonal de San Juan y modal de asignación de inspectores.
- [ ] Vista `AuditReviewDetailView.vue`: Muro comparativo de fotos subidas por el validador vs datos catastrales, checklist técnico y coordenadas GPS.
- [ ] Botones de acción rápida: "Aprobar y Emitir Sello TS" (con confirmación de emisión de certificado) o "Rechazar con Observaciones".

---

### FASE 8 — Panel Super Admin (Negocio, Métricas y Procedencia) `[ ]`
**Objetivo:** Monitoreo integral de la plataforma, volumen de ofertas por departamento, liquidación de comisiones a inspectores y analítica de procedencia de turistas.

#### 8.1 Backend (`server/`)
- [ ] Endpoint `GET /api/v1/admin/metrics`: Total de propiedades activas, solicitudes en curso, facturación bruta y comisiones adeudadas.
- [ ] Endpoint `GET /api/v1/admin/metrics/provenance`: **Analítica de Procedencia Geográfica** (desglose de usuarios `rent` entre habitantes locales de San Juan, turistas de Buenos Aires, Córdoba, Mendoza, etc., y turistas internacionales).
- [ ] Endpoint `GET /api/v1/admin/users` y `PATCH /api/v1/admin/users/{id}/role`: Gestión completa de roles y habilitación de cuentas.
- [ ] Endpoint `GET /api/v1/admin/commissions/payouts` y `POST .../mark-paid`: Registro de transferencias bancarias de comisiones a los validadores.

#### 8.2 Frontend (`client/`)
- [ ] Vista `AdminDashboardView.vue`: Tarjetas de KPI principales con diseño Stitch Navy/Emerald.
- [ ] Vista `ProvenanceStatsView.vue`: Gráficos interactivos de procedencia geográfica de turistas y demanda estacional.
- [ ] Vista `CommissionPayoutsView.vue`: Tabla de liquidación con marcado masivo de comisiones pagadas y descarga de comprobantes.
- [ ] Vista `UserManagementView.vue`: Administración de usuarios y validadores matriculados.

---

### FASE 9 — Suscripciones y Facturación `[ ]`
**Objetivo:** Cobro de membresías mensuales a los clientes oferentes (anfitriones) y generación automática de comisiones residuales.
- [ ] Modelo `Subscription`: Periodo de gracia, fechas de vencimiento y estado (`active`, `past_due`, `cancelled`).
- [ ] Lógica de lanzamiento: Primeras 50 ofertas con 6 meses de suscripción bonificada al 100%.
- [ ] Tarea programada (Artisan Schedule/Cron): Detección de membresías vencidas y suspensión temporal automática de la publicación en el catálogo público si hay falta de pago.
- [ ] Vista para el anfitrión en `/panel/cliente/suscripcion`: Detalle de plan activo, fecha de renovación y comprobantes.

---

### FASE 10 — Google OAuth (Autenticación Dual), SEO y OpenGraph `[ ]`
**Objetivo:** Permitir inicio de sesión con un clic vía Google conviviendo con el login tradicional con email/contraseña, y optimizar la plataforma para que los enlaces compartidos por WhatsApp luzcan impecables.
- [ ] Backend: Integración con Laravel Socialite (`GET /api/v1/auth/google/redirect` y `callback`).
- [ ] Frontend: Botón oficial "Continuar con Google" en `LoginView.vue` y registros, manteniendo activos los formularios de correo y contraseña.
- [ ] Enlace social para usuarios `rent`: Si el usuario ingresa por primera vez con Google, capturar su ciudad/provincia de procedencia mediante un modal ágil.
- [ ] SEO & WhatsApp Preview (Skill `seo`): Metadatos OpenGraph dinámicos en fichas de propiedades (`og:image`, `og:title` con sello verificado, `og:description`).
- [ ] Generación automática de `sitemap.xml` con todas las quintas verificadas y los 19 departamentos.

---

### FASE 11 — Hardening de Seguridad, Auditoría y Testing E2E `[ ]`
**Objetivo:** Blindar la plataforma antes del uso público masivo.
- [ ] Cobertura de tests PHPUnit superior al 85% en módulos de dinero, actas y autenticación.
- [ ] Auditoría OWASP (CSRF, inyecciones, rate limiting contra ataques de fuerza bruta en login/registro).
- [ ] Protección contra falsificación de geolocalización o imágenes en actas de inspección.
- [ ] Políticas de backup automatizado para SQLite/MySQL y archivos multimedia en `storage/`.

---

### FASE 12 — Puesta en Producción y Despliegue `[ ]`
**Objetivo:** Lanzamiento oficial en servidores públicos.
- [ ] Configuración de variables de entorno de producción (`APP_DEBUG=false`, certificados SSL HTTPS, cookies seguras).
- [ ] Optimización de assets en Vite (`pnpm run build`) y almacenamiento en CDN/servidor dedicado.
- [ ] Monitoreo en vivo de excepciones y logs del sistema.

---

## 📝 PROTOCOLO DE ACTUALIZACIÓN DEL DOCUMENTO
1. El agente o programador que inicie una fase cambiará el indicador a `[~] En curso`.
2. Una vez completados y testeados los endpoints y vistas de esa fase, marcará cada ítem con `[x]` y el tablero con `[x] Completada`.
3. Está estrictamente prohibido saltear fases o romper contratos OpenAPI existentes.
