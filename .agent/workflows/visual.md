# Guía de Estilo y Visual: Turismo Seguro San Juan

## 1. Identidad Visual y Concepto de Diseño
La interfaz de **Turismo Seguro San Juan (TS)** debe transmitir tres pilares fundamentales: **Confianza (Seguridad)**, **Frescura Local (San Juan)** y **Modernidad (Premium)**.

- **Confianza**: Se traduce en colores limpios, amplios espacios en blanco, bordes redondeados suaves, iconografía clara y el protagonismo absoluto del sello "Verificado TS".
- **Frescura Local**: Evocar la geografía de San Juan (el sol radiante, el verde de los valles como Zonda y Calingasta, y las montañas de la Cordillera).
- **Premium**: Abandonar la estética genérica de Bootstrap 5 para adoptar un diseño moderno con Tailwind CSS que utilice sutiles sombras, efectos de vidrio (glassmorphism), tipografías estilizadas y micro-interacciones suaves.

---

## 2. Paleta de Colores (Configuración de Tailwind CSS)
La paleta se configurará en el archivo `tailwind.config.js` para asegurar consistencia a lo largo de toda la plataforma:

| Propósito | Nombre Tailwind | Color HEX | Representación Visual / Uso |
| :--- | :--- | :--- | :--- |
| **Principal / Confianza** | `indigo-primary` | `#1e3a8a` | Azul Marino Profundo. Navbar, títulos principales, botones primarios. |
| **Seguridad / Éxito** | `emerald-safe` | `#059669` | Verde Esmeralda. Insignias de verificación, botones de WhatsApp. |
| **Acento / Sol Sanjuanino** | `amber-accent` | `#d97706` | Ámbar / Naranja Cálido. Precios, elementos destacados, puntuaciones. |
| **Fondo Claro** | `slate-bg` | `#f8fafc` | Gris Pizarra Muy Claro (`slate-50`). Fondo general de la plataforma. |
| **Fondo Tarjetas / Paneles**| `white` | `#ffffff` | Blanco Puro. Tarjetas, contenedores, barras de navegación. |
| **Textos Primarios** | `slate-text-dark` | `#0f172a` | Slate Oscuro (`slate-900`). Texto de títulos y párrafos importantes. |
| **Textos Secundarios** | `slate-text-light`| `#475569` | Slate Medio (`slate-600`). Subtítulos, descripciones secundarias, detalles. |

---

## 3. Tipografía
Se utilizará una combinación de dos familias tipográficas desde Google Fonts para optimizar la jerarquía y legibilidad:

- **Títulos (`font-display`)**: **Montserrat** o **Outfit**
  - Estilo: Sans-serif geométrica, moderna y con gran personalidad.
  - Peso: `font-bold` (700) para encabezados principales y `font-semibold` (600) para títulos de tarjetas o secciones.
- **Cuerpo de Texto (`font-sans`)**: **Inter**
  - Estilo: Extremadamente legible en todo tipo de pantallas, neutral y limpia.
  - Peso: `font-normal` (400) para texto corrido y `font-medium` (500) para etiquetas y botones.

---

## 4. Reemplazo de Bootstrap 5 por Tailwind CSS
La versión legacy utiliza Bootstrap 5 mediante CDN. La migración a Tailwind CSS se estructurará de la siguiente manera:

| Elemento Legacy | Estilo Bootstrap 5 | Equivalente Tailwind CSS (Modernizado) |
| :--- | :--- | :--- |
| **Contenedor** | `.container` | `container mx-auto px-4 max-w-7xl` |
| **Grilla** | `.row` / `.col-md-4` | `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6` |
| **Navbar** | `.navbar .navbar-expand-lg` | `sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-100 flex items-center justify-between px-6 py-4` |
| **Tarjetas** | `.card .shadow-sm` | `bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden` |
| **Botón Primario** | `.btn .btn-primary` | `bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-xl transition-all shadow-sm shadow-indigo-100` |
| **Botón Contorno** | `.btn-outline-light` | `border border-white/40 bg-white/10 hover:bg-white/20 text-white font-medium px-5 py-2.5 rounded-xl backdrop-blur-sm transition-all` |

---

## 5. Diseño de Componentes Clave

### A. La Tarjeta de Alquiler / Experiencia (`rental-card`)
Las tarjetas estáticas de la versión legacy se rediseñan para dar una sensación tridimensional y de calidad premium:
- **Contenedor**: Bordes redondeados amplios (`rounded-2xl`).
- **Insignia "Verificado TS"**: En lugar de una cinta simple, se usará un badge flotante con fondo verde traslúcido, bordes redondeados y un sutil efecto de vidrio:
  ```html
  <span class="absolute top-4 left-4 bg-emerald-500/90 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-sm">
      <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">...</svg>
      Verificado TS
  </span>
  ```
- **Imagen**: Efecto zoom sutil al pasar el cursor sobre la tarjeta (`group-hover:scale-105 transition-transform duration-500`).
- **Precios**: Destacados en negrita y color ámbar/dorado para capturar la atención rápidamente.
- **Detalles Rápidos (Iconos)**: Separados por bloques en una cuadrícula interna limpia, reemplazando la lista clásica.

### B. Panel de Filtros Lateral
El panel de filtros en `alquileres.html` pasará de ser un sidebar Bootstrap gris a una tarjeta flotante elegante:
- Fondo: Blanco puro con una sombra suave (`bg-white p-6 rounded-2xl border border-slate-100`).
- Inputs & Selects: Bordes grises muy finos que cambian a azul marino al enfocarse, con sombras de transición:
  ```html
  <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all">
  ```

### C. Botones de Contacto Directo en Ficha de Detalle
El contacto directo es el corazón transaccional del MVP:
- **WhatsApp**: Botón verde esmeralda brillante con un degradado sutil (`bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white shadow-sm shadow-emerald-150`).
- **Llamar**: Botón slate/gris oscuro para dar un contraste elegante y orden jerárquico secundario (`bg-slate-800 hover:bg-slate-900 text-white`).

---

## 6. Micro-interacciones y Transiciones
Para que la aplicación se sienta "viva" y responda dinámicamente al usuario:
- **Efecto de Elevación**: Las tarjetas de alquileres y experiencias deben elevarse sutilmente al pasar el cursor sobre ellas (`hover:-translate-y-1 hover:shadow-lg transition-all duration-300`).
- **Hover de Enlaces del Menú**: Subrayados animados de izquierda a derecha usando pseudo-elementos o transiciones de color de texto suaves (`text-slate-600 hover:text-indigo-600 transition-colors`).
- **Feedback de Carga en Filtros**: Al aplicar filtros, la grilla de alquileres debe tener una transición de opacidad (`transition-opacity duration-200`) para simular la carga asíncrona de resultados de manera fluida.
