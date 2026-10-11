# Futuras Implementaciones: Sistema de Reputación, Scoring y Ficha Prontuarial de Inquilinos y Propietarios

Este documento describe la arquitectura, reglas de negocio y lógica de gobernanza para el sistema de reputación bidireccional y control de admisiones en **Turismo Seguro San Juan**.

---

## 1. Escala Canónica de Calificación del Anfitrión (5 Estados)

Actualmente implementada en la base de datos (`bookings.owner_rating`) y en la interfaz del panel:

| Estado | Código BD | Visualización UI | Propósito y Comportamiento |
| :--- | :--- | :--- | :--- |
| **Buen Inquilino** | `up` | 🟢 Verde con `thumb_up` | Huésped ejemplar: cuidó las instalaciones, respetó límites de decibeles, dejó limpio y cumplió horarios de egreso. |
| **En Observación / Seguimiento** | `follow_up` | 🟡 Ámbar con `visibility` | Inquilino admisible pero que requiere control especial (ej: demoras leves en entrega, llamados de atención menores, grupo numeroso de jóvenes). |
| **No Conforme** | `down` | 🔴 Rojo claro con `thumb_down` | Incumplimientos comprobados de reglas de convivencia o daño menor no intencional sin llegar a conducta maliciosa. |
| **No Aceptar / Bloqueado** | `rejected` | ⛔ Rojo oscuro con `block` | Falta grave, maltrato a instalaciones, intento de estafa, ruidos molestos desmedidos o desobediencia al aforo. El anfitrión veta futuras estadías del inquilino en su predio. |
| **Sin Historial** | `none` | ⚪ Gris con `horizontal_rule` | Inquilino nuevo o reserva aún no calificada tras el egreso. |

---

## 2. Ficha Prontuarial del Inquilino (Reputación Cruzada)

### 2.1. Trazabilidad por Usuario (`user_id`)
- Las calificaciones y observaciones privadas (`owner_observation`) no mueren en la reserva: **se acumulan en el perfil del inquilino** a lo largo del tiempo.
- Cuando el inquilino solicita una nueva fecha (en la misma quinta o en otra de la Red):
  - El nuevo propietario puede consultar su **Ficha Prontuarial de Estadías Previas**:
    - Cantidad total de alquileres concluidos.
    - Ratio de pulgares arriba vs. observaciones.
    - Comentarios privados redactados por dueños anteriores (ej: *"Excelente familia"*, *"Dejó residuos en pileta"*).

### 2.2. Restricción "No Aceptar" (`rejected`)
- **Regla a implementar en el Catálogo y Buscador:**
  - Cuando un anfitrión marca a un inquilino como `rejected`, la relación queda registrada en una tabla de veto/bloqueo de host (`host_blocked_tenants` o mediante el historial de sus propiedades).
  - Si ese inquilino inicia sesión e intenta reservar una propiedad de ese mismo anfitrión:
    - **Opción A (Ocultamiento):** Las ofertas de ese dueño no aparecen en los resultados de búsqueda de ese inquilino.
    - **Opción B (Bloqueo al solicitar):** La ficha de la propiedad se muestra, pero el botón de reserva se deshabilita con un mensaje sobrio: *"El anfitrión no tiene disponibilidad habilitada para su perfil en este momento."*

---

## 3. Algoritmo Antifraude y Control de "Dueños Hipercríticos" (Anti-Bias)

Para evitar abusos donde un propietario excesivamente exigente o conflictivo perjudique injustamente a inquilinos correctos:

### 3.1. Detección de Desvío Estadístico del Anfitrión
- Se calculará el **Ratio Crítico del Anfitrión** ($R_{host}$):
  $$R_{host} = \frac{\text{Calificaciones Negativas (\textit{down} + \textit{rejected})}}{\text{Total de Alquileres Realizados}}$$
- Si el promedio de la red provincial es del $5\%$ de malas calificaciones y un anfitrión tiene más del $35\%$, el sistema activa una **Alerta de Sesgo en Auditoría**:
  - Las calificaciones de ese dueño pierden peso ponderado en el score global del inquilino.
  - La mesa de supervisores de Turismo Seguro (`supervisor`) audita los motivos antes de afectar permanentemente el prontuario del huésped.

### 3.2. Derecho a Descargo y Mediación
- El inquilino podrá visualizar que cuenta con una observación en su panel (sin exponer datos confidenciales de terceros) y solicitar una revisión ante el Validador Comisionado si el reclamo fue injustificado.

---

## 4. Próximos Pasos en el Código

1. [ ] Crear migración `create_host_blocked_tenants_table` para indexar bloqueos permanentes anfitrión-inquilino.
2. [ ] Filtrar propiedades en `CatalogController@properties` para excluir automáticamente predios de anfitriones que hayan marcado `rejected` al usuario autenticado.
3. [ ] Diseñar vista ejecutiva de la Ficha Prontuarial Completa con gráfico de scoring en el modal del inquilino.
4. [ ] Integrar el Score de Confianza en el panel del inquilino (`InquilinoPanelView.vue`).
