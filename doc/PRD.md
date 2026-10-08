# PRD — Product Requirements Document
## Turismo Seguro San Juan

> **Modelo de Negocio e Iniciativa Privada:** Turismo Seguro San Juan es una plataforma digital y empresa privada de certificación y catálogo seguro de alquileres temporales y experiencias turísticas. Su modelo de negocio se basa en el cobro de auditorías físicas in situ y membresías de suscripción a los anfitriones, articulando una red de validadores comerciales comisionados y convenios institucionales de colaboración con entes turísticos provinciales y municipales.

---

## 1. Resumen Ejecutivo

**Nombre del Producto**: Turismo Seguro San Juan  
**Tipo de Proyecto**: Plataforma digital privada de certificación y catálogo seguro de alojamientos y experiencias  
**Titularidad / Operación**: Empresa privada independiente  
**Convenios y Alianzas**: Acuerdos marco de colaboración con la Secretaría / Ministerio de Turismo de San Juan, municipios y cámaras de turismo locales para homologación, difusión y respaldo institucional.  
**Dominio Oficial**: Dominios comerciales privados (`turismoseguro.com.ar`, `turismoseguro.ar` y `api.turismoseguro.com.ar`).  
**Objetivo General**: Erradicar las estafas en alquileres temporales en San Juan mediante certificación física *in situ* y el sello oficial **"Verificado TS"**, conectando a turistas con anfitriones legítimos y brindando herramientas operativas de gestión y disponibilidad.

---

## 2. Modelo de Negocio, Monetización y Comisiones

### 2.1 Regla Inquebrantable de Publicación
- **Cero publicación sin verificación:** Ninguna propiedad o experiencia puede aparecer publicada en el catálogo público sin haber completado la inspección física *in situ*, haber sido aprobada por el área de Calidad (Supervisor) y contar con la suscripción/tarifa al día.
- **Romper la inercia del pago:** Para incentivar la adhesión inicial del anfitrión ("romper la inercia"), la tarifa de ingreso se fija a un precio accesible y competitivo, apalancada en el alto valor diferencial: Sello Verificado TS, blindaje contra estafas, difusión oficial y soporte de disponibilidad.

### 2.2 Esquema de Comisiones para la Fuerza de Validadores en Terreno
Los validadores cumplen un doble rol: **auditores técnicos y captadores comerciales en la calle**. Para potenciar el crecimiento territorial, se implementa un modelo de incentivos escalonado:
1. **Mes 1 (Adquisición y Auditoría In Situ):**  
   El validador que capta al cliente y realiza la inspección física cobra un **porcentaje mayoritario (ej. 60% al 70%)** del primer pago del anfitrión. Esto premia el esfuerzo de salir a la calle a convencer al prestador.
2. **Meses Subsiguientes (Mantenimiento y Cartera Activa):**  
   El validador retiene un **ingreso residual mensual (ej. 15% al 20%)** por cada anfitrión de su cartera que mantenga la suscripción activa. Esto incentiva al validador a mantener una buena relación con sus anfitriones y asegurar su permanencia.
3. **Margen Neto de la Empresa:**  
   El porcentaje restante queda para la plataforma TS para financiar la infraestructura, marketing digital del catálogo, soporte técnico y margen de beneficio.
4. **Módulo Administrativo de Liquidaciones:**  
   El sistema registra automáticamente cada anfitrión captado, calcula las comisiones devengadas y genera las liquidaciones periódicas para el equipo comercial/validadores.

---

## 3. Jerarquía y Roles de Usuario (5 Roles)

| Rol | Identificador | Responsabilidades Clave |
| :--- | :--- | :--- |
| **Super Admin** | `super_admin` | Dirección general del negocio. Visualiza métricas financieras, gestiona tarifas, audita usuarios y aprueba las liquidaciones de comisiones a los validadores. |
| **Supervisor de Calidad** | `supervisor` | **Control antifraude interno.** Audita las actas digitales y fotografías subidas por los validadores en terreno. Da de alta definitiva o de baja las propiedades y emite el Sello TS formal. |
| **Validador Comercial** | `validator` | Fuerza de venta y auditoría en calle. Capta anfitriones, realiza visitas in situ, completa el checklist digital, sube fotos de control y consulta su balance de comisiones ganadas. |
| **Anfitrión / Prestador** | `host` | Cliente pago. Registra sus propiedades, solicita validación, gestiona sus descripciones y administra su **calendario de disponibilidad** para evitar consultas en fechas ocupadas. |
| **Turista / Usuario** | `tourist` | Público general. Busca alojamientos, filtra por comodidades/departamentos, consulta disponibilidad en calendario y contacta directamente al WhatsApp verificado sin intermediación. |

---

## 4. Alcance Funcional del Sistema

### 4.1 Portal Público Web (`client/` - Vue 3)
- Landing page institucional con buscador y departamentos destacados de San Juan.
- Catálogo reactivo de alquileres y experiencias con filtros (departamento, capacidad, precio, amenities).
- Ficha de detalle completa con sello "Verificado TS" dinámico, fecha de inspección y galería de alta resolución.
- **Consulta de Disponibilidad Pública:** El turista puede seleccionar fechas o ver el calendario de disponibilidad del inmueble antes de iniciar el contacto.
- Botones de contacto directo (WhatsApp con mensaje preformateado y llamada telefónica).

### 4.2 Panel del Anfitrión (`client/` + `server/`)
- Registro y gestión de perfil del anfitrión.
- Carga y edición de fichas de propiedades/experiencias.
- Solicitud de validación física in situ.
- **Gestor de Disponibilidad (Calendario):** Herramienta interactiva para bloquear días ya reservados (ej. fin de semana ocupado) o habilitar fechas libres, evitando mensajes innecesarios de turistas.
- Estado de su membresía y sello verificado.

### 4.3 Panel Móvil del Validador Técnico
- Aplicación responsive optimizada para smartphone en terreno.
- Listado de visitas programadas y captura de nuevos prospectos en calle.
- Checklist in situ (servicios básicos, piscinas, titularidad, coordenadas GPS).
- Carga de fotos de control directamente desde la cámara del celular.
- **Dashboard de Comisiones:** Visualización transparente de anfitriones activos en cartera y comisiones acumuladas (mes 1 vs recurrentes).

### 4.4 Panel de Supervisión y Calidad
- Bandeja de actas de inspección pendientes de revisión.
- Verificación cruzada de fotos y datos de titularidad para evitar fraudes internos.
- Dictamen de aprobación o rechazo fundamentado del Sello TS.
- Alta o baja de publicaciones en el catálogo público.

### 4.5 Panel de Super Administración
- Dashboard de métricas: propiedades activas, solicitudes en proceso, facturación estimada.
- Módulo de liquidación y pagos a validadores.
- Gestión de usuarios y asignación de roles.

---

## 5. Estrategia de Autenticación

1. **Fase Actual (MVP):**
   - Autenticación tradicional mediante correo electrónico y contraseña.
   - Sesiones seguras mediante cookies `HttpOnly` y protección CSRF con **Laravel Sanctum**.
2. **Fase Inmediata (Roadmap):**
   - **Google Sign-In (OAuth 2.0):** Integración con Google Identity Services y Laravel Socialite para facilitar el inicio de sesión con un solo clic tanto a anfitriones como a turistas.

---

## 6. Criterios de Éxito del Negocio

| Indicador | Meta Inicial (6 meses) |
| :--- | :--- |
| Anfitriones validados activos | 60+ propiedades con membresía y sello vigente |
| Retención mensual de anfitriones | > 85% de renovación de membresía |
| Tasa de incidentes / denuncias | 0 denuncias por fraude en propiedades certificadas |
| Equipo comercial activo | 5 a 10 validadores con cartera activa de anfitriones |
| Tiempo promedio de auditoría | < 72 horas desde la solicitud hasta la visita |
