<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import { useSupervisorDictamenes } from '../composables/supervisor/useSupervisorDictamenes'

// Subcomponentes desacoplados (regla de vue-best-practices: composición delgada)
import SupervisorTopHeader from '../components/supervisor/SupervisorTopHeader.vue'
import SupervisorMetricsSection from '../components/supervisor/SupervisorMetricsSection.vue'
import SupervisorDocketsList from '../components/supervisor/SupervisorDocketsList.vue'
import SupervisorDetailPanel from '../components/supervisor/SupervisorDetailPanel.vue'

const router = useRouter()
const { logout } = useAuth()

const {
  user,
  dockets,
  metrics,
  isLoading,
  isResolving,
  actionFeedback,
  activeTab,
  searchQuery,
  selectedValidatorFilter,
  selectedDepartmentFilter,
  selectedDocketId,
  selectedDocket,
  filteredDockets,
  availableValidators,
  fetchDockets,
  resolveDocket,
  assignValidator,
} = useSupervisorDictamenes()

const handleLogout = async () => {
  await logout()
  router.push('/staff/login')
}

onMounted(() => {
  fetchDockets()
})
</script>

<template>
  <div class="bg-surface font-body-md text-on-surface min-h-screen flex flex-col">
    <!-- 1. HEADER SUPERVISOR (MISMO ESTILO PROPIETARIO TOP HEADER: H-16, BLUR, BOTONES TOKENS STITCH) -->
    <SupervisorTopHeader
      :user-name="user?.name"
      @logout="handleLogout"
    />

    <!-- 2. MAIN CONTAINER (pt-16 IDÉNTICO AL PANEL DE ANFITRIÓN) -->
    <main class="w-full pt-16 bg-surface flex-1">
      <div class="flex flex-col w-full">
        <!-- HEADER DE CONTEXTO TÉCNICO Y CREDENCIAL -->
        <section class="w-full bg-surface-container-low border-b border-surface-container px-4 sm:px-6 lg:px-8 py-5">
          <div class="max-w-7xl mx-auto flex flex-col gap-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
              <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary text-on-primary font-label-sm text-label-sm tracking-wider uppercase font-bold shadow-xs">
                  <span class="material-symbols-outlined text-[15px] text-secondary-fixed">shield_lock</span>
                  <span>MÓDULO DE SUPERVISIÓN &amp; DICTAMEN OFICIAL</span>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm font-semibold">
                  <span class="material-symbols-outlined text-[15px] text-secondary">verified</span>
                  <span>Nivel de Control: Aprobación Definitiva &amp; Firma Digital</span>
                </div>
              </div>

              <!-- Identificación oficial del Supervisor desde la DB -->
              <div v-if="user" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-surface-container-lowest border border-surface-container shadow-xs">
                <div class="w-2.5 h-2.5 rounded-full bg-secondary"></div>
                <span class="font-label-sm text-label-sm text-on-surface">
                  <strong>Supervisor a Cargo:</strong> {{ user.name }}
                </span>
                <span class="text-outline font-label-sm text-label-sm">•</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant font-mono font-bold">
                  SUP-SJ-{{ String(user.id).padStart(3, '0') }}
                </span>
              </div>
              <div v-else class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-surface-container-lowest border border-surface-container shadow-xs text-on-surface-variant text-xs font-medium">
                <span class="w-2 h-2 rounded-full bg-outline"></span>
                <span>Cargando identidad del supervisor...</span>
              </div>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
              <div class="max-w-3xl">
                <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight">
                  Mesa de Supervisión y Control de Dictámenes Técnicos
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1 leading-relaxed">
                  Revisión final de actas emitidas por validadores en terreno. Auditoría cruzada de evidencias fotográficas, geolocalización, titularidad bancaria y emisión del Sello Oficial QR San Juan Seguro.
                </p>
              </div>

              <div class="flex flex-wrap items-center gap-2 shrink-0">
                <button
                  type="button"
                  class="px-4 py-2.5 rounded-xl bg-surface-container-lowest text-primary hover:bg-surface-container transition-colors font-label-md text-label-md font-bold flex items-center gap-2 border border-surface-container shadow-xs cursor-pointer"
                  @click="fetchDockets"
                >
                  <span class="material-symbols-outlined text-[18px]" :class="isLoading ? 'animate-spin' : ''">refresh</span>
                  <span>Sincronizar DB</span>
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- 3. MÉTRICAS CLAVE EN TIEMPO REAL (100% CONSULTAS ELOQUENT DE LA DB) -->
        <SupervisorMetricsSection :metrics="metrics" />

        <!-- 4. BARRA DE FILTROS Y BÚSQUEDA -->
        <section class="w-full px-4 sm:px-6 lg:px-8 py-2">
          <div class="max-w-7xl mx-auto bg-surface-container-lowest p-4 rounded-2xl border border-surface-container shadow-xs flex flex-col gap-3">
            <div class="flex flex-col md:flex-row items-center gap-3">
              <!-- Search -->
              <div class="relative w-full md:flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input
                  v-model="searchQuery"
                  class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm focus:outline-none focus:bg-surface-container-lowest border border-surface-container transition-colors"
                  placeholder="Buscar por N° de Acta, Código, Quinta, Titular o DNI..."
                  type="text"
                />
              </div>

              <!-- Filtro Validador -->
              <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm w-full md:w-auto border border-surface-container">
                <span class="material-symbols-outlined text-outline text-[18px]">badge</span>
                <select
                  v-model="selectedValidatorFilter"
                  class="bg-transparent text-on-surface font-body-sm text-body-sm focus:outline-none cursor-pointer"
                >
                  <option value="all">Validador: Todos</option>
                  <option v-for="val in availableValidators" :key="val.id" :value="String(val.id)">
                    {{ val.name }}
                  </option>
                </select>
              </div>

              <!-- Filtro Departamento -->
              <div class="flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-container-low text-on-surface font-body-sm text-body-sm w-full md:w-auto border border-surface-container">
                <span class="material-symbols-outlined text-outline text-[18px]">location_on</span>
                <select
                  v-model="selectedDepartmentFilter"
                  class="bg-transparent text-on-surface font-body-sm text-body-sm focus:outline-none cursor-pointer"
                >
                  <option value="all">Departamento: Todos</option>
                  <option value="Rawson">Rawson / Médano</option>
                  <option value="Pocito">Pocito</option>
                  <option value="Zonda">Zonda</option>
                  <option value="Ullum">Ullum</option>
                  <option value="Rivadavia">Rivadavia</option>
                </select>
              </div>
            </div>

            <!-- Tabs de Estado -->
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-surface-container">
              <button
                type="button"
                class="px-3.5 py-1.5 rounded-xl font-label-md text-label-md flex items-center gap-2 cursor-pointer transition-colors"
                :class="activeTab === 'pending'
                  ? 'bg-primary text-on-primary font-bold shadow-xs'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                @click="activeTab = 'pending'"
              >
                <span>Por Dictaminar / En Cola</span>
                <span class="px-2 py-0.2 rounded-full bg-secondary-container text-on-secondary-container font-extrabold text-[11px]">
                  {{ metrics.pending }}
                </span>
              </button>

              <button
                type="button"
                class="px-3.5 py-1.5 rounded-xl font-label-md text-label-md flex items-center gap-2 cursor-pointer transition-colors"
                :class="activeTab === 'modifications'
                  ? 'bg-primary text-on-primary font-bold shadow-xs'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                @click="activeTab = 'modifications'"
              >
                <span>En Subsanación</span>
                <span class="px-2 py-0.2 rounded-full bg-surface-container-high text-on-surface font-bold text-[11px]">
                  {{ metrics.inProgress }}
                </span>
              </button>

              <button
                type="button"
                class="px-3.5 py-1.5 rounded-xl font-label-md text-label-md flex items-center gap-2 cursor-pointer transition-colors"
                :class="activeTab === 'approved'
                  ? 'bg-primary text-on-primary font-bold shadow-xs'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                @click="activeTab = 'approved'"
              >
                <span>Aprobadas Definitivas</span>
                <span class="px-2 py-0.2 rounded-full bg-surface-container-high text-on-surface font-bold text-[11px]">
                  {{ metrics.approved }}
                </span>
              </button>

              <button
                type="button"
                class="px-3.5 py-1.5 rounded-xl font-label-md text-label-md flex items-center gap-2 cursor-pointer transition-colors"
                :class="activeTab === 'rejected'
                  ? 'bg-primary text-on-primary font-bold shadow-xs'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                @click="activeTab = 'rejected'"
              >
                <span>Rechazadas Ratificadas</span>
                <span class="px-2 py-0.2 rounded-full bg-surface-container-high text-on-surface font-bold text-[11px]">
                  {{ metrics.rejected }}
                </span>
              </button>

              <button
                type="button"
                class="px-3.5 py-1.5 rounded-xl font-label-md text-label-md flex items-center gap-2 cursor-pointer transition-colors ml-auto"
                :class="activeTab === 'todos'
                  ? 'bg-primary text-on-primary font-bold shadow-xs'
                  : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container'"
                @click="activeTab = 'todos'"
              >
                <span>Ver Todos ({{ dockets.length }})</span>
              </button>
            </div>
          </div>
        </section>

        <!-- 5. FEEDBACK DINÁMICO DE ACCIÓN DEL SUPERVISOR -->
        <section v-if="actionFeedback" class="w-full px-4 sm:px-6 lg:px-8 py-2">
          <div
            class="max-w-7xl mx-auto p-4 rounded-2xl flex items-center justify-between gap-3 shadow-sm border"
            :class="actionFeedback.type === 'approve'
              ? 'bg-secondary-container/40 text-on-secondary-container border-secondary/30'
              : actionFeedback.type === 'modify'
              ? 'bg-amber-500/20 text-amber-900 dark:text-amber-200 border-amber-500/30'
              : 'bg-error-container text-on-error-container border-error/30'"
          >
            <div class="flex items-center gap-2.5">
              <span class="material-symbols-outlined text-[24px]">
                {{ actionFeedback.type === 'approve' ? 'verified' : actionFeedback.type === 'modify' ? 'hourglass_top' : 'block' }}
              </span>
              <span class="font-bold text-sm">{{ actionFeedback.message }}</span>
            </div>
            <button
              type="button"
              class="text-xs font-bold underline cursor-pointer"
              @click="actionFeedback = null"
            >
              Cerrar
            </button>
          </div>
        </section>

        <!-- 6. LAYOUT DIVIDIDO: BANDEJA (58%) vs. PANEL DICTAMEN (42%) -->
        <section class="w-full px-4 sm:px-6 lg:px-8 py-4 mb-12">
          <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- COLUMNA IZQUIERDA: LISTADO DESACOPLADO -->
            <div class="lg:col-span-7">
              <SupervisorDocketsList
                :dockets="filteredDockets"
                :selected-docket-id="selectedDocket?.id"
                @select="selectedDocketId = $event"
                @reset-filter="activeTab = 'todos'"
              />
            </div>

            <!-- COLUMNA DERECHA: PANEL DE DICTAMEN DESACOPLADO -->
            <div class="lg:col-span-5">
              <SupervisorDetailPanel
                :docket="selectedDocket"
                :is-resolving="isResolving"
                :available-validators="availableValidators"
                @resolve="(action, notes) => resolveDocket(action, notes)"
                @assign-validator="(valId, notes) => assignValidator(valId, notes)"
              />
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>
