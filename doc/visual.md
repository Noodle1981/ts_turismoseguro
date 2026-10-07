# Especificación de Diseño Visual y Experiencia de Usuario (UI/UX): Turismo Seguro San Juan

> **Design Intelligence & Master Reference:** Este documento integra los principios y estándares de **UI/UX Pro Max** (persistidos en [`design-system/turismo-seguro-san-juan/MASTER.md`](file:///d:/ts_turismoseguro/design-system/turismo-seguro-san-juan/MASTER.md)). El objetivo es posicionar a Turismo Seguro San Juan como una plataforma de confianza de categoría internacional, combinando estética editorial de hospitalidad moderna (estilo *Airbnb Plus* / *Boutique Stays*) con la rigurosidad de un sello de certificación técnica.

---

## 1. Dirección de Arte y Estilo: "Trust & Adventure Editorial"

- **Estilo Rector:** *Editorial Hospitalario + Motion-Driven Microinteractions*.
- **Sensación Buscada:** Transmitir tranquilidad instantánea al turista ("aquí no me van a estafar") y prestigio al anfitrión verificado, con la belleza paisajística de la Cordillera de los Andes, el sol cuyano y el cielo sanjuanino.
- **Profundidad y Texturas:** Eliminación de contenedores planos tipo Bootstrap. Se emplean capas de profundidad con elevación visual (`shadow-xl` suave, degradados de iluminación y glassmorphism `backdrop-blur-md`).
- **Anti-Patrones Prohibidos:**
  - ❌ Prohibido el uso de emojis como iconos (usar siempre SVG vectorizados: Heroicons o Lucide).
  - ❌ Prohibidas tarjetas planas con bordes grises duros sin sombras ni estados hover.
  - ❌ Prohibido saturar la pantalla con texto sin espacio en blanco generoso (`space-y-6`, `gap-8`).
  - ❌ Prohibido ocultar el sello "Verificado TS" en texto plano: debe ser la joya visual de cada ficha.

---

## 2. Paleta Cromática y Tokens de Identidad (Tailwind CSS)

Inspirada en el cielo diáfano de San Juan, los valles cordilleranos y la solidez institucional:

```javascript
// client/tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        // Azul Autoridad / Confianza Primaria (Cielo & Cordillera)
        brand: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          500: '#0ea5e9',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e', // Color principal de marca y encabezados
          950: '#082f49',
        },
        // Verde Esmeralda Certificado (El Sello Verificado TS)
        safe: {
          50: '#ecfdf5',
          100: '#d1fae5',
          500: '#10b981',
          600: '#059669', // Identificador de inspección in situ aprobada
          700: '#047857',
        },
        // Naranja / Terracota Acento (Sol Cuyano & Llamados a la Acción)
        accent: {
          50: '#fff7ed',
          100: '#ffedd5',
          500: '#f97316',
          600: '#ea580c', // Botones CTA y contacto
          700: '#c2410c',
        },
        // Superficies y Fondos
        surface: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        }
      },
      fontFamily: {
        display: ['Montserrat', 'sans-serif'], // Encabezados con impacto geométrico
        sans: ['Inter', 'sans-serif'],          // Texto, métricas y controles legibles
      },
      boxShadow: {
        'card-hover': '0 20px 25px -5px rgba(12, 74, 110, 0.1), 0 10px 10px -5px rgba(12, 74, 110, 0.04)',
        'safe-glow': '0 4px 14px 0 rgba(5, 150, 105, 0.35)',
      }
    }
  }
}
```

---

## 3. Tipografía y Jerarquía Visual

- **Titulares y H1/H2:** `Montserrat` (pesos 700 y 800) para transmitir seriedad, solidez y elegancia comercial.
- **Cuerpo, Filtros y Tablas:** `Inter` (pesos 400, 500 y 600) para garantizar lectura ágil y nítida en pantallas móviles.

```html
<!-- Enlace de fuentes en client/index.html -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
```

---

## 4. El Sello Oficial "Verificado TS" (Componente Premium)

El sello no es un texto ordinario; es el **emblema de valor de la plataforma**:

### 4.1 Anatomía y Micro-Interacciones:
* **Escudo Vectorial:** SVG optimizado con check central.
* **Gradiente de Confianza:** `bg-gradient-to-r from-emerald-600 to-teal-700`.
* **Halo de Resplandor:** `shadow-safe-glow` con anillo translúcido `ring-1 ring-white/40`.
* **Punto de Estado Activo:** Indicador pulsante (`animate-ping`) que comunica verificación viva y vigente.

### 4.2 Código Vue 3 (`client/src/components/common/BadgeVerified.vue`)

```vue
<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    size?: 'sm' | 'md' | 'lg'
    withTooltip?: boolean
    verifiedAt?: string
    variant?: 'pill' | 'card'
  }>(),
  {
    size: 'md',
    withTooltip: true,
    variant: 'pill',
  }
)

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-2 py-0.5 text-xs gap-1'
    case 'lg':
      return 'px-4 py-2 text-sm sm:text-base gap-2'
    default:
      return 'px-3 py-1 text-xs sm:text-sm gap-1.5'
  }
})
</script>

<template>
  <div
    :class="[
      'inline-flex items-center font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-700 rounded-full shadow-safe-glow ring-1 ring-white/40 tracking-wide select-none backdrop-blur-sm transition-all duration-300 hover:scale-105',
      sizeClasses,
    ]"
    :title="withTooltip ? 'Propiedad inspeccionada físicamente in situ por el equipo de Turismo Seguro' : undefined"
  >
    <!-- Pulso indicador de vigencia -->
    <span class="relative flex h-2 w-2">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
      <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
    </span>

    <!-- Icono de escudo verificado -->
    <svg class="w-4 h-4 text-emerald-100 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zm3.707 6.763a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
        clip-rule="evenodd"
      />
    </svg>
    <span>Verificado TS</span>
  </div>
</template>
```

---

## 5. Arquitectura de Pantallas y Componentes Principales

### 5.1 Hero Section y Experiencia de Entrada (`HomeView.vue`)
- **Fotografía Editorial Inmersiva:** Vista panorámica de Calingasta / Dique de Ullum con máscara gradiente oscura profunda (`from-slate-950/85 via-slate-900/60 to-transparent`).
- **Buscador Flotante con Glassmorphism:**
  - Contenedor elevado `bg-white/95 backdrop-blur-xl rounded-2xl shadow-2xl p-4 sm:p-6 border border-white/20`.
  - Tres pasos intuitivos: ¿A qué departamento vas? | Capacidad de personas | Botón de Búsqueda Seguro.

### 5.2 Tarjetas de Alquiler (`PropertyCard.vue`) — Nivel Hospitalidad Premium
- **Efecto Hover 3D Sutil:** Elevación con `hover:-translate-y-1.5 hover:shadow-card-hover transition-all duration-300 ease-out`.
- **Galería Integrada con Indicador de Puntos:** Permite previsualizar fotos deslizando con el dedo en móvil o botones discretos en escritorio.
- **Sello Flotante Protegido:** Posicionado en la esquina superior izquierda con fondo translúcido protector para garantizar contraste sobre cualquier foto.
- **Micro-datos Claros:** Fila con iconos SVG minimalistas de capacidad, dormitorios y baños.
- **Precio Jerarquizado:** Precio en tamaño grande con etiqueta de periodicidad ("por día" / "por noche").

### 5.3 Tarjetas de Experiencias Turísticas (`ExperienceCard.vue`)
- Énfasis en imágenes paisajísticas de alto impacto (Parque Ischigualasto, Astroturismo Barreal, Dique Cuesta del Viento).
- Tag de duración y categoría (ej. "Aventura", "Enoturismo", "Astronomía").
- Identificación visible del operador turístico verificado con enlace a su contacto legal.

### 5.4 Ficha de Detalle de Alojamiento (`PropertyDetailView.vue`)
- **Mosaico Fotográfico Editorial:** Estilo galería de 5 fotos (1 principal panorámica + 4 secundarias) con botón flotante "Ver las X fotos de la inspección".
- **Garantía Visual de Auditoría:** Bloque destacado que certifica:
  - Fecha de la última inspección física *in situ*.
  - Número de acta de verificación.
  - Fotografía de fachada certificada.
- **Panel de Contacto Fijo (Sticky Booking Bar):**
  - Botón de **WhatsApp Directo Verificado:** Color esmeralda vibrante con texto preformateado contra fraudes.
  - Botón de llamada directa al anfitrión verificado.
  - Aviso de seguridad: *"Turismo Seguro certifica la existencia física del inmueble y los datos del anfitrión. Las transferencias se acuerdan directamente sin comisiones intermedias"*.

---

## 6. Panel Móvil del Validador Técnico en Terreno (Mobile-First)

Diseñado con criterios ergonómicos para trabajo bajo la luz solar en recorridos de campo:

1. **Objetivos Táctiles Amplios:** Botones y selectores con altura mínima de `52px` para toque seguro con una sola mano.
2. **Carga Inmediata desde la Cámara:** Botón flotante accesible con icono de cámara que invoca la API nativa de captura del teléfono (`<input type="file" accept="image/*" capture="environment">`).
3. **Checklist In Situ Interactivo:**
   - [ ] Verificación de titularidad y DNI del anfitrión.
   - [ ] Inspección de seguridad de piscina (rejas perimetrales, bomba, limpieza).
   - [ ] Control de servicios básicos (presión de agua, suministro eléctrico, gas).
   - [ ] Registro de coordenadas GPS de la propiedad.
4. **Modo Alto Contraste:** Textos con alto índice de luminancia para asegurar visibilidad al aire libre bajo el sol sanjuanino.

---

## 7. Transiciones, Animaciones y Rendimiento

1. **Transiciones de Rutas en Vue 3:**
   - Envolvimiento de vistas con `<Transition name="page" mode="out-in">` para transiciones suaves de opacidad (`fade 200ms`).
2. **Skeleton Loading:**
   - Durante la petición a la API de Laravel, las tarjetas muestran estructuras animadas con shimmer (`animate-pulse bg-surface-200`) para evitar saltos de layout (CLS = 0).
3. **Respeto a Preferencias del Usuario:**
   - Directiva CSS `@media (prefers-reduced-motion: reduce)` para desactivar transiciones complejas en dispositivos que lo requieran.
