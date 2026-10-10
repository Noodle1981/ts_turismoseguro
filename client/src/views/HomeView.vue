<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useCatalog } from '../composables/useCatalog'
import type { Property, Department, Experience } from '../types/catalog'
import PropertyCard from '../components/catalog/PropertyCard.vue'

const { getProperties, getDepartments, getExperiences } = useCatalog()

const featuredProperties = ref<Property[]>([])
const departments = ref<Department[]>([])
const featuredExperiences = ref<Experience[]>([])

// Buscador de Quintas y Turnos
const searchDepartment = ref('')
const selectedShift = ref<'dia' | 'tarde_noche'>('dia')
const guestCount = ref('20')

onMounted(async () => {
  try {
    const [propsRes, deptsRes, expsRes] = await Promise.all([
      getProperties({ page: 1 }),
      getDepartments(),
      getExperiences(),
    ])
    featuredProperties.value = propsRes.data.slice(0, 6)
    departments.value = deptsRes
    featuredExperiences.value = expsRes.slice(0, 3)
  } catch (err) {
    console.error('Error al cargar datos del portal:', err)
  }
})
</script>

<template>
  <main class="flex-1 bg-surface">
    <!-- Hero Section with Stitch Scrim and Inspection Badge -->
    <section class="relative w-full overflow-hidden bg-primary-container text-on-primary">
      <!-- Background Image with Overlay -->
      <div
        class="absolute inset-0 bg-cover bg-center mix-blend-overlay opacity-30"
        style="background-image: url('https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1600&q=80')"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-r from-primary via-primary-container/95 to-primary-container/80"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-12 lg:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          <!-- Hero Text -->
          <div class="lg:col-span-7 flex flex-col items-start gap-4">
            <div class="inline-flex items-center gap-1.5 bg-surface-container-lowest/15 backdrop-blur-md px-3.5 py-1.5 rounded-full text-secondary-fixed shadow-xs">
              <span class="material-symbols-outlined text-[18px]">verified_user</span>
              <span class="text-xs font-bold uppercase tracking-wider">Especialistas en alquileres para pasar el día</span>
            </div>

            <h1 class="font-headline-xl text-3xl sm:text-5xl lg:text-[46px] font-extrabold text-on-primary tracking-tight leading-tight max-w-2xl">
              Alquilá tu quinta o quincho para pasar el día sin riesgo de estafas en San Juan
            </h1>

            <p class="text-base sm:text-lg text-on-primary-container max-w-xl font-normal leading-relaxed">
              Verificamos cada pileta, quincho, servicios y anfitrión en persona antes de publicar. Mirá fotos reales, horarios de estadía y reservá con total tranquilidad.
            </p>

            <!-- Key Trust Signals -->
            <div class="grid grid-cols-3 gap-3 w-full max-w-lg pt-2">
              <div class="bg-surface-container-lowest/10 backdrop-blur-xs p-3 rounded-xl flex flex-col border border-white/10">
                <span class="font-headline-md text-2xl font-extrabold text-secondary-fixed">100%</span>
                <span class="text-[11px] text-on-primary-container font-medium">Inspeccionadas in situ</span>
              </div>
              <div class="bg-surface-container-lowest/10 backdrop-blur-xs p-3 rounded-xl flex flex-col border border-white/10">
                <span class="font-headline-md text-2xl font-extrabold text-tertiary-fixed">0</span>
                <span class="text-[11px] text-on-primary-container font-medium">Estafas registradas</span>
              </div>
              <div class="bg-surface-container-lowest/10 backdrop-blur-xs p-3 rounded-xl flex flex-col border border-white/10">
                <span class="font-headline-md text-2xl font-extrabold text-surface-bright">19 Dptos.</span>
                <span class="text-[11px] text-on-primary-container font-medium">Cobertura provincial</span>
              </div>
            </div>
          </div>

          <!-- Hero Feature Card: Inspección Activa en Vivo -->
          <div class="lg:col-span-5 relative">
            <div class="bg-surface-container-lowest text-on-surface rounded-2xl p-6 shadow-modal-stitch relative border border-surface-container">
              <div class="flex items-center justify-between pb-3">
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full bg-secondary animate-pulse"></span>
                  <span class="text-xs font-bold text-secondary uppercase tracking-wider">Auditoría Activa Hoy</span>
                </div>
                <span class="text-xs font-semibold text-on-surface-variant">Sello Oficial TS</span>
              </div>

              <div class="relative rounded-xl overflow-hidden mb-4">
                <img
                  class="w-full h-44 object-cover rounded-xl"
                  alt="Inspector técnico de Turismo Seguro en quinta con pileta"
                  src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80"
                />
                <div class="absolute bottom-3 left-3 bg-primary/90 backdrop-blur-md text-on-primary px-3 py-1 rounded-lg flex items-center gap-1.5 shadow-md">
                  <span class="material-symbols-outlined text-secondary-fixed text-[16px]">verified</span>
                  <span class="text-xs font-bold">Certificado de Seguridad Sanjuanino</span>
                </div>
              </div>

              <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between font-semibold">
                  <span class="text-on-surface-variant flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    Estado de agua de pileta
                  </span>
                  <span class="text-secondary font-bold">Filtrado • Clorado</span>
                </div>
                <div class="flex items-center justify-between font-semibold">
                  <span class="text-on-surface-variant flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    Protección para niños
                  </span>
                  <span class="text-secondary font-bold">Cerca perimetral OK</span>
                </div>
                <div class="flex items-center justify-between font-semibold">
                  <span class="text-on-surface-variant flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                    Freezer &amp; Heladeras
                  </span>
                  <span class="text-secondary font-bold">Operativas a 100%</span>
                </div>
              </div>

              <div class="mt-4 p-3 bg-surface-container rounded-lg flex items-center gap-2.5">
                <span class="material-symbols-outlined text-secondary text-[22px]">gpp_good</span>
                <p class="text-xs text-on-surface-variant font-medium leading-tight">
                  Garantía de devolución directa si la propiedad no coincide con lo certificado.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Interactive Day-Rental Search Hub -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 w-full -mt-6 sm:-mt-8 relative z-20">
      <div class="bg-surface-container-lowest rounded-2xl p-5 sm:p-6 shadow-modal-stitch border border-surface-container">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 mb-4 border-b border-surface-container gap-2">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[24px]">wb_sunny</span>
            <span class="font-headline-sm font-bold text-primary text-base sm:text-lg">
              Buscador para pasar el día en San Juan
            </span>
          </div>
          <span class="text-xs font-semibold text-on-surface-variant bg-surface-container px-3 py-1 rounded-full w-fit">
            Precios oficiales y transparentes en pesos
          </span>
        </div>

        <!-- Search Controls Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <!-- Departamento -->
          <div>
            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">
              Departamento / Zona
            </label>
            <div class="relative">
              <select
                v-model="searchDepartment"
                class="w-full bg-surface-container-low border border-surface-container hover:border-outline-variant text-on-surface text-sm rounded-lg px-3.5 py-2.5 focus:outline-none focus:border-primary font-medium cursor-pointer"
              >
                <option value="">Todos los departamentos</option>
                <option v-for="d in departments" :key="d.id" :value="d.slug">{{ d.name }}</option>
              </select>
            </div>
          </div>

          <!-- Selector de Turno (Stitch Segmented Control) -->
          <div>
            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">
              Turno deseado
            </label>
            <div class="grid grid-cols-2 gap-1 bg-surface-container-low p-1 rounded-lg border border-surface-container">
              <button
                type="button"
                :class="[
                  'px-2 py-1.5 rounded-md text-xs font-bold flex items-center justify-center gap-1 transition-all cursor-pointer',
                  selectedShift === 'dia'
                    ? 'bg-surface-container-lowest text-primary shadow-xs border border-primary/20'
                    : 'text-on-surface-variant hover:text-on-surface',
                ]"
                @click="selectedShift = 'dia'"
              >
                <span class="material-symbols-outlined text-[15px] text-amber-500">sunny</span>
                <span>Día (10-20h)</span>
              </button>
              <button
                type="button"
                :class="[
                  'px-2 py-1.5 rounded-md text-xs font-bold flex items-center justify-center gap-1 transition-all cursor-pointer',
                  selectedShift === 'tarde_noche'
                    ? 'bg-surface-container-lowest text-primary shadow-xs border border-primary/20'
                    : 'text-on-surface-variant hover:text-on-surface',
                ]"
                @click="selectedShift = 'tarde_noche'"
              >
                <span class="material-symbols-outlined text-[15px] text-primary">bedtime</span>
                <span>Noche (18-02h)</span>
              </button>
            </div>
          </div>

          <!-- Capacidad de Invitados -->
          <div>
            <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">
              Cantidad de personas
            </label>
            <select
              v-model="guestCount"
              class="w-full bg-surface-container-low border border-surface-container hover:border-outline-variant text-on-surface text-sm rounded-lg px-3.5 py-2.5 focus:outline-none focus:border-primary font-medium cursor-pointer"
            >
              <option value="15">Hasta 15 invitados (Familiar)</option>
              <option value="25">20 a 30 invitados (Cumpleaños)</option>
              <option value="50">40 a 60 invitados (Celebración)</option>
              <option value="100">80+ invitados (Gran evento)</option>
            </select>
          </div>

          <!-- CTA Button -->
          <div>
            <RouterLink
              :to="{ path: '/alquileres', query: searchDepartment ? { department: searchDepartment } : {} }"
              class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-on-primary bg-primary hover:bg-primary-container rounded-lg shadow-xs transition-colors cursor-pointer"
            >
              <span class="material-symbols-outlined text-[18px]">search</span>
              <span>Buscar Quintas</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Propuesta de Valor: Los 3 Pilares de Seguridad Oficial Stitch -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-16">
      <div class="text-center max-w-2xl mx-auto space-y-2 mb-10">
        <div class="inline-flex items-center gap-1 text-xs font-bold text-secondary uppercase tracking-wider">
          <span class="material-symbols-outlined text-[16px]">verified</span>
          <span>Garantía de Alquiler Seguro</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline-lg">
          ¿Por qué alquilar a través de Turismo Seguro?
        </h2>
        <p class="text-sm text-on-surface-variant">
          Protegemos tu dinero, tus vacaciones y tu evento familiar con inspecciones reales en territorio sanjuanino.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Pilar 1 -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-surface-container shadow-card-stitch space-y-3">
          <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">fact_check</span>
          </div>
          <h3 class="text-base font-bold text-primary font-headline-sm">Auditoría Presencial In Situ</h3>
          <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
            No publicamos por foto de WhatsApp. Un validador asiste físicamente, revisa el agua de la pileta, cerco de seguridad, baños y matafuegos.
          </p>
        </div>

        <!-- Pilar 2 -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-surface-container shadow-card-stitch space-y-3">
          <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">account_balance</span>
          </div>
          <h3 class="text-base font-bold text-primary font-headline-sm">Identidad Bancaria Cotejada</h3>
          <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
            Validamos el DNI del titular y el CBU oficial para señas. Evitás transferir a estafadores o cuentas puente anónimas.
          </p>
        </div>

        <!-- Pilar 3 -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-surface-container shadow-card-stitch space-y-3">
          <div class="w-12 h-12 rounded-xl bg-tertiary-fixed/40 text-on-tertiary-fixed flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">chat</span>
          </div>
          <h3 class="text-base font-bold text-primary font-headline-sm">Trato Directo sin Comisiones</h3>
          <p class="text-xs text-on-surface-variant leading-relaxed font-medium">
            Contactás al anfitrión directamente por WhatsApp con el mensaje pre-verificado de Turismo Seguro. Sin comisiones abusivas por reserva.
          </p>
        </div>
      </div>
    </section>

    <!-- Quintas y Quinchos Destacados -->
    <section class="bg-surface-container-lowest py-16 border-y border-surface-container">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-1.5 text-xs text-secondary font-bold uppercase tracking-wider mb-1">
              <span class="material-symbols-outlined text-[16px]">pool</span>
              <span>Quintas Verificadas</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline-lg">
              Quintas para pasar el día en San Juan
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
              Con pileta filtrada, quincho techado y cercas perimetrales para niños
            </p>
          </div>

          <RouterLink
            to="/alquileres"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-secondary transition-colors"
          >
            <span>Ver todas las quintas</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
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

    <!-- Zonas y Departamentos de San Juan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 py-16 space-y-8">
      <div class="text-center max-w-2xl mx-auto space-y-2">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline-lg">
          Zonas de Quintas en San Juan
        </h2>
        <p class="text-xs sm:text-sm text-on-surface-variant">
          Pocito, Rawson, Rivadavia, Santa Lucía, Zonda y Ullum con accesos verificados
        </p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
        <RouterLink
          v-for="dept in departments"
          :key="dept.id"
          :to="`/alquileres?department=${dept.slug}`"
          class="group bg-surface-container-lowest rounded-xl overflow-hidden border border-surface-container shadow-card-stitch hover:shadow-hover-stitch hover:-translate-y-0.5 transition flex flex-col text-center"
        >
          <div class="aspect-4/3 bg-surface-container overflow-hidden relative">
            <img
              :src="dept.hero_image || 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80'"
              :alt="dept.name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-transparent"></div>
            <div class="absolute bottom-2 inset-x-2 text-center">
              <span class="text-xs font-bold text-white drop-shadow-xs truncate block">
                {{ dept.name }}
              </span>
            </div>
          </div>
          <div class="p-2.5">
            <span class="text-[11px] text-on-surface-variant font-medium">
              {{ dept.properties_count ? `${dept.properties_count} ofertas` : 'Auditado TS' }}
            </span>
          </div>
        </RouterLink>
      </div>
    </section>

    <!-- Experiencias y Eventos Turísticos -->
    <section class="bg-surface-container-low py-16 border-t border-surface-container">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <div class="inline-flex items-center gap-1.5 text-xs text-primary font-bold uppercase tracking-wider mb-1">
              <span class="material-symbols-outlined text-[16px] text-secondary">explore</span>
              <span>Turismo Activo &amp; Eventos</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline-lg">
              Eventos y Experiencias en San Juan
            </h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-1">
              Astroturismo en Barreal, Diques Punta Negra y Ullum, Ruta del Vino y FNS
            </p>
          </div>

          <RouterLink
            to="/experiencias"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-secondary transition-colors"
          >
            <span>Ver todas las experiencias</span>
            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
          </RouterLink>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            v-for="exp in featuredExperiences"
            :key="exp.id"
            class="group bg-surface-container-lowest rounded-2xl overflow-hidden border border-surface-container shadow-card-stitch hover:shadow-hover-stitch transition flex flex-col"
          >
            <div class="aspect-16/10 relative overflow-hidden bg-surface-container">
              <img
                :src="exp.images && exp.images[0] ? exp.images[0].image_path : 'https://images.unsplash.com/photo-1518173946687-a4c8a383392e?auto=format&fit=crop&w=800&q=80'"
                :alt="exp.title"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
              />
              <div class="absolute top-3 left-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-surface-container-lowest/90 text-secondary border border-secondary/30 backdrop-blur-md">
                  <span class="material-symbols-outlined text-[13px]">verified</span>
                  <span>Operador Habilitado</span>
                </span>
              </div>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
              <div>
                <span class="text-[11px] font-bold text-secondary uppercase tracking-wider">{{ exp.category }}</span>
                <h3 class="font-bold text-primary text-base leading-snug line-clamp-1 mt-0.5">
                  {{ exp.title }}
                </h3>
                <p class="text-xs text-on-surface-variant line-clamp-2 mt-2 leading-relaxed font-medium">
                  {{ exp.short_description }}
                </p>
              </div>
              <div class="pt-3 border-t border-surface-container flex items-center justify-between">
                <div>
                  <span class="text-[10px] text-on-surface-variant block font-bold uppercase tracking-wider">Por persona</span>
                  <p class="font-extrabold text-primary text-base leading-tight">
                    ${{ exp.price_per_person.toLocaleString('es-AR') }}
                  </p>
                </div>
                <RouterLink
                  to="/experiencias"
                  class="px-3.5 py-2 text-xs font-bold text-primary bg-surface-container hover:bg-surface-dim rounded-lg transition"
                >
                  Ver Experiencia
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Bottom Institutional Banner -->
    <section class="bg-primary text-on-primary py-12 border-t border-primary-container">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
        <div>
          <h3 class="text-xl font-bold font-headline-sm">¿Sos dueño de una quinta o quincho en San Juan?</h3>
          <p class="text-xs text-on-primary-container mt-1 max-w-xl">
            Suma tu propiedad a la red oficial. Te auditamos sin costo durante el periodo de lanzamiento y recibís consultas directas a tu WhatsApp.
          </p>
        </div>
        <RouterLink
          to="/register-client"
          class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-secondary text-on-secondary hover:bg-secondary/90 font-bold text-sm shadow-safe-glow transition-all"
        >
          <span class="material-symbols-outlined text-[18px]">verified</span>
          <span>Solicitar Inspección In Situ</span>
        </RouterLink>
      </div>
    </section>
  </main>
</template>
