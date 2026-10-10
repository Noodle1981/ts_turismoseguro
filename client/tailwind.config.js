/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        // 🏔️ Azul Cordillera & Cielo Sanjuanino (Primario Institucional)
        brand: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          500: '#0ea5e9',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
          950: '#082f49',
        },
        // 🛡️ Verde Esmeralda Certificado (El Sello Verificado TS)
        safe: {
          50: '#ecfdf5',
          100: '#d1fae5',
          500: '#10b981',
          600: '#059669',
          700: '#047857',
        },
        // ☀️ Naranja Sol Cuyano & Acento (Llamados a la Acción y Reservas)
        accent: {
          50: '#fff7ed',
          100: '#ffedd5',
          500: '#f97316',
          600: '#ea580c',
          700: '#c2410c',
        },
        // 🏛️ Superficies y Fondos
        surface: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        },
      },
      fontFamily: {
        display: ['Montserrat', 'sans-serif'],
        sans: ['Inter', 'sans-serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      boxShadow: {
        'card-hover': '0 20px 25px -5px rgba(12, 74, 110, 0.1), 0 10px 10px -5px rgba(12, 74, 110, 0.04)',
        'safe-glow': '0 4px 14px 0 rgba(5, 150, 105, 0.35)',
      },
    },
  },
  plugins: [],
}
