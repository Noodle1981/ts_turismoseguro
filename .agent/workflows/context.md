# Contexto del Proyecto: Turismo Seguro San Juan

## 1. Visión General
**Turismo Seguro San Juan (TS)** es una plataforma digital monolítica diseñada para conectar a turistas con propietarios de alojamientos temporales y proveedores de experiencias turísticas en la provincia de San Juan, Argentina. Su propuesta de valor principal es la **confianza y seguridad in situ**, garantizando que cada propiedad y servicio publicado esté físicamente verificado para erradicar las estafas virtuales de alquileres temporales.

---

## 2. El Problema a Resolver
En los últimos años, las estafas en alquileres temporarios han aumentado exponencialmente:
- **Alojamientos Fantasma**: Anuncios con fotos reales de propiedades que no existen o no pertenecen al anunciante.
- **Depósitos Perdidos**: Turistas que realizan pagos de reserva por adelantado y luego el "anfitrión" desaparece.
- **Falta de Soporte Local**: Plataformas internacionales no tienen presencia física ni validan la veracidad de lo publicado a nivel de calle en destinos específicos como San Juan.

---

## 3. La Solución
Turismo Seguro San Juan actúa como un sello de confianza:
1. **Verificación In Situ**: Un equipo de validadores locales visita las propiedades físicamente, verifica su existencia, servicios declarados (piscina, habitaciones, etc.) y la identidad del propietario.
2. **Publicación Certificada**: Las propiedades y experiencias aprobadas reciben el distintivo **"Verificado TS"**.
3. **Contacto Directo Protegido**: La plataforma facilita el contacto a través de WhatsApp o teléfono directo, eliminando intermediarios costosos pero brindando la seguridad de que el destino es legítimo.

---

## 4. Perfiles de Usuario
- **Turista**: Busca alquileres y experiencias en San Juan. Quiere la tranquilidad de que no será estafado.
- **Propietario / Anfitrión**: Publica su propiedad/experiencia. Solicita la validación para obtener el sello TS y destacar en el catálogo.
- **Validador (Rol Administrativo Interno)**: Personal en terreno que recibe solicitudes de validación, realiza las visitas, saca fotos reales de control y aprueba/rechaza listados en la plataforma.
- **Administrador**: Gestiona usuarios, valida solicitudes, actualiza departamentos y modera la plataforma.

---

## 5. Alcance del MVP (Mínimo Producto Viable)
- **Portal de Búsqueda y Filtrado**:
  - Buscador de alquileres por Departamento (Zonda, Calingasta, Rawson, etc.), precio, tipo, capacidad y comodidades.
  - Catálogo de Experiencias locales de turismo (excursiones, cabalgatas, catas de vino) verificadas.
- **Fichas de Detalle**:
  - Galería de fotos reales validadas, descripción, características técnicas, comodidades, reglas y ubicación aproximada.
  - Botones de contacto directo (WhatsApp autogenerado con mensaje preestablecido, llamada telefónica).
- **Flujo de Solicitud de Validación**:
  - Formulario sencillo para que un anfitrión envíe los datos de su propiedad y solicite una visita del validador.
- **Panel Administrativo Básico**:
  - Gestión de listados (alta, baja, modificación y estado de verificación).
  - Base de datos local (SQLite) para alojar dinámicamente lo que hoy en la versión legacy está hardcodeado en scripts JS.
