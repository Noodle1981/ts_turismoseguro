# Plan de Trabajo - Próximo Sprint

Documento de seguimiento y backlog para la próxima sesión de desarrollo en **Turismo Seguro San Juan**.

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

## 3. Lógica y Estructura de la Base de Datos & Laravel Best Practices

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
- [x] **Reglas de Filtros y Cobertura (Puntos 6 y 7):**
  - `GET /api/v1/departments?with_offers=true`: Oculta automáticamente los departamentos que no tienen ofertas activas en los filtros de búsqueda.
  - `GET /api/v1/departments`: Retorna los 19 departamentos completos de San Juan para la navegación general/portada.
  - `GET /api/v1/departments/{slug}/localities`: Endpoint para obtener las localidades de cada departamento.
- [x] **Eventos Provinciales y Radio de Alcance (Punto 5):**
  - Agregado `radius_km` a eventos y tabla relacional `event_departments` para alcance dual por departamentos y por proximidad GPS.
- [x] **Tests Backend:**
  - Suite de pruebas de Laravel (`php artisan test`) ejecutada exitosamente: **26 tests pasados, 310 assertions, 0 fallos**.

---

## 4. Próximos Pasos en el Flujo Backend $\rightarrow$ Frontend

- [ ] **Conectar el Panel del Propietario con la API:**
  - Reemplazar el guardado mockeado en `usePropietarioOffers.ts` por llamadas reales a `PUT /api/v1/client/properties/{id}` con las tarifas multimodales y horarios.
- [ ] **Flujo End-to-End: "Agregar Oferta" $\rightarrow$ Bandeja del Validador:**
  - El modal *"Agregar Oferta"* (`ModalAddOffer.vue`) persiste la propiedad en `POST /api/v1/client/properties` y genera la `verification_request` en estado `pending`.
  - Debe verse reflejada en la bandeja del validador (`ValidadorBandejaView.vue`).