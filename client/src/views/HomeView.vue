<script setup lang="ts">
import { ref, defineAsyncComponent } from 'vue'
import { useRouter } from 'vue-router'
import FiltroDepartamentos from '../components/quintas/FiltroDepartamentos.vue'
import CardQuintaDiurna from '../components/quintas/CardQuintaDiurna.vue'
import CalculadoraAsistentes from '../components/quintas/CalculadoraAsistentes.vue'
import type { QuintaItem } from '../components/quintas/CardQuintaDiurna.vue'

// Carga asíncrona para Lighthouse (Code-Splitting optimizado del chunk inicial)
const ExperienciasCercanasHub = defineAsyncComponent(
  () => import('../components/quintas/ExperienciasCercanasHub.vue')
)

const router = useRouter()

// Estado del buscador extraído
const searchZone = ref('todos')
const searchOccasion = ref('quinta-pileta')
const searchCapacity = ref('20-40')
const searchShift = ref('dia-completo')

// Filtros rápidos
const activePills = ref<string[]>(['fence'])

// Quintas oficiales de Stitch
const quintas: QuintaItem[] = [
  {
    id: 1,
    title: 'Quinta Los Olivos',
    location: 'El Medanito, Rawson',
    price: 45000,
    priceFormatted: '$45.000',
    shift: 'día (10:00 a 20:00 hs)',
    capacity: 25,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuCFE8VNKwk6C-6laXIn64SOPW1ZYffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk',
    amenities: ['Pileta 8x4m cercada', 'Quincho cerrado 60m²', '2 Freezers grandes', 'Parrilla doble'],
    whatsappPhone: '5492644111222',
  },
  {
    id: 2,
    title: 'La Posada del Valle',
    location: 'Valle de Zonda',
    price: 55000,
    priceFormatted: '$55.000',
    shift: 'día (11:00 a 21:00 hs)',
    capacity: 30,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRpm0tRCyFxkTL09ItJSDkIOwkwNPf2S4StWzAOhZtrO9PX-w3GysmFiXgOZYKLIw4_baYSO4EJvtHq0OUXD_u-FCo0krxmFwI1dedCnKXMAdYwxUB8YfNuimCzZMg2WZkDj9GZ6elPAWN-bLYu7y2UPeGlnETL4y94IdPWDKEhD3EwPzDyVoFS7qHRL9wAYpkujJUcq9qtghigFGsuJxtjzNI8VCzOc6XadX6c1-u8JmT5MBco4HV',
    amenities: ['Pileta con solárium', 'Galería techada', 'Vistas a la sierra', 'Horno de barro'],
    whatsappPhone: '5492644222333',
  },
  {
    id: 3,
    title: 'Quincho & Parque Don Antonio',
    location: 'Pocito Sur (Calle 12)',
    price: 38000,
    priceFormatted: '$38.000',
    shift: 'día (10:00 a 20:00 hs)',
    capacity: 40,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDy9PLSFQrRBslTy_bvrA-1TCT9yqpH3AOaIgMErqcdnTNQIurXvbPz7rgzvuqQygWTMNM3lWQl1YdQxlP_uKXm1OEN5W99s8w2qShVbdnAQUHMTaBGvIVDuE7F1IoBk_Dse-4TnvUcYoCTEqytxmZA4zJ0l2d0E2d6cFrc6xaRLIQT_JKgzZuhQCKdbJFlKOjHdx7pe3M-3Myd19vWSDUrTna7yHad2MKpYNtg7DdvqkMl3Bt1DVxq',
    amenities: ['Gran arboleda con sombra', 'Piscina con cascada', 'Sector asador iluminado'],
    whatsappPhone: '5492644333444',
  },
  {
    id: 4,
    title: 'Rincón de la Sierra',
    location: 'Dique de Ullum',
    price: 65000,
    priceFormatted: '$65.000',
    shift: 'día (10:00 a 20:00 hs)',
    capacity: 20,
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAd2EiOfveL3i9TOsDt_6jA0bkXgBOha_sUJ_2PXl84NszF67_xG-0qf9N1k0ywlm8O1rvG0uARNxvVxBZWdJPGIYgvzMOn7YEjGUdSknqiEQbNYhkgnxvrVimvoylJD9JyRit-6mvV0f7IrJR2DPMtXtu568sf1ykKlTTf7OPG8a3b8Ma8OZZqNR3kbQY9dMAxs_CNhkwnDJVKPCjQmovW3_Fr4ZFEotLw6jrrPLDjYGcRMsRlyHEd',
    amenities: ['Deck panorámico sobre el agua', 'Bajada privada para kayaks', 'Parrillero móvil'],
    whatsappPhone: '5492644444555',
  },
]

const handleSearch = () => {
  router.push({
    path: '/explorar',
    query: {
      zone: searchZone.value !== 'todos' ? searchZone.value : undefined,
      occasion: searchOccasion.value,
      capacity: searchCapacity.value,
      shift: searchShift.value,
    },
  })
}
</script>

<template>
  <main class="w-full bg-background">
    <!-- Hero Section with Sun-Drenched Background Image & Scrim -->
    <section class="relative w-full overflow-hidden bg-primary-container text-on-primary">
      <div
        class="absolute inset-0 bg-cover bg-center mix-blend-overlay opacity-30"
        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB11JFOZvrQLcqebSEofwWj9PIt2LF7QfrCg6BljouhOUVMUMr_8DOvxhWBLHZ-qQwfwXH5hQKem79Q-ND3SJidfcrMpJeGUoVSkhidl7F75iE-O7V5iB0_GwLlNPxpYeE8ks1ccjb1Yywy3Ik2UIhxAc8K7MMRJJzGcc7wCzhN9v9vpLmYUzDdbVhHkDvm5I_0_XjADL8pZUG2j0c17qtXh-IyLyUSr-QBPaOxSfryqvwKVwfTvtj0')"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary-container/95 to-primary-container/75"></div>

      <div class="relative max-w-7xl mx-auto px-6 lg:px-12 py-space-xl lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
          <!-- Hero Text -->
          <div class="lg:col-span-7 flex flex-col items-start gap-space-md">
            <div class="inline-flex items-center gap-space-xs bg-surface-container-lowest/15 backdrop-blur-md px-space-md py-1.5 rounded-full text-secondary-fixed shadow-sm">
              <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">shield</span>
              <span class="font-label-md text-label-md uppercase tracking-wider">Especialistas en alquileres para pasar el día</span>
            </div>

            <h1 class="font-headline-xl text-headline-xl text-on-primary tracking-tight max-w-2xl">
              Alquilá tu quinta, cabaña o quincho para pasar el día sin riesgo de estafas en San Juan
            </h1>

            <p class="font-body-lg text-body-lg text-on-primary-container max-w-xl">
              Verificamos cada quinta, cabaña, quincho, pileta y anfitrión en persona antes de publicar. Mirá fotos reales, horarios de estadía y reservá con total tranquilidad.
            </p>

            <!-- Key Trust Signals / Contadores Comunitarios -->
            <div class="grid grid-cols-3 gap-space-md w-full max-w-lg pt-space-xs">
              <div class="bg-surface-container-lowest/10 backdrop-blur-sm p-space-sm rounded-xl flex flex-col">
                <span class="font-headline-md text-headline-md text-secondary-fixed font-bold">+120</span>
                <span class="font-label-sm text-label-sm text-on-primary-container">Ofertas validadas</span>
              </div>
              <div class="bg-surface-container-lowest/10 backdrop-blur-sm p-space-sm rounded-xl flex flex-col">
                <span class="font-headline-md text-headline-md text-tertiary-fixed font-bold">+3.400</span>
                <span class="font-label-sm text-label-sm text-on-primary-container">Usuarios registrados</span>
              </div>
              <div class="bg-surface-container-lowest/10 backdrop-blur-sm p-space-sm rounded-xl flex flex-col">
                <span class="font-headline-md text-headline-md text-surface-bright font-bold">+8.900</span>
                <span class="font-label-sm text-label-sm text-on-primary-container">Alquileres consultados</span>
              </div>
            </div>
          </div>

          <!-- Hero Feature Card: Inspección Activa -->
          <div class="lg:col-span-5 relative">
            <div class="bg-surface-container-lowest text-on-surface rounded-2xl p-space-lg shadow-xl relative overflow-hidden">
              <div class="flex items-center justify-between pb-space-sm">
                <div class="flex items-center gap-space-xs">
                  <span class="w-3 h-3 rounded-full bg-secondary animate-pulse"></span>
                  <span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">Auditoría Activa Hoy</span>
                </div>
                <span class="font-label-sm text-label-sm text-on-surface-variant font-semibold">Sello Oficial TS</span>
              </div>

              <div class="relative rounded-xl overflow-hidden mb-space-md">
                <img
                  class="w-full h-48 object-cover rounded-xl"
                  alt="Inspector técnico de Turismo Seguro San Juan"
                  src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-KblXpDFmkd1I3C_w8SVRktSYLOCOV0v2ot_dMeZI1aN59rCAd_z2Gss294DIeq115NWf8uaJmr3PP__p6F_Ij2el6w9qWKf2s_EAIqayziI5n4TXEDzkK1bHNr5x3g4Ft-SSNofAGZaWKZiU7wJXoJFNCgylz-mYxqOdOXjN3duA7Q83c1n2aGeIOaYRO0vE-Yq6m8_YALBTQeAYs6x2DTW95pyETYM7lzrhZY6AXwax7BTyfWd0"
                  loading="eager"
                  fetchpriority="high"
                  width="480"
                  height="192"
                />
                <div class="absolute bottom-3 left-3 bg-primary-container/90 backdrop-blur-md text-on-primary px-space-sm py-1 rounded-lg flex items-center gap-1.5 shadow-md">
                  <span class="material-symbols-outlined text-secondary-fixed text-[16px]">verified</span>
                  <span class="font-label-sm text-label-sm">Certificado de Seguridad Sanjuanino</span>
                </div>
              </div>

              <div class="space-y-space-xs">
                <div class="flex items-center justify-between font-label-md text-label-md">
                  <span class="text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    Estado de agua de pileta
                  </span>
                  <span class="text-secondary font-label-sm text-label-sm font-bold">Filtrado • Clorado</span>
                </div>
                <div class="flex items-center justify-between font-label-md text-label-md">
                  <span class="text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    Protección para niños
                  </span>
                  <span class="text-secondary font-label-sm text-label-sm font-bold">Cerca perimetral OK</span>
                </div>
                <div class="flex items-center justify-between font-label-md text-label-md">
                  <span class="text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    Freezer &amp; Heladeras
                  </span>
                  <span class="text-secondary font-label-sm text-label-sm font-bold">Operativas a 100%</span>
                </div>
              </div>

              <div class="mt-space-md p-space-sm bg-surface-container rounded-lg flex items-center gap-space-sm">
                <span class="material-symbols-outlined text-secondary text-[24px]">gpp_good</span>
                <p class="font-label-sm text-label-sm text-on-surface-variant leading-tight">
                  Garantía de devolución si la quinta, cabaña o predio no coincide con lo publicado.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Componente Extraído: Buscador / Filtro de Departamentos -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 w-full -mt-8 relative z-20">
      <FiltroDepartamentos
        v-model:zone="searchZone"
        v-model:occasion="searchOccasion"
        v-model:capacity="searchCapacity"
        v-model:shift="searchShift"
        v-model:pills="activePills"
        @search="handleSearch"
      />
    </section>

    <!-- Catálogo de Quintas Diurnas: Componente Extraído CardQuintaDiurna -->
    <section id="catalogo-quintas" class="max-w-7xl mx-auto px-6 lg:px-12 py-space-md w-full">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
        <CardQuintaDiurna
          v-for="quinta in quintas"
          :key="quinta.id"
          :quinta="quinta"
        />
      </div>
    </section>

    <!-- Calculador Estimativo por Persona (Extraído con accesibilidad completa) -->
    <CalculadoraAsistentes />

    <!-- Módulo de Ofertas de Eventos y Experiencias Turísticas Cercanas (Lazy Loaded) -->
    <ExperienciasCercanasHub />

    <!-- Value Proposition: ¿Por qué reservar a través de Turismo Seguro? -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 py-space-xl w-full">
      <div class="flex flex-col items-center text-center max-w-3xl mx-auto mb-space-xl">
        <div class="inline-flex items-center gap-1 bg-surface-container px-space-sm py-1 rounded-full text-primary mb-space-xs">
          <span class="material-symbols-outlined text-secondary text-[18px]">security</span>
          <span class="font-label-sm text-label-sm uppercase tracking-wider font-semibold">Sistema Anti-Estafas Sanjuanino</span>
        </div>
        <h2 class="font-headline-lg text-headline-lg text-primary tracking-tight">
          ¿Por qué reservar a través de Turismo Seguro?
        </h2>
        <p class="font-body-md text-body-md text-on-surface-variant mt-space-xs">
          En las redes abundan perfiles falsos que piden señas por quintas, cabañas y quinchos que no existen o están usurpados. Así blindamos cada reserva para vos y tu familia.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-md">
        <!-- 4 Pilares -->
        <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary mb-space-md">
              <span class="material-symbols-outlined text-[28px]">search_check</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-space-xs">Inspección Física en el Lugar</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
              Un validador sanjuanino visita cada quinta, cabaña o predio personalmente. Revisa el estado real del agua de pileta, baños, electrodomésticos y parrillero antes de autorizar la publicación.
            </p>
          </div>
          <div class="mt-space-md pt-space-xs text-secondary flex items-center gap-1 font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px]">done_all</span> Verificación in situ obligatoria
          </div>
        </div>

        <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary mb-space-md">
              <span class="material-symbols-outlined text-[28px]">handshake</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-space-xs">Trato Directo sin Intermediarios</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
              Hablás y transferís directamente al dueño verificado. No cobramos comisiones de reserva ni retenemos tu dinero; validamos al titular para que no caigas en perfiles truchos.
            </p>
          </div>
          <div class="mt-space-md pt-space-xs text-secondary flex items-center gap-1 font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px]">done_all</span> CUIT y CBU cotejados en mano
          </div>
        </div>

        <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary mb-space-md">
              <span class="material-symbols-outlined text-[28px]">price_check</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-space-xs">Precios y Reglas Transparentes</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
              El precio acordado y publicado es el que se respeta en WhatsApp. Horarios definidos de turno diurno, pautas claras de sonido y depósito en garantía sin sorpresas.
            </p>
          </div>
          <div class="mt-space-md pt-space-xs text-secondary flex items-center gap-1 font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px]">done_all</span> 0% comisiones a inquilinos
          </div>
        </div>

        <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary mb-space-md">
              <span class="material-symbols-outlined text-[28px]">support_agent</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-space-xs">Soporte y Asistencia Local</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
              Equipo 100% en San Juan disponible por WhatsApp ante dudas sobre la identidad de un anfitrión o para verificar un número de contacto antes de que transfieras la seña.
            </p>
          </div>
          <div class="mt-space-md pt-space-xs text-secondary flex items-center gap-1 font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px]">done_all</span> Validación directa por WhatsApp
          </div>
        </div>
      </div>
    </section>

    <!-- Sección de Propietarios / Anfitriones -->
    <section id="anfitriones" class="max-w-7xl mx-auto px-6 lg:px-12 py-space-xl w-full">
      <div class="bg-primary text-on-primary rounded-3xl p-space-lg lg:p-space-xl relative overflow-hidden shadow-2xl">
        <div class="absolute -right-16 -top-16 w-96 h-96 bg-secondary/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
          <div class="lg:col-span-8 flex flex-col gap-space-sm">
            <div class="inline-flex items-center gap-space-xs bg-surface-container-lowest/15 backdrop-blur-md px-space-md py-1 rounded-full text-secondary-fixed w-fit font-bold">
              <span class="material-symbols-outlined text-[16px]">real_estate_agent</span>
              <span class="font-label-sm text-label-sm uppercase tracking-wide">Modelo Transparente para Propietarios</span>
            </div>
            <h2 class="font-headline-lg text-headline-lg text-on-primary tracking-tight font-extrabold">
              ¿Sos dueño de una quinta, cabaña o quincho? Sumate con el Sello de Verificación TS
            </h2>
            <p class="font-body-lg text-body-lg text-on-primary-container max-w-2xl">
              Cobramos una suscripción mensual fija a propietarios para auditar presencialmente tu espacio y certificar tu cuenta. Sin comisiones abusivas por cada alquiler que hagas: tus clientes te pagan directamente a vos.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-space-md pt-space-sm">
              <div class="flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">task_alt</span>
                <span class="font-label-sm text-label-sm text-surface-bright font-semibold">Auditoría presencial y fotos certificadas</span>
              </div>
              <div class="flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">task_alt</span>
                <span class="font-label-sm text-label-sm text-surface-bright font-semibold">Los clientes te pagan 100% directo a tu WhatsApp y banco</span>
              </div>
              <div class="flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">task_alt</span>
                <span class="font-label-sm text-label-sm text-surface-bright font-semibold">Precios de suscripción accesibles para anfitriones</span>
              </div>
            </div>
          </div>

          <div class="lg:col-span-4 flex flex-col items-start lg:items-end justify-center">
            <div class="bg-surface-container-lowest/10 backdrop-blur-md p-space-lg rounded-2xl w-full flex flex-col gap-space-md text-center border border-white/10">
              <div class="inline-flex items-center justify-center gap-1 bg-secondary text-on-secondary px-space-sm py-1 rounded-full text-label-sm font-label-sm font-bold shadow-md">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                Promoción Especial Apertura
              </div>
              <div class="flex flex-col gap-1 text-center">
                <span class="font-headline-sm text-headline-sm text-surface-bright font-bold">
                  Primeras 50 ofertas: 6 meses 100% bonificados
                </span>
                <span class="font-label-sm text-label-sm text-secondary-fixed font-semibold">
                  Auditoría presencial in situ y sesión fotográfica incluida
                </span>
              </div>
              <div class="flex flex-col gap-space-xs w-full">
                <a
                  href="https://wa.me/5492644000000?text=Hola,%20deseo%20sumar%20mi%20quinta%20o%20caba%C3%B1a%20a%20Turismo%20Seguro"
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label="Contactar al validador por WhatsApp para sumar propiedad (se abre en nueva ventana)"
                  class="inline-flex items-center justify-center gap-space-xs py-3.5 px-space-md rounded-xl bg-secondary text-on-secondary font-headline-sm text-headline-sm hover:bg-on-secondary-container active:scale-[0.98] transition-all shadow-lg hover:shadow-xl font-bold cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary focus-visible:ring-offset-2"
                >
                  <span class="material-symbols-outlined text-[22px]">support_agent</span>
                  Contactar Validador
                </a>
              </div>
              <span class="font-label-sm text-label-sm text-on-primary-container text-center">
                Atención directa a propietarios: Calle Mitre 345 Este, San Juan
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
