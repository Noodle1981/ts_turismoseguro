# PRD — Product Requirements Document
## Turismo Seguro San Juan

> **Nota:** Al ser un proyecto con financiamiento o impulso estatal, el modelo de negocio no se basa en la generación de ingresos comerciales directos, sino en la **generación de valor público**: protección ciudadana, fomento del turismo local verificado y apoyo a la economía regional de San Juan.

---

## 1. Resumen Ejecutivo

**Nombre del Producto**: Turismo Seguro San Juan  
**Tipo de Proyecto**: Plataforma digital de interés público (impulso estatal / municipal)  
**Cliente / Comitente**: Estado provincial o municipal de San Juan (a definir la dependencia exacta: Secretaría de Turismo, Ministerio de Producción, etc.)  
**Objetivo General**: Combatir las estafas en alquileres temporales turísticos y experiencias en la provincia de San Juan, creando un sello digital de confianza verificado in situ, accesible a ciudadanos y turistas.

---

## 2. El Problema Público

Las estafas en alquileres temporales afectan a miles de turistas y dañan la reputación del destino San Juan:

- Anunciantes falsos publican propiedades que no existen o no les pertenecen en plataformas libres (Marketplace, Grupos de Facebook, MercadoLibre).
- Los turistas realizan pagos de seña o reservas completas y no reciben nada a cambio.
- El estado no tiene una herramienta digital propia para certificar oficialmente a los prestadores de servicios turísticos locales.
- Los anfitriones legítimos y honestos pierden credibilidad frente a los estafadores, que ofrecen precios más bajos y mayor visibilidad.

**Impacto medible del problema:**
- Pérdida de confianza en el destino turístico San Juan.
- Daño económico directo a turistas estafados.
- Reducción de ingresos de anfitriones y prestadores turísticos legítimos.
- Aumento de denuncias en comisarías y organismos de defensa del consumidor.

---

## 3. La Propuesta de Valor Pública

| Para quién | El beneficio |
| :--- | :--- |
| **Turistas nacionales e internacionales** | Acceso a un catálogo certificado por el estado, donde cada propiedad fue verificada físicamente. Cero riesgo de estafa al contactar directamente a propietarios validados. |
| **Anfitriones y prestadores legítimos** | Visibilidad oficial y diferenciación real frente a la competencia fraudulenta. El sello "Verificado TS" aumenta la confianza y puede incrementar sus reservas. |
| **El Estado (comitente)** | Herramienta concreta de política pública turística. Reduce denuncias, mejora la imagen del destino y posiciona a San Juan como un destino seguro y moderno. |
| **La economía local** | Más turistas que viajan con confianza generan más gasto local: hotelería, gastronomía, transporte, comercios. |

---

## 4. Partes Interesadas (Stakeholders)

| Actor | Rol en el Sistema | Necesidades Clave |
| :--- | :--- | :--- |
| **Secretaría / Ministerio de Turismo** | Comitente y autoridad que avala el sello TS | Reportes de verificaciones, control de contenido publicado, estadísticas de uso |
| **Equipo de Validadores** | Personal en terreno que realiza visitas físicas | App o panel simple para gestionar visitas asignadas, subir fotos de control y aprobar/rechazar |
| **Anfitriones / Propietarios** | Publican y solicitan verificación de sus inmuebles | Proceso de solicitud de validación simple, seguimiento del estado de su solicitud |
| **Turistas** | Usuarios finales que buscan alquileres y experiencias | Búsqueda rápida, filtros claros, contacto directo verificado |
| **Administradores del Sistema** | Gestionan la plataforma y el equipo validador | Panel de control completo, gestión de usuarios y asignación de validadores |

---

## 5. Funcionalidades Requeridas (Alcance del MVP)

### 5.1 Portal Público (Turistas y Anfitriones)

| ID | Funcionalidad | Prioridad |
| :--- | :--- | :--- |
| F-01 | Página de inicio con alquileres y experiencias destacadas | Alta |
| F-02 | Catálogo de alquileres con filtros por departamento, precio, capacidad y comodidades | Alta |
| F-03 | Catálogo de experiencias turísticas con filtros básicos | Alta |
| F-04 | Ficha de detalle de alquiler (galería, descripción, comodidades, reglas, mapa y botón de contacto directo) | Alta |
| F-05 | Ficha de detalle de experiencia (galería, descripción, duración, precio y contacto) | Alta |
| F-06 | Formulario de solicitud de verificación para anfitriones nuevos | Alta |
| F-07 | Sello visual "Verificado TS" en todas las tarjetas y fichas de propiedades aprobadas | Alta |
| F-08 | Integración de WhatsApp y llamada telefónica directa desde la ficha de detalle | Alta |
| F-09 | Exploración por departamento / mapa de San Juan | Media |
| F-10 | Sistema de búsqueda por texto libre | Media |

### 5.2 Panel Administrativo (Uso Interno del Estado)

| ID | Funcionalidad | Prioridad |
| :--- | :--- | :--- |
| A-01 | Login seguro para administradores y validadores | Alta |
| A-02 | Listado de solicitudes de verificación pendientes | Alta |
| A-03 | Asignación de solicitudes a validadores específicos | Alta |
| A-04 | Vista de gestión de visita (subir fotos de control, agregar notas, aprobar/rechazar) | Alta |
| A-05 | CRUD completo de propiedades y experiencias | Alta |
| A-06 | Gestión de usuarios (anfitriones, validadores) | Alta |
| A-07 | Dashboard con estadísticas clave (total verificados, pendientes, visitas del mes) | Media |
| A-08 | Exportación de reportes en PDF o Excel para el comitente | Media |

---

## 6. Funcionalidades Fuera del Alcance del MVP (Fase Futura)

Estas funciones se reservan para versiones posteriores, una vez consolidado el MVP:
- Sistema de pagos o reservas en línea integrado en la plataforma.
- Sistema de calificaciones y reseñas de anfitriones por turistas verificados.
- Mapa interactivo con geolocalización de propiedades.
- Aplicación móvil nativa (iOS / Android).
- Portal multilingüe (inglés / portugués) para turismo extranjero.
- Notificaciones automáticas por email o WhatsApp en el flujo de verificación.

---

## 7. Criterios de Éxito

Dado que el cliente es el Estado, el éxito no se mide en ingresos monetarios sino en **impacto social y operacional**:

| Métrica | Meta del MVP (6 meses) |
| :--- | :--- |
| Propiedades verificadas y publicadas | 30+ alquileres con sello TS |
| Experiencias turísticas verificadas | 10+ experiencias publicadas |
| Solicitudes de verificación procesadas | 100% de solicitudes con respuesta en menos de 7 días |
| Denuncias por estafa en propiedades listadas | 0 denuncias sobre propiedades con sello TS |
| Accesos únicos al portal (turistas) | Medir línea de base en primer mes para proyectar crecimiento |

---

## 8. Restricciones y Supuestos

- **Restricciones Técnicas**: El proyecto debe funcionar en un monolito (backend + frontend en un mismo servidor) para simplificar el despliegue inicial.
- **Restricciones Presupuestarias**: El uso de SQLite en el inicio evita costos de servidor de base de datos adicionales.
- **Restricción de Equipo**: El equipo de validadores debe ser capacitado. La plataforma debe ser lo suficientemente simple para que un validador no técnico pueda usarla.
- **Supuesto Clave**: Se asume que el comitente estatal proveerá el equipo de validadores en terreno. La plataforma digital gestiona el proceso, pero la verificación física es responsabilidad del estado.

---

## 9. Preguntas Abiertas a Resolver con el Comitente

- [ ] ¿Qué organismo exacto del estado es el responsable y firmante del sello "Verificado TS"?
- [ ] ¿Existirá algún costo para los anfitriones por obtener el sello (ej. tasa administrativa) o será 100% gratuito?
- [ ] ¿Cuántos validadores en terreno se destinarán al equipo inicial?
- [ ] ¿Qué periodicidad tendrán las re-verificaciones de propiedades ya aprobadas?
- [ ] ¿El sistema debe integrarse con algún registro provincial de turismo existente?
- [ ] ¿Se requiere un dominio oficial del gobierno (ej. `.gob.ar`) para el hosting?
