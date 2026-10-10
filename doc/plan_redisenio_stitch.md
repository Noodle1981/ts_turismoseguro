# Ultra Plan de Rediseño: Turismo Seguro San Juan (Stitch 2.0)

**Fecha:** Octubre 2026  
**Objetivo:** Transformar la interfaz visual y la experiencia de usuario del proyecto Turismo Seguro San Juan para alinearlo 100% al sistema de diseño maestro de Stitch (`san_juan_verificado`), preservando intacta toda la solidez técnica del backend Laravel 13, sus 26 tests en verde y la gobernanza Contract-First.

---

## 1. Diagnóstico Comparativo: Actual vs Stitch

| Dimensión | Implementación Actual | Nuevo Diseño Stitch (`stitch_...`) | Acción en el Rediseño |
| :--- | :--- | :--- | :--- |
| **Paleta de Colores** | Azul cielo (`#0EA5E9`), verde genérico, naranja brillante | **Deep Navy** (`#001428`, `#0F2942`), **Emerald Assurance** (`#006C4A`, `#059669`), **San Juan Amber** (`#F59E0B`) | Migrar tokens en `tailwind.config.js` y clases en componentes |
| **Tipografía** | Montserrat + Inter | **Plus Jakarta Sans** (Google Fonts) para toda la jerarquía | Actualizar `index.html` y configuración Tailwind |
| **Enfoque de Negocio** | Alquiler vacacional tradicional pernocte | **Alquileres Diurnos y Eventos (Quintas y Quinchos)** para pasar el día sin pernocte + grandes eventos provinciales (FNS, Diques, Villicum) | Adaptar selector de turnos (Día vs Noche) y calculador de invitados |
| **Ficha de Propiedad** | Cards estándar de catálogo inmobiliario | **Bento Grid Fotográfico (5 fotos en cuadrícula editorial)** + Pill Verificado In Situ con fecha de inspección técnica | Rediseñar `PropertyDetailView.vue` y `PropertyCard.vue` |
| **Conversión / CTA** | Formulario simple de contacto por mail | **Calculador Dinámico de Invitados** (+ / -) con precio por tramo y botón **"Reservar vía WhatsApp Verificado"** | Integrar cotizador reactivo en detalle de propiedad |
| **Autenticación** | Pantalla simple centrada de login | **Pantalla Dividida (7:5) con Banner Promocional de Apertura** (6 meses gratis para las primeras 50 quintas) + Selector Tab Login/Registro | Rediseñar `LoginView.vue`, `RegisterClientView.vue` y `RegisterRentView.vue` |
| **Panel del Validador** | Modelo de datos listo en backend | **Acta de Inspección Técnica In Situ Digital** con barra de sincronización, checklist por pasos (Titularidad, Seguridad, Pileta) y geolocalización GPS en vivo | Implementar `InspectionView.vue` basada en `panel_de_validador...` |
| **Gobernanza de Skills** | `ui-ux-pro-max` activo en modo general | `visual` como **Autoridad Suprema** de diseño; `ui-ux-pro-max` **restringido a asesor bajo demanda exclusiva** sin poder de decisión | Actualizado en `.agents/skills/` |

---

## 2. Compatibilidad del Backend (Tablas, Modelos y Eloquent)

¡Buenas noticias! El modelo de datos construido en la Fase 2 cubre de forma natural las necesidades de Stitch:
1. **Campos Diurnos:** `properties` ya cuenta con `price_type` (`por día` / `/noche`), `capacity` (capacidad hasta decenas de personas para quintas diurnas) y `bedrooms` (permitiendo 0 dormitorios para quinchos de día).
2. **Servicios Especiales:** La tabla `property_amenities` permite almacenar dinámicamente:
   - `Pileta cercada (seguridad infantil)`
   - `Parrilla techada (2 metros)`
   - `Freezer para bebidas`
   - `Parque arbolado`
   - `Vajilla completa`
3. **Auditorías y Comisiones:** Las tablas `verification_requests` y `validator_commissions` ya soportan `checklist`, `notes`, `gps_latitude`, `gps_longitude` y `visit_date`, alineándose perfectamente con el acta de inspección de Stitch.

---

## 3. Hoja de Ruta de Ejecución en 5 Etapas

### Etapa 1: Cimientos de Diseño (Tailwind & Fuentes)
- [ ] Incorporar **Plus Jakarta Sans** y **Material Symbols Outlined** en `client/index.html`.
- [ ] Actualizar `client/tailwind.config.js` con la paleta Stitch:
  - `primary` (`#001428`, `#0F2942`)
  - `secondary` (`#006C4A`, `#82F5C1`)
  - `tertiary` (`#1F1000`, `#3B2200`, `#FFDDB8`)
  - `surface` y `surface-container` (jerarquía de 5 niveles de blanco/porcelana)
- [ ] Crear el componente canónico `BadgeVerifiedInSitu.vue` con el diseño exacto de Stitch (escudo, fecha de inspección y color esmeralda institucional).

### Etapa 2: Rediseño del Layout Global (Navbar, Top Tickers y Footer)
- [ ] Barra superior fija con ticker de garantía anti-estafas ("Auditoría in situ • Trato directo por WhatsApp • Sin comisiones").
- [ ] Banner de lanzamiento con cupos restantes ("6 meses gratis y auditoría sin cargo").
- [ ] Navbar premium con logo oficial, acceso a "Cómo Validamos", "Zonas", "Publicar Quinta", "Contactar Validador" e "Iniciar Sesión".

### Etapa 3: Rediseño de la Landing Page (`HomeView.vue`)
- [ ] Hero Section con imagen de quinta sanjuanina, pileta cercada y cordillera, con tarjetas de confianza ("100% Inspeccionadas", "0 Estafas", "7 Dptos").
- [ ] Tarjeta de "Auditoría Activa Hoy" (inspección de agua de pileta, cerco y heladeras).
- [ ] Buscador diurno para pasar el día en San Juan (zonas, turnos y cantidad de personas).
- [ ] Cuadrícula de quintas destacadas con fotos reales y badges in situ.

### Etapa 4: Rediseño de la Ficha de Propiedad (`PropertyDetailView.vue`)
- [ ] Bento Grid fotográfico (1 foto principal panorámica + 4 fotos de cuadrícula: parrilla, quincho, baños exteriores, portón seguro).
- [ ] Selector de Turno interactivo (Día Completo `10:00 a 20:00` vs Tarde/Noche `18:00 a 02:00`).
- [ ] Calculador dinámico de invitados (+ / -) con precio estimado.
- [ ] Botón de conversión oficial: "Reservar vía WhatsApp Verificado" con preformateado anti-fraude.

### Etapa 5: Rediseño del Portal de Autenticación (`LoginView.vue` y Registros)
- [ ] Formulario con split 7:5 y banner de beneficio de apertura para propietarios.
- [ ] Switch interactivo entre Iniciar Sesión y Crear Cuenta (Huésped / Anfitrión).
- [ ] Credenciales de prueba accesibles para demostración instantánea.
