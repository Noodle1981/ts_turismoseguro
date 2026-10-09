# 🛡️ Protocolo Maestro: Auditoría, Refactorización y Blindaje (Stack: Laravel + Vue 3)
> **Estándar Universal para Proyectos Laravel + Vue**  
> Diseñado para ser agnóstico a la topología del repositorio (Monorepo o Monolito) y al modo de integración (API REST + SPA o Laravel + Inertia.js), garantizando portabilidad absoluta de Base de Datos (SQLite ➔ MySQL/PostgreSQL) y desacoplamiento de APIs.

Actúa como un **Tech Lead Full-Stack Senior** especializado en el ecosistema **Laravel + Vue 3**.
Quiero que audites, optimices y blindes este proyecto siguiendo una secuencia causal estricta dividida en fases secuenciales.

---

## 🧭 FASE 0: DETECCIÓN DE TOPOLOGÍA Y PROTOCOLO DE SKILLS

Antes de tocar código o ejecutar scripts, inspecciona el entorno para determinar la estructura y activar las skills correctas sin generar conflictos.

### 1. Detección de Topología de Carpetas
Identifica dónde residen el backend y el frontend:
* **Topología A (Monorepo / Desacoplado):** Carpetas separadas `backend/` (Laravel) y `frontend/` (Vue/Vite). *Comandos PHP en `backend/`, comandos Node en `frontend/`.*
* **Topología B (Monolito Integrado):** Laravel y Vue conviven en la raíz (`app/`, `resources/js/`, etc.). *Todos los comandos se ejecutan en la raíz.*

### 2. Detección del Modo de Integración Frontend
Revisa `package.json` (frontend) y `composer.json` (backend):
* **Modo A: API REST + Vue SPA (Default):** El frontend usa `vue-router` y consume la API vía HTTP. Si existe `contracts/openapi.yaml`, se rige por el protocolo **Contract-First** (`api-contract-spec`).
* **Modo B: Monolito Inertia:** Si `@inertiajs/vue3` está explícitamente instalado en las dependencias, aplica directivas de Inertia (`useForm`, `Link`, etc.).

### 3. Detección del Runner de Testing
Revisa `composer.json` en el backend:
* Si contiene `pestphp/pest`: utiliza `pest` / `php artisan test`.
* Si contiene `phpunit/phpunit` (sin Pest): utiliza `phpunit` / `php artisan test --parallel`. **Prohibido forzar la instalación de Pest si el proyecto ya usa PHPUnit.**

### 4. Matriz Canónica de Skills (Protocolo Anti-Conflictos)
Usa exclusivamente las skills estándar sin duplicar responsabilidades:
* **Backend, Eloquent y Datos:**
  - `laravel-best-practices`: Arquitectura limpia, consultas optimizadas, Thin Controllers, Services, Form Requests, y reglas clave:
    - [`rules/eloquent.md`]: Relaciones tipadas, scopes, casts estrictos, prevención de N+1.
    - [`rules/migrations.md`]: Esquemas agnósticos, llaves foráneas e índices.
    - [`rules/routing.md`]: Controladores limpios y API Resources.
  - `sql-high-concurrency-architect`: Prevención de cuellos de botella en ráfagas de inserción (08:00 AM), presupuesto de índices, ingestión desacoplada (buffer/queues) y protocolo de migración fluida SQLite ➔ PostgreSQL/MySQL (particionamiento por rango, connection pooling).
  - `laravel-security`: Sanitización, políticas de autorización (Policies/Gates), strict mode, composer audit.
  - `testing-best-practices`: Estrategia de tests (adaptada al runner detectado: PHPUnit o Pest).
* **Capa de APIs y Contratos:**
  - `api-contract-spec`: Gobernanza Contract-First vía OpenAPI 3.1 (`contracts/openapi.yaml`). Todo endpoint, parámetro y respuesta debe existir en el contrato antes de codificarse.
* **Frontend, UI/UX y Diseño:**
  - `vue-best-practices`: Vue 3 Composition API (`<script setup lang="ts">`), reactividad, `computed`, tipado estricto `vue-tsc`.
  - `tailwindcss-development`: Layouts responsivos, utilidades semánticas y optimización CSS.
  - `visual` / `design` (Gobernanza del Dominio del Proyecto):
    - Reglas canónicas de identidad, tokens corporativos y semántica funcional del negocio específico (definidas en `tailwind.config.*`, `@theme` o documentación institucional en `.agents/skills/visual` o `doc/`).
    - Filosofía de layout del proyecto (ej. interfaces de monitoreo/dashboards *scroll-free* o flujos guiados).
    - Cero alteración arbitraria de las paletas y convenciones semánticas definidas por el dominio.
  - `ui-ux-pro-max` (Elevación y Mejores Prácticas de Diseño):
    - **Principio de Subordinación:** Actúa como motor de inteligencia y refinamiento UI/UX de nivel mundial (SaaS / B2B Industrial / Dashboards), pero **siempre supeditado** a los tokens y reglas canónicas de la skill de dominio (`visual` o `design`).
    - **Jerarquía y Pulido Visual:** Aplica mejores prácticas de contraste, espaciados sistemáticos, micro-bordes elegantes (`border-slate-200/80`), radios consistentes y micro-interacciones suaves (`hover:-translate-y-0.5`, transiciones fluidas de 150-200ms).
    - **Tipografía Técnica Dual:** Configuración de fuente sans para interfaz y monoespaciada para datos técnicos/cifras, forzando `tabular-nums` en valores numéricos dinámicos para evitar parpadeos o saltos de layout.
* **Documentación y Arquitectura:**
  - `documentacion`: Registrar cambios arquitectónicos relevantes en la documentación técnica del proyecto (`doc/`).

> [!NOTE]
> Antes de comenzar las modificaciones, crea o usa la rama de trabajo dedicada: `feature/skills-audit` o la rama de feature correspondiente.

---

## 🛡️ FASE 1: BACKEND, ELOQUENT Y PORTABILIDAD DE BASE DE DATOS
*(Skills de referencia: `laravel-best-practices`, `sql-high-concurrency-architect`, `laravel-security`, `testing-best-practices`)*

### 1.1 Estilo y Normalización PSR-12
* Ejecuta el linter oficial del proyecto en el backend:
  ```bash
  vendor/bin/pint
  ```
* Normaliza imports, tipado estricto en parámetros y retornos (`declare(strict_types=1);` donde aplique).

### 1.2 Arquitectura y Desacople
* **Thin Controllers:** Los controladores solo reciben peticiones, autorizan, delegan y retornan un Resource. Prohibidas transacciones complejas o lógica de negocio pesada en controladores.
* **Extracción de Lógica:** Centraliza la lógica de negocio en Services (o Single-Purpose Actions según las convenciones del proyecto).
* **Form Requests:** Mueve toda la validación manual `$request->validate(...)` a clases dedicadas en `app/Http/Requests/`.

### 1.3 Portabilidad Absoluta de Base de Datos (SQLite ➔ MySQL / PostgreSQL / SQL Server)
Para garantizar que la base de datos pueda migrar de SQLite a un motor SQL de producción sin romper código ni datos:
* **Cero Funciones SQL Propietarias (Dialect-Free):**
  - **Prohibido** usar `DB::raw()` con funciones exclusivas de SQLite (ej. `strftime()`, `DATETIME('now')`, operador de concatenación `||`).
  - Utiliza siempre los métodos nativos y agnósticos del Query Builder de Laravel: `whereDate()`, `whereYear()`, `whereBetween()`, etc.
* **Tipado Estricto de Columnas en Migraciones:**
  - Define siempre longitudes y tipos explícitos (`$table->string('codigo', 50)`, `$table->unsignedInteger()`, `$table->boolean()`). No confíes en la laxitud de tipos de SQLite (donde cualquier columna acepta cualquier tipo de dato).
  - Llaves foráneas con integridad referencial explícita: `$table->foreignId('...')->constrained()->cascadeOnDelete();`.
  - Orden cronológico estricto en migraciones para evitar fallos de dependencias en motores que exigen integridad referencial inmediata (MySQL/Postgres).
* **Casts Obligatorios en Modelos (`casts(): array`):**
  - Todo modelo Eloquent debe implementar el método nativo `casts(): array` tipando rigurosamente:
    - Fechas / Timestamps (`'fecha_registro' => 'datetime'`, `'dia' => 'date'`).
    - Booleanos (`'activo' => 'boolean'`). *(En SQLite se almacenan como 0/1; sin cast, fallan comparaciones estrictas `=== true`)*.
    - JSON / Arrays (`'metadata' => 'array'` o `'collection'`).
    - Enums tipados de PHP 8.4 donde aplique.
* **Eloquent Strict Mode:**
  - Activa en `AppServiceProvider`:
    ```php
    Model::shouldBeStrict(!app()->isProduction());
    ```
    Esto previene de forma temprana: N+1 queries (`preventLazyLoading`), asignación masiva de campos no fillable (`preventSilentlyDiscardingAttributes`) y acceso a atributos inexistentes (`preventAccessingMissingAttributes`).

### 1.4 Auditoría de Seguridad
* Ejecuta auditoría de vulnerabilidades de dependencias:
  ```bash
  composer audit
  ```
  Resuelve cualquier paquete con avisos de seguridad críticos o advertencias de abandono.
* Comprueba que cada endpoint o mutación crítica esté protegido por Form Request `authorize()`, `Policies`, `Gates` o Middlewares de rol/permiso.

### 1.5 Gate de Calidad Backend
* Corre la suite de pruebas del proyecto:
  ```bash
  php artisan test
  ```
* **Condición de corte:** 100% de los tests deben pasar en verde sin regresiones antes de pasar a la siguiente fase.

---

## 🌐 FASE 2: CAPA DE APIS, CONTRACTS Y SERIALIZACIÓN
*(Skills de referencia: `api-contract-spec`, `laravel-best-practices` [`rules/routing.md`])*

### 2.1 Estandarización de Respuestas con API Resources
* **Regla Anti-Fuga de Datos:** **PROHIBIDO** retornar modelos Eloquent directamente desde controladores (`return $model;` o `return response()->json($model);`).
  - Toda respuesta debe serializarse a través de un **API Resource** (`JsonResource` o `ResourceCollection` en `app/Http/Resources/`).
  - Beneficios: Desacopla los nombres de columnas de la base de datos del contrato público, oculta campos sensibles y garantiza estabilidad ante cambios de esquema.
* **Estructura Consistente:**
  - Recursos individuales: `{ "message": "...", "data": { ... } }`
  - Listados paginados: uso de `AnonymousResourceCollection` con metadatos de paginación (`meta`, `links`).
  - Errores de validación: formato canónico de Laravel con HTTP 422 (`{ "message": "...", "errors": { ... } }`).
  - Códigos HTTP semánticos: `200 OK`, `201 Created`, `204 No Content`, `400 Bad Request`, `401 Unauthorized`, `403 Forbidden`, `404 Not Found`, `422 Unprocessable Content`, `500 Server Error`.

### 2.2 Sincronización Contract-First (OpenAPI 3.1)
* Si el proyecto opera en **Modo A (API REST)**:
  - Todo endpoint nuevo o modificado debe reflejarse en `contracts/openapi.yaml`.
  - Los esquemas `components/schemas` de OpenAPI deben coincidir fielmente con las propiedades retornadas por los API Resources de Laravel y con los tipos TypeScript del Frontend (`frontend/src/types/`).

---

## ⚡ FASE 3: FRONTEND & REACTIVE PERFORMANCE
*(Skills de referencia: `vue-best-practices`, `tailwindcss-development`)*

### 3.1 Estandarización de Componentes Vue 3
* **Composition API Estricta:** Todo nuevo componente o refactorización debe usar `<script setup lang="ts">`.
* **Reactividad Eficiente:**
  - Sustituye funciones o métodos recalculados repetidamente en plantillas por propiedades reactivas computadas (`computed()`).
  - Respeta el flujo unidireccional de datos (*Props down, Events up* / `defineEmits`).
  - Extrae la lógica reutilizable a composables dedicados (`composables/use*.ts`).

### 3.2 Gestión de Formularios y Estados
* **Si el proyecto es Modo A (API REST + Vue SPA):**
  - Servicios de API centralizados con tipado estricto derivado de OpenAPI.
  - Gestión rigurosa de estados de carga (`isLoading`), prevención de doble submit (`:disabled="isLoading"`) y captura centralizada de errores HTTP.
* **Si el proyecto es Modo B (Inertia.js):**
  - Estandariza con `useForm` de `@inertiajs/vue3` (`form.processing`, `form.errors`, `form.isDirty`).
  - Aplica `prefetch="hover"` en enlaces `<Link>` de navegación frecuente y `preserveScroll: true` / `preserveState: true` en filtros y listados.

### 3.3 Verificación de Tipos y Compilación de Bundle
* Ejecuta la comprobación de tipos y el build de Vite en la carpeta frontend:
  ```bash
  npm run build
  # o: npx vue-tsc -b && vite build
  ```
* Resuelve cualquier error de tipado TypeScript o advertencia de transpilación antes de avanzar.

---

## 🎨 FASE 4: UI/UX PRO MAX, ACCESIBILIDAD Y BRANDING INSTITUCIONAL
*(Skills de referencia: `visual` / `design` [Gobernanza del Dominio], `ui-ux-pro-max` [Elevación Visual y Mejores Prácticas], `tailwindcss-development`)*

### 4.1 Principio de Simbiosis: Dominio (`visual` / `design`) + Excelencia (`ui-ux-pro-max`)
* **Jerarquía de Autoridad:**
  - La skill de dominio del proyecto (`visual` o `design`) define **QUÉ** es el sistema: identidad de marca, paleta semántica, tokens corporativos, significado funcional de los colores y reglas de negocio irrenunciables.
  - La skill `ui-ux-pro-max` define **CÓMO** llevar esa identidad a su máxima expresión de calidad visual y táctil: espaciados rítmicos, contraste optimizado, terminación visual pulida, jerarquía tipográfica y micro-interacciones.
* **Prohibido Alterar la Semántica del Dominio:** `ui-ux-pro-max` nunca debe inventar o reemplazar colores que tengan un significado de negocio ya establecido por el proyecto (ej. códigos de estado, estados operativos o entidades clave). Su rol es ordenar, elevar contrastes, agregar refinamiento óptico y estructurar la información con profesionalismo de clase mundial.

### 4.2 Tipografía Técnica y Estabilidad Numérica
* **Dual Font System:**
  - `font-sans`: Familia tipográfica primaria (ej. Inter, Plus Jakarta Sans) para la navegación, títulos, formularios y textos de lectura.
  - `font-mono`: Familia monoespaciada técnica (ej. JetBrains Mono, Fira Code) para códigos de serie, identificadores, hashes, telemetría y lecturas métricas.
* **`tabular-nums` Obligatorio:** Toda métrica dinámica, contador reactivo, importe monetario, porcentaje o valor numérico que cambie en tiempo real debe usar `tabular-nums` (`font-variant-numeric: tabular-nums`) para evitar el molesto temblor o desplazamiento horizontal (*layout shift*) durante recálculos.

### 4.3 Densidad de Información, Profundidad y Micro-Interacciones
* **Prioridad de Layout según el Dominio:** Si el proyecto exige vistas ejecutivas o de monitoreo operativo, priorizar arquitecturas *scroll-free* donde los indicadores críticos se aprecien de un solo vistazo. Si es transaccional, estructurar pasos y paneles colapsables limpios.
* **Micro-Bordes Semitransparentes:** Sustituir bordes sólidos pesados por micro-bordes elegantes con transparencia (`border-slate-200/80` en temas claros o `border-slate-800/80` en oscuros).
* **Profundidad y Elevación Suave:** Sombras tenues y naturales (`shadow-xs` / `shadow-2xs`), elevaciones reactivas sutiles (`hover:-translate-y-0.5`) y transiciones de 150–200ms para sensación de agilidad.
* **Interactividad Táctil y Punteros:** Todo elemento interactivo o clickable debe declarar `cursor-pointer` explícito y feedback de pulsación (`active:scale-95` o variación de brillo).

### 4.4 Accesibilidad (WCAG AA) y Feedback al Usuario
* **Semántica y Lectores de Pantalla:**
  - Añade atributos `role="alert"` o `aria-live="polite"` en mensajes de error (`InputError.vue`) y notificaciones (`Toast.vue`).
  - Proporciona etiquetas accesibles (`aria-label`) en botones iconográficos o acciones sin texto visible.
* **Contraste de Color:**
  - Verifica que el texto principal sobre fondo claro sea de alto contraste (mínimo ratio 4.5:1, priorizando texto oscuro puro donde lo exija la normativa de accesibilidad).
  - En badges y píldoras de estado, combinar fondos tintados suaves (`bg-{color}-50`) con texto saturado (`text-{color}-700`) y bordes armónicos (`border-{color}-200`).
* **Estados Interactivos:**
  - Garantiza indicadores visuales de `hover`, `focus-visible` (anillo de enfoque visible `ring-2`) y estado `disabled` (con opacidad reducida y cursor bloqueado cuando una acción esté en proceso).

---

## 📜 FASE 5: DOCUMENTACIÓN VIVA Y CHANGELOG
*(Skills de referencia: `documentacion`)*

### 5.1 Registro Técnico
* Asienta en `doc/` (o en la bitácora técnica del repositorio) los cambios estructurales realizados:
  - Nuevos servicios, acciones o API Resources implementados.
  - Migraciones o adaptaciones de tipos para compatibilidad de base de datos.
  - Correcciones de seguridad o dependencias actualizadas.

---

## 🏁 ENTREGABLES Y CRITERIOS DE ACEPTACIÓN

1. **Rama Git Limpia:** Commits semánticos y atómicos por cada fase (`chore: pint & backend lint`, `refactor: thin controllers & api resources`, `db: agnostic migrations & strict models`, `sec: composer audit & strict models`, `test: green test suite`, `ui: accessibility & wcag aa`).
2. **Quality Gates en Verde:**
   - Backend: `php artisan test` pasando al 100%.
   - Frontend: `npm run build` sin errores de compilación ni advertencias de tipos.
3. **Informe Ejecutivo Final:**
   - Controladores desacoplados y API Resources creados.
   - Verificación de compatibilidad e independencia de motor de base de datos.
   - Vulnerabilidades mitigadas en `composer audit`.
   - Mejoras de reactividad, accesibilidad y rendimiento implementadas.