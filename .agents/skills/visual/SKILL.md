---
name: visual
description: Gobernanza visual canónica oficial de Turismo Seguro San Juan basada en el Design System Stitch 'San Juan Verificado' (Deep Navy, Emerald Assurance, San Juan Amber, Plus Jakarta Sans). Activa siempre al diseñar, modificar o refactorizar vistas Vue 3, temas de Tailwind CSS y componentes del sistema.
license: Proprietary
metadata:
  project: "Turismo Seguro San Juan"
  version: "2.0.0 - Stitch San Juan Verificado"
  role: "Autoridad Suprema de Diseño Visual"
---

# 🎨 Skill Visual: Gobernanza Canónica Oficial "San Juan Verificado" (Stitch 2.0)

> **PRINCIPIO DE AUTORIDAD ABSOLUTA:**
> Esta skill es la **ÚNICA fuente de verdad** para el diseño visual, tokens, paletas, tipografía, layouts y componentes de **Turismo Seguro San Juan**.
> Se basa directamente en la entrega maestra de **Stitch** (`stitch_portal_alquileres_temporarios_vue/san_juan_verificado/DESIGN.md`).
> Ninguna otra skill (incluida `ui-ux-pro-max`, la cual queda en modo asesor subordinado bajo demanda) puede modificar, sugerir colores alternativos o contradecir las especificaciones aquí plasmadas.

---

## 1. Filosofía de Marca & Dirección de Arte

El sistema expresa **seguridad institucional, hospitalidad bajo el sol cuyano y autenticidad comprobable in situ**.
Su propósito primordial es **eliminar el fraude en alquileres temporarios y de día (quintas, quinchos, casas de campo)** en los 19 departamentos de San Juan:

1. **Foundational Trust (Confianza Institucional):** Azul Marino Mineral Profundo (`#0F2942` / `#001428`) para representar respaldo técnico, auditorías presenciales y peso arquitectónico.
2. **Emerald Assurance (Garantía Esmeralda):** Verde Esmeralda Seguro (`#059669` / `#006C4A`) para el sello "Verificado In Situ", piletas cercadas, estados aprobados y botón de reserva directa por WhatsApp.
3. **Sunlit Vibrancy (Sol Sanjuanino):** Ámbar Cálido (`#F59E0B` / `#FFB95F` / `#3B2200`) para turnos diurnos (Día Completo / Sol), destacados promocionales y avisos de lanzamiento.
4. **Canvas Limpio & Táctil:** Superficies en blanco puro (`#FFFFFF`) y fondos suaves porcelana (`#F8F9FF` / `#F8FAFC`) con bordes sutiles (`#E2E8F0` / `#C3C6CE`), evitando sombras pesadas artificiales.

---

## 2. Tokens Oficiales de Tailwind CSS

```javascript
// tailwind.config.js - San Juan Verificado
module.exports = {
  theme: {
    extend: {
      colors: {
        // 🏛️ Primario: Deep Navy (Autoridad y Navegación Principal)
        primary: {
          DEFAULT: '#001428',
          container: '#0f2942',
          'on-container': '#7991af',
          fixed: '#d1e4ff',
          'fixed-dim': '#b0c9e8',
        },
        'on-primary': '#ffffff',

        // 🛡️ Secundario: Emerald Assurance (Sello In Situ & WhatsApp Verificado)
        secondary: {
          DEFAULT: '#006c4a',
          container: '#82f5c1',
          'on-container': '#00714e',
          fixed: '#85f8c4',
          'fixed-dim': '#68dba9',
        },
        'on-secondary': '#ffffff',

        // ☀️ Terciario: San Juan Amber (Sol Cuyano, Turno Diurno, Promos)
        tertiary: {
          DEFAULT: '#1f1000',
          container: '#3b2200',
          'on-container': '#c77f00',
          fixed: '#ffddb8',
          'fixed-dim': '#ffb95f',
        },
        'on-tertiary': '#ffffff',

        // 🏛️ Canvas, Fondos y Superficies
        background: '#f8f9ff',
        'on-background': '#0b1c30',
        surface: {
          DEFAULT: '#f8f9ff',
          dim: '#cbdbf5',
          bright: '#f8f9ff',
          container: {
            lowest: '#ffffff',
            low: '#eff4ff',
            DEFAULT: '#e5eeff',
            high: '#dce9ff',
            highest: '#d3e4fe',
          },
          variant: '#d3e4fe',
          'on-variant': '#43474d',
        },
        'on-surface': '#0b1c30',

        // Bordes y Delineados
        outline: {
          DEFAULT: '#74777e',
          variant: '#c3c6ce',
        },

        // Error / Peligro
        error: {
          DEFAULT: '#ba1a1a',
          container: '#ffdad6',
          'on-container': '#93000a',
        },
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        display: ['"Plus Jakarta Sans"', 'sans-serif'],
      },
      borderRadius: {
        DEFAULT: '0.25rem', // 4px
        md: '0.5rem',       // 8px (estándar controles e inputs)
        lg: '0.75rem',      // 12px
        xl: '1rem',         // 16px (tarjetas y galerías)
        '2xl': '1.5rem',    // 24px (modales y contenedores)
        full: '9999px',     // Pills e insignias
      },
      boxShadow: {
        card: '0 1px 3px 0 rgba(15, 41, 66, 0.04), 0 1px 2px -1px rgba(15, 41, 66, 0.02)',
        'card-hover': '0 10px 15px -3px rgba(15, 41, 66, 0.08), 0 4px 6px -4px rgba(15, 41, 66, 0.04)',
        modal: '0 20px 25px -5px rgba(15, 41, 66, 0.12), 0 8px 10px -6px rgba(15, 41, 66, 0.06)',
      },
    },
  },
}
```

---

## 3. Tipografía Oficial: Plus Jakarta Sans

Se utiliza **exclusivamente Plus Jakarta Sans** en toda la interfaz (Google Fonts):
- **Titulares:** ExtraBold (`800`) y Bold (`700`) con kerning ajustado (`tracking-tight` / `-0.02em`).
- **Cuerpo:** Regular (`400`) y Medium (`500`) con altura de línea generosa (`leading-relaxed` / `1.5` a `1.55`).
- **Etiquetas y Micro-copy:** SemiBold (`600`) y Bold (`700`) con tracking positivo para metadatos, insignias y estados.

---

## 4. Componentes Clave de Dominio

### 1. Sello In Situ ("Verificado In Situ")
- **Visual:** Píldora con icono de escudo check, texto en mayúsculas `"VERIFICADA IN SITU"` y fecha de inspección `(Inspección: [Mes/Año])`.
- **Fondo:** `bg-secondary-fixed/40` o `bg-secondary/10`.
- **Borde:** `border border-secondary/30`.
- **Texto:** `text-on-secondary-fixed font-bold text-xs`.

### 2. Selector de Turno Diurno (Shift Segment)
- **Turno Día Completo:** `10:00 a 20:00 hs` (Icono sol `wb_sunny`, acento ámbar).
- **Turno Tarde / Noche:** `18:00 a 02:00 hs` (Icono atardecer/luna, acento azul profundo).

### 3. Calculador Dinámico de Invitados (Quotation Stepper)
- Controles `+` y `-` para volumen de personas (20, 40, 60+ asistentes).
- Feedback dinámico de tarifa por tramo y costo por persona.
- **CTA Primario:** Botón verde esmeralda `#006C4A` con icono oficial de WhatsApp: `"Reservar vía WhatsApp Verificado"`.

### 4. Chips de Servicios Diurnos
- `Pileta cercada` (con check esmeralda especial para seguridad infantil).
- `Parrilla techada`, `Freezer bebidas`, `Parque arbolado`, `Vajilla completa`, `Sonido moderado`.

### 5. Banner de Lanzamiento San Juan
- Estilo `bg-gradient-to-r from-tertiary-container via-primary-container to-primary`.
- Promoción: "Primeras 50 quintas: 6 meses de suscripción 100% gratis con inspección in situ sin costo".

---

## 5. Reglas Estrictas Anti-Patrones

- ❌ **NO usar emojis como iconos:** Usar Google Material Symbols Outlined o Lucide SVG icons.
- ❌ **NO usar azul genérico brillante ni Bootstrap:** Los colores deben corresponder exactamente a los tokens `primary` (`#001428` / `#0F2942`) y `secondary` (`#006C4A` / `#059669`).
- ❌ **NO rediseñar pantallas inventando estilos:** Toda pantalla debe replicar la jerarquía visual de los prototipos Stitch de la carpeta `stitch_portal_alquileres_temporarios_vue/`.
