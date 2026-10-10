/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        // Stitch 2.0 Official Tokens: San Juan Verificado
        primary: {
          DEFAULT: '#001428',
          container: '#0f2942',
          fixed: '#d1e4ff',
          'fixed-dim': '#b0c9e8',
        },
        'on-primary': {
          DEFAULT: '#ffffff',
          container: '#7991af',
          fixed: '#011d35',
          'fixed-variant': '#314863',
        },
        'inverse-primary': '#b0c9e8',

        // Emerald Assurance (Seguridad y Verificación In Situ)
        secondary: {
          DEFAULT: '#006c4a',
          container: '#82f5c1',
          fixed: '#85f8c4',
          'fixed-dim': '#68dba9',
        },
        'on-secondary': {
          DEFAULT: '#ffffff',
          container: '#00714e',
          fixed: '#002114',
          'fixed-variant': '#005137',
        },

        // San Juan Amber & Daylight
        tertiary: {
          DEFAULT: '#1f1000',
          container: '#3b2200',
          fixed: '#ffddb8',
          'fixed-dim': '#ffb95f',
        },
        'on-tertiary': {
          DEFAULT: '#ffffff',
          container: '#c77f00',
          fixed: '#2a1700',
          'fixed-variant': '#653e00',
        },

        // Canvas & Surfaces
        surface: {
          DEFAULT: '#f8f9ff',
          dim: '#cbdbf5',
          bright: '#f8f9ff',
          variant: '#d3e4fe',
          'container-lowest': '#ffffff',
          'container-low': '#eff4ff',
          container: '#e5eeff',
          'container-high': '#dce9ff',
          'container-highest': '#d3e4fe',
          // Compatibilidad numérica con componentes previos
          50: '#f8f9ff',
          100: '#eff4ff',
          200: '#e5eeff',
          300: '#cbd5e1',
          700: '#334155',
          800: '#1e293b',
          900: '#0b1c30',
        },
        'on-surface': {
          DEFAULT: '#0b1c30',
          variant: '#43474d',
        },
        'inverse-surface': '#213145',
        'inverse-on-surface': '#eaf1ff',

        outline: {
          DEFAULT: '#74777e',
          variant: '#c3c6ce',
        },
        'surface-tint': '#49607c',

        // Backward compatibility mappings
        brand: {
          50: '#eff4ff',
          100: '#d1e4ff',
          200: '#b0c9e8',
          500: '#0f2942',
          700: '#001428',
          800: '#001428',
          900: '#001428',
          950: '#000c19',
        },
        safe: {
          50: '#e6fbf2',
          100: '#82f5c1',
          500: '#006c4a',
          600: '#005a3d',
          700: '#004831',
        },
        accent: {
          50: '#fff8f0',
          100: '#ffddb8',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
        },
      },
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        display: ['"Plus Jakarta Sans"', 'sans-serif'],
        mono: ['"JetBrains Mono"', 'monospace'],
      },
      borderRadius: {
        sm: '0.25rem',
        DEFAULT: '0.5rem',
        md: '0.75rem',
        lg: '1rem',
        xl: '1.5rem',
        full: '9999px',
      },
      boxShadow: {
        'card-stitch': '0 1px 3px 0 rgba(15, 41, 66, 0.04), 0 1px 2px -1px rgba(15, 41, 66, 0.02)',
        'hover-stitch': '0 10px 15px -3px rgba(15, 41, 66, 0.08), 0 4px 6px -4px rgba(15, 41, 66, 0.04)',
        'modal-stitch': '0 20px 25px -5px rgba(15, 41, 66, 0.12), 0 8px 10px -6px rgba(15, 41, 66, 0.06)',
        'safe-glow': '0 4px 14px 0 rgba(0, 108, 74, 0.35)',
      },
    },
  },
  plugins: [],
}
