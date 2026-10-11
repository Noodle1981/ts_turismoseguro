<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'

interface PropiedadItem {
  id: number
  title: string
  tipo: 'quinta' | 'cabaña' | 'quincho'
  department: string
  departmentSlug: string
  location: string
  distance: string
  price: number
  capacity: number
  capacityGroup: '10-15' | '16-25' | '26-40' | '40+'
  shift: string
  acta: string
  owner: string
  image: string
  amenities: string[]
  poolFenced: boolean
  hasGrill: boolean
  hasFreezer: boolean
  hasAc: boolean
  hasWifi: boolean
  hasSoccer: boolean
  recentInspection: boolean
  cbuVerified: boolean
  whatsapp: string
}

const route = useRoute()

// Catálogo completo de Stitch adaptado
const catalogo: PropiedadItem[] = [
  {
    id: 1,
    title: 'Quinta Don Antonio & Quincho Familiar',
    tipo: 'quinta',
    department: 'Rawson • Médano de Oro',
    departmentSlug: 'rawson',
    location: 'Calle Ramón Franco y Calle 6',
    distance: 'A 12 min de Capital',
    price: 38000,
    capacity: 25,
    capacityGroup: '16-25',
    shift: 'Día 10:00 a 20:00 hs',
    acta: 'Acta #849-MED/26',
    owner: 'Carlos F. (DNI 24.*** - Verificado in situ)',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDj5JdNvlvVCvOFR2GhtBY1-6rDPha1R2ohacChehW_TqG0Fg0MDRf12LkXONOg5T7s0o9b_KXDawuA8zSXPiGH5-sUFQ2rA46wAjhUJqY3xyjZvh11xw-gId-pvhT63NZ0lrOBFaeqdYO8ivKJpCVIxFm8t1fiJLwHfxUiA2xuiH5eXjVTRvjVA6tqIpacdMCZu63k9ntt60XGbtfvCCJUkorGEgBXjmoEjV6dAE69WZirODYZfwmw',
    amenities: ['Pileta 9x4m cercada', 'Quincho asador criollo', 'Freezer 400L', 'Arboleda añosa'],
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
    hasAc: false,
    hasWifi: true,
    hasSoccer: true,
    recentInspection: true,
    cbuVerified: true,
    whatsapp: '5492644333444',
  },
  {
    id: 2,
    title: 'Cabaña & Posada El Refugio de los Olivos',
    tipo: 'cabaña',
    department: 'Pocito • Quinto Cuartel',
    departmentSlug: 'pocito',
    location: 'Calle 12 y Alfonso XIII',
    distance: 'A 18 min de Capital',
    price: 45000,
    capacity: 30,
    capacityGroup: '26-40',
    shift: 'Día 10:00 a 20:30 hs',
    acta: 'Acta #792-POC/26',
    owner: 'Banco San Juan a nombre de titular catastral',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAm0w3Eo3bIt_7_3405BlGYRthz836ni90tEsKwGbg4jcxaXwi7z66yjMC9regCjEdZzHZ32e2nsRjCv5T8LNXjcr6fhViHukkw4RNlK1SkQ7iWFEUSaW6eR68N6uggcTmSJ1HyFp7q8nY7XpEI4bZOFIhcjvkuleFCt1Z6fEXD2OcOLe7I7hpWifF13iUYWVSSNihJPiUKDBd3DDsVkzgtW9NqCeERC3L1gRdArXuRl_vi_XCT6ofS',
    amenities: ['Cerco perimetral de pileta', '2 Freezers verticales', 'Galería techada', 'Horno de barro'],
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
    hasAc: true,
    hasWifi: true,
    hasSoccer: false,
    recentInspection: true,
    cbuVerified: true,
    whatsapp: '5492644111222',
  },
  {
    id: 3,
    title: 'La Casona & Cabañas de Zonda',
    tipo: 'cabaña',
    department: 'Zonda • Sierras Azules',
    departmentSlug: 'zonda',
    location: 'Ruta 12 y Calle Las Moras',
    distance: 'A 25 min de Capital',
    price: 52000,
    capacity: 35,
    capacityGroup: '26-40',
    shift: 'Día 10:00 a 20:00 hs',
    acta: 'Acta #914-ZON/26',
    owner: 'Paula G. (Validación técnica vigente)',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBx8uAiyq4md3Wvpq7sTjq10T5qHApVGmQbZTLpfmAPnUQzlgYTrT1JeQUhm0Nm2nIXWfI77DxHZ7vrgJEKERfTYgM_GGmLBj12ePrbybPMrwoiOXOtpARKIjC0Ufv9YsqwURVi15R-7EWJTHv0mUXhi1z4In9xQhnXUZ_jsoNc96zRx_n0ak_kubCIEhtiEPNqf5eb88uL5gyu93jKxugsrs2u-jMbKdVfh2Re5w9NE9Pk7QeVL4Np',
    amenities: ['Piscina 10x5m cercada', 'Quincho cerrado con A/A', 'Vistas panorámicas a la sierra'],
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
    hasAc: true,
    hasWifi: true,
    hasSoccer: true,
    recentInspection: true,
    cbuVerified: true,
    whatsapp: '5492644222333',
  },
  {
    id: 4,
    title: 'Cabaña & Deck Rincón del Dique',
    tipo: 'cabaña',
    department: 'Ullum • Zona Dique',
    departmentSlug: 'ullum',
    location: 'Calle Hermógenes Ruiz s/n',
    distance: 'A 22 min de Capital',
    price: 34000,
    capacity: 20,
    capacityGroup: '16-25',
    shift: 'Día 10:00 a 20:00 hs',
    acta: 'Acta #620-ULL/26',
    owner: 'Matías L. (CBU y acta municipal cotejados)',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuAwqS8Q82Mzq6y4Z-SuTT7EpmVuA2zDXo7V3CykET_0piwap4i5ri3PbsvTw8jpTE2s0bodUQ4JYDGGCafF_2zpyXIW-nvH3Zin2Z7leK91pgO24VrM7WpCIe_gEb9TXkxoUtAH2IcYceF8HCxTxPxB5qkjm8PkRE-h7WGf8ChcqG1IIfZrWz0PVSZiRjhCRXo1bicBKKYUTsh4A4HMTHG-WDc0Mx2YiW0L2vgrDglMeMDrPPphEjPJ',
    amenities: ['Pileta con protección', 'Cancha de vóley/fútbol', 'Bajada para kayaks', 'Parrillero'],
    poolFenced: true,
    hasGrill: true,
    hasFreezer: false,
    hasAc: false,
    hasWifi: false,
    hasSoccer: true,
    recentInspection: false,
    cbuVerified: true,
    whatsapp: '5492644444555',
  },
  {
    id: 5,
    title: 'Quinta El Sol Cuyano',
    tipo: 'quinta',
    department: 'Rivadavia • Marquesado',
    departmentSlug: 'rivadavia',
    location: 'Av. Ignacio de la Roza Oeste',
    distance: 'A 10 min de Capital',
    price: 40000,
    capacity: 22,
    capacityGroup: '16-25',
    shift: 'Día 10:00 a 20:00 hs',
    acta: 'Acta #553-RIV/26',
    owner: 'Titular auditado presencialmente en el predio',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuDJzQDZYxLdsiQMFfh2h6qlOj-N9IS5FMNOkqEzwTIawkMPF8Kp39Du6PgiDHebF7-ySV6-jLygbsOrX96-RY4z9DxymzM8eJ4mNUy6XK5pWzJDOxD_-M_9dvI4V0OtjIWkT2E_QKO8dQXNo75IuorSQma6PRklAArN0-fUw2NArhZNDK6msoNBn15uo9f0G_uwaQmGb9Baaoear-z9xFPR5DWatSfMpYzhfQ0RZtrL1O8VmgOH-y3W',
    amenities: ['Cerco con traba de seguridad', 'WiFi 300 Mbps', 'Quincho de paja rústico'],
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
    hasAc: false,
    hasWifi: true,
    hasSoccer: false,
    recentInspection: true,
    cbuVerified: true,
    whatsapp: '5492644555666',
  },
  {
    id: 6,
    title: 'Quinta El Sauce de Médano',
    tipo: 'quinta',
    department: 'Rawson • Médano Norte',
    departmentSlug: 'rawson',
    location: 'Calle 5 y América',
    distance: 'A 14 min de Capital',
    price: 36000,
    capacity: 28,
    capacityGroup: '26-40',
    shift: 'Día 10:00 a 20:00 hs',
    acta: 'Acta #488-RAW/26',
    owner: 'CBU cotejado: Sin denuncias y verificado 2026',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBguaYDytem2CyyxHZ-3JgaO6BKtcWS-iyTNFrPfNzqLjCFZBjI76zeBKyQueKX6ol8Hx6mFAZvVbSMxWMb_gntKLiSWWGRUVX1ddvHwxFn4j8VaqYJblS9WSFOS5rAJaYs3zODgdcKatzrQxq7XYTylUpkKuWnDGDe19qXd5bIV0fbVqD60LfZd_tlT-hUC1P2-tJQj9HQQGfC7rJePgMOWaHU4rDp-mk6WoZmpB1brQiHrekIfati',
    amenities: ['Pileta con reja de seguridad', 'Asador criollo cubierto', 'Parque con sauces'],
    poolFenced: true,
    hasGrill: true,
    hasFreezer: true,
    hasAc: false,
    hasWifi: false,
    hasSoccer: true,
    recentInspection: true,
    cbuVerified: true,
    whatsapp: '5492644666777',
  },
]

// Estados de Filtros Reactivos
const searchQuery = ref('')
const selectedZones = ref<string[]>([])
const maxPrice = ref(70000)
const selectedCapacity = ref<string>('todos')
const filterPoolFenced = ref(false)
const filterGrill = ref(false)
const filterFreezer = ref(false)
const filterAc = ref(false)
const filterWifi = ref(false)
const filterSoccer = ref(false)
const filterRecent = ref(false)
const filterCbuOnly = ref(true)

// Vista y orden
const viewMode = ref<'grid' | 'map'>('grid')
const sortBy = ref<'calificacion' | 'precio-menor' | 'capacidad' | 'distancia'>('calificacion')

// Inicializar filtros desde la query de la URL (si viene del buscador del Home)
onMounted(() => {
  if (route.query.zone && typeof route.query.zone === 'string' && route.query.zone !== 'todos') {
    selectedZones.value = [route.query.zone]
  }
  if (route.query.capacity && typeof route.query.capacity === 'string') {
    if (route.query.capacity === '10-20') selectedCapacity.value = '10-15'
    else if (route.query.capacity === '20-40') selectedCapacity.value = '16-25'
    else if (route.query.capacity === '40-60') selectedCapacity.value = '26-40'
    else if (route.query.capacity === '60-plus') selectedCapacity.value = '40+'
  }
})

const toggleZone = (zone: string) => {
  if (selectedZones.value.includes(zone)) {
    selectedZones.value = selectedZones.value.filter(z => z !== zone)
  } else {
    selectedZones.value.push(zone)
  }
}

const clearAllFilters = () => {
  searchQuery.value = ''
  selectedZones.value = []
  maxPrice.value = 70000
  selectedCapacity.value = 'todos'
  filterPoolFenced.value = false
  filterGrill.value = false
  filterFreezer.value = false
  filterAc.value = false
  filterWifi.value = false
  filterSoccer.value = false
  filterRecent.value = false
  filterCbuOnly.value = false
}

// Filtro computado
const propiedadesFiltradas = computed(() => {
  return catalogo.filter(item => {
    // Texto
    if (searchQuery.value.trim()) {
      const q = searchQuery.value.toLowerCase()
      const matchTitle = item.title.toLowerCase().includes(q)
      const matchDep = item.department.toLowerCase().includes(q)
      const matchLoc = item.location.toLowerCase().includes(q)
      const matchAmenities = item.amenities.some(a => a.toLowerCase().includes(q))
      if (!matchTitle && !matchDep && !matchLoc && !matchAmenities) return false
    }

    // Zonas
    if (selectedZones.value.length > 0) {
      if (!selectedZones.value.includes(item.departmentSlug)) return false
    }

    // Precio
    if (item.price > maxPrice.value) return false

    // Capacidad
    if (selectedCapacity.value !== 'todos') {
      if (item.capacityGroup !== selectedCapacity.value) return false
    }

    // Comodidades específicas
    if (filterPoolFenced.value && !item.poolFenced) return false
    if (filterGrill.value && !item.hasGrill) return false
    if (filterFreezer.value && !item.hasFreezer) return false
    if (filterAc.value && !item.hasAc) return false
    if (filterWifi.value && !item.hasWifi) return false
    if (filterSoccer.value && !item.hasSoccer) return false

    // Toggles de seguridad
    if (filterRecent.value && !item.recentInspection) return false
    if (filterCbuOnly.value && !item.cbuVerified) return false

    return true
  }).sort((a, b) => {
    if (sortBy.value === 'precio-menor') return a.price - b.price
    if (sortBy.value === 'capacidad') return b.capacity - a.capacity
    return 0 // Por defecto orden de auditoría
  })
})

const activeChipsCount = computed(() => {
  let count = 0
  if (selectedZones.value.length > 0) count += selectedZones.value.length
  if (selectedCapacity.value !== 'todos') count++
  if (maxPrice.value < 70000) count++
  if (filterPoolFenced.value) count++
  if (filterGrill.value) count++
  if (filterFreezer.value) count++
  if (filterAc.value) count++
  if (filterWifi.value) count++
  if (filterRecent.value) count++
  if (filterCbuOnly.value) count++
  return count
})
</script>

<template>
  <main class="w-full bg-background min-h-screen">
    <!-- SEARCH BAR & TOP CONTROL STRIP -->
    <section class="w-full bg-surface-container-lowest shadow-sm border-b border-surface-container relative z-20">
      <div class="w-full max-w-[98%] 2xl:max-w-[1850px] mx-auto px-3 sm:px-6 py-space-sm sm:py-space-md">
        <!-- Main quick search row -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
          <!-- Text query input -->
          <div class="lg:col-span-5 relative">
            <div class="flex items-center bg-surface-container-low rounded-xl px-4 py-3 shadow-[inset_0_1px_2px_rgba(15,41,66,0.06)]">
              <span class="material-symbols-outlined text-outline mr-2.5 text-[22px]">search</span>
              <input
                v-model="searchQuery"
                class="w-full bg-transparent font-body-sm text-body-sm text-on-surface placeholder:text-outline focus:outline-none"
                placeholder="Buscar ej. Pileta cercada, Médano de Oro, cabaña, quincho..."
                type="text"
              />
              <button
                v-if="searchQuery"
                type="button"
                class="text-outline hover:text-on-surface transition-colors p-0.5 rounded-full cursor-pointer"
                title="Borrar búsqueda"
                @click="searchQuery = ''"
              >
                <span class="material-symbols-outlined text-[18px]">close</span>
              </button>
            </div>
          </div>

          <!-- Date selector display -->
          <div class="lg:col-span-3 relative">
            <div class="flex items-center bg-surface-container-low rounded-xl px-4 py-3 cursor-pointer hover:bg-surface-container transition-colors">
              <span class="material-symbols-outlined text-secondary mr-2.5 text-[20px]">calendar_month</span>
              <div class="flex flex-col flex-1 min-w-0">
                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Fecha estimada</span>
                <span class="font-label-md text-label-md text-on-surface truncate font-semibold">Próximo Fin de Semana (Diurno)</span>
              </div>
              <span class="material-symbols-outlined text-outline text-[18px]">expand_more</span>
            </div>
          </div>

          <!-- Live badge and sort container -->
          <div class="lg:col-span-4 flex items-center justify-between gap-3">
            <!-- Live badge -->
            <div class="flex items-center gap-2 bg-secondary/10 px-3.5 py-2.5 rounded-xl">
              <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-secondary"></span>
              </span>
              <span class="font-label-md text-label-md text-secondary font-bold whitespace-nowrap">
                {{ propiedadesFiltradas.length }} Espacios Auditados
              </span>
            </div>

            <!-- Sort dropdown -->
            <div class="flex-1 min-w-[170px] relative">
              <select
                v-model="sortBy"
                class="w-full bg-surface-container-low text-on-surface font-label-md text-label-md py-3 px-3.5 rounded-xl appearance-none cursor-pointer focus:outline-none font-semibold"
              >
                <option value="calificacion">Calificación de auditoría</option>
                <option value="precio-menor">Menor precio por día</option>
                <option value="capacidad">Mayor capacidad</option>
              </select>
              <span class="material-symbols-outlined absolute right-3 top-3 text-outline pointer-events-none text-[18px]">swap_vert</span>
            </div>
          </div>
        </div>

        <!-- Quick toggles and view switcher bar -->
        <div class="flex flex-wrap items-center justify-between gap-3 mt-3 pt-3 border-t border-surface-container">
          <div class="flex flex-wrap items-center gap-2.5">
            <!-- Toggle 1: Recientes -->
            <button
              type="button"
              :class="[
                'inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-label-md font-label-md transition-colors cursor-pointer border',
                filterRecent ? 'bg-secondary text-on-secondary border-secondary' : 'bg-surface-container-low text-on-surface hover:bg-surface-container border-transparent font-medium'
              ]"
              @click="filterRecent = !filterRecent"
            >
              <span class="material-symbols-outlined text-[16px]">schedule</span>
              Inspección últimos 30 días
            </button>

            <!-- Toggle 2: Piscina cercada -->
            <button
              type="button"
              :class="[
                'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-label-md font-label-md transition-colors cursor-pointer border',
                filterPoolFenced ? 'bg-secondary text-on-secondary border-secondary font-bold' : 'bg-secondary/10 text-secondary hover:bg-secondary/20 border-transparent font-semibold'
              ]"
              @click="filterPoolFenced = !filterPoolFenced"
            >
              <span class="material-symbols-outlined text-[16px]">fence</span>
              Pileta cercada niños
            </button>

            <!-- Toggle 3: CBU Seguro -->
            <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-container text-on-primary font-label-sm text-label-sm font-semibold shadow-xs">
              <span class="material-symbols-outlined text-secondary-fixed text-[16px]">verified_user</span>
              <span>CBU Titular Validado con DNI</span>
            </div>
          </div>

          <!-- View mode switcher & Agregar Oferta -->
          <div class="flex items-center gap-2">
            <RouterLink
              to="/propietario/panel"
              class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-secondary text-on-secondary hover:bg-on-secondary-container transition-colors font-label-md text-label-md font-bold shadow-xs"
              title="Publicar tu propiedad o quinta diurna"
            >
              <span class="material-symbols-outlined text-[18px]">add_business</span>
              <span>+ Agregar Oferta</span>
            </RouterLink>

            <div class="flex items-center bg-surface-container-low p-1 rounded-xl">
              <button
                type="button"
                :class="[
                  'flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-label-md text-label-md transition-all cursor-pointer font-bold',
                  viewMode === 'grid' ? 'bg-surface-container-lowest text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'
                ]"
                @click="viewMode = 'grid'"
              >
                <span class="material-symbols-outlined text-[18px]">grid_view</span>
                <span>Grilla</span>
              </button>
              <button
                type="button"
                :class="[
                  'flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-label-md text-label-md transition-all cursor-pointer font-bold',
                  viewMode === 'map' ? 'bg-surface-container-lowest text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface'
                ]"
                @click="viewMode = 'map'"
              >
                <span class="material-symbols-outlined text-[18px]">map</span>
                <span>Mapa San Juan</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- DUAL COLUMN WORKSPACE -->
    <div class="w-full max-w-[98%] 2xl:max-w-[1850px] mx-auto px-3 sm:px-6 py-space-md lg:py-space-lg">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-md lg:gap-space-lg items-start">
        <!-- LEFT SIDEBAR: ADVANCED AUDIT FILTERS (COL 1 to 3 ~ 280-320px) -->
        <aside class="lg:col-span-3 xl:col-span-3 2xl:col-span-2 bg-surface-container-lowest rounded-2xl p-space-md shadow-sm space-y-space-md border border-surface-container sticky top-24">
          <div class="flex items-center justify-between pb-3 border-b border-surface-container">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[22px]">tune</span>
              <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Filtros de Auditoría</h2>
            </div>
            <button
              type="button"
              class="font-label-sm text-label-sm text-secondary hover:underline cursor-pointer font-bold"
              @click="clearAllFilters"
            >
              Limpiar todo
            </button>
          </div>

          <!-- 1. Departamento / Zona de San Juan -->
          <div class="space-y-2.5">
            <div class="flex items-center justify-between">
              <span class="font-label-lg text-label-lg text-primary font-bold">Zona / Departamento</span>
              <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">8 zonas</span>
            </div>
            <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 scrollbar-none">
              <label
                v-for="d in [
                  { slug: 'rawson', name: 'Rawson (Médano de Oro)', count: 18 },
                  { slug: 'pocito', name: 'Pocito (Calles 11 a 14)', count: 14 },
                  { slug: 'zonda', name: 'Zonda (Interlagos)', count: 7 },
                  { slug: 'ullum', name: 'Ullum (Dique y Playas)', count: 5 },
                  { slug: 'rivadavia', name: 'Rivadavia (Ruta del Sol)', count: 4 },
                  { slug: 'santa-lucia', name: 'Santa Lucía (Zona Este)', count: 3 },
                  { slug: 'albardon', name: 'Albardón', count: 3 },
                ]"
                :key="d.slug"
                class="flex items-center justify-between p-2 rounded-xl hover:bg-surface-container-low cursor-pointer transition-colors"
                :class="selectedZones.includes(d.slug) ? 'bg-surface-container-low font-semibold' : ''"
              >
                <div class="flex items-center gap-2.5">
                  <input
                    type="checkbox"
                    :checked="selectedZones.includes(d.slug)"
                    class="rounded accent-secondary w-4 h-4 cursor-pointer"
                    @change="toggleZone(d.slug)"
                  />
                  <span class="font-body-sm text-body-sm text-on-surface">{{ d.name }}</span>
                </div>
                <span class="font-label-sm text-label-sm px-2 py-0.5 rounded-full bg-surface-container text-primary font-bold">
                  {{ d.count }}
                </span>
              </label>
            </div>
          </div>

          <!-- 2. Rango de Precio por Día (ARS) -->
          <div class="space-y-2.5 pt-2 border-t border-surface-container">
            <div class="flex items-center justify-between">
              <span class="font-label-lg text-label-lg text-primary font-bold">Precio Máximo por Día</span>
              <span class="font-label-md text-label-md text-secondary font-bold">${{ maxPrice.toLocaleString('es-AR') }}</span>
            </div>
            <div class="space-y-2">
              <input
                v-model.number="maxPrice"
                type="range"
                min="30000"
                max="70000"
                step="5000"
                class="w-full accent-secondary cursor-pointer h-1.5 bg-surface-container rounded-lg"
              />
              <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="bg-surface-container-low rounded-lg p-2">
                  <span class="font-label-sm text-label-sm text-on-surface-variant block">Base</span>
                  <span class="font-label-md text-label-md text-on-surface font-semibold">$30.000</span>
                </div>
                <div class="bg-surface-container-low rounded-lg p-2 text-right">
                  <span class="font-label-sm text-label-sm text-on-surface-variant block">Tope</span>
                  <span class="font-label-md text-label-md text-on-surface font-semibold">${{ maxPrice.toLocaleString('es-AR') }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- 3. Capacidad de Personas -->
          <div class="space-y-2 pt-2 border-t border-surface-container">
            <span class="font-label-lg text-label-lg text-primary block font-bold">Capacidad Diurna</span>
            <div class="grid grid-cols-2 gap-1.5">
              <button
                type="button"
                :class="[
                  'px-2.5 py-2 rounded-xl font-label-md text-label-md text-center transition-colors cursor-pointer font-semibold',
                  selectedCapacity === 'todos' ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface'
                ]"
                @click="selectedCapacity = 'todos'"
              >
                Cualquiera
              </button>
              <button
                type="button"
                :class="[
                  'px-2.5 py-2 rounded-xl font-label-md text-label-md text-center transition-colors cursor-pointer font-semibold',
                  selectedCapacity === '10-15' ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface'
                ]"
                @click="selectedCapacity = '10-15'"
              >
                10 a 15 pers.
              </button>
              <button
                type="button"
                :class="[
                  'px-2.5 py-2 rounded-xl font-label-md text-label-md text-center transition-colors cursor-pointer font-semibold',
                  selectedCapacity === '16-25' ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface'
                ]"
                @click="selectedCapacity = '16-25'"
              >
                16 a 25 pers.
              </button>
              <button
                type="button"
                :class="[
                  'px-2.5 py-2 rounded-xl font-label-md text-label-md text-center transition-colors cursor-pointer font-semibold',
                  selectedCapacity === '26-40' ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low hover:bg-surface-container text-on-surface'
                ]"
                @click="selectedCapacity = '26-40'"
              >
                26 a 40 pers.
              </button>
            </div>
          </div>

          <!-- 4. Instalaciones y Comodidades In Situ -->
          <div class="space-y-2 pt-2 border-t border-surface-container">
            <div class="flex items-center justify-between">
              <span class="font-label-lg text-label-lg text-primary font-bold">Comodidades In Situ</span>
              <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
            </div>
            <div class="space-y-2 text-on-surface">
              <label class="flex items-center gap-2.5 cursor-pointer">
                <input v-model="filterPoolFenced" type="checkbox" class="rounded accent-secondary w-4 h-4 cursor-pointer" />
                <span class="font-body-sm text-body-sm text-on-surface flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-secondary text-[18px]">fence</span>
                  Pileta con cerco perimetral
                </span>
              </label>

              <label class="flex items-center gap-2.5 cursor-pointer">
                <input v-model="filterGrill" type="checkbox" class="rounded accent-secondary w-4 h-4 cursor-pointer" />
                <span class="font-body-sm text-body-sm text-on-surface flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-primary text-[18px]">outdoor_grill</span>
                  Parrilla techada / asador criollo
                </span>
              </label>

              <label class="flex items-center gap-2.5 cursor-pointer">
                <input v-model="filterFreezer" type="checkbox" class="rounded accent-secondary w-4 h-4 cursor-pointer" />
                <span class="font-body-sm text-body-sm text-on-surface flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-primary text-[18px]">kitchen</span>
                  Freezer extra / heladera amplia
                </span>
              </label>

              <label class="flex items-center gap-2.5 cursor-pointer">
                <input v-model="filterAc" type="checkbox" class="rounded accent-secondary w-4 h-4 cursor-pointer" />
                <span class="font-body-sm text-body-sm text-on-surface flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-outline text-[18px]">ac_unit</span>
                  Quincho cerrado con A/A
                </span>
              </label>

              <label class="flex items-center gap-2.5 cursor-pointer">
                <input v-model="filterWifi" type="checkbox" class="rounded accent-secondary w-4 h-4 cursor-pointer" />
                <span class="font-body-sm text-body-sm text-on-surface flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-outline text-[18px]">wifi</span>
                  WiFi Fibra óptica comprobado
                </span>
              </label>

              <label class="flex items-center gap-2.5 cursor-pointer">
                <input v-model="filterSoccer" type="checkbox" class="rounded accent-secondary w-4 h-4 cursor-pointer" />
                <span class="font-body-sm text-body-sm text-on-surface flex items-center gap-1.5">
                  <span class="material-symbols-outlined text-outline text-[18px]">sports_soccer</span>
                  Canchita de fútbol / parque
                </span>
              </label>
            </div>
          </div>

          <!-- 5. Filtro de Garantía y Cotejo -->
          <div class="p-3 bg-secondary/10 rounded-xl space-y-2 border border-secondary/20">
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-secondary text-[18px]">shield_lock</span>
              <span class="font-label-md text-label-md text-secondary font-bold">Auditoría Cero Estafas</span>
            </div>
            <label class="flex items-start gap-2 cursor-pointer">
              <input v-model="filterCbuOnly" type="checkbox" class="mt-0.5 rounded accent-secondary w-4 h-4 cursor-pointer" />
              <span class="font-label-sm text-label-sm text-on-surface leading-tight font-medium">
                Solo cuentas bancarias con DNI cotejado in situ
              </span>
            </label>
          </div>

          <!-- Sidebar Actions -->
          <div class="space-y-2 pt-1">
            <button
              type="button"
              class="w-full py-2.5 px-4 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
            >
              <span class="material-symbols-outlined text-[18px]">filter_alt</span>
              <span>{{ propiedadesFiltradas.length }} Propiedades Disponibles</span>
            </button>
            <a
              href="https://wa.me/5492644000000?text=Hola,%20deseo%20crear%20una%20alerta%20de%20disponibilidad%20para%20quintas%20o%20caba%C3%B1as"
              target="_blank"
              rel="noopener noreferrer"
              class="w-full py-2 px-4 bg-surface-container-low hover:bg-surface-container text-on-surface-variant rounded-xl font-label-sm text-label-sm font-semibold transition-all flex items-center justify-center gap-1.5 cursor-pointer"
            >
              <span class="material-symbols-outlined text-[16px] text-secondary">notifications_active</span>
              <span>Crear Alerta de Disponibilidad</span>
            </a>
          </div>
        </aside>

        <!-- RIGHT COLUMN: RESULTS GRID & LIVE LISTINGS -->
        <main class="lg:col-span-9 xl:col-span-9 2xl:col-span-10 flex flex-col space-y-space-md">
          <!-- ACTIVE FILTER CHIPS BAR -->
          <div class="flex flex-wrap items-center gap-2 bg-surface-container-lowest p-3 rounded-2xl shadow-sm border border-surface-container">
            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider pl-1 font-bold">Filtros Activos:</span>

            <span
              v-for="z in selectedZones"
              :key="z"
              class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm font-semibold"
            >
              {{ z.toUpperCase() }}
              <span class="material-symbols-outlined text-[14px] cursor-pointer hover:text-error" @click="toggleZone(z)">close</span>
            </span>

            <span
              v-if="selectedCapacity !== 'todos'"
              class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm font-semibold"
            >
              {{ selectedCapacity }} personas
              <span class="material-symbols-outlined text-[14px] cursor-pointer hover:text-error" @click="selectedCapacity = 'todos'">close</span>
            </span>

            <span
              v-if="filterPoolFenced"
              class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-semibold"
            >
              <span class="material-symbols-outlined text-[14px]">fence</span> Pileta cercada
              <span class="material-symbols-outlined text-[14px] cursor-pointer hover:text-error" @click="filterPoolFenced = false">close</span>
            </span>

            <span
              v-if="maxPrice < 70000"
              class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm font-semibold"
            >
              Tope ${{ maxPrice.toLocaleString('es-AR') }}
              <span class="material-symbols-outlined text-[14px] cursor-pointer hover:text-error" @click="maxPrice = 70000">close</span>
            </span>

            <button
              v-if="activeChipsCount > 0"
              type="button"
              class="font-label-sm text-label-sm text-secondary hover:underline ml-auto pr-1 cursor-pointer font-bold"
              @click="clearAllFilters"
            >
              Restablecer todos
            </button>
          </div>

          <!-- GRID VIEW (DEFAULT) -->
          <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4" id="grid-view-container">
            <article
              v-for="item in propiedadesFiltradas"
              :key="item.id"
              class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col justify-between group border border-surface-container"
            >
              <div>
                <div class="relative h-56 w-full overflow-hidden">
                  <img
                    :src="item.image"
                    :alt="item.title"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                  <!-- Seal badge -->
                  <div class="absolute top-3 left-3 flex items-center gap-1.5 bg-surface-container-lowest/95 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm">
                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                    <span class="font-label-sm text-label-sm text-secondary font-bold">Validado</span>
                  </div>

                  <!-- Shift indicator -->
                  <div class="absolute top-3 right-3 bg-primary/80 backdrop-blur-sm text-on-primary px-2.5 py-1 rounded-full font-label-sm text-label-sm font-semibold">
                    {{ item.shift }}
                  </div>

                  <!-- Department Pill -->
                  <div class="absolute bottom-3 left-3 bg-primary-container/90 backdrop-blur-sm text-on-primary px-2.5 py-0.5 rounded-lg font-label-sm text-label-sm font-bold uppercase tracking-wider">
                    {{ item.department }}
                  </div>
                </div>

                <div class="p-space-md space-y-3">
                  <div>
                    <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm mb-1">
                      <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">pin_drop</span>
                        {{ item.location }}
                      </span>
                      <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">schedule</span>
                        {{ item.distance }}
                      </span>
                    </div>

                    <h3 class="font-headline-sm text-headline-sm text-primary group-hover:text-secondary transition-colors line-clamp-1 font-bold">
                      {{ item.title }}
                    </h3>
                  </div>

                  <!-- Key verified features -->
                  <div class="flex flex-wrap gap-1.5">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-surface-container font-label-sm text-label-sm text-on-surface font-semibold">
                      <span class="material-symbols-outlined text-[15px] text-secondary">groups</span>
                      Hasta {{ item.capacity }} pers.
                    </span>

                    <span
                      v-for="amenity in item.amenities.slice(0, 2)"
                      :key="amenity"
                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-surface-container-low font-label-sm text-label-sm text-on-surface font-medium"
                    >
                      <span class="material-symbols-outlined text-[14px] text-secondary">check_circle</span>
                      {{ amenity }}
                    </span>
                  </div>

                  <!-- Owner verified check -->
                  <div class="flex items-center gap-2 p-2 rounded-xl bg-surface-container-low text-on-surface-variant">
                    <span class="material-symbols-outlined text-secondary text-[18px]">task_alt</span>
                    <span class="font-label-sm text-label-sm truncate font-medium">{{ item.owner }}</span>
                  </div>
                </div>
              </div>

              <!-- Price & CTA actions -->
              <div class="p-space-md pt-0 border-t border-surface-container mt-2 flex items-center justify-between gap-2">
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant block">Pasar el día</span>
                  <div class="flex items-baseline gap-1">
                    <span class="font-headline-md text-headline-md text-primary font-extrabold">
                      ${{ item.price.toLocaleString('es-AR') }}
                    </span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">/turno</span>
                  </div>
                </div>

                <div class="flex items-center gap-1.5">
                  <RouterLink
                    :to="`/quintas/${item.id}`"
                    class="p-2.5 rounded-xl bg-surface-container hover:bg-surface-dim text-primary transition-colors cursor-pointer"
                    title="Ver Ficha e Inspección 360°"
                  >
                    <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                  </RouterLink>

                  <a
                    :href="`https://wa.me/${item.whatsapp}?text=Hola,%20vi%20tu%20quinta%20o%20caba%C3%B1a%20${encodeURIComponent(item.title)}%20en%20Turismo%20Seguro`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="px-3.5 py-2.5 rounded-xl bg-secondary text-on-secondary hover:bg-on-secondary-container transition-all flex items-center gap-2 font-label-md text-label-md font-bold shadow-sm cursor-pointer"
                  >
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                      <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.54 1.772.78 2.79.78 3.182 0 5.768-2.587 5.768-5.766.001-3.182-2.585-5.766-5.767-5.766zm9.969 5.766c0 5.505-4.475 9.98-9.97 9.98-1.742 0-3.376-.447-4.808-1.23l-5.222 1.369 1.393-5.087c-.89-1.493-1.393-3.238-1.393-5.032 0-5.506 4.475-9.981 9.97-9.981 5.505 0 10.03 4.475 10.03 9.981z" />
                    </svg>
                    <span>Contactar</span>
                  </a>
                </div>
              </div>
            </article>
          </div>

          <!-- MAP VIEW -->
          <div v-else class="w-full bg-surface-container-lowest rounded-2xl p-4 shadow-sm space-y-4 border border-surface-container" id="map-view-container">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <div>
                <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Mapa Interactivo de Quintas y Cabañas Validadas</h3>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Puntos geolocalizados e inspeccionados por Turismo Seguro en los departamentos sanjuaninos.</p>
              </div>
              <div class="flex items-center gap-2 bg-secondary/10 px-3 py-1.5 rounded-xl font-label-sm text-label-sm text-secondary font-semibold">
                <span class="material-symbols-outlined text-[16px]">location_on</span>
                {{ propiedadesFiltradas.length }} ubicaciones con coordenadas reales
              </div>
            </div>

            <!-- Integrated Static Map Component -->
            <div
              class="w-full h-[520px] rounded-xl relative overflow-hidden bg-cover bg-center shadow-inner flex flex-col justify-between p-4"
              style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCM83K8O32O_rYPHiCSjC1wBJtVsN7c8vl2uWlXgYUXbnF_LZbCnzKzDu5K17AaE_7c3PsYO5b8NhPiW82GQeAIDMkreaEBw7HWwHC5wDAAlvLpHm8BgX5ErGTONsfq0-UDW-o9h0H3RyLZMT2fERYb3qRxhnhqM9DRUfy1ysuxWQacykZHi5Ud64UJHjuu6lGrGYNhFMqVyCDnOjHINeK1SVtWSgBjM2SMI4YOGD6r58sFIiGhv9bN')"
            >
              <div class="flex items-center gap-2 bg-surface-container-lowest/90 backdrop-blur-md p-2.5 rounded-xl max-w-sm shadow-md">
                <span class="material-symbols-outlined text-secondary text-[24px]">verified</span>
                <p class="font-label-sm text-label-sm text-on-surface font-semibold leading-tight">
                  Marcadores verificados con acta municipal y número de contacto directo del titular.
                </p>
              </div>

              <div class="bg-primary/95 text-on-primary backdrop-blur-md p-4 rounded-xl max-w-md shadow-xl flex items-center justify-between gap-4">
                <div>
                  <span class="font-label-sm text-label-sm text-secondary-fixed block uppercase font-bold">Predio destacado en mapa:</span>
                  <span class="font-headline-sm text-headline-sm font-bold">Quinta Don Antonio &amp; Quincho</span>
                  <p class="font-body-sm text-body-sm text-surface-tint">Rawson • A 12 min de Capital • $38.000 / día</p>
                </div>
                <RouterLink
                  to="/quintas/1"
                  class="px-4 py-2 bg-secondary text-on-secondary rounded-lg font-label-md text-label-md font-bold whitespace-nowrap shadow-sm hover:bg-on-secondary-container transition-colors"
                >
                  Ver Ficha
                </RouterLink>
              </div>
            </div>
          </div>

          <!-- PAGINATION & METRICS -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-space-md bg-surface-container-lowest rounded-2xl shadow-sm border border-surface-container">
            <span class="font-body-sm text-body-sm text-on-surface-variant font-medium">
              Mostrando <strong class="text-on-surface">{{ propiedadesFiltradas.length }} de {{ catalogo.length }}</strong> quintas, cabañas y quinchos auditados
            </span>
            <div class="flex items-center gap-1.5">
              <button type="button" class="px-3 py-1.5 rounded-lg bg-surface-container font-label-md text-label-md text-on-surface-variant opacity-60 cursor-not-allowed" disabled>
                Anterior
              </button>
              <button type="button" class="px-3 py-1.5 rounded-lg bg-primary text-on-primary font-label-md text-label-md font-bold shadow-xs">
                1
              </button>
              <button type="button" class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-dim font-label-md text-label-md text-on-surface transition-colors cursor-pointer font-semibold">
                Siguiente
              </button>
            </div>
          </div>
        </main>
      </div>
    </div>

    <!-- VALUE GUARANTEE BANNER -->
    <section class="w-full bg-primary-container text-on-primary py-space-xl relative overflow-hidden mt-space-lg">
      <div class="absolute inset-0 bg-gradient-to-r from-secondary/15 via-transparent to-primary/40 pointer-events-none"></div>
      <div class="max-w-[98%] 2xl:max-w-[1850px] mx-auto px-4 sm:px-6 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          <div class="lg:col-span-8 space-y-4">
            <div class="inline-flex items-center gap-2 bg-secondary/20 px-3.5 py-1 rounded-full">
              <span class="material-symbols-outlined text-secondary-fixed text-[18px]">verified</span>
              <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-wider font-bold">Fiscalización Comunitaria Activa</span>
            </div>
            <h2 class="font-headline-lg text-headline-lg text-on-primary font-bold">
              Garantía de Seña Segura: Cero Estafas en San Juan
            </h2>
            <p class="font-body-md text-body-md text-on-primary-container leading-relaxed max-w-2xl">
              Cada quinta, cabaña, pileta y quincho listado en nuestra plataforma ha sido visitado personalmente por nuestro equipo en San Juan. Cotejamos que el CBU de la seña coincida rigurosamente con el DNI del titular registrado.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
              <div class="flex items-start gap-3 bg-surface-container-lowest/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                <span class="material-symbols-outlined text-secondary-fixed text-[24px]">fingerprint</span>
                <div>
                  <strong class="font-label-md text-label-md block text-on-primary">Titular Cotejado</strong>
                  <span class="font-body-sm text-body-sm text-on-primary-container">DNI y CBU validados in situ.</span>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-surface-container-lowest/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                <span class="material-symbols-outlined text-secondary-fixed text-[24px]">fence</span>
                <div>
                  <strong class="font-label-md text-label-md block text-on-primary">Piletas Auditadas</strong>
                  <span class="font-body-sm text-body-sm text-on-primary-container">Cercos perimetrales comprobados.</span>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-surface-container-lowest/10 p-3.5 rounded-xl backdrop-blur-sm border border-white/10">
                <span class="material-symbols-outlined text-secondary-fixed text-[24px]">handshake</span>
                <div>
                  <strong class="font-label-md text-label-md block text-on-primary">Trato Directo</strong>
                  <span class="font-body-sm text-body-sm text-on-primary-container">0% comisión al inquilino.</span>
                </div>
              </div>
            </div>
          </div>

          <div class="lg:col-span-4 flex flex-col items-center justify-center p-6 bg-surface-container-lowest/10 rounded-2xl backdrop-blur-md text-center space-y-3 border border-white/10">
            <div class="w-16 h-16 rounded-full bg-secondary flex items-center justify-center shadow-lg">
              <span class="material-symbols-outlined text-on-secondary text-[36px]">gpp_good</span>
            </div>
            <div>
              <span class="font-headline-xl text-headline-xl text-secondary-fixed font-black block">100%</span>
              <span class="font-label-lg text-label-lg text-on-primary font-bold">Inspección Presencial Real</span>
            </div>
            <p class="font-body-sm text-body-sm text-on-primary-container">
              ¿Tenés dudas sobre una publicación o querés verificar un contacto?
            </p>
            <a
              class="w-full py-2.5 px-4 bg-secondary text-on-secondary hover:bg-on-secondary-container rounded-xl font-label-md text-label-md font-bold transition-colors flex items-center justify-center gap-2 shadow-sm cursor-pointer"
              href="https://wa.me/5492644000000?text=Hola,%20deseo%20verificar%20una%20propiedad%20en%20Turismo%20Seguro"
              target="_blank"
              rel="noopener noreferrer"
            >
              <span class="material-symbols-outlined text-[18px]">verified</span>
              <span>Verificar Propiedad por WhatsApp</span>
            </a>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
