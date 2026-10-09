---
name: visual
description: Gobernanza visual, tokens de identidad, paleta semántica y reglas de diseño de dominio para Turismo Seguro San Juan. Activa cuando se diseñen o modifiquen interfaces, componentes Vue, temas de Tailwind CSS, vistas públicas o paneles de validación.
license: Proprietary
metadata:
  project: "Turismo Seguro San Juan"
  version: "1.0.0"
  role: "Dominio Visual Canónico"
---

# 🎨 Skill Visual: Gobernanza Canónica de Turismo Seguro San Juan

> **Principio de Simbiosis y Autoridad:**
> Esta skill define **QUÉ** es el sistema visual: la identidad de marca de San Juan, los tokens cromáticos inmutables, la semántica funcional de negocio y la filosofía editorial de confianza hospitalaria.
> Cualquier motor o skill de refinamiento visual (como `ui-ux-pro-max`) actúa **subordinado** a estas reglas: puede elevar contrastes, micro-interacciones y ritmo espacial, pero **tiene estrictamente prohibido alterar la paleta o semántica aquí establecida**.

---

## 1. Dirección de Arte: "Trust & Adventure Editorial"

- **Estilo Rector:** Hospitalidad Editorial Moderna (*Boutique Stays / Airbnb Plus*) fusionada con un sello de certificación técnica rigurosa (*Auditoría Oficial in situ*).
- **Sensación Psicológica:**
  - *Para el Turista:* Tranquilidad inmediata contra fraudes y estafas en reservas vacacionales ("aquí no me van a estafar").
  - *Para el Propietario:* Prestigio, distinción y respaldo de calidad para su cabaña o servicio.
  - *Entorno:* Valles cordilleranos, cielo diáfano sanjuanino, sol cuyano y calidez hospitalaria.
- **Profundidad y Texturas:**
  - Contenedores elevados con sombras difusas naturales (`shadow-md`, `shadow-xl`).
  - Capas de vidrio esmerilado (*glassmorphism*) con `backdrop-blur-xl` y bordes translúcidos `border-white/20`.
- **Reglas Anti-Patrones Estrictas:**
  - ❌ **Prohibido el uso de emojis como iconos:** Utilizar siempre SVGs vectorizados limpios (Lucide Icons o Heroicons).
  - ❌ **Prohibidas tarjetas planas o contenedores rígidos tipo Bootstrap:** Nada de bordes grises duros sin elevación o sin estados hover.
  - ❌ **Prohibido el abarrotamiento de información:** Respetar espacios generosos (`space-y-6`, `gap-8`, padding amplio).
  - ❌ **Prohibido ocultar o simplificar el sello "Verificado TS":** Es el núcleo de valor diferencial del producto.

### 1.1 Directriz de Velocidad: Fidelidad Estructural con Legacy
- **Preservación de Vistas:** Mantener la estructura funcional, layout y componentes ya resueltos en `legacy/` (`index.html`, `alquileres.html`, `detalle-alquiler.html`, `experiencias-listado.html`, `detalle-experiencia.html`).
- **Rol de UI/UX Pro:** Aplicar buenas prácticas sobre la maqueta existente (reemplazo de Bootstrap por clases semánticas de Tailwind, elevación natural de sombras, espaciado uniforme y micro-interacciones) sin redefinir la arquitectura de pantallas. Esto garantiza velocidad máxima de traslación a componentes Vue 3.

---

## 2. Paleta Cromática y Tokens Semánticos

Toda la aplicación se rige por estos tokens de Tailwind CSS:

```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        // 🏔️ Azul Cordillera & Cielo Sanjuanino (Confianza Institucional / Primario)
        brand: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          500: '#0ea5e9',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e', // Color corporativo primordial y encabezados
          950: '#082f49',
        },

        // 🛡️ Verde Esmeralda Certificado (El Sello Verificado TS / Inspección aprobada)
        safe: {
          50: '#ecfdf5',
          100: '#d1fae5',
          500: '#10b981',
          600: '#059669', // Identificador de inspección in situ aprobada
          700: '#047857',
        },

        // ☀️ Naranja Sol Cuyano & Acento (Llamados a la Acción, Aventura y Conversión)
        accent: {
          50: '#fff7ed',
          100: '#ffedd5',
          500: '#f97316',
          600: '#ea580c', // Botones CTA, reservas y contacto clave
          700: '#c2410c',
        },

        // 🏛️ Superficies, Fondos y Contraste Neutro
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
      boxShadow: {
        'card-hover': '0 20px 25px -5px rgba(12, 74, 110, 0.1), 0 10px 10px -5px rgba(12, 74, 110, 0.04)',
        'safe-glow': '0 4px 14px 0 rgba(5, 150, 105, 0.35)',
      }
    }
  }
}
```

### Semántica de Uso de Colores:
1. **`brand` (Azul):** Navegación, títulos, badges informativos, enlaces secundarios y estructura.
2. **`safe` (Verde Esmeralda):** **Exclusivo para certificación, seguridad y auditoría in situ.** Nunca utilizarlo para acciones genéricas no relacionadas a validación técnica o contacto seguro.
3. **`accent` (Naranja):** Botones de llamada a la acción ("Buscar Alojamientos", "Contactar Anfitrión", "Reservar"), estados destacados de aventura y promociones verificadas.
4. **`surface` (Slate):** Fondos de página (`bg-surface-50`), tarjetas (`bg-white` o `bg-surface-100`), bordes sutiles y textos de lectura (`text-surface-700`).

---

## 3. Tipografía y Estabilidad Numérica

Sistema de fuentes dual estructurado:
- **`Montserrat` (`font-display`):** Para titulares principales (`h1`, `h2`, `h3`) y el logo institucional. Confiere carácter, solidez, elegancia y prestigio comercial.
- **`Inter` (`font-sans`):** Para textos explicativos, descripciones, formularios, filtros y tablas. Máxima legibilidad en pantallas móviles y desktop.
- **Monoespaciada con `tabular-nums` (`font-mono`):** Obligatorio en precios dinámicos (`$120.000 / noche`), números de acta, coordenadas GPS y contadores para prevenir saltos de maquetación (*layout shift*).

```html
<!-- Fuentes recomendadas en index.html -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
```

---

## 4. El Sello Oficial "Verificado TS" (Componente Canónico)

El sello no es un badge ordinario de texto; es el estandarte que justifica la existencia del producto.

### Especificación del Componente (`BadgeVerified.vue`):
- **Gradiente:** `bg-gradient-to-r from-emerald-600 to-teal-700` con anillo translúcido `ring-1 ring-white/40`.
- **Efecto Glow:** `shadow-safe-glow`.
- **Pulso Activo:** Punto pulsante con `animate-ping` que indica vigencia de la auditoría.
- **Icono:** Escudo con check vectorizado SVG.
- **Micro-interacción:** `hover:scale-105 transition-all duration-300`.

---

## 5. Arquitectura de Interfaces Principales

### 5.1 Portal Público Turístico
- **Hero Section:** Fotografía panorámica sanjuanina de alta resolución (Calingasta, Dique de Ullum, Valle Fértil) con gradiente oscuro inmersivo (`from-slate-950/85 via-slate-900/60 to-transparent`).
- **Buscador Flotante (Glassmorphism):** Caja de búsqueda elevada flotando sobre el hero (`backdrop-blur-xl rounded-2xl shadow-2xl p-4 sm:p-6 border border-white/20`).
- **Tarjetas de Alojamiento (`PropertyCard.vue`):**
  - Elevación suave en reposo y hover 3D sutil: `hover:-translate-y-1.5 hover:shadow-card-hover transition-all duration-300`.
  - Galería deslizable de fotos con micro-dots indicadores.
  - Sello "Verificado TS" posicionado de forma flotante con fondo protector contra cualquier fotografía.
  - Micro-datos limpios: capacidad, dormitorios, baños y precio visible con periodicidad clara.
- **Ficha de Detalle (`PropertyDetailView.vue`):**
  - Mosaico fotográfico editorial estilo 5 fotos.
  - Ficha de Certificación Técnica visible: fecha de última inspección física in situ, acta de verificación y foto de fachada autenticada.
  - Barra fija de reserva / contacto directo (*Sticky Booking Bar*) con botón a WhatsApp Verificado sin comisiones intermedias.

### 5.2 Panel Móvil del Validador Técnico en Terreno (Mobile-First)
- **Objetivos Táctiles Ergonómicos:** Botones con altura mínima de `52px` operables con una sola mano.
- **Cámara Nativa Integrada:** Acceso rápido con `<input type="file" accept="image/*" capture="environment">`.
- **Checklist In Situ:** DNI/titularidad, control de piscina/rejas, presión de agua, gas, luz y coordenadas GPS automáticas.
- **Alto Contraste al Sol:** Luminancia alta para trabajo de campo bajo sol radiante.

---

## 6. Micro-Bordes, Transiciones y Accesibilidad (WCAG AA)

1. **Micro-Bordes Semitransparentes:** En lugar de bordes grises opacos, utilizar `border-slate-200/80` (en modo claro) o `border-white/10` sobre fondos oscuros.
2. **Transiciones Suaves:** Clases `transition-all duration-200 ease-out`. Efecto click con feedback `active:scale-95`.
3. **Puntero Explícito:** Todo elemento clickeable debe declarar `cursor-pointer`.
4. **Estados de Carga (Skeleton Shimmer):** Durante la carga de datos desde Laravel, renderizar placeholders con `animate-pulse bg-surface-200 rounded-lg` para garantizar Cumulative Layout Shift (CLS) = 0.
5. **Accesibilidad:** Relación de contraste mínima 4.5:1 en textos, `role="alert"` en mensajes de validación y anillos de foco visibles (`focus-visible:ring-2 focus-visible:ring-brand-500`).
