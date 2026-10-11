# Plan de Trabajo - Próximo Sprint

Documento de seguimiento, registro de hitos completados y backlog para la próxima sesión de desarrollo en **Turismo Seguro San Juan**.

---

## 1. Ajustes UI/UX y Funcionales en Vista "Configurar Tarifas" (`#tarifas`)

- [x] **Arreglar la tarjeta de Días y Horas:**
  - Rediseñado con distribución aireada y clara: Aforo Homologado y Franja Horaria en columnas amplias con botón de edición, y días habilitados (`L`, `M`, `M`, `J`, `V`, `S`, `D`) en fila independiente con botones legibles y contrastados.
- [x] **Funcionalidad "Visible en Buscador" (Pausar / Desactivar publicación):**
  - Implementado `ModalVacationPause.vue` con dos modalidades:
    - *Modo Vacaciones / Receso Temporal:* Permite seleccionar rango de fechas (Desde / Hasta) con reactivación automática.
    - *Pausa Indefinida:* Oculta la oferta hasta reactivación manual.
    - Incluye aviso institucional de preservación de Póliza de Seguro y reservas previas vigentes.

---

## 2. Ajustes en Vista "Calendario y Reservas" (`#calendario`)

- [x] **Selector de Período y Botón de Bloqueo:**
  - Reorganizada la barra de herramientas del calendario en una única fila balanceada y responsiva: navegador temporal (flechas + selectores mes/año) y botones de acción (*Eventos Especiales* y *Bloquear Fechas / Particular*).

---

## 3. Seguridad, Control de Accesos (RBAC) y Aislamiento de Sesiones

- [x] **Navigation Guards Globales en Vue Router (`router/index.ts`):**
  - Protección estricta con `requiresAuth: true` y control de roles (`roles`):
    - `/propietario/panel` restringido a roles `client` y `super_admin`.
    - `/inquilino/panel` restringido a roles `rent` y `super_admin`.
    - `/validador/*` restringido a roles de staff (`validator`, `supervisor`, `admin`, `super_admin`).
  - Redirección automática al login correspondiente si el usuario no está autenticado o si intenta acceder a un panel que no corresponde a su rol.
- [x] **Prevención de Sesiones Zombis y Conflicto de Roles (`StaffAuthView.vue`):**
  - Detección de sesión activa de Propietario/Inquilino al ingresar al Portal Staff (`/staff/login`).
  - Banner interactivo de advertencia con acciones claras: *"Cerrar sesión de Propietario y continuar como Staff"* o *"Volver a mi panel"*.
  - Limpieza atómica de sesión previa antes de procesar el login de staff para evitar mezcla de tokens y estados residuales.
- [x] **Navegación Cruzada entre Vistas Pública y Privada:**
  - En `PropietarioTopHeader.vue`: botón **"Ver como Inquilino"** que lleva a `/` y botón explícito de **"Cerrar Sesión"** en la barra superior.
  - En `AppHeader.vue`:
    - Eliminado el enlace *"Panel Propietario"* para visitantes no autenticados (solo se muestra *"Iniciar Sesión"*).
    - Eliminados los accesos internos del propietario (`Mis Ofertas`, `Agregar Oferta`, `Pagos`) del header público.
    - Eliminado el enlace `#protocolo` (*"Cómo Validamos"*).
    - Agregado el botón **"Ver como Propietario"** en el navbar público cuando se navega con sesión activa de anfitrión, permitiendo volver a `/propietario/panel` en un clic.
- [x] **Salvaguarda defensiva en vistas:**
  - Chequeo de autenticación en ciclo de vida `onMounted` de `PropietarioPanelView.vue` e `InquilinoPanelView.vue`.

---

## 4. Lógica y Estructura de la Base de Datos & Laravel Best Practices

- [x] **Modelo de Datos y Localidades Reales (San Juan):**
  - Creada tabla `localities` vinculada a los 19 departamentos oficiales de San Juan.
  - Creado y ejecutado `LocalitySeeder.php` extrayendo las **266 localidades reales de San Juan** desde `establecimientos.sqlite` con centroides GPS calculados.
  - Vinculación `locality_id` (nullable) agregada a la entidad de propiedades.
- [x] **Atributos de la Oferta alineados con el Panel:**
  - Migración `enhance_properties_and_events`:
    - Tarifas multimodales (diurna lun-jue, vie-dom, sáb-feriados, pernocte camas/carpas, finde completo, fiesta nocturna, evento especial).
    - Franja diurna (`schedule_start`, `schedule_end`) y días activos (`active_days` JSON).
    - Visibilidad y vacaciones (`is_listing_visible`, `pause_mode`, `pause_start_date`, `pause_end_date`).
    - Póliza de seguro (`insurance_company`, `policy_number`, `policy_valid_until`, `acta_inspeccion_fecha`).
- [x] **Reglas de Filtros y Cobertura (Puntos 6 y 7 de respuestas):**
  - `GET /api/v1/departments?with_offers=true`: Oculta automáticamente los departamentos que no tienen ofertas activas en los filtros de búsqueda.
  - `GET /api/v1/departments`: Retorna los 19 departamentos completos de San Juan para la navegación general/portada.
  - `GET /api/v1/departments/{slug}/localities`: Endpoint para obtener las localidades de cada departamento.
- [x] **Eventos Provinciales y Radio de Alcance (Punto 5):**
  - Agregado `radius_km` a eventos y tabla relacional `event_departments` para alcance dual por departamentos y por proximidad GPS.
- [x] **Tests Backend:**
  - Suite de pruebas de Laravel (`php artisan test`) ejecutada exitosamente: **26 tests pasados, 310 assertions, 0 fallos**.

---

## 5. Optimización Frontend, Accesibilidad y Calidad UI/UX (HomeView)

- [x] **Rendimiento y Code-Splitting Inicial:**
  - Carga asíncrona optimizada (`defineAsyncComponent`) para `ExperienciasCercanasHub.vue`, reduciendo el bundle inicial en ~36 kB.
  - Corrección de advertencia deprecada en Vue Router 4 migrando `beforeEach` a retornos modernos (eliminando callback `next()`).
- [x] **Accesibilidad (a11y) y SEO:**
  - Emparejamiento de `<label for>` con `<select id>` en buscador principal.
  - Preconexión y `display=swap` en Google Fonts.
  - Meta tags Open Graph y descripciones semánticas.
- [x] **Auditoría UI/UX Pro Max y Micro-interacciones:**
  - Clic integral y affordance en cards de quintas (`CardQuintaDiurna.vue`).
  - Anillos de foco accesible `focus-visible` y ergonomía táctil en móviles para filtros (`FiltroDepartamentos.vue`).
  - Respuesta física elástica (`active:scale-*`) en calculador y botones de llamada a la acción.

---

## 6. Próximos Pasos Pendientes (Backlog para Reanudar)

- [x] **Conectar el Panel del Propietario con la API Real:**
  - En `usePropietarioOffers.ts`, implementado `fetchOffers()` conectado a `GET /api/v1/client/properties` con mapeador bidireccional y preservación de estado local.
  - Implementado `saveOfferToServer()` y conectado a `PUT /api/v1/client/properties/{id}` enviando tarifas multimodales (`tariff_lunes_jueves`, `tariff_viernes_domingo`, `tariff_sabado_feriados`, `tariff_evento_especial`, pernoctes, finde completo, fiesta nocturna), franja horaria (`schedule_start`, `schedule_end`, `active_days`), visibilidad y modo vacaciones (`pause_mode`, `pause_start_date`, `pause_end_date`).
  - Conectados los handlers de activación de vacaciones (`handleConfirmPause`) y reactivación (`handleReactivateOffer`) con persistencia en base de datos.
  - Test Feature agregado en `ClientPropertyTest.php` validando la actualización de tarifas multimodales y vacaciones en Laravel (27 tests en verde, 320 assertions).
- [x] **Conectar Inquilinos (`#inquilinos`) y Reservas con la BD Real:**
  - Creada migración `2026_10_11_000800_add_owner_rating_to_bookings_table.php` agregando el atributo `owner_rating` a la tabla `bookings`.
  - Implementado endpoint `GET /api/v1/client/properties/{id}/tenants` con división automática de reservas en `proximos` (futuros) y `pasados` (completados/historial), y formateo de datos de contacto (DNI, teléfono, póliza).
  - Creada migración `2026_10_11_000900_drop_payment_fields_from_bookings_table.php` eliminando de forma definitiva `deposit_paid`, `balance_due` y `total_price` de la base de datos y de las interfaces de usuario (alineado al modelo de negocio de 0% retención monetaria de señas).
  - Implementado endpoint `POST /api/v1/client/bookings/{id}/rate` para persistir la calificación del propietario (`up`, `down`, `none`).
  - Actualizado composable `usePropietarioTenants.ts` y enlazado reactivamente con `watch(selectedOfferId)` y `onMounted` en `PropietarioPanelView.vue`.
  - Cobertura de tests en Laravel ampliada (28 tests en verde, 323 assertions).
- [ ] **Flujo End-to-End: "Agregar Oferta" $\rightarrow$ Bandeja del Validador:**
  - El modal *"Agregar Oferta"* (`ModalAddOffer.vue`) persiste la propiedad en `POST /api/v1/client/properties` y genera la `verification_request` en estado `pending`.
  - Debe verse reflejada automáticamente en la bandeja del validador (`ValidadorBandejaView.vue`).
- [ ] **Geocodificación y Marcaciones Geográficas:**
  - Integrar el JSON de marcaciones geográficas en preparación para la visualización del mapa territorial de inspecciones y catálogo geoespacial.