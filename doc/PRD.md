# PRD — Product Requirements Document
## Turismo Seguro San Juan

> **Modelo de Negocio e Iniciativa Privada:** Turismo Seguro San Juan es una plataforma y servicio de certificación de origen **privado**, con foco en la confianza ciudadana y turística. Su modelo de negocio se basa en la certificación física de inmuebles y experiencias, brindando visibilidad calificada y protección frente a fraudes, con capacidad de articular **convenios de cooperación institucional con organismos de turismo provinciales, municipales y cámaras del sector**.

---

## 1. Resumen Ejecutivo

**Nombre del Producto**: Turismo Seguro San Juan  
**Tipo de Proyecto**: Plataforma digital privada de certificación y catálogo seguro de alquileres temporales y experiencias turísticas  
**Titularidad / Operación**: Empresa privada independiente  
**Convenios y Alianzas**: Posibilidad de articulación con la Secretaría / Ministerio de Turismo de San Juan, municipios y cámaras hoteleras/turísticas locales para homologación, difusión y respaldo institucional.  
**Dominio Oficial**: Dominios privados de primer nivel (ej. `turismoseguro.com.ar`, `turismoseguro.ar`, con backend API en `api.turismoseguro.com.ar`).  
**Objetivo General**: Erradicar las estafas en alquileres temporales y experiencias turísticas en la provincia de San Juan, mediante un sello digital de confianza ("Verificado TS") otorgado tras auditorías e inspecciones físicas *in situ*, conectando de forma directa y segura a turistas con anfitriones legítimos.

---

## 2. El Problema del Mercado

Las estafas en alquileres temporarios afectan gravemente la economía turística de San Juan:

- Anunciantes falsos publican inmuebles inexistentes o ajenos en redes sociales y plataformas libres (Facebook Marketplace, grupos abiertos, avisos clasificados).
- Los turistas realizan depósitos o transferencias bancarias de reserva y, al arribar a destino, se encuentran desamparados ante una estafa.
- Los anfitriones y prestadores honestos pierden clientes frente a perfiles fraudulentos con precios irreales y ven perjudicada su reputación.
- El destino San Juan pierde competitividad y confianza turística global.

**Impacto comercial y social:**
- Daño económico directo y desprotección del visitante.
- Pérdida de ingresos legítimos para los anfitriones locales.
- Fricción y saturación en canales de denuncias y reclamos.

---

## 3. Propuesta de Valor

| Actor | Beneficio Principal |
| :--- | :--- |
| **Turistas (Nacionales e Internacionales)** | Certeza absoluta: acceso a un catálogo auditado físicamente donde el 100% de los anfitriones existen, fueron inspeccionados y sus datos de contacto directo (WhatsApp y teléfono) están validados. Cero comisión por intermediación en el MVP. |
| **Anfitriones y Prestadores Locales** | Sello de reputación y blindaje contra la competencia desleal. El sello "Verificado TS" eleva la tasa de conversión de reservas al transmitir confianza instantánea. |
| **Organismos de Turismo (Vía Convenio)** | Disponen de un aliado privado tecnológico que eleva el estándar de seguridad del destino, aporta trazabilidad de la oferta y reduce las denuncias por estafas en la provincia. |
| **Ecosistema Privado TS** | Plataforma escalable con modelo de monetización basado en auditorías de certificación, membresías de anfitriones y servicios de visibilidad destacada. |

---

## 4. Partes Interesadas (Stakeholders)

| Actor | Rol en el Sistema | Necesidades Clave |
| :--- | :--- | :--- |
| **Administración TS (Dirección Privada)** | Gestión integral de la empresa y plataforma | Dashboard operativo, métricas de verificación, auditoría del equipo, gestión de convenios y usuarios. |
| **Cuerpo de Validadores / Auditores** | Auditores de campo de la empresa privada | Web app / Panel móvil ágil para gestionar visitas asignadas, completar checklist técnico in situ y subir fotografías de control. |
| **Anfitriones / Prestadores** | Propietarios y operadores turísticos | Registro ágil, solicitud de auditoría para su propiedad/experiencia y seguimiento de estado del sello TS. |
| **Turistas** | Clientes finales | Búsqueda rápida, filtros reactivos por departamento y servicios, ficha transparente y contacto directo por WhatsApp verificado. |
| **Entes de Turismo Aliados (Convenio)** | Veedores institucionales colaboradores | Reportes consolidados de oferta formalizada y colaboración en campañas de destino seguro. |

---

## 5. Alcance Funcional del MVP

### 5.1 Portal Público Web / Móvil (`client/` - Vue 3)

| ID | Funcionalidad | Prioridad |
| :--- | :--- | :--- |
| F-01 | Landing page institucional con buscador, departamentos y alojamientos destacados | Alta |
| F-02 | Catálogo reactivo de alquileres con filtros (departamento, precio, capacidad, amenities) | Alta |
| F-03 | Catálogo de experiencias turísticas con filtros temáticos | Alta |
| F-04 | Ficha de detalle de propiedad (galería, geolocalización aproximada, reglas, amenities, contacto) | Alta |
| F-05 | Ficha de detalle de experiencia (duración, qué incluye, qué no incluye, operador verificado) | Alta |
| F-06 | Formulario para anfitriones: solicitud de auditoría e inspección para obtener el sello | Alta |
| F-07 | Sello visual "Verificado TS" con badge dinámico y tooltip de garantía de inspección | Alta |
| F-08 | Enlaces de contacto directo (WhatsApp con mensaje autocompletado y llamada telefónica) | Alta |
| F-09 | Navegación por los 19 departamentos de San Juan | Media |
| F-10 | Buscador predictivo por texto libre | Media |

### 5.2 Panel Operativo y Auditoría (`client/` + `server/`)

| ID | Funcionalidad | Prioridad |
| :--- | :--- | :--- |
| A-01 | Autenticación segura para administradores y validadores (Laravel Sanctum SPA Cookies) | Alta |
| A-02 | Bandeja de solicitudes de auditoría pendientes | Alta |
| A-03 | Asignación de visitas técnicas a validadores de zona | Alta |
| A-04 | Acta digital de inspección in situ (checklist de habitabilidad, fotos de verificación, dictamen) | Alta |
| A-05 | Gestión CRUD de propiedades, experiencias y departamentos | Alta |
| A-06 | Gestión de roles de usuario (admin, validator, host, tourist) | Alta |
| A-07 | Dashboard con métricas de propiedades activas, solicitudes y tiempos de validación | Media |
| A-08 | Exportación de reportes de auditoría en PDF / Excel | Media |

---

## 6. Funcionalidades de Fases Futuras

- **Aplicación Móvil Nativa (Android / iOS)** mediante empaquetado de la UI de Vue con **Capacitor** o app dedicada.
- Pasarela de cobro integrada para el arancel de validación o suscripción de anfitriones.
- Sistema de reseñas y reputación comunitaria de turistas validados.
- Módulo de integración API con registros oficiales de entes de turismo bajo convenio.
- Notificaciones automáticas por WhatsApp / SMS para estados de verificación.

---

## 7. Criterios de Éxito del Negocio

| Indicador | Meta MVP (Primeros 6 meses) |
| :--- | :--- |
| Alquileres auditados y publicados | 50+ inmuebles con certificación activa |
| Experiencias turísticas verificadas | 15+ experiencias con prestadores validados |
| Tasa de incidentes o estafas en alojamientos certificados | 0% denuncias sobre propiedades con sello TS |
| Tiempo promedio de visita y dictamen | < 5 días hábiles desde la solicitud del anfitrión |
| Convenios institucionales | Al menos 1 convenio marco de colaboración turística formalizado |

---

## 8. Arquitectura y Restricciones Técnicas

- **Arquitectura Desacoplada**:
  - `server/`: Backend API REST en **Laravel**, con base de datos relacional (SQLite para MVP, preparado para PostgreSQL/MySQL) y autenticación con Laravel Sanctum.
  - `client/`: Frontend SPA reactivo en **Vue 3** (Composition API, `<script setup>`, Vite, Tailwind CSS, Pinia y Vue Router).
- **Despliegue y Dominios Privados**:
  - Frontend servido en dominio principal privado (ej. `turismoseguro.com.ar`).
  - Backend API en subdominio privado (ej. `api.turismoseguro.com.ar`).
  - Comunicación segura mediante CORS estricto y cookies `HttpOnly` / `SameSite=Lax`.
