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

## 4. Estrategia de Publicación en Dos Categorías y Algoritmo de Posicionamiento

Este módulo define la gobernanza del catálogo público para maximizar el volumen de captación sin perder la diferenciación de seguridad ni la viabilidad comercial.

### 4.1. Fundamento Estratégico
- **No exclusión obligatoria:** Prohibir publicar a quienes no tengan póliza vaciaría la plataforma al inicio debido a la informalidad del mercado.
- **Diferenciación por incentivo (Zanahoria en vez de Palo):** No se exige seguro para subir la oferta, pero la validación técnica in situ y el seguro de Responsabilidad Civil operan como el motor ponderador #1 del algoritmo de búsqueda.

### 4.2. Las Dos Categorías Canónicas
| Parámetro | Categoría 1: Publicación Estándar | Categoría 2: Premium / Verificada ("Oferta con Seguro") |
| :--- | :--- | :--- |
| **Requisitos Previos** | Alta libre sin validación obligatoria ni póliza previa. | Validación técnica in situ aprobada + Póliza de RC vigente constatada. |
| **Insignia Visual** | Sin insignia de seguridad. Muestra cartel transparente informativo. | Insignia destacada verde: **"Oferta con Seguro"** (`verified_user`). |
| **Aviso al Huésped** | *«Esta propiedad no cuenta con validación de seguro activa»*. | *«Predio Verificado In Situ · Cobertura RC Triunfo Seguros / Póliza Propia»*. |
| **Posicionamiento** | Indexación orgánica base (segundo orden). | **Prioridad Top #1 absoluta** en portada, búsquedas y filtros por departamento. |
| **Términos y Deslinde Legal** | Intermediación publicitaria pura. El inquilino acepta asumir el riesgo civil antes de reservar. | Respaldo legal integral y trazabilidad con número de póliza y acta técnica provincial. |
| **Conversión Estimada** | Nivel base de consultas informales. | **Hasta 5x más conversiones** por WhatsApp debido a la alta confianza familiar. |

### 4.3. Impacto en el Algoritmo de Búsqueda (`CatalogController`)
La fórmula de ordenamiento (`ordering score`) en los listados ponderará:
$$Score = (P_{seguro} \times 100) + (P_{visita\_insitu} \times 50) + (Score_{reputacion} \times 20) + Reciente$$
Donde $P_{seguro} = 1$ para propiedades con insignia *"Oferta con Seguro"*, garantizando que los predios asegurados copen los primeros lugares y carruseles recomendados de San Juan.

---

## 5. Flujo Post-Validación: Contratación y Homologación de Pólizas

Define el flujo de negocio y la interfaz que se habilitará para el anfitrión una vez que el validador oficial visite la propiedad y apruebe el acta técnica presencial.

### 5.1. Dos Caminos de Homologación de Seguro

#### Camino A: "Poseo Seguro Contratado" (Póliza Propia)
1. **Selección previa en panel:** El anfitrión marca el tilde interactivo *«Poseo seguro contratado»*.
2. **Requisito obligatorio en mano:** Durante la inspección física, el propietario presenta la póliza vigente (impresa o digital con certificado de cobertura al día).
3. **Cotejo del Validador:** En el acta de inspección, el validador verifica compañía aseguradora, titular, vigencia y número de póliza.
4. **Activación inmediata:** Al cerrar el acta con dictamen aprobado, la propiedad adquiere automáticamente la insignia **"Oferta con Seguro"**.

#### Camino B: Contratación Post-Constatación (Alianza Triunfo Seguros)
1. **Etapa previa (Catálogo Informativo):** Antes de la visita, el anfitrión solo visualiza el catálogo referencial de coberturas sin capacidad de contratación anticipada ni cobros previos.
2. **Constatación física aprobatoria:** El inspector verifica el checklist preventivo (cerco perimetral de pileta 1.20m con traba infantil, disyuntor/térmica, matafuego ABC vigente y botiquín).
3. **Desbloqueo de la Vista de Contratación:** Una vez aprobada el acta técnica por la Mesa de Supervisión, el panel del propietario habilita el módulo interactivo **"Activar Cobertura Oficial"**:
   - **Plan Mensual Temporada ($18.500/mes · Diurno):** Cobertura diurna continua para predios con alquiler frecuente y días de pileta.
   - **Microseguro por Jornada ($3.200/día):** Activación por fin de semana o reserva puntual devengada.
   - **Seguro de Eventos (Fiestas):** Cobertura extendida para cumpleaños, recepciones, bodas o celebraciones nocturnas con mayor aforo de asistentes y música/sonido.
   - **Plan Anual Bonificado (12 Meses):** Cobertura total 365 días con arancel preferencial e insignia permanente.
4. **Emisión en 2 clics:** El anfitrión selecciona el plan deseado y confirma sin trámites burocráticos gracias a la pre-aprobación del informe técnico del validador.
5. **Impacto en Publicación:** La oferta migra de *Estándar* a *Categoría Premium / Oferta con Seguro*, escalando inmediatamente a la cima del algoritmo de búsqueda.

### 5.3. El Acta Oficial de Homologación (ej: `Acta #849-MED/26`)
- **¿Qué es?** Instrumento público y técnico oficial emitido y suscripto por el validador matriculado colegiado al culminar la auditoría física in situ. Su codificación oficial (ej: `Acta #849-MED/26`) representa el número correlativo de legajo territorial, el departamento auditado (`MED` = Médano de Oro / Rawson) y el año de ejercicio provincial (`26` = 2026).
- **¿Para qué sirve?** Acredita y certifica de manera fehaciente ante el Estado provincial y los inquilinos que el predio existe materialmente, no es un alojamiento fantasma ni una estafa de redes sociales, constatando georreferenciación GPS exacta, fotos tomadas en terreno, titularidad dominial DNI y cumplimiento del checklist preventivo (cerco perimetral de pileta 1.20m, disyuntor, matafuegos ABC vigente y botiquín).
- **¿Cuál es su finalidad?**
  1. **Habilitación de la Insignia y Algoritmo:** Es el dictamen aprobatorio indispensable exigido por la Mesa de Supervisión para otorgar la insignia verde **"Oferta con Seguro"** y rankear en el Top #1 de búsquedas.
  2. **Emisión de Póliza RC:** Constituye el documento técnico legal que exige Triunfo Seguros para activar la cobertura de Responsabilidad Civil sin adelantos previos.
  3. **Blindaje Jurídico y Administrativo:** Otorga respaldo oficial al anfitrión frente al Ministerio de Turismo y Cultura de San Juan y certidumbre legal plena ante cualquier controversia, incidente o reclamo de inquilinos.

---

## 6. Próximos Pasos en el Código y Roadmap

1. [ ] Crear migración `create_host_blocked_tenants_table` para indexar bloqueos permanentes anfitrión-inquilino.
2. [ ] Ajustar algoritmo de ponderación en `CatalogController@properties` para priorizar ofertas con `insurance_company` y `policy_number` vigentes.
3. [ ] Diseñar vista/modal post-validación `ModalActivateInsurance.vue` desbloqueable tras la aprobación del acta de inspección.
4. [ ] Agregar selector de tipo de cobertura (Diurna vs. Seguro de Eventos/Fiestas) en el flujo de reservas y tarifas del anfitrión.
5. [ ] Filtrar propiedades en `CatalogController@properties` para excluir automáticamente predios de anfitriones que hayan marcado `rejected` al usuario autenticado.
6. [ ] Diseñar vista ejecutiva de la Ficha Prontuarial Completa con gráfico de scoring en el modal del inquilino.
7. [ ] Integrar el Score de Confianza en el panel del inquilino (`InquilinoPanelView.vue`).
