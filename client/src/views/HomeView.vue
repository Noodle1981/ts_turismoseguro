<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useCatalog } from '../composables/useCatalog'
import type { Property, Department } from '../types/catalog'

const { getProperties, getDepartments } = useCatalog()

const properties = ref<Property[]>([])
const departments = ref<Department[]>([])

// Buscador Stitch
const searchZone = ref('todos')
const searchOccasion = ref('quinta-pileta')
const searchCapacity = ref('20-40')
const searchShift = ref('dia-completo')

// Calculador Estimativo Stitch
const guestCount = ref(25)
const baseDailyRate = 45000

const costPerPerson = computed(() => {
  return Math.round(baseDailyRate / guestCount.value)
})

const incrementGuests = () => {
  if (guestCount.value < 80) guestCount.value += 5
}

const decrementGuests = () => {
  if (guestCount.value > 10) guestCount.value -= 5
}

// Filtros rápidos activos
const activePills = ref<string[]>(['fence'])
const togglePill = (id: string) => {
  if (activePills.value.includes(id)) {
    activePills.value = activePills.value.filter(p => p !== id)
  } else {
    activePills.value.push(id)
  }
}

const resetPills = () => {
  activePills.value = []
}

onMounted(async () => {
  try {
    const [propsRes, deptsRes] = await Promise.all([
      getProperties({ page: 1 }),
      getDepartments(),
    ])
    properties.value = propsRes.data
    departments.value = deptsRes
  } catch (err) {
    console.error('Error al cargar datos:', err)
  }
})
</script>

<template>
  <main class="w-full bg-background">
    <!-- Hero Section with Sun-Drenched Background Image & Scrim -->
    <section class="relative w-full overflow-hidden bg-primary-container text-on-primary">
      <!-- Background Image with Scrim -->
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
              Alquilá tu quinta o quincho para pasar el día sin riesgo de estafas en San Juan
            </h1>

            <p class="font-body-lg text-body-lg text-on-primary-container max-w-xl">
              Verificamos cada pileta, quincho, servicios y anfitrión en persona antes de publicar. Mirá fotos reales, horarios de estadía y reservá con total tranquilidad.
            </p>

            <!-- Key Trust Signals -->
            <div class="grid grid-cols-3 gap-space-md w-full max-w-lg pt-space-xs">
              <div class="bg-surface-container-lowest/10 backdrop-blur-sm p-space-sm rounded-xl flex flex-col">
                <span class="font-headline-md text-headline-md text-secondary-fixed">100%</span>
                <span class="font-label-sm text-label-sm text-on-primary-container">Inspeccionadas in situ</span>
              </div>
              <div class="bg-surface-container-lowest/10 backdrop-blur-sm p-space-sm rounded-xl flex flex-col">
                <span class="font-headline-md text-headline-md text-tertiary-fixed">0</span>
                <span class="font-label-sm text-label-sm text-on-primary-container">Estafas registradas</span>
              </div>
              <div class="bg-surface-container-lowest/10 backdrop-blur-sm p-space-sm rounded-xl flex flex-col">
                <span class="font-headline-md text-headline-md text-surface-bright">7 Dptos.</span>
                <span class="font-label-sm text-label-sm text-on-primary-container">Cobertura provincial</span>
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
                  Garantía de devolución si la quinta no coincide con lo publicado.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Day-Rental Search Bar Component -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 w-full -mt-8 relative z-20">
      <div class="bg-surface-container-lowest rounded-2xl p-space-lg shadow-xl">
        <div class="flex items-center justify-between pb-space-md mb-space-md">
          <div class="flex items-center gap-space-xs">
            <span class="material-symbols-outlined text-secondary text-[24px]">wb_sunny</span>
            <span class="font-headline-sm text-headline-sm text-primary">Buscador para pasar el día en San Juan</span>
          </div>
          <span class="font-label-sm text-label-sm text-on-surface-variant bg-surface-container px-space-sm py-1 rounded-full hidden sm:inline-block font-semibold">
            Precios fijados en pesos sanjuaninos
          </span>
        </div>

        <!-- Search Fields Grid -->
        <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-md" @submit.prevent>
          <!-- Field 1: Zona / Departamento -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
              <span class="material-symbols-outlined text-secondary text-[16px]">pin_drop</span>
              Zona / Departamento
            </label>
            <div class="relative">
              <select
                v-model="searchZone"
                class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
              >
                <option value="todos">Todos los Departamentos</option>
                <option value="pocito">Pocito (Calle 11 a 14 / Quinto Cuartel)</option>
                <option value="rawson">Rawson / El Medanito</option>
                <option value="zonda">Zonda (Calle Las Moras / Valle)</option>
                <option value="rivadavia">Rivadavia / La Bebida</option>
                <option value="santa-lucia">Santa Lucía (Zona Este)</option>
                <option value="albardon">Albardón</option>
                <option value="ullum">Ullum (Dique y Bahía)</option>
              </select>
              <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
            </div>
          </div>

          <!-- Field 2: Tipo de Ocasión -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
              <span class="material-symbols-outlined text-secondary text-[16px]">celebration</span>
              Tipo de Ocasión
            </label>
            <div class="relative">
              <select
                v-model="searchOccasion"
                class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
              >
                <option value="quinta-pileta">Quinta con Pileta</option>
                <option value="quincho-asados">Quincho Familiar / Asados</option>
                <option value="cumpleanos-evento">Festejo de Cumpleaños / Evento diurno</option>
                <option value="cabana-descanso">Cabaña descanso de día</option>
              </select>
              <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
            </div>
          </div>

          <!-- Field 3: Capacidad Diurna -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
              <span class="material-symbols-outlined text-secondary text-[16px]">groups</span>
              Capacidad Diurna
            </label>
            <div class="relative">
              <select
                v-model="searchCapacity"
                class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
              >
                <option value="10-20">10 a 20 personas (Grupos chicos)</option>
                <option value="20-40">20 a 40 personas (Ideal cumpleaños)</option>
                <option value="40-60">40 a 60 personas (Familias grandes)</option>
                <option value="60-plus">+60 personas (Eventos y bautismos)</option>
              </select>
              <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
            </div>
          </div>

          <!-- Field 4: Horario / Turno -->
          <div class="flex flex-col gap-1.5">
            <label class="font-label-sm text-label-sm text-on-surface uppercase tracking-wider flex items-center gap-1 font-bold">
              <span class="material-symbols-outlined text-secondary text-[16px]">schedule</span>
              Horario / Turno
            </label>
            <div class="relative">
              <select
                v-model="searchShift"
                class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md rounded-lg py-3 px-space-md appearance-none focus:outline-none focus:bg-surface-container transition-colors cursor-pointer"
              >
                <option value="dia-completo">Día Completo (10:00 a 20:00 hs)</option>
                <option value="tarde-noche">Turno Tarde / Noche (14:00 a 02:00 hs)</option>
                <option value="finde-completo">Todo el fin de semana</option>
              </select>
              <span class="material-symbols-outlined absolute right-3 top-3 pointer-events-none text-on-surface-variant text-[20px]">expand_more</span>
            </div>
          </div>
        </form>

        <!-- Search Submit Row -->
        <div class="mt-space-md pt-space-sm flex flex-col sm:flex-row items-center justify-between gap-space-md">
          <div class="flex items-center gap-space-xs text-on-surface-variant">
            <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
            <span class="font-body-sm text-body-sm">48 quintas auditadas disponibles para el próximo fin de semana</span>
          </div>
          <RouterLink
            :to="{ path: '/alquileres', query: searchZone !== 'todos' ? { department: searchZone } : {} }"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-lg py-3.5 rounded-xl bg-secondary text-on-secondary font-label-lg text-label-lg hover:bg-on-secondary-container transition-all shadow-md hover:shadow-lg font-bold"
          >
            <span class="material-symbols-outlined text-[20px]">search</span>
            Buscar Quintas Verificadas
          </RouterLink>
        </div>
      </div>
    </section>

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
          En las redes abundan perfiles falsos que piden señas por quintas que no existen o están usurpadas. Así blindamos cada reserva para vos y tu familia.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-space-md">
        <!-- Tarjeta 1 -->
        <div class="bg-surface-container-lowest p-space-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary mb-space-md">
              <span class="material-symbols-outlined text-[28px]">search_check</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-space-xs">Inspección Física en el Lugar</h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
              Un validador sanjuanino visita el predio personalmente. Revisa el estado real del agua de pileta, baños, electrodomésticos y parrillero antes de autorizar la publicación.
            </p>
          </div>
          <div class="mt-space-md pt-space-xs text-secondary flex items-center gap-1 font-label-sm text-label-sm font-bold">
            <span class="material-symbols-outlined text-[16px]">done_all</span> Verificación in situ obligatoria
          </div>
        </div>

        <!-- Tarjeta 2 -->
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

        <!-- Tarjeta 3 -->
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

        <!-- Tarjeta 4 -->
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

    <!-- Interactive Quick-Filter Pills -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 w-full pt-space-sm pb-space-md">
      <div class="flex items-center justify-between mb-space-sm">
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-secondary text-[20px]">filter_list</span>
          <span class="font-headline-sm text-headline-sm text-primary font-bold">Filtros rápidos por comodidades</span>
        </div>
        <button
          type="button"
          class="font-label-sm text-label-sm text-secondary hover:underline cursor-pointer font-bold"
          @click="resetPills"
        >
          Restablecer filtros
        </button>
      </div>

      <!-- Pills Carousel -->
      <div class="flex items-center gap-space-sm overflow-x-auto pb-2 scrollbar-none">
        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-space-md py-2 rounded-full font-label-md text-label-md transition-all shadow-sm cursor-pointer',
            activePills.includes('fence') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container',
          ]"
          @click="togglePill('fence')"
        >
          <span class="material-symbols-outlined text-secondary text-[16px]">fence</span>
          Piscina con cerca de seguridad (niños)
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-space-md py-2 rounded-full font-label-md text-label-md transition-all shadow-sm cursor-pointer',
            activePills.includes('grill') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container',
          ]"
          @click="togglePill('grill')"
        >
          <span class="material-symbols-outlined text-secondary text-[16px]">outdoor_grill</span>
          Parrilla techada con mesada
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-space-md py-2 rounded-full font-label-md text-label-md transition-all shadow-sm cursor-pointer',
            activePills.includes('kitchen') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container',
          ]"
          @click="togglePill('kitchen')"
        >
          <span class="material-symbols-outlined text-secondary text-[16px]">kitchen</span>
          Freezer / Heladera amplia
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-space-md py-2 rounded-full font-label-md text-label-md transition-all shadow-sm cursor-pointer',
            activePills.includes('park') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container',
          ]"
          @click="togglePill('park')"
        >
          <span class="material-symbols-outlined text-secondary text-[16px]">park</span>
          Parque con sombra natural
        </button>

        <button
          type="button"
          :class="[
            'shrink-0 flex items-center gap-1.5 px-space-md py-2 rounded-full font-label-md text-label-md transition-all shadow-sm cursor-pointer',
            activePills.includes('car') ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface hover:bg-surface-container',
          ]"
          @click="togglePill('car')"
        >
          <span class="material-symbols-outlined text-secondary text-[16px]">directions_car</span>
          Cochera para +5 autos
        </button>
      </div>
    </section>

    <!-- Featured Day-Rental Property Grid (Direct Stitch Cards) -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 py-space-md w-full">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
        <!-- Quinta 1: Los Olivos -->
        <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="relative h-64 overflow-hidden">
              <img
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                alt="Quinta Los Olivos Rawson"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCFE8VNKwk6C-6laXIn64SOPW1ZYffatwwjHBr2RNecI7Gf49w0OyzheH8aK9DIobLdIn4W35_NN33g4fMHjlQ0QcL7uHBmf4uRFRViMuNahLbAe9SepGVFsLndtDM7a7mQ2gshqkerWImCWWnyzz1T5kCGwtJDYRmRM5pHS9i1AWfMh8i_IrGmOHfC__cUP2bFmIZnCi_rwLs_2280LksARH0G4JmNHrcIRoIzScDnaGiBEsiZZOmk"
              />
              <div class="absolute top-3 left-3 bg-surface-container-lowest/95 backdrop-blur-md px-space-sm py-1.5 rounded-full flex items-center gap-1.5 text-secondary shadow-md">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span class="font-label-sm text-label-sm font-semibold">Sello Verificado In Situ</span>
              </div>
              <div class="absolute top-3 right-3 bg-secondary text-on-secondary px-space-sm py-1 rounded-full text-label-sm font-label-sm shadow-md flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">bolt</span>
                <span>Trato directo • 0% comisión</span>
              </div>
              <div class="absolute bottom-3 left-3 bg-primary/80 backdrop-blur-md px-space-sm py-1 rounded-md text-on-primary font-label-sm text-label-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">location_on</span> El Medanito, Rawson
              </div>
            </div>

            <div class="p-space-lg">
              <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Quinta Los Olivos</h3>
                <div class="text-right">
                  <div class="font-headline-md text-headline-md text-secondary font-bold">$45.000</div>
                  <div class="font-label-sm text-label-sm text-on-surface-variant">/ día (10:00 a 20:00 hs)</div>
                </div>
              </div>
              <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
                <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                <span class="font-label-md text-label-md font-semibold text-on-surface">Capacidad: Hasta 25 personas (diurno)</span>
              </div>
              <div class="flex flex-wrap gap-1.5 mb-space-md">
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Pileta 8x4m cercada
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Quincho cerrado 60m²
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> 2 Freezers grandes
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Parrilla doble
                </span>
              </div>
            </div>
          </div>

          <div class="px-space-lg pb-space-lg pt-0 flex items-center gap-space-sm">
            <RouterLink
              to="/alquileres/quinta-los-olivos-rawson"
              class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">info</span> Ver Detalles y Reservar
            </RouterLink>
            <a
              href="https://wa.me/5492644111222?text=Hola,%20vi%20Quinta%20Los%20Olivos%20en%20Turismo%20Seguro"
              target="_blank"
              rel="noopener noreferrer"
              class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm"
              title="Abrir WhatsApp Directo con el Dueño"
            >
              <span class="material-symbols-outlined text-[20px]">chat</span>
            </a>
          </div>
        </article>

        <!-- Quinta 2: La Posada del Valle -->
        <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="relative h-64 overflow-hidden">
              <img
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                alt="La Posada del Valle Zonda"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDRpm0tRCyFxkTL09ItJSDkIOwkwNPf2S4StWzAOhZtrO9PX-w3GysmFiXgOZYKLIw4_baYSO4EJvtHq0OUXD_u-FCo0krxmFwI1dedCnKXMAdYwxUB8YfNuimCzZMg2WZkDj9GZ6elPAWN-bLYu7y2UPeGlnETL4y94IdPWDKEhD3EwPzDyVoFS7qHRL9wAYpkujJUcq9qtghigFGsuJxtjzNI8VCzOc6XadX6c1-u8JmT5MBco4HV"
              />
              <div class="absolute top-3 left-3 bg-surface-container-lowest/95 backdrop-blur-md px-space-sm py-1.5 rounded-full flex items-center gap-1.5 text-secondary shadow-md">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span class="font-label-sm text-label-sm font-semibold">Sello Verificado In Situ</span>
              </div>
              <div class="absolute top-3 right-3 bg-secondary text-on-secondary px-space-sm py-1 rounded-full text-label-sm font-label-sm shadow-md flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">bolt</span>
                <span>Trato directo • 0% comisión</span>
              </div>
              <div class="absolute bottom-3 left-3 bg-primary/80 backdrop-blur-md px-space-sm py-1 rounded-md text-on-primary font-label-sm text-label-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">location_on</span> Valle de Zonda
              </div>
            </div>

            <div class="p-space-lg">
              <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                <h3 class="font-headline-md text-headline-md text-primary font-bold">La Posada del Valle</h3>
                <div class="text-right">
                  <div class="font-headline-md text-headline-md text-secondary font-bold">$55.000</div>
                  <div class="font-label-sm text-label-sm text-on-surface-variant">/ día (11:00 a 21:00 hs)</div>
                </div>
              </div>
              <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
                <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                <span class="font-label-md text-label-md font-semibold text-on-surface">Capacidad: Hasta 30 personas</span>
              </div>
              <div class="flex flex-wrap gap-1.5 mb-space-md">
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Pileta con solárium
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Galería techada
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Vistas a la sierra
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Horno de barro
                </span>
              </div>
            </div>
          </div>

          <div class="px-space-lg pb-space-lg pt-0 flex items-center gap-space-sm">
            <RouterLink
              to="/alquileres"
              class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">info</span> Ver Detalles y Reservar
            </RouterLink>
            <a
              href="https://wa.me/5492644222333?text=Hola,%20vi%20La%20Posada%20del%20Valle%20en%20Turismo%20Seguro"
              target="_blank"
              rel="noopener noreferrer"
              class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm"
              title="Abrir WhatsApp Directo con el Dueño"
            >
              <span class="material-symbols-outlined text-[20px]">chat</span>
            </a>
          </div>
        </article>

        <!-- Quinta 3: Don Antonio Pocito -->
        <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="relative h-64 overflow-hidden">
              <img
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                alt="Quincho Don Antonio Pocito"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDy9PLSFQrRBslTy_bvrA-1TCT9yqpH3AOaIgMErqcdnTNQIurXvbPz7rgzvuqQygWTMNM3lWQl1YdQxlP_uKXm1OEN5W99s8w2qShVbdnAQUHMTaBGvIVDuE7F1IoBk_Dse-4TnvUcYoCTEqytxmZA4zJ0l2d0E2d6cFrc6xaRLIQT_JKgzZuhQCKdbJFlKOjHdx7pe3M-3Myd19vWSDUrTna7yHad2MKpYNtg7DdvqkMl3Bt1DVxq"
              />
              <div class="absolute top-3 left-3 bg-surface-container-lowest/95 backdrop-blur-md px-space-sm py-1.5 rounded-full flex items-center gap-1.5 text-secondary shadow-md">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span class="font-label-sm text-label-sm font-semibold">Sello Verificado In Situ</span>
              </div>
              <div class="absolute top-3 right-3 bg-secondary text-on-secondary px-space-sm py-1 rounded-full text-label-sm font-label-sm shadow-md flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">bolt</span>
                <span>Trato directo • 0% comisión</span>
              </div>
              <div class="absolute bottom-3 left-3 bg-primary/80 backdrop-blur-md px-space-sm py-1 rounded-md text-on-primary font-label-sm text-label-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">location_on</span> Pocito Sur (Calle 12)
              </div>
            </div>

            <div class="p-space-lg">
              <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Quincho &amp; Parque Don Antonio</h3>
                <div class="text-right">
                  <div class="font-headline-md text-headline-md text-secondary font-bold">$38.000</div>
                  <div class="font-label-sm text-label-sm text-on-surface-variant">/ día (10:00 a 20:00 hs)</div>
                </div>
              </div>
              <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
                <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                <span class="font-label-md text-label-md font-semibold text-on-surface">Capacidad: Hasta 40 personas</span>
              </div>
              <div class="flex flex-wrap gap-1.5 mb-space-md">
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Gran arboleda con sombra
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Piscina con cascada
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Sector asador iluminado
                </span>
              </div>
            </div>
          </div>

          <div class="px-space-lg pb-space-lg pt-0 flex items-center gap-space-sm">
            <RouterLink
              to="/alquileres"
              class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">info</span> Ver Detalles y Reservar
            </RouterLink>
            <a
              href="https://wa.me/5492644333444?text=Hola,%20vi%20Quincho%20Don%20Antonio%20en%20Turismo%20Seguro"
              target="_blank"
              rel="noopener noreferrer"
              class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm"
              title="Abrir WhatsApp Directo con el Dueño"
            >
              <span class="material-symbols-outlined text-[20px]">chat</span>
            </a>
          </div>
        </article>

        <!-- Quinta 4: Rincón de la Sierra Ullum -->
        <article class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
          <div>
            <div class="relative h-64 overflow-hidden">
              <img
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                alt="Rincón de la Sierra Dique de Ullum"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAd2EiOfveL3i9TOsDt_6jA0bkXgBOha_sUJ_2PXl84NszF67_xG-0qf9N1k0ywlm8O1rvG0uARNxvVxBZWdJPGIYgvzMOn7YEjGUdSknqiEQbNYhkgnxvrVimvoylJD9JyRit-6mvV0f7IrJR2DPMtXtu568sf1ykKlTTf7OPG8a3b8Ma8OZZqNR3kbQY9dMAxs_CNhkwnDJVKPCjQmovW3_Fr4ZFEotLw6jrrPLDjYGcRMsRlyHEd"
              />
              <div class="absolute top-3 left-3 bg-surface-container-lowest/95 backdrop-blur-md px-space-sm py-1.5 rounded-full flex items-center gap-1.5 text-secondary shadow-md">
                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                <span class="font-label-sm text-label-sm font-semibold">Sello Verificado In Situ</span>
              </div>
              <div class="absolute top-3 right-3 bg-secondary text-on-secondary px-space-sm py-1 rounded-full text-label-sm font-label-sm shadow-md flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">bolt</span>
                <span>Trato directo • 0% comisión</span>
              </div>
              <div class="absolute bottom-3 left-3 bg-primary/80 backdrop-blur-md px-space-sm py-1 rounded-md text-on-primary font-label-sm text-label-sm flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">location_on</span> Dique de Ullum
              </div>
            </div>

            <div class="p-space-lg">
              <div class="flex items-start justify-between gap-space-sm mb-space-xs">
                <h3 class="font-headline-md text-headline-md text-primary font-bold">Rincón de la Sierra</h3>
                <div class="text-right">
                  <div class="font-headline-md text-headline-md text-secondary font-bold">$65.000</div>
                  <div class="font-label-sm text-label-sm text-on-surface-variant">/ día (10:00 a 20:00 hs)</div>
                </div>
              </div>
              <div class="flex items-center gap-space-xs text-on-surface-variant mb-space-md">
                <span class="material-symbols-outlined text-secondary text-[18px]">groups</span>
                <span class="font-label-md text-label-md font-semibold text-on-surface">Capacidad: Hasta 20 personas</span>
              </div>
              <div class="flex flex-wrap gap-1.5 mb-space-md">
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Deck panorámico sobre el agua
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Bajada privada para kayaks
                </span>
                <span class="inline-flex items-center gap-1 px-space-sm py-1 rounded-full bg-surface-container text-on-surface font-label-sm text-label-sm">
                  <span class="material-symbols-outlined text-secondary text-[14px]">check_circle</span> Parrillero móvil
                </span>
              </div>
            </div>
          </div>

          <div class="px-space-lg pb-space-lg pt-0 flex items-center gap-space-sm">
            <RouterLink
              to="/alquileres"
              class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold"
            >
              <span class="material-symbols-outlined text-[18px]">info</span> Ver Detalles y Reservar
            </RouterLink>
            <a
              href="https://wa.me/5492644444555?text=Hola,%20vi%20Rincon%20de%20la%20Sierra%20en%20Turismo%20Seguro"
              target="_blank"
              rel="noopener noreferrer"
              class="w-12 h-12 shrink-0 rounded-xl bg-secondary text-on-secondary flex items-center justify-center hover:bg-on-secondary-container transition-colors shadow-sm"
              title="Abrir WhatsApp Directo con el Dueño"
            >
              <span class="material-symbols-outlined text-[20px]">chat</span>
            </a>
          </div>
        </article>
      </div>
    </section>

    <!-- Interactive Day Cost Calculator & Security Protocol Box -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 py-space-xl w-full">
      <div class="bg-surface-container-high rounded-3xl p-space-lg lg:p-space-xl overflow-hidden relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
          <!-- Interactive Guest Volume Calculator -->
          <div class="lg:col-span-7 flex flex-col gap-space-md">
            <div class="inline-flex items-center gap-space-xs text-secondary font-label-sm text-label-sm font-semibold uppercase tracking-wider">
              <span class="material-symbols-outlined text-[18px]">calculate</span>
              Calculadora Estimativa para Festejos Diurnos
            </div>
            <h3 class="font-headline-lg text-headline-lg text-primary">
              Calculá el costo por persona para tu asado o evento
            </h3>
            <p class="font-body-md text-body-md text-on-surface-variant">
              Alquilar una quinta para pasar el día en grupo es más económico y seguro que un salón tradicional. Ajustá la cantidad de invitados:
            </p>

            <!-- Interactive Counter Widget -->
            <div class="bg-surface-container-lowest p-space-md rounded-2xl shadow-sm flex flex-col sm:flex-row items-center justify-between gap-space-md">
              <div>
                <span class="font-label-sm text-label-sm text-on-surface-variant block font-bold">Cantidad de Asistentes</span>
                <div class="flex items-center gap-space-md mt-1">
                  <button
                    type="button"
                    class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary hover:bg-surface-dim font-headline-sm text-headline-sm font-bold cursor-pointer"
                    @click="decrementGuests"
                  >
                    -
                  </button>
                  <span class="font-headline-lg text-headline-lg text-primary min-w-[50px] text-center font-extrabold">
                    {{ guestCount }}
                  </span>
                  <button
                    type="button"
                    class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary hover:bg-surface-dim font-headline-sm text-headline-sm font-bold cursor-pointer"
                    @click="incrementGuests"
                  >
                    +
                  </button>
                  <span class="font-label-md text-label-md text-on-surface-variant font-medium">personas</span>
                </div>
              </div>

              <div class="sm:text-right w-full sm:w-auto p-space-sm bg-surface-container-low rounded-xl">
                <span class="font-label-sm text-label-sm text-on-surface-variant block font-semibold">Estimado por invitado:</span>
                <span class="font-headline-md text-headline-md text-secondary font-extrabold">
                  ${{ costPerPerson.toLocaleString('es-AR') }} / pers.
                </span>
                <span class="block font-label-sm text-label-sm text-on-surface-variant font-medium">
                  Base promedio $45.000 día
                </span>
              </div>
            </div>

            <div class="flex items-center gap-space-xs text-on-surface-variant font-label-sm text-label-sm">
              <span class="material-symbols-outlined text-secondary text-[16px]">info</span>
              Incluye uso total de piscina, quincho techado, asadores y freezers de refrigeración.
            </div>
          </div>

          <!-- Shift & Payment Verification Badge Widget -->
          <div class="lg:col-span-5 bg-surface-container-lowest p-space-lg rounded-2xl shadow-md">
            <h4 class="font-headline-sm text-headline-sm text-primary mb-space-sm flex items-center gap-2 font-bold">
              <span class="material-symbols-outlined text-secondary text-[22px]">policy</span>
              Protocolo de Reserva Verificada
            </h4>
            <ul class="space-y-space-sm">
              <li class="flex items-start gap-space-xs text-on-surface">
                <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">chat</span>
                <div>
                  <strong class="font-label-md text-label-md block">1. Contacto Directo por WhatsApp:</strong>
                  <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Hablás directamente con el propietario autenticado, sin chats robóticos ni intermediarios que encarezcan.
                  </span>
                </div>
              </li>
              <li class="flex items-start gap-space-xs text-on-surface">
                <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">account_balance</span>
                <div>
                  <strong class="font-label-md text-label-md block">2. CBU Cotejado en Mano:</strong>
                  <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Te brindamos el Alias/CBU validado en persona del titular catastral para que transfieras tu seña con total tranquilidad.
                  </span>
                </div>
              </li>
              <li class="flex items-start gap-space-xs text-on-surface">
                <span class="material-symbols-outlined text-secondary text-[18px] mt-0.5">payments</span>
                <div>
                  <strong class="font-label-md text-label-md block">3. Cero Comisiones al Inquilino:</strong>
                  <span class="font-body-sm text-body-sm text-on-surface-variant">
                    El dinero va 100% al dueño. Nosotros cobramos una suscripción fija de validación al anfitrión para certificar su predio.
                  </span>
                </div>
              </li>
            </ul>
            <div class="mt-space-md pt-space-sm">
              <a
                href="https://wa.me/5492644000000?text=Deseo%20conocer%20como%20auditan%20las%20cuentas"
                target="_blank"
                rel="noopener noreferrer"
                class="w-full inline-flex items-center justify-center gap-space-xs py-3 px-space-md rounded-xl bg-secondary text-on-secondary font-label-md text-label-md hover:bg-on-secondary-container transition-colors shadow-sm font-bold"
              >
                <span class="material-symbols-outlined text-[18px]">verified_user</span>
                Conocer cómo auditamos las cuentas bancarias
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Section for Property Owners (Host Call to Action) -->
    <section class="max-w-7xl mx-auto px-6 lg:px-12 py-space-xl w-full">
      <div class="bg-primary text-on-primary rounded-3xl p-space-lg lg:p-space-xl relative overflow-hidden shadow-2xl">
        <div class="absolute -right-16 -top-16 w-96 h-96 bg-secondary/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">
          <div class="lg:col-span-8 flex flex-col gap-space-sm">
            <div class="inline-flex items-center gap-space-xs bg-surface-container-lowest/15 backdrop-blur-md px-space-md py-1 rounded-full text-secondary-fixed w-fit font-bold">
              <span class="material-symbols-outlined text-[16px]">real_estate_agent</span>
              <span class="font-label-sm text-label-sm uppercase tracking-wide">Modelo Transparente para Propietarios</span>
            </div>
            <h2 class="font-headline-lg text-headline-lg text-on-primary tracking-tight font-extrabold">
              ¿Sos dueño de una quinta? Sumate con el Sello de Verificación TS
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
                <RouterLink
                  to="/register-client"
                  class="inline-flex items-center justify-center gap-space-xs py-3.5 px-space-md rounded-xl bg-secondary text-on-secondary font-headline-sm text-headline-sm hover:bg-on-secondary-container transition-all shadow-lg hover:shadow-xl font-bold"
                >
                  <span class="material-symbols-outlined text-[22px]">support_agent</span>
                  Contactar Validador
                </RouterLink>
                <RouterLink
                  to="/register-client"
                  class="inline-flex items-center justify-center gap-space-xs py-2.5 px-space-md rounded-xl bg-surface-container-lowest/15 hover:bg-surface-container-lowest/25 text-surface-bright font-label-md text-label-md transition-colors border border-surface-container-lowest/20 font-bold"
                >
                  <span class="material-symbols-outlined text-[18px]">loyalty</span>
                  Ver Planes de Suscripción
                </RouterLink>
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
