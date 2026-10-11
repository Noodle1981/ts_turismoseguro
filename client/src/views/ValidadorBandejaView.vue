<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import { api } from '../composables/useApi'

const router = useRouter()
const { user, logout } = useAuth()

const isSupervisorOrAdmin = computed(() => {
  const role = user.value?.role
  return role === 'supervisor' || role === 'admin' || role === 'super_admin'
})

// Métricas de la bandeja
const metrics = ref({
  pending: 0,
  inProgress: 0,
  approved: 0,
  rejected: 0,
})

// Tab activo
type TabKey = 'todos' | 'pendientes' | 'proceso' | 'validados' | 'rechazados'
const activeTab = ref<TabKey>('todos')

// Filtros
const searchQuery = ref('')
const selectedDepartment = ref('Todos')
const onlyMyAssignments = ref(false)

// Solicitud seleccionada en el Drawer lateral
const selectedRequestId = ref<string>('')

interface InspectionRequest {
  id: string
  code: string
  propertyTitle: string
  department: string
  address: string
  ownerName: string
  ownerDni: string
  ownerPhone: string
  hoursAgo: string
  status: 'pending' | 'in_progress' | 'approved' | 'rejected'
  priority: 'urgent' | 'normal'
  photosCount: number
  imageUrl: string
  amenities: string[]
  progressPercentage?: number
  actCode?: string
  notes?: string
  validator_id?: number | null
  validator_name?: string | null
  hasLocalValidator?: boolean
  coverageAlert?: string | null
}

interface ValidatorItem {
  id: number
  name: string
  phone?: string
  origin_city?: string
}

const requests = ref<InspectionRequest[]>([])
const availableValidators = ref<ValidatorItem[]>([])
const isLoading = ref(false)
const isAssigning = ref(false)
const selectedAssignValidatorId = ref<number | ''>('')
const assignSuccessMsg = ref<string | null>(null)

// Estados para Sincronizar y Registrar Solicitud Manual
const isSyncing = ref(false)
const showManualModal = ref(false)
const syncSuccess = ref(false)

const fetchRequests = async () => {
  isLoading.value = true
  try {
    const params: Record<string, any> = {}
    if (activeTab.value !== 'todos') {
      params.status = activeTab.value
    }
    if (selectedDepartment.value !== 'Todos') {
      params.department = selectedDepartment.value
    }
    if (onlyMyAssignments.value) {
      params.only_my_assignments = true
    }

    const res = await api.get('/staff/verifications', { params })
    const data = res.data.data || []

    requests.value = data.map((item: any): InspectionRequest => {
      let uiStatus: 'pending' | 'in_progress' | 'approved' | 'rejected' = 'pending'
      if (item.status === 'assigned' || item.status === 'visited' || item.status === 'under_supervision') {
        uiStatus = 'in_progress'
      } else if (item.status === 'approved') {
        uiStatus = 'approved'
      } else if (item.status === 'rejected') {
        uiStatus = 'rejected'
      }

      return {
        id: String(item.id),
        code: item.code || `SOL-TS-${item.id}`,
        propertyTitle: item.propertyTitle,
        department: item.department,
        address: item.address,
        ownerName: item.ownerName,
        ownerDni: item.ownerDni,
        ownerPhone: item.ownerPhone,
        hoursAgo: item.hoursAgo,
        status: uiStatus,
        priority: item.priority || 'normal',
        photosCount: item.photosCount || 0,
        imageUrl: item.imageUrl || 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
        amenities: item.amenities || [],
        notes: item.notes || 'Inspección de seguridad in situ.',
        validator_id: item.validator_id,
        validator_name: item.validator_name,
        hasLocalValidator: item.hasLocalValidator ?? true,
        coverageAlert: item.coverageAlert ?? null,
      }
    })

    if (res.data.metrics) {
      metrics.value = {
        pending: res.data.metrics.pending ?? 0,
        inProgress: res.data.metrics.inProgress ?? 0,
        approved: res.data.metrics.approved ?? 0,
        rejected: res.data.metrics.rejected ?? 0,
      }
    }

    if (res.data.available_validators) {
      availableValidators.value = res.data.available_validators
    }

    if (requests.value.length > 0 && !requests.value.some(r => r.id === selectedRequestId.value)) {
      selectedRequestId.value = requests.value[0].id
    }
  } catch (err) {
    console.warn('Error fetching verifications from API, keeping local fallback:', err)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchRequests()
})

// Solicitudes filtradas
const filteredRequests = computed(() => {
  return requests.value.filter((req) => {
    // Tab filter
    if (activeTab.value === 'pendientes' && req.status !== 'pending') return false
    if (activeTab.value === 'proceso' && req.status !== 'in_progress') return false
    if (activeTab.value === 'validados' && req.status !== 'approved') return false
    if (activeTab.value === 'rechazados' && req.status !== 'rejected') return false

    // Department filter
    if (selectedDepartment.value !== 'Todos' && req.department !== selectedDepartment.value) return false

    // Search query
    if (searchQuery.value.trim()) {
      const q = searchQuery.value.toLowerCase()
      const matchTitle = req.propertyTitle.toLowerCase().includes(q)
      const matchOwner = req.ownerName.toLowerCase().includes(q)
      const matchCode = req.code.toLowerCase().includes(q)
      const matchDept = req.department.toLowerCase().includes(q)
      if (!matchTitle && !matchOwner && !matchCode && !matchDept) return false
    }

    return true
  })
})

const selectedRequest = computed<InspectionRequest | null>(() => {
  return requests.value.find((r) => r.id === selectedRequestId.value) ?? requests.value[0] ?? null
})

const handleSelectRequest = (id: string) => {
  selectedRequestId.value = id
  assignSuccessMsg.value = null
  selectedAssignValidatorId.value = ''
}

const handleStartActa = (request: InspectionRequest) => {
  router.push(`/validador/acta/${request.id}`)
}

const handleAssignValidator = async () => {
  if (!selectedRequest.value || !selectedAssignValidatorId.value) return
  isAssigning.value = true
  assignSuccessMsg.value = null
  try {
    const res = await api.put(`/staff/verifications/${selectedRequest.value.id}/assign`, {
      validator_id: Number(selectedAssignValidatorId.value),
      notes: 'Validador designado desde la Mesa de Supervisión.',
    })
    assignSuccessMsg.value = res.data.message || 'Validador asignado correctamente.'
    await fetchRequests()
  } catch (err: any) {
    alert(err?.response?.data?.message || 'Error al asignar validador.')
  } finally {
    isAssigning.value = false
  }
}

const handleSync = async () => {
  isSyncing.value = true
  syncSuccess.value = false
  await fetchRequests()
  isSyncing.value = false
  syncSuccess.value = true
  setTimeout(() => {
    syncSuccess.value = false
  }, 2500)
}

const newManualRequest = ref({
  propertyTitle: '',
  department: 'Rawson',
  ownerName: '',
  ownerPhone: '',
  ownerDni: '',
  notes: '',
})

const handleCreateManualRequest = () => {
  if (!newManualRequest.value.propertyTitle || !newManualRequest.value.ownerName) {
    alert('Por favor complete el nombre de la propiedad y del titular.')
    return
  }

  const generatedCode = `SOL-2026-${Math.floor(1000 + Math.random() * 9000)}`
  const created: InspectionRequest = {
    id: generatedCode,
    code: generatedCode,
    propertyTitle: newManualRequest.value.propertyTitle,
    department: newManualRequest.value.department,
    address: 'Ubicación registrada in situ (GPS pendiente)',
    ownerName: newManualRequest.value.ownerName,
    ownerDni: newManualRequest.value.ownerDni || 'En verificación',
    ownerPhone: newManualRequest.value.ownerPhone || '+54 9 264 400-0000',
    hoursAgo: 'Recién creada',
    status: 'pending',
    priority: 'normal',
    photosCount: 0,
    imageUrl: 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80',
    amenities: ['Carga presencial de campo'],
    notes: newManualRequest.value.notes || 'Solicitud generada manualmente por el inspector en territorio.',
  }

  requests.value.unshift(created)
  selectedRequestId.value = created.id
  showManualModal.value = false

  // Reset form
  newManualRequest.value = {
    propertyTitle: '',
    department: 'Rawson',
    ownerName: '',
    ownerPhone: '',
    ownerDni: '',
    notes: '',
  }
}

const handleLogout = async () => {
  await logout()
  router.push('/staff/login')
}
</script>

<template>
  <main class="w-full bg-surface min-h-screen py-6 px-4 sm:px-6 lg:px-12">
    <div class="max-w-7xl mx-auto flex flex-col gap-6">

      <!-- 1. HEADER OPERATIVO DEL VALIDADOR -->
      <header class="bg-surface-container-lowest rounded-2xl p-6 sm:p-8 shadow-sm border border-surface-container flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex flex-col gap-2 max-w-3xl">
          <div class="flex flex-wrap items-center gap-2">
            <span class="px-2.5 py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-label-sm uppercase tracking-wider font-bold">
              Módulo Fiscalizador Oficial
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-fixed/50 text-on-secondary-fixed font-label-sm text-label-sm font-bold">
              <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
              En Guardia Operativa In Situ
            </span>
          </div>

          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-extrabold">
            Bandeja Operativa de Solicitudes
          </h1>
          <p class="font-body-md text-body-md text-on-surface-variant">
            Gestión centralizada de inmuebles recreativos en San Juan. Visitas presenciales, cotejo de seguridad y validación de identidad y CBU antifraude.
          </p>

          <div class="flex items-center gap-2 pt-2 font-label-md text-label-md text-primary">
            <span class="material-symbols-outlined text-secondary text-[20px]">badge</span>
            <span class="font-bold">Validador Asignado:</span>
            <span>{{ user?.name ?? 'Matías Fernández (Validador Matriculado)' }}</span>
            <span class="text-outline">•</span>
            <span class="text-on-surface-variant">San Juan (Rawson, Pocito, Zonda, Rivadavia)</span>
          </div>
        </div>

        <!-- Action Group oficial de Stitch: Sincronizar Solicitudes & Registrar Solicitud Manual -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 self-start lg:self-center">
          <button
            type="button"
            class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md font-bold transition-all border border-surface-container shadow-xs cursor-pointer"
            :disabled="isSyncing"
            @click="handleSync"
          >
            <span
              class="material-symbols-outlined text-[20px] transition-transform duration-500"
              :class="{ 'animate-spin': isSyncing }"
            >
              sync
            </span>
            <span>{{ isSyncing ? 'Sincronizando...' : syncSuccess ? '¡Sincronizado!' : 'Sincronizar Solicitudes' }}</span>
          </button>

          <button
            type="button"
            class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-bold transition-all shadow-md cursor-pointer"
            @click="showManualModal = true"
          >
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            <span>+ Registrar Solicitud Manual</span>
          </button>

          <button
            type="button"
            class="p-2.5 rounded-xl text-on-surface-variant hover:text-error hover:bg-surface-container transition-colors cursor-pointer border border-surface-container hidden sm:inline-flex items-center justify-center"
            title="Cerrar sesión"
            @click="handleLogout"
          >
            <span class="material-symbols-outlined text-[20px]">logout</span>
          </button>
        </div>
      </header>

      <!-- 2. MÉTRICAS OPERATIVAS (BENTO CARDS) -->
      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <span class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant font-bold">Nuevas Pendientes</span>
            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary">
              <span class="material-symbols-outlined text-[18px]">inbox</span>
            </div>
          </div>
          <div class="my-3">
            <span class="font-headline-xl text-headline-xl text-primary font-extrabold">{{ metrics.pending }}</span>
            <span class="font-body-sm text-body-sm text-on-surface-variant ml-1.5">solicitudes</span>
          </div>
          <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm pt-2 border-t border-surface-container">
            <span class="px-2 py-0.5 rounded-full bg-secondary-fixed/50 text-on-secondary-fixed font-bold">+4 hoy</span>
            <span>Espera prom: 18 hs</span>
          </div>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <span class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant font-bold">Visitas en Proceso</span>
            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
              <span class="material-symbols-outlined text-[18px]">event_upcoming</span>
            </div>
          </div>
          <div class="my-3">
            <span class="font-headline-xl text-headline-xl text-primary font-extrabold">{{ metrics.inProgress }}</span>
            <span class="font-body-sm text-body-sm text-on-surface-variant ml-1.5">quintas</span>
          </div>
          <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm pt-2 border-t border-surface-container">
            <span class="px-2 py-0.5 rounded-full bg-primary-fixed text-primary font-bold">4 para hoy</span>
            <span>Acta digital activa</span>
          </div>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <span class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant font-bold">Validadas / Publicadas</span>
            <div class="w-8 h-8 rounded-lg bg-secondary-fixed/50 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-[18px]">verified</span>
            </div>
          </div>
          <div class="my-3">
            <span class="font-headline-xl text-headline-xl text-secondary font-extrabold">{{ metrics.approved }}</span>
            <span class="font-body-sm text-body-sm text-on-surface-variant ml-1.5">actas emitidas</span>
          </div>
          <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm pt-2 border-t border-surface-container">
            <span class="text-secondary font-bold">82% efectividad</span>
            <span>0 denuncias</span>
          </div>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-2xl shadow-xs border border-surface-container flex flex-col justify-between">
          <div class="flex items-center justify-between">
            <span class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant font-bold">Observadas / A Subsanar</span>
            <div class="w-8 h-8 rounded-lg bg-error-container/40 flex items-center justify-center text-error">
              <span class="material-symbols-outlined text-[18px]">gpp_bad</span>
            </div>
          </div>
          <div class="my-3">
            <span class="font-headline-xl text-headline-xl text-error font-extrabold">{{ metrics.rejected }}</span>
            <span class="font-body-sm text-body-sm text-on-surface-variant ml-1.5">en revisión</span>
          </div>
          <div class="flex items-center justify-between text-on-surface-variant font-label-sm text-label-sm pt-2 border-t border-surface-container">
            <span class="text-error font-bold">CBU o Documentación</span>
            <span>Plazo: 72 hs</span>
          </div>
        </div>
      </section>

      <!-- 3. TABS Y FILTROS -->
      <section class="flex flex-col gap-3">
        <!-- Tabs -->
        <div class="flex flex-wrap items-center gap-1.5 bg-surface-container-low p-1.5 rounded-xl border border-surface-container">
          <button
            type="button"
            :class="[
              'px-3.5 py-2 rounded-lg font-label-md text-label-md font-bold transition-all flex items-center gap-1.5 cursor-pointer',
              activeTab === 'todos' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
            ]"
            @click="activeTab = 'todos'"
          >
            <span>Todas</span>
            <span class="px-1.5 py-0.2 rounded-full text-[11px]" :class="activeTab === 'todos' ? 'bg-white/20' : 'bg-surface-container'">
              {{ requests.length }}
            </span>
          </button>

          <button
            type="button"
            :class="[
              'px-3.5 py-2 rounded-lg font-label-md text-label-md font-bold transition-all flex items-center gap-1.5 cursor-pointer',
              activeTab === 'pendientes' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
            ]"
            @click="activeTab = 'pendientes'"
          >
            <span class="material-symbols-outlined text-[16px]">pending_actions</span>
            <span>Pendientes por Visitar</span>
            <span class="px-1.5 py-0.2 rounded-full text-[11px]" :class="activeTab === 'pendientes' ? 'bg-white/20' : 'bg-surface-container'">
              12
            </span>
          </button>

          <button
            type="button"
            :class="[
              'px-3.5 py-2 rounded-lg font-label-md text-label-md font-bold transition-all flex items-center gap-1.5 cursor-pointer',
              activeTab === 'proceso' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
            ]"
            @click="activeTab = 'proceso'"
          >
            <span class="material-symbols-outlined text-[16px]">fact_check</span>
            <span>En Proceso</span>
            <span class="px-1.5 py-0.2 rounded-full text-[11px]" :class="activeTab === 'proceso' ? 'bg-white/20' : 'bg-surface-container'">
              8
            </span>
          </button>

          <button
            type="button"
            :class="[
              'px-3.5 py-2 rounded-lg font-label-md text-label-md font-bold transition-all flex items-center gap-1.5 cursor-pointer',
              activeTab === 'validados' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
            ]"
            @click="activeTab = 'validados'"
          >
            <span class="material-symbols-outlined text-[16px]">verified</span>
            <span>Validadas & Aprobadas</span>
          </button>

          <button
            type="button"
            :class="[
              'px-3.5 py-2 rounded-lg font-label-md text-label-md font-bold transition-all flex items-center gap-1.5 cursor-pointer',
              activeTab === 'rechazados' ? 'bg-primary text-on-primary shadow-xs' : 'text-on-surface-variant hover:text-on-surface'
            ]"
            @click="activeTab = 'rechazados'"
          >
            <span class="material-symbols-outlined text-[16px]">gpp_bad</span>
            <span>Observadas</span>
          </button>
        </div>

        <!-- Barra de Búsqueda y Filtros de Departamento -->
        <div class="bg-surface-container-lowest p-3 rounded-xl border border-surface-container flex flex-col md:flex-row items-center justify-between gap-3 shadow-xs">
          <div class="relative w-full md:w-96">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar por quinta, propietario o código (SOL-2025)..."
              class="w-full pl-10 pr-4 py-2 rounded-lg bg-surface-container-low text-on-surface placeholder:text-on-surface-variant font-body-sm text-body-sm focus:outline-none focus:ring-1 focus:ring-primary border border-surface-container"
            />
          </div>

          <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <select
              v-model="selectedDepartment"
              class="bg-surface-container-low text-on-surface font-label-md text-label-md py-2 px-3 rounded-lg border border-surface-container focus:outline-none cursor-pointer"
            >
              <option value="Todos">Departamento: Todos</option>
              <option value="Rawson">Rawson / Médano</option>
              <option value="Pocito">Pocito</option>
              <option value="Zonda">Zonda</option>
              <option value="Rivadavia">Rivadavia</option>
              <option value="Ullum">Ullum</option>
            </select>

            <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg bg-surface-container-low hover:bg-surface-container border border-surface-container">
              <input v-model="onlyMyAssignments" type="checkbox" class="accent-primary cursor-pointer" />
              <span class="font-label-md text-label-md text-on-surface font-bold">Mis asignaciones</span>
            </label>
          </div>
        </div>
      </section>

      <!-- 4. WORKSPACE EN 2 COLUMNAS: BANDEJA (7 COLS) + DRAWER RESUMEN (5 COLS) -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LISTA DE SOLICITUDES (7 COLS) -->
        <section class="lg:col-span-7 flex flex-col gap-4">
          <article
            v-for="req in filteredRequests"
            :key="req.id"
            :class="[
              'bg-surface-container-lowest p-5 rounded-2xl border transition-all flex flex-col gap-4 cursor-pointer relative overflow-hidden',
              selectedRequestId === req.id
                ? 'border-primary ring-2 ring-primary/20 shadow-md'
                : 'border-surface-container hover:shadow-sm'
            ]"
            @click="handleSelectRequest(req.id)"
          >
            <!-- Header Card -->
            <div class="flex flex-wrap items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <span class="font-label-sm text-label-sm px-2.5 py-0.5 rounded-full bg-surface-container font-mono font-bold text-primary">
                  {{ req.code }}
                </span>
                <span class="text-on-surface-variant font-label-sm text-label-sm">{{ req.hoursAgo }}</span>
                <span v-if="req.priority === 'urgent'" class="px-2 py-0.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed font-label-sm text-label-sm font-bold flex items-center gap-1">
                  <span class="material-symbols-outlined text-[13px]">priority_high</span> Urgente
                </span>
              </div>

              <!-- Status & Alert Badges -->
              <div class="flex flex-wrap items-center gap-1.5">
                <span
                  v-if="req.coverageAlert"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 font-label-sm text-label-sm font-bold border border-amber-500/30"
                  title="Falta de cobertura en este departamento"
                >
                  <span class="material-symbols-outlined text-[15px] text-amber-600">warning</span>
                  Sin Validador Local
                </span>

                <span
                  v-if="req.validator_name"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-primary/10 text-primary font-label-sm text-label-sm font-semibold"
                >
                  <span class="material-symbols-outlined text-[14px]">badge</span>
                  {{ req.validator_name }}
                </span>

                <span
                  v-if="req.status === 'pending'"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-tertiary-container/20 text-tertiary font-label-sm text-label-sm font-bold"
                >
                  <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                  Pendiente de Visita
                </span>
                <span
                  v-else-if="req.status === 'in_progress'"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-primary font-label-sm text-label-sm font-bold"
                >
                  <span class="material-symbols-outlined text-[14px] text-secondary">sync</span>
                  En Curso ({{ req.progressPercentage ?? 50 }}%)
                </span>
                <span
                  v-else-if="req.status === 'approved'"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-fixed/50 text-secondary font-label-sm text-label-sm font-bold"
                >
                  <span class="material-symbols-outlined text-[14px]">verified</span>
                  Auditada & Publicada
                </span>
                <span
                  v-else
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold"
                >
                  <span class="material-symbols-outlined text-[14px]">error</span>
                  Observada
                </span>
              </div>
            </div>

            <!-- Banner Alerta de Cobertura en la tarjeta si aplica -->
            <div
              v-if="req.coverageAlert"
              class="px-3.5 py-2 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-300 dark:border-amber-800 flex items-center gap-2 text-amber-800 dark:text-amber-300 text-xs font-semibold"
            >
              <span class="material-symbols-outlined text-[18px] text-amber-600 shrink-0">emergency_home</span>
              <span>{{ req.coverageAlert }}</span>
            </div>

            <!-- Content Body -->
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="w-full sm:w-32 h-24 rounded-xl overflow-hidden shrink-0 bg-surface-container relative">
                <img :src="req.imageUrl" :alt="req.propertyTitle" class="w-full h-full object-cover" />
                <span class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded bg-primary/80 backdrop-blur text-on-primary font-label-sm text-label-sm text-[10px]">
                  {{ req.photosCount }} fotos
                </span>
              </div>

              <div class="flex flex-col gap-1 flex-1">
                <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
                  {{ req.propertyTitle }}
                </h3>
                <p class="font-body-sm text-body-sm text-on-surface font-semibold flex items-center gap-1">
                  <span class="material-symbols-outlined text-[16px] text-on-surface-variant">person</span>
                  {{ req.ownerName }} <span class="text-on-surface-variant font-normal">(DNI {{ req.ownerDni }})</span>
                </p>
                <p class="font-body-sm text-body-sm text-on-surface-variant flex items-center gap-1">
                  <span class="material-symbols-outlined text-[16px]">location_on</span>
                  {{ req.address }}
                </p>

                <!-- Amenities chips -->
                <div class="flex flex-wrap items-center gap-1 pt-1.5">
                  <span
                    v-for="am in req.amenities"
                    :key="am"
                    class="px-2 py-0.5 rounded-full bg-surface-container-low text-on-surface font-label-sm text-label-sm text-[11px] border border-surface-container"
                  >
                    {{ am }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Card Actions -->
            <div class="pt-2 border-t border-surface-container flex flex-wrap items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <a
                  :href="`https://wa.me/${req.ownerPhone.replace(/[^0-9]/g, '')}`"
                  target="_blank"
                  class="px-3 py-1.5 rounded-lg bg-surface-container-low hover:bg-surface-container text-primary font-label-md text-label-md flex items-center gap-1 transition-colors text-xs font-semibold"
                  @click.stop
                >
                  <span class="material-symbols-outlined text-[15px] text-secondary">chat</span> WhatsApp Dueño
                </a>
              </div>

              <button
                type="button"
                :class="[
                  'px-4 py-2 rounded-xl font-label-md text-label-md font-bold flex items-center gap-1.5 transition-all shadow-xs cursor-pointer',
                  req.status === 'in_progress' ? 'bg-primary text-on-primary hover:bg-primary-container' : 'bg-secondary text-on-secondary hover:bg-secondary/90'
                ]"
                @click.stop="handleStartActa(req)"
              >
                <span class="material-symbols-outlined text-[18px]">
                  {{ req.status === 'in_progress' ? 'edit_note' : 'verified_user' }}
                </span>
                <span>{{ req.status === 'in_progress' ? 'Continuar Acta In Situ' : 'Iniciar Acta In Situ' }}</span>
              </button>
            </div>
          </article>
        </section>

        <!-- DRAWER RESUMEN RÁPIDO DE SOLICITUD SELECCIONADA (5 COLS) -->
        <aside class="lg:col-span-5 sticky top-6 bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-surface-container flex flex-col gap-5">
          <div v-if="selectedRequest" class="flex flex-col gap-5">
            <div class="flex items-center justify-between pb-3 border-b border-surface-container">
              <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary text-on-primary flex items-center justify-center">
                  <span class="material-symbols-outlined text-[18px]">assignment</span>
                </div>
                <div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Inspección In Situ</span>
                  <h2 class="font-headline-sm text-headline-sm text-primary font-bold">
                    {{ selectedRequest.propertyTitle }}
                  </h2>
                </div>
              </div>

              <span class="font-mono text-xs font-bold text-secondary bg-secondary-fixed/50 px-2 py-0.5 rounded-full">
                {{ selectedRequest.code }}
              </span>
            </div>

            <!-- Imagen y Datos del Predio -->
            <div class="rounded-xl overflow-hidden h-40 bg-surface-container relative">
              <img :src="selectedRequest.imageUrl" :alt="selectedRequest.propertyTitle" class="w-full h-full object-cover" />
              <div class="absolute bottom-2 left-2 bg-primary/80 backdrop-blur px-2.5 py-1 rounded-lg text-white font-label-sm text-label-sm font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">pin_drop</span>
                {{ selectedRequest.department }}, San Juan
              </div>
            </div>

            <!-- Datos del Propietario -->
            <div class="p-3.5 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-1.5">
              <span class="font-label-sm text-label-sm text-on-surface-variant uppercase font-bold">Titular Declarado</span>
              <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ selectedRequest.ownerName }}</span>
              <span class="font-body-sm text-body-sm text-on-surface-variant">DNI: {{ selectedRequest.ownerDni }}</span>
              <span class="font-body-sm text-body-sm text-secondary font-semibold flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">call</span> {{ selectedRequest.ownerPhone }}
              </span>
            </div>

            <!-- Notas y Puntos Clave -->
            <div class="flex flex-col gap-1.5">
              <span class="font-label-sm text-label-sm text-on-surface-variant uppercase font-bold">Instrucciones de Auditoría</span>
              <p class="font-body-sm text-body-sm text-on-surface p-3 rounded-xl bg-surface-container-low border border-surface-container">
                {{ selectedRequest.notes }}
              </p>
            </div>

            <!-- MESA DE SUPERVISIÓN: ASIGNACIÓN DE VALIDADOR MATRICULADO -->
            <div
              v-if="isSupervisorOrAdmin"
              class="p-4 rounded-xl bg-surface-container-low border border-primary/20 flex flex-col gap-3 shadow-xs"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5 font-label-md text-label-md font-bold text-primary">
                  <span class="material-symbols-outlined text-[18px] text-secondary">admin_panel_settings</span>
                  <span>Despacho de Supervisión</span>
                </div>
                <span
                  v-if="selectedRequest.validator_name"
                  class="px-2 py-0.5 rounded-full bg-secondary-fixed/50 text-secondary text-[11px] font-bold"
                >
                  Asignado
                </span>
                <span
                  v-else
                  class="px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700 text-[11px] font-bold"
                >
                  Sin asignar
                </span>
              </div>

              <div v-if="selectedRequest.coverageAlert" class="p-2.5 rounded-lg bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-300 text-xs flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-amber-600 mt-0.5 shrink-0">info</span>
                <span><strong>Alerta Departamental:</strong> Designe a un validador con cobertura limítrofe para realizar el traslado in situ.</span>
              </div>

              <div class="flex flex-col gap-1.5">
                <label class="font-label-sm text-label-sm text-on-surface font-semibold">
                  Designar Validador Matriculado:
                </label>
                <div class="flex items-center gap-2">
                  <select
                    v-model="selectedAssignValidatorId"
                    class="flex-1 px-3 py-2 rounded-lg bg-surface-container-lowest border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary"
                  >
                    <option value="">Seleccione un inspector...</option>
                    <option
                      v-for="val in availableValidators"
                      :key="val.id"
                      :value="val.id"
                    >
                      {{ val.name }} ({{ val.origin_city ?? 'San Juan' }})
                    </option>
                  </select>

                  <button
                    type="button"
                    class="px-3.5 py-2 rounded-lg bg-primary hover:bg-primary-container text-on-primary font-label-sm text-label-sm font-bold disabled:opacity-50 transition-all cursor-pointer flex items-center gap-1"
                    :disabled="!selectedAssignValidatorId || isAssigning"
                    @click="handleAssignValidator"
                  >
                    <span v-if="isAssigning" class="material-symbols-outlined text-[16px] animate-spin">sync</span>
                    <span>{{ isAssigning ? 'Designando...' : 'Asignar' }}</span>
                  </button>
                </div>

                <p v-if="assignSuccessMsg" class="text-secondary text-xs font-bold pt-1 flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">check_circle</span>
                  {{ assignSuccessMsg }}
                </p>
              </div>
            </div>

            <!-- Botón de apertura de Acta -->
            <button
              type="button"
              class="w-full py-3.5 px-4 rounded-xl bg-secondary text-on-secondary font-label-md text-label-md hover:bg-secondary/90 transition-all font-bold shadow-md cursor-pointer flex items-center justify-center gap-2"
              @click="handleStartActa(selectedRequest)"
            >
              <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
              <span>Completar Acta de Inspección In Situ</span>
            </button>
          </div>

          <div v-else class="text-center py-12 text-on-surface-variant font-body-sm text-body-sm">
            <span class="material-symbols-outlined text-[36px] text-on-surface-variant/40 mb-2 block">touch_app</span>
            <span>Seleccione una solicitud para ver los detalles e iniciar la inspección.</span>
          </div>
        </aside>

      </div>
    </div>

    <!-- MODAL: REGISTRAR SOLICITUD MANUAL -->
    <div
      v-if="showManualModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4"
    >
      <div class="bg-surface-container-lowest rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-surface-container flex flex-col gap-4">
        <div class="flex items-center justify-between pb-2 border-b border-surface-container">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[22px]">add_circle</span>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Registrar Solicitud Manual</h3>
          </div>
          <button
            type="button"
            class="p-1 rounded-lg text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors cursor-pointer"
            @click="showManualModal = false"
          >
            <span class="material-symbols-outlined text-[20px]">close</span>
          </button>
        </div>

        <form class="flex flex-col gap-3.5" @submit.prevent="handleCreateManualRequest">
          <div class="flex flex-col gap-1">
            <label class="font-label-sm text-label-sm font-bold text-on-surface">Nombre de la Quinta o Propiedad</label>
            <input
              v-model="newManualRequest.propertyTitle"
              type="text"
              required
              placeholder="Ej: Cabaña Los Álamos"
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="font-label-sm text-label-sm font-bold text-on-surface">Departamento</label>
              <select
                v-model="newManualRequest.department"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary"
              >
                <option value="Rawson">Rawson</option>
                <option value="Pocito">Pocito</option>
                <option value="Zonda">Zonda</option>
                <option value="Rivadavia">Rivadavia</option>
                <option value="Ullum">Ullum</option>
                <option value="Caucete">Caucete</option>
                <option value="San Martín">San Martín</option>
              </select>
            </div>

            <div class="flex flex-col gap-1">
              <label class="font-label-sm text-label-sm font-bold text-on-surface">DNI Propietario</label>
              <input
                v-model="newManualRequest.ownerDni"
                type="text"
                placeholder="Ej: 30.123.456"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary"
              />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="flex flex-col gap-1">
              <label class="font-label-sm text-label-sm font-bold text-on-surface">Nombre y Apellido Titular</label>
              <input
                v-model="newManualRequest.ownerName"
                type="text"
                required
                placeholder="Ej: Juan Pérez"
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary"
              />
            </div>

            <div class="flex flex-col gap-1">
              <label class="font-label-sm text-label-sm font-bold text-on-surface">WhatsApp Oficial</label>
              <input
                v-model="newManualRequest.ownerPhone"
                type="text"
                placeholder="+54 9 264 4..."
                class="w-full px-3 py-2 rounded-lg bg-surface-container-low border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary"
              />
            </div>
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-label-sm text-label-sm font-bold text-on-surface">Notas del Relevamiento In Situ</label>
            <textarea
              v-model="newManualRequest.notes"
              rows="2"
              placeholder="Detalles sobre acceso, horarios o puntos a inspeccionar..."
              class="w-full px-3 py-2 rounded-lg bg-surface-container-low border border-surface-container text-on-surface text-sm focus:outline-none focus:ring-1 focus:ring-primary resize-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-2 pt-2 border-t border-surface-container">
            <button
              type="button"
              class="px-4 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container font-label-md text-label-md font-semibold transition-colors cursor-pointer"
              @click="showManualModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-5 py-2 rounded-lg bg-primary hover:bg-primary-container text-on-primary font-label-md text-label-md font-bold transition-all shadow-sm cursor-pointer"
            >
              Registrar en Bandeja
            </button>
          </div>
        </form>
      </div>
    </div>
  </main>
</template>
