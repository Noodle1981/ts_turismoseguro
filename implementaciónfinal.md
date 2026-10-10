A continuación tienes el Plan y Orden de Trabajo Óptimo (Roadmap de 5 Fases), diseñado con dependencias lógicas para que cada paso reutilice lo que construyó el anterior sin rehacer código.

🗺️ Hoja de Ruta de Implementación
[Fase 0: Cimientos]  ──► san_juan_verificado (Tokens, Tailwind y Layout Base)
                               │
[Fase 1: Catálogo]   ──► alquileres_diurnos_verificados_san_juan (Home, Filtros y Cards)
                               │
[Fase 2: Conversión] ──► detalle_quinta_diurna_verificada (Bento Grid, Checklist Auditor, Cotizador)
                               │
[Fase 3: Cuentas]    ──► iniciar_sesi_n_y_registro_turismo_seguro_san_juan (Auth y 50 Cupos Gratis)
                               │
[Fase 4: Operación]  ──► panel_de_validador_acta_de_inspecci_n_in_situ (Acta, CBU y QR)
                               │
[Fase 5: Expansión]  ──► experiencias_eventos_y_alquileres_cercanos (FNS y Turismo Activo)
Paso a Paso Detallado: Qué pedirle a Antigravity en cada carpeta
🟢 Fase 0: Cimientos del Sistema (san_juan_verificado)
Por qué primero: Configura los colores de marca (#0f2942, verdes de validación, tonos tierra sanjuaninos), fuentes (Plus Jakarta Sans) y radios antes de maquetar ninguna vista.
Qué pedirle:
"Configura tailwind.config.js e importa los tokens de san_juan_verificado/DESIGN.md. Establece la fuente Plus Jakarta Sans y define los colores semánticos (primary, surface, emerald-check). Luego crea el layout compartido AppHeader.vue y AppFooter.vue."

🟢 Fase 1: El Núcleo de la Plataforma (alquileres_diurnos_verificados_san_juan)
Por qué segunda: Es la puerta de entrada. Si esto funciona, ya tienes el 60% del producto visible.
Componentes clave a extraer:
TopBannerPromo.vue (Banner de las primeras 50 ofertas con 6 meses gratis).
FiltroDepartamentos.vue (Selector horizontal de departamentos sanjuaninos).
CardQuintaDiurna.vue (Tarjeta reutilizable con pildoritas de pileta, quincho, capacidad y badge verificado).
Qué pedirle:
"Toma alquileres_diurnos_verificados_san_juan/code.html y conviértelo en CatalogoView.vue en Vue 3 (<script setup>). Extrae la card de quinta a src/components/quintas/CardQuintaDiurna.vue y los filtros a FiltroDepartamentos.vue. Utiliza un array reactivo con ref() para los datos mockeados."

🟢 Fase 2: La Ficha de Detalle y Conversión (detalle_quinta_diurna_verificada)
Por qué tercera: Es el destino natural al hacer clic en cualquier tarjeta de la Fase 1 (/quintas/:id).
Componentes clave a extraer:
BentoGallery.vue (Mosaico de 5 fotografías).
AuditorReportCard.vue (Checklist técnico in situ: pileta con cerco perimetral, DNI y CBU cotejado).
CotizadorTurno.vue (Calculador de turno diurno vs. noche).
Botón de WhatsApp directo con mensaje prearmado al dueño.
Qué pedirle:
"Implementa detalle_quinta_diurna_verificada/code.html como DetalleQuintaView.vue (/quintas/:id). Conecta la navegación para que al hacer clic en CardQuintaDiurna.vue abra esta vista con el id correspondiente. Extrae el reporte del auditor a AuditorReportCard.vue."

🟢 Fase 3: Registro y Acceso de Propietarios (iniciar_sesi_n_y_registro_turismo_seguro_san_juan)
Por qué cuarta: Permite a los dueños de quintas registrarse para solicitar la auditoría gratuita de lanzamiento.
Componentes clave:
Formulario dual (Login / Registro) con botón de Google Credential.
Columna lateral con el testimonio del propietario de El Medanito y las garantías anti-fraude.
Qué pedirle:
"Convierte iniciar_sesi_n_y_registro_turismo_seguro_san_juan/code.html en AuthView.vue (/login y /registro). Maneja el estado reactivo entre 'Iniciar Sesión' y 'Crear Cuenta Propietario'."

🟢 Fase 4: Operación Interna (panel_de_validador_acta_de_inspecci_n_in_situ)
Por qué quinta: Es la herramienta de backoffice que da respaldo a todo el sello de confianza.
Componentes clave:
Formulario de inspección de 32 puntos (seguridad de piscina, salvavidas, disyuntor, agua potable).
Verificación de CBU y DNI del titular.
Preview del certificado con código de acta y hash SHA-256.
Qué pedirle:
"Convierte panel_de_validador_acta_de_inspecci_n_in_situ/code.html en ValidadorActaView.vue (/validador/acta/:id). Modela el checklist reactivo y el botón para aprobar la auditoría."

🟢 Fase 5: Módulo de Eventos Dinámicos (experiencias_eventos_y_alquileres_cercanos_turismo_seguro_san_juan)
Por qué última: Es una capa de valor adicional que expande el catálogo en épocas de alta demanda (Fiesta Nacional del Sol, turismo de estrellas en Barreal, etc.).
Componentes clave:
Selector dinámico de eventos por fecha y departamento.
Grilla de experiencias y quintas con radio de cercanía al predio ferial o dique.
Qué pedirle:
"Convierte experiencias_eventos_y_alquileres_cercanos_.../code.html en EventosCercanosView.vue (/eventos). Configura el estado para filtrar qué quintas están a menos de 15 minutos del evento seleccionado."

💡 Regla de Oro para ejecutar en Antigravity
Ejecuta una fase a la vez. No le pidas pasar a la siguiente hasta que la vista actual compile (npm run dev), no tenga errores en consola y se vea idéntica. Si una fase queda lista, haz un git commit y pasa a la siguiente. ¡El flujo va a ser ultra fluid