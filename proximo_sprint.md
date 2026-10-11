# Plan de Trabajo - Próximo Sprint

Documento de seguimiento y backlog para la próxima sesión de desarrollo en **Turismo Seguro San Juan**.

---

## 1. Ajustes UI/UX y Funcionales en Vista "Configurar Tarifas" (`#tarifas`)

- [ ] **Arreglar la tarjeta de Días y Horas:**
  - En la tarjeta de gestión de oferta, los selectores de días (`L`, `M`, `M`, `J`, `V`, `S`, `D`), la franja horaria y el botón de configurar se ven apretujados y antiestéticos.
  - Rediseñar el componente para que respete una distribución espaciosa, armónica y limpia conforme al Design System Stitch (*San Juan Verificado*).
- [ ] **Funcionalidad "Visible en Buscador" (Pausar / Desactivar publicación):**
  - Validar y refinar la lógica de pausar visibilidad para evitar aparecer en el buscador público.
  - Diseñar flujo para propietarios que quieran tomarse vacaciones o pausar temporalmente:
    - Evaluar/implementar un modal que pregunte por rango de fechas (desde / hasta) de la pausa.
    - Analizar impacto con el estado de la suscripción/póliza activa durante el período de inactividad.

---

## 2. Ajustes en Vista "Calendario y Reservas" (`#calendario`)

- [ ] **Selector de Período y Botón de Bloqueo:**
  - El selector de meses/años se ve visualmente desajustado junto a los botones *"Bloquear Fechas / Uso Particular"*.
  - Reorganizar la barra de herramientas del calendario para que la navegación temporal (mes anterior, selector mes/año, mes siguiente) y los accesos de acción (*Bloqueo Interno / Eventos Especiales*) queden correctamente alineados y responsivos.

---

## 3. Integración Backend con Base de Datos & Laravel Best Practices

- [ ] **Conexión de Vistas con la Base de Datos:**
  - Reemplazar datos mockeados en las vistas (`tarifas`, `calendario`, `inquilinos`) por endpoints reales de la API en Laravel.
  - Emplear la skill **`laravel-best-practices`** y arquitectura de API Resources / DTOs.
- [ ] **Validación y Testing (TDD):**
  - Implementar Form Requests para validación de tarifas, reservas manuales y solicitudes de revalidación.
  - Ejecutar suites de pruebas con PHPUnit / Pest (`laravel-tdd`) para asegurar consistencia e integridad de datos.

---

## 4. Flujo End-to-End: "Agregar Oferta" $\rightarrow$ Bandeja del Validador

- [ ] **Interconexión con la Vista del Validador:**
  - El modal *"Agregar Oferta"* (`ModalAddOffer.vue`) debe persistir la nueva oferta en la base de datos con estado pendiente de homologación (`pendiente_validacion`).
  - Dicha oferta debe reflejarse en tiempo real en la bandeja de inspección del validador (`ValidadorBandejaView.vue` / `ValidadorMapaView.vue`).
  - Una vez validado y homologado por el validador oficial, completar el ciclo de vida del predio para dar por concluida esta vista.
