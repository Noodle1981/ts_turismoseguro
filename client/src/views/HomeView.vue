<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useCatalog } from '../composables/useCatalog'
import type { Property, Department, Experience } from '../types/catalog'
import PropertyCard from '../components/catalog/PropertyCard.vue'
import BadgeVerified from '../components/common/BadgeVerified.vue'
import {
  Search,
  ShieldCheck,
  CheckCircle,
  MapPin,
  Lock,
  Compass,
  ArrowRight,
  Sparkles,
} from 'lucide-vue-next'

const { getProperties, getDepartments, getExperiences } = useCatalog()

const featuredProperties = ref<Property[]>([])
const departments = ref<Department[]>([])
const featuredExperiences = ref<Experience[]>([])
const searchDestination = ref('')

onMounted(async () => {
  try {
    const [propsRes, deptsRes, expsRes] = await Promise.all([
      getProperties({ page: 1 }),
      getDepartments(),
      getExperiences(),
    ])
    featuredProperties.value = propsRes.data.slice(0, 3)
    departments.value = deptsRes
    featuredExperiences.value = expsRes.slice(0, 3)
  } catch (err) {
    console.error('Error al cargar datos del home:', err)
  }
})
</script>

<template>
  <main class="flex-1 bg-surface-50">
    <!-- Hero Section -->
    <header class="relative bg-gradient-to-b from-brand-950 via-brand-900 to-slate-900 text-white overflow-hidden py-20 lg:py-28">
      <!-- Glow ambiental de fondo -->
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(14,165,233,0.18),transparent_50%)]"></div>
      <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-surface-50 to-transparent"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs sm:text-sm font-medium text-brand-100">
          <span>Garantía Oficial Anti-Fraudes</span>
          <span class="text-white/40">•</span>
          <BadgeVerified size="sm" />
        </div>

        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight max-w-4xl mx-auto leading-tight font-display">
          Descubre San Juan con <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-teal-300 to-emerald-400">Total Confianza</span>
        </h1>

        <p class="text-base sm:text-xl text-brand-100 max-w-2xl mx-auto font-normal leading-relaxed">
          Ofertas de alquileres y turismo 100% verificadas in situ por validadores técnicos. ¡Tu tranquilidad es nuestra prioridad!
        </p>

        <!-- Buscador Inteligente -->
        <div class="max-w-3xl mx-auto bg-white/95 backdrop-blur-xl p-3 sm:p-4 rounded-2xl shadow-2xl border border-white/30 text-surface-900 text-left">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
            <div class="px-3 py-2 border-b sm:border-b-0 sm:border-r border-slate-200">
              <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider">Destino</label>
              <select
                v-model="searchDestination"
                class="w-full bg-transparent text-sm font-medium text-surface-800 focus:outline-none cursor-pointer"
              >
                <option value="">Todos los departamentos</option>
                <option v-for="d in departments" :key="d.id" :value="d.slug">{{ d.name }}</option>
              </select>
            </div>
            <div class="px-3 py-2 border-b sm:border-b-0 sm:border-r border-slate-200">
              <label class="block text-xs font-semibold text-surface-500 uppercase tracking-wider">Certificación</label>
              <span class="text-sm font-medium text-safe-700 flex items-center gap-1">
                <ShieldCheck class="w-4 h-4 text-safe-600" />
                <span>100% Inspeccionado</span>
              </span>
            </div>
            <div>
              <RouterLink
                :to="{ path: '/alquileres', query: searchDestination ? { department: searchDestination } : {} }"
                class="w-full flex items-center justify-center gap-2 px-5 py-3 text-sm font-bold text-white bg-accent-500 hover:bg-accent-600 active:scale-95 rounded-xl shadow-md transition-all duration-200 cursor-pointer"
              >
                <Search class="w-4 h-4" />
                <span>Ver Alquileres</span>
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Propuesta de Valor / ¿Por qué Turismo Seguro? -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="text-center max-w-2xl mx-auto space-y-2 mb-12">
        <h2 class="text-3xl font-extrabold text-surface-900 font-display">
          ¿Por Qué <span class="text-brand-600">Turismo Seguro</span>?
        </h2>
        <p class="text-sm text-surface-500">
          Te ofrecemos más que un simple listado de propiedades. Te damos respaldo y paz mental.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Tarjeta 1 -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs text-center space-y-4 hover:shadow-md transition">
          <div class="w-14 h-14 rounded-2xl bg-safe-50 text-safe-600 mx-auto flex items-center justify-center">
            <CheckCircle class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-surface-900 font-display">Verificación In Situ</h3>
          <p class="text-xs text-surface-500 leading-relaxed">
            Cada propiedad y oferta es validada físicamente por un inspector técnico en San Juan. Cero publicaciones falsas o estafas.
          </p>
        </div>

        <!-- Tarjeta 2 -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs text-center space-y-4 hover:shadow-md transition">
          <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 mx-auto flex items-center justify-center">
            <Lock class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-surface-900 font-display">Acuerdos Transparentes</h3>
          <p class="text-xs text-surface-500 leading-relaxed">
            Precios regulados, contratos claros y comunicación directa con anfitriones cuyos datos de identidad están verificados.
          </p>
        </div>

        <!-- Tarjeta 3 -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs text-center space-y-4 hover:shadow-md transition">
          <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center">
            <MapPin class="w-8 h-8" />
          </div>
          <h3 class="text-lg font-bold text-surface-900 font-display">Conocimiento Local</h3>
          <p class="text-xs text-surface-500 leading-relaxed">
            Somos de San Juan. Conocemos cada rincón de los 19 departamentos y te asesoramos para que tu viaje sea perfecto.
          </p>
        </div>
      </div>
    </section>

    <!-- Alquileres Destacados (Datos Auténticos del Seeder) -->
    <section class="bg-white py-16 border-y border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-1.5 text-xs text-safe-700 font-bold uppercase tracking-wider mb-1">
              <Sparkles class="w-4 h-4 text-amber-500" />
              <span>Alojamientos Verificados</span>
            </div>
            <h2 class="text-3xl font-extrabold text-surface-900 font-display tracking-tight">
              Alquileres Destacados en San Juan
            </h2>
            <p class="text-sm text-surface-500 mt-1">
              Encontrá tu hogar temporal ideal con la seguridad oficial que merecés
            </p>
          </div>

          <RouterLink
            to="/alquileres"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline"
          >
            <span>Ver todos los alquileres</span>
            <ArrowRight class="w-4 h-4" />
          </RouterLink>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <PropertyCard
            v-for="property in featuredProperties"
            :key="property.id"
            :property="property"
          />
        </div>
      </div>
    </section>

    <!-- Explorar San Juan por Departamento (19 Departamentos Auténticos) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
      <div class="text-center max-w-2xl mx-auto space-y-2">
        <h2 class="text-3xl font-extrabold text-surface-900 font-display">
          Explorá San Juan <span class="text-brand-600">por Departamento</span>
        </h2>
        <p class="text-sm text-surface-500">
          Encontrá alojamientos auditados en tu zona preferida de la provincia
        </p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <RouterLink
          v-for="dept in departments"
          :key="dept.id"
          :to="`/alquileres?department=${dept.slug}`"
          class="group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition flex flex-col text-center"
        >
          <div class="aspect-4/3 bg-surface-100 overflow-hidden relative">
            <img
              :src="dept.hero_image || `/img/departamentos/${dept.slug}.jpg`"
              :alt="dept.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
          </div>
          <div class="p-3">
            <h4 class="text-xs font-bold text-surface-900 group-hover:text-brand-600 transition truncate">
              {{ dept.name }}
            </h4>
            <span class="text-[10px] text-surface-400 font-medium">
              {{ dept.properties_count ? `${dept.properties_count} ofertas` : 'Disponible' }}
            </span>
          </div>
        </RouterLink>
      </div>
    </section>

    <!-- Experiencias Turísticas -->
    <section class="bg-surface-100/70 py-16 border-t border-slate-200/80">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-1.5 text-xs text-brand-600 font-bold uppercase tracking-wider mb-1">
              <Compass class="w-4 h-4 text-brand-600" />
              <span>Turismo Activo y Naturaleza</span>
            </div>
            <h2 class="text-3xl font-extrabold text-surface-900 font-display tracking-tight">
              Experiencias Únicas y Seguras
            </h2>
            <p class="text-sm text-surface-500 mt-1">
              Descubrí los mejores destinos de San Juan con operadores acreditados
            </p>
          </div>

          <RouterLink
            to="/experiencias"
            class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline"
          >
            <span>Ver todas las experiencias</span>
            <ArrowRight class="w-4 h-4" />
          </RouterLink>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="exp in featuredExperiences"
            :key="exp.id"
            class="group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg transition flex flex-col"
          >
            <div class="aspect-16/10 relative overflow-hidden bg-surface-100">
              <img
                :src="exp.images && exp.images[0] ? exp.images[0].image_path : '/img/experiencies/placeholder.jpg'"
                :alt="exp.title"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
              />
              <div class="absolute top-3 left-3">
                <BadgeVerified size="sm" />
              </div>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
              <div>
                <span class="text-[11px] font-semibold text-brand-700 uppercase tracking-wider">{{ exp.category }}</span>
                <h3 class="font-bold text-surface-900 text-base leading-snug line-clamp-1 mt-0.5">
                  {{ exp.title }}
                </h3>
                <p class="text-xs text-surface-500 line-clamp-2 mt-2 leading-relaxed">
                  {{ exp.short_description }}
                </p>
              </div>
              <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <div>
                  <span class="text-[11px] text-surface-400 block font-medium">Por persona</span>
                  <p class="font-extrabold text-brand-900 text-base leading-tight">
                    ${{ exp.price_per_person.toLocaleString('es-AR') }}
                  </p>
                </div>
                <RouterLink
                  to="/experiencias"
                  class="px-3.5 py-1.5 text-xs font-semibold text-brand-600 bg-brand-50 hover:bg-brand-100 rounded-xl transition"
                >
                  Ver Detalle
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Proceso de Validación (Cómo Funciona) -->
    <section class="bg-brand-900 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 text-center">
        <div class="max-w-2xl mx-auto space-y-2">
          <h2 class="text-3xl font-extrabold font-display">
            Nuestro Proceso de <span class="text-safe-400">Validación In Situ</span>
          </h2>
          <p class="text-sm text-brand-200">
            Así garantizamos la seguridad de tu estadía paso a paso:
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="space-y-3 p-6 rounded-2xl bg-white/5 border border-white/10">
            <div class="w-12 h-12 rounded-xl bg-brand-700 text-brand-200 mx-auto flex items-center justify-center font-bold text-lg">
              1
            </div>
            <h3 class="font-bold text-base">Solicitud y Registro</h3>
            <p class="text-xs text-brand-200 leading-relaxed">
              El propietario sube la documentación y ubicación de su alojamiento en San Juan.
            </p>
          </div>

          <div class="space-y-3 p-6 rounded-2xl bg-white/5 border border-white/10">
            <div class="w-12 h-12 rounded-xl bg-safe-600 text-white mx-auto flex items-center justify-center font-bold text-lg">
              2
            </div>
            <h3 class="font-bold text-base">Inspección Presencial</h3>
            <p class="text-xs text-brand-200 leading-relaxed">
              Un validador matriculado visita físicamente el inmueble, corrobora fotos, matafuegos y comodidades.
            </p>
          </div>

          <div class="space-y-3 p-6 rounded-2xl bg-white/5 border border-white/10">
            <div class="w-12 h-12 rounded-xl bg-accent-500 text-white mx-auto flex items-center justify-center font-bold text-lg">
              3
            </div>
            <h3 class="font-bold text-base">Sello TS y Certificado QR</h3>
            <p class="text-xs text-brand-200 leading-relaxed">
              Se emite el certificado oficial de Turismo Seguro y la propiedad queda habilitada en el catálogo público.
            </p>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>
