<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCatalog } from '../../composables/useCatalog'
import PropertyCard from '../../components/catalog/PropertyCard.vue'
import type { Property, Department } from '../../types/catalog'
import {
  Search,
  Filter,
  SlidersHorizontal,
  RotateCcw,
  Building,
  Loader2,
  AlertCircle,
  ChevronLeft,
  ChevronRight,
  ShieldCheck,
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()
const { getProperties, getDepartments, isLoading, errorMessage } = useCatalog()

const properties = ref<Property[]>([])
const departments = ref<Department[]>([])
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
})

const isFilterDrawerOpen = ref(false)

// Filtros reactivos
const filters = ref({
  search: (route.query.search as string) || '',
  department: (route.query.department as string) || '',
  property_type: (route.query.property_type as string) || '',
  capacity: route.query.capacity ? Number(route.query.capacity) : undefined,
  max_price: route.query.max_price ? Number(route.query.max_price) : undefined,
  sort: (route.query.sort as string) || 'default',
  amenities: {
    Piscina: false,
    Parrilla: false,
    WiFi: false,
    Cochera: false,
    'Acepta Mascotas': false,
  },
})

const propertyTypes = ['Cabaña', 'Quinta con Piscina', 'Departamento', 'Casa', 'Posada']

const loadData = async () => {
  const selectedAmenities = Object.entries(filters.value.amenities)
    .filter(([_, checked]) => checked)
    .map(([name]) => name)
    .join(',')

  try {
    const res = await getProperties({
      search: filters.value.search || undefined,
      department: filters.value.department || undefined,
      property_type: filters.value.property_type || undefined,
      capacity: filters.value.capacity || undefined,
      max_price: filters.value.max_price || undefined,
      sort: filters.value.sort !== 'default' ? (filters.value.sort as any) : undefined,
      amenities: selectedAmenities || undefined,
      page: pagination.value.current_page,
    })

    properties.value = res.data
    if (res.meta) {
      pagination.value.current_page = res.meta.current_page || 1
      pagination.value.last_page = res.meta.last_page || 1
      pagination.value.total = res.meta.total || 0
    }
  } catch (err) {
    // Handled in composable
  }
}

const applyFilters = () => {
  pagination.value.current_page = 1
  isFilterDrawerOpen.value = false

  const query: Record<string, string> = {}
  if (filters.value.search) query.search = filters.value.search
  if (filters.value.department) query.department = filters.value.department
  if (filters.value.property_type) query.property_type = filters.value.property_type
  if (filters.value.capacity) query.capacity = String(filters.value.capacity)
  if (filters.value.max_price) query.max_price = String(filters.value.max_price)
  if (filters.value.sort && filters.value.sort !== 'default') query.sort = filters.value.sort

  router.push({ query })
  loadData()
}

const resetFilters = () => {
  filters.value = {
    search: '',
    department: '',
    property_type: '',
    capacity: undefined,
    max_price: undefined,
    sort: 'default',
    amenities: {
      Piscina: false,
      Parrilla: false,
      WiFi: false,
      Cochera: false,
      'Acepta Mascotas': false,
    },
  }
  pagination.value.current_page = 1
  router.push({ query: {} })
  loadData()
}

const goToPage = (page: number) => {
  if (page < 1 || page > pagination.value.last_page) return
  pagination.value.current_page = page
  loadData()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(async () => {
  try {
    departments.value = await getDepartments()
  } catch {}
  loadData()
})

watch(() => route.query, () => {
  if (route.query.department) {
    filters.value.department = String(route.query.department)
  }
})
</script>

<template>
  <div class="min-h-screen bg-surface-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
      <!-- Breadcrumb & Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200/80 pb-6">
        <div>
          <div class="inline-flex items-center gap-1 text-xs text-brand-600 font-semibold uppercase tracking-wider mb-1">
            <ShieldCheck class="w-4 h-4 text-safe-600" />
            <span>Alojamientos Auditados y Homologados</span>
          </div>
          <h1 class="text-3xl font-extrabold text-surface-900 font-display tracking-tight">
            Alquileres Verificados de San Juan
          </h1>
          <p class="text-sm text-surface-500 mt-1">
            Encontrá hospedajes seguros inspeccionados in situ con QR y garantía oficial
          </p>
        </div>

        <!-- Botón filtros móvil -->
        <div class="flex items-center gap-3 lg:hidden">
          <button
            type="button"
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-surface-800 shadow-xs"
            @click="isFilterDrawerOpen = !isFilterDrawerOpen"
          >
            <SlidersHorizontal class="w-4 h-4 text-brand-600" />
            <span>Filtros de Búsqueda</span>
          </button>
        </div>
      </div>

      <!-- Main Layout: Sidebar Filtros + Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        <!-- Sidebar Filtros (Desktop) -->
        <aside class="hidden lg:block bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-6 sticky top-24">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="font-display font-bold text-surface-900 text-base flex items-center gap-2">
              <Filter class="w-4 h-4 text-brand-600" />
              <span>Filtros</span>
            </h2>
            <button
              type="button"
              class="text-xs text-surface-400 hover:text-brand-600 flex items-center gap-1 transition-colors cursor-pointer"
              @click="resetFilters"
            >
              <RotateCcw class="w-3 h-3" />
              <span>Limpiar</span>
            </button>
          </div>

          <form class="space-y-4" @submit.prevent="applyFilters">
            <!-- Búsqueda textual -->
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Buscar</label>
              <div class="relative">
                <Search class="w-4 h-4 text-surface-400 absolute left-3 top-2.5" />
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Ej: Cabaña, piscina, Zonda..."
                  class="w-full pl-9 pr-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
                />
              </div>
            </div>

            <!-- Departamento -->
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Departamento</label>
              <select
                v-model="filters.department"
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              >
                <option value="">Todos los departamentos</option>
                <option v-for="dept in departments" :key="dept.id" :value="dept.slug">
                  {{ dept.name }} {{ dept.properties_count ? `(${dept.properties_count})` : '' }}
                </option>
              </select>
            </div>

            <!-- Tipo de Propiedad -->
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Tipo de Propiedad</label>
              <select
                v-model="filters.property_type"
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              >
                <option value="">Todos los tipos</option>
                <option v-for="pt in propertyTypes" :key="pt" :value="pt">{{ pt }}</option>
              </select>
            </div>

            <!-- Capacidad mínima -->
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Capacidad Mínima</label>
              <input
                v-model.number="filters.capacity"
                type="number"
                min="1"
                placeholder="Ej: 4 personas"
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              />
            </div>

            <!-- Precio Máximo -->
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-1">Precio Máximo (ARS)</label>
              <input
                v-model.number="filters.max_price"
                type="number"
                step="5000"
                min="0"
                placeholder="Ej: 50000"
                class="w-full px-3 py-2 bg-surface-50 border border-slate-200 rounded-xl text-surface-900 text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white focus:outline-none"
              />
            </div>

            <!-- Comodidades (Checkboxes) -->
            <div>
              <label class="block text-xs font-semibold text-surface-700 uppercase tracking-wider mb-2">Comodidades</label>
              <div class="space-y-1.5">
                <label
                  v-for="(_, amenity) in filters.amenities"
                  :key="amenity"
                  class="flex items-center gap-2 text-xs text-surface-700 cursor-pointer"
                >
                  <input
                    v-model="filters.amenities[amenity]"
                    type="checkbox"
                    class="rounded text-brand-600 focus:ring-brand-500 w-4 h-4"
                  />
                  <span>{{ amenity }}</span>
                </label>
              </div>
            </div>

            <button
              type="submit"
              class="w-full py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl text-xs shadow-xs transition cursor-pointer"
            >
              Aplicar Filtros
            </button>
          </form>
        </aside>

        <!-- Listings Content Area -->
        <main class="lg:col-span-3 space-y-6">
          <!-- Top bar: Resultados + Orden -->
          <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-surface-600 font-medium">
              Mostrando <span class="font-bold text-surface-900">{{ properties.length }}</span> de <span class="font-bold text-surface-900">{{ pagination.total }}</span> alojamientos verificados
            </p>

            <div class="flex items-center gap-2 text-xs">
              <label for="sort" class="text-surface-500 font-medium">Ordenar:</label>
              <select
                id="sort"
                v-model="filters.sort"
                class="bg-surface-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-surface-800 focus:outline-none focus:ring-1 focus:ring-brand-500"
                @change="applyFilters"
              >
                <option value="default">Relevancia / Destacados</option>
                <option value="price_asc">Precio: Menor a Mayor</option>
                <option value="price_desc">Precio: Mayor a Menor</option>
                <option value="recent">Más Recientes</option>
              </select>
            </div>
          </div>

          <!-- Loading State -->
          <div v-if="isLoading" class="py-20 text-center flex flex-col items-center justify-center space-y-3">
            <Loader2 class="w-8 h-8 text-brand-600 animate-spin" />
            <p class="text-sm text-surface-500 font-medium">Cargando alojamientos certificados de San Juan...</p>
          </div>

          <!-- Error State -->
          <div v-else-if="errorMessage" class="p-6 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-700">
            <AlertCircle class="w-6 h-6 flex-shrink-0" />
            <span>{{ errorMessage }}</span>
          </div>

          <!-- Empty State -->
          <div v-else-if="properties.length === 0" class="py-16 text-center bg-white rounded-2xl border border-slate-200/80 p-8 space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-surface-100 text-surface-400 mx-auto flex items-center justify-center">
              <Building class="w-8 h-8" />
            </div>
            <div>
              <h3 class="text-base font-bold text-surface-900">No encontramos alquileres para estos criterios</h3>
              <p class="text-xs text-surface-500 mt-1">
                Probá modificando el departamento, ampliando el rango de precio o limpiando los filtros seleccionados.
              </p>
            </div>
            <button
              type="button"
              class="px-4 py-2 text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-xl transition"
              @click="resetFilters"
            >
              Restablecer Filtros
            </button>
          </div>

          <!-- Grid de Propiedades -->
          <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <PropertyCard
              v-for="property in properties"
              :key="property.id"
              :property="property"
            />
          </div>

          <!-- Paginación -->
          <div v-if="pagination.last_page > 1" class="flex items-center justify-center gap-2 pt-6">
            <button
              type="button"
              :disabled="pagination.current_page <= 1"
              class="p-2 rounded-xl border border-slate-200 bg-white text-surface-600 disabled:opacity-40 hover:bg-surface-50 transition"
              @click="goToPage(pagination.current_page - 1)"
            >
              <ChevronLeft class="w-4 h-4" />
            </button>
            <span class="text-xs font-semibold text-surface-700 px-3">
              Página {{ pagination.current_page }} de {{ pagination.last_page }}
            </span>
            <button
              type="button"
              :disabled="pagination.current_page >= pagination.last_page"
              class="p-2 rounded-xl border border-slate-200 bg-white text-surface-600 disabled:opacity-40 hover:bg-surface-50 transition"
              @click="goToPage(pagination.current_page + 1)"
            >
              <ChevronRight class="w-4 h-4" />
            </button>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>
