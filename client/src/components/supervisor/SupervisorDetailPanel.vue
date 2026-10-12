<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import type { DocketItem } from '../../composables/supervisor/useSupervisorDictamenes'

const props = defineProps<{
  docket: DocketItem | null
  isResolving: boolean
  availableValidators?: Array<{ id: number; name: string }>
}>()

const emit = defineEmits<{
  (e: 'resolve', action: 'approve' | 'modify' | 'reject', notes: string): void
  (e: 'assignValidator', validatorId: number, notes?: string): void
}>()

const supervisorNotes = ref<string>('')
const selectedValidatorToAssign = ref<number | ''>('')
const assignmentNotes = ref<string>('')

// Sincronizar notas y reseteo al cambiar de expediente
watch(
  () => props.docket?.id,
  () => {
    if (props.docket?.notes) {
      supervisorNotes.value = props.docket.notes
    } else {
      supervisorNotes.value =
        'Auditoría in situ 100% conforme. Cumple normativa provincial Res. 182-MTyC/24. Sello San Juan Seguro habilitado para publicación y reserva inmediata.'
    }
    selectedValidatorToAssign.value = props.docket?.validator_id || ''
    assignmentNotes.value = ''
  },
  { immediate: true }
)

const isPending = computed(() => props.docket?.status === 'pending')
const isAssigned = computed(() => props.docket?.status === 'assigned')
const isAudited = computed(() => ['visited', 'under_supervision', 'approved', 'rejected'].includes(props.docket?.status || ''))
const hasChecklist = computed(() => !!props.docket?.checklist && Object.keys(props.docket.checklist).length > 0)

const statusBadge = computed(() => {
  if (!props.docket) return { label: '', class: '' }
  switch (props.docket.status) {
    case 'pending':
      return { label: 'Bandeja de Entrada (Sin Asignar)', class: 'bg-amber-500/20 text-amber-900 dark:text-amber-200' }
    case 'assigned':
      return { label: 'Inspector Asignado (Visita Pendiente)', class: 'bg-primary/20 text-primary' }
    case 'visited':
      return { label: 'Visita Realizada (Por Dictaminar)', class: 'bg-blue-500/20 text-blue-900 dark:text-blue-200' }
    case 'under_supervision':
      return { label: 'En Supervisión (Modificación Solicitada)', class: 'bg-tertiary-fixed/60 text-on-tertiary-container' }
    case 'approved':
      return { label: 'Aprobado Oficial (Sello QR)', class: 'bg-secondary-container/50 text-on-secondary-container' }
    case 'rejected':
      return { label: 'Rechazado Definitivo', class: 'bg-error-container text-on-error-container' }
    default:
      return { label: props.docket.status, class: 'bg-surface-container text-on-surface' }
  }
})

const handleAssign = () => {
  if (!selectedValidatorToAssign.value) return
  emit('assignValidator', Number(selectedValidatorToAssign.value), assignmentNotes.value)
}
</script>

<template>
  <div class="flex flex-col gap-4 sticky top-20">
    <div
      v-if="docket"
      class="p-5 rounded-2xl bg-surface-container-lowest border border-surface-container shadow-md flex flex-col gap-4"
    >
      <!-- Header del Expediente Seleccionado -->
      <div class="flex items-start justify-between pb-3 bg-surface-container-low/40 p-3.5 rounded-xl border border-surface-container">
        <div class="flex flex-col">
          <span class="font-label-sm text-label-sm text-outline uppercase font-mono font-bold">
            Expediente Oficial {{ docket.code }}
          </span>
          <h3 class="font-headline-md text-headline-md text-primary font-bold mt-0.5">
            {{ docket.propertyTitle }}
          </h3>
          <span class="font-body-sm text-body-sm text-on-surface-variant">
            {{ docket.address }} ({{ docket.department }})
          </span>
        </div>

        <span
          class="px-3 py-1 rounded-full font-label-sm text-label-sm font-bold shrink-0 text-center"
          :class="statusBadge.class"
        >
          {{ statusBadge.label }}
        </span>
      </div>

      <!-- 1. Identidad Registral y Antifraude -->
      <div class="flex flex-col gap-2 pt-1">
        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-bold">
          1. Identidad Registral y Antifraude
        </span>
        <div class="grid grid-cols-2 gap-2 p-3 rounded-xl bg-surface-container-low font-body-sm text-body-sm border border-surface-container">
          <div>
            <span class="text-outline text-xs block">Titular Registral:</span>
            <span class="font-bold text-on-surface">{{ docket.ownerName }}</span>
          </div>
          <div>
            <span class="text-outline text-xs block">DNI Validado:</span>
            <span class="font-mono font-bold text-on-surface">{{ docket.ownerDni }}</span>
          </div>
          <div class="col-span-2 mt-1 pt-2 border-t border-surface-container space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-outline text-xs block">
                {{ docket.payout_account_type === 'billetera_virtual' || docket.payout_account_type === 'cvu' ? 'Billetera Virtual' : 'CBU Bancario' }} (Medio de Pago Registrado):
              </span>
              <span
                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                :class="docket.payout_verified ? 'bg-secondary-fixed/40 text-on-secondary-fixed' : 'bg-amber-500/20 text-amber-800 dark:text-amber-200'"
              >
                {{ docket.payout_verified ? 'Confirmado / Verificado' : 'Pendiente de Confirmación' }}
              </span>
            </div>

            <div
              class="flex items-center gap-1 font-mono font-bold text-xs"
              :class="docket.payout_verified ? 'text-secondary' : 'text-amber-800 dark:text-amber-300'"
            >
              <span class="material-symbols-outlined text-[16px]">
                {{ docket.payout_verified ? 'verified' : 'pending' }}
              </span>
              <span>
                {{ docket.payout_account_number || 'Sin cuenta declarada' }}
                <span v-if="docket.payout_bank_name" class="font-sans font-normal text-on-surface-variant text-[11px]">
                  ({{ docket.payout_bank_name }})
                </span>
              </span>
            </div>

            <!-- Notificación de Política Turismo Seguro -->
            <div class="p-2.5 rounded-lg bg-surface-container border border-surface-container-high/80 text-[11px] leading-relaxed text-on-surface-variant flex items-start gap-2">
              <span class="material-symbols-outlined text-primary text-[16px] shrink-0 mt-0.5">verified_user</span>
              <div>
                <p class="font-semibold text-primary">Política Oficial de Turismo Seguro San Juan:</p>
                <p class="mt-0.5">
                  El medio de pago (CBU o Billetera Virtual) debe ser <strong>único</strong> y encontrarse registrado <strong>estrictamente bajo la misma titularidad</strong> del propietario registral (<strong>{{ docket.ownerName }}</strong> - DNI <span class="font-mono">{{ docket.ownerDni }}</span>).
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Checklist Técnico In Situ (CONDICIONAL AL ESTADO REAL) -->
      <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
          <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider font-bold">
            2. Checklist Técnico In Situ
          </span>
          <span
            class="font-label-sm text-label-sm font-bold"
            :class="isAudited && hasChecklist ? 'text-secondary' : 'text-outline'"
          >
            {{ isAudited && hasChecklist ? 'Inspección Favorable Realizada' : isAssigned ? 'Inspección Coordinada (Pendiente)' : 'Pendiente de Asignación e Inspección' }}
          </span>
        </div>

        <!-- Si aún está en 'pending' o 'assigned' sin visita -->
        <div v-if="!isAudited || !hasChecklist" class="p-3.5 rounded-xl bg-surface-container-low border border-dashed border-surface-container flex flex-col gap-2 text-center items-center py-4">
          <span class="material-symbols-outlined text-outline text-[28px]">assignment_late</span>
          <p class="font-body-sm text-body-sm font-bold text-on-surface">
            Checklist no completado aún
          </p>
          <p class="font-body-xs text-xs text-on-surface-variant max-w-sm">
            {{ isPending
              ? 'Esta solicitud se encuentra en la bandeja de entrada inicial. Debe asignarse un inspector matriculado para que asista a la propiedad y complete el acta técnica.'
              : `Inspector matriculado ${docket.validator_name || 'designado'} está a cargo. La visita técnica presencial in situ está pendiente de realización.`
            }}
          </p>
        </div>

        <!-- Si ya fue auditado y existe el checklist en DB -->
        <div v-else class="space-y-1.5 font-body-sm text-body-sm">
          <div class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low border border-surface-container">
            <div class="flex items-center gap-2">
              <span
                class="material-symbols-outlined text-[18px]"
                :class="docket.checklist?.cerco_pileta ? 'text-secondary' : 'text-error'"
              >
                {{ docket.checklist?.cerco_pileta ? 'check_circle' : 'cancel' }}
              </span>
              <span class="text-xs">Cerco perimetral pileta (1.20m+ con traba)</span>
            </div>
            <span class="font-label-sm text-label-sm font-bold font-mono" :class="docket.checklist?.cerco_pileta ? 'text-secondary' : 'text-error'">
              {{ docket.checklist?.cerco_pileta ? 'OK' : 'No Conforme' }}
            </span>
          </div>

          <div class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low border border-surface-container">
            <div class="flex items-center gap-2">
              <span
                class="material-symbols-outlined text-[18px]"
                :class="docket.checklist?.disyuntor ? 'text-secondary' : 'text-error'"
              >
                {{ docket.checklist?.disyuntor ? 'check_circle' : 'cancel' }}
              </span>
              <span class="text-xs">Disyuntor diferencial test 30mA</span>
            </div>
            <span class="font-label-sm text-label-sm font-bold" :class="docket.checklist?.disyuntor ? 'text-secondary' : 'text-error'">
              {{ docket.checklist?.disyuntor ? 'Activo / Test OK' : 'Falla / Ausente' }}
            </span>
          </div>

          <div class="flex items-center justify-between p-2.5 rounded-xl bg-surface-container-low border border-surface-container">
            <div class="flex items-center gap-2">
              <span
                class="material-symbols-outlined text-[18px]"
                :class="docket.checklist?.matafuegos ? 'text-secondary' : 'text-error'"
              >
                {{ docket.checklist?.matafuegos ? 'check_circle' : 'cancel' }}
              </span>
              <span class="text-xs">Matafuegos ABC con carga vigente</span>
            </div>
            <span class="font-label-sm text-label-sm font-mono font-semibold" :class="docket.checklist?.matafuegos ? 'text-secondary' : 'text-error'">
              {{ docket.checklist?.matafuegos ? 'Carga OK' : 'Vencido' }}
            </span>
          </div>

          <div v-if="docket.visit_date" class="text-right text-[11px] text-outline font-mono pt-0.5">
            Fecha de visita in situ: {{ docket.visit_date }}
          </div>
        </div>
      </div>

      <!-- 3. ACCIÓN SECUENCIAL: ASIGNAR INSPECTOR (SI ESTÁ PENDIENTE) O EMITIR DICTAMEN (SI ESTÁ AUDITADO) -->
      <div v-if="isPending" class="mt-2 pt-3 flex flex-col gap-3 bg-amber-500/10 dark:bg-amber-950/20 p-4 rounded-xl border border-amber-500/30">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-amber-700 dark:text-amber-400 text-[20px]">person_add</span>
          <span class="font-label-md text-label-md text-on-surface font-bold">
            Paso Requerido: Asignar Inspector Matriculado
          </span>
        </div>
        <p class="font-body-xs text-xs text-on-surface-variant">
          Esta propiedad no puede dictaminarse ni recibir QR hasta que un inspector matriculado asista al inmueble y labre el acta técnica.
        </p>

        <div class="flex flex-col gap-1.5">
          <label class="font-label-sm text-xs text-on-surface-variant font-semibold">
            Seleccionar Inspector para {{ docket.department }}:
          </label>
          <select
            v-model="selectedValidatorToAssign"
            class="w-full p-2.5 rounded-xl bg-surface-container-lowest text-on-surface font-body-sm text-body-sm border border-surface-container focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer"
          >
            <option value="" disabled>Seleccione inspector habilitado...</option>
            <option
              v-for="v in availableValidators"
              :key="v.id"
              :value="v.id"
            >
              {{ v.name }}
            </option>
          </select>
        </div>

        <button
          type="button"
          :disabled="!selectedValidatorToAssign || isResolving"
          class="w-full py-2.5 px-4 rounded-xl bg-primary hover:bg-primary/90 text-on-primary font-label-md text-label-md font-bold flex items-center justify-center gap-2 shadow-sm cursor-pointer disabled:opacity-50 transition-colors"
          @click="handleAssign"
        >
          <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
          <span>{{ isResolving ? 'Asignando en DB...' : 'Designar Inspector y Habilitar Acta' }}</span>
        </button>
      </div>

      <div v-else-if="isAssigned" class="mt-2 pt-3 flex flex-col gap-3 bg-surface-container-low p-4 rounded-xl border border-surface-container">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-primary text-[20px]">pending_actions</span>
          <span class="font-label-md text-label-md text-primary font-bold">
            Inspección Asignada en Curso
          </span>
        </div>
        <p class="font-body-xs text-xs text-on-surface-variant leading-relaxed">
          Inspector designado: <strong>{{ docket.validator_name || 'Validador matriculado' }}</strong>.<br />
          Esperando que el inspector efectúe el peritaje in situ desde su bandeja o app móvil y suba el acta oficial.
        </p>

        <div class="flex items-center justify-between text-xs font-mono text-outline border-t border-surface-container pt-2">
          <span>Token de Seguridad:</span>
          <span class="font-bold text-on-surface">{{ docket.security_token || 'TS-PENDIENTE' }}</span>
        </div>
      </div>

      <!-- CAJA DE RESOLUCIÓN DEL SUPERVISOR (SOLO CUANDO YA FUE VISITADO / AUDITADO) -->
      <div v-else class="mt-2 pt-3 flex flex-col gap-3 bg-surface-container-low p-4 rounded-xl border border-surface-container">
        <div class="flex items-center justify-between">
          <span class="font-label-md text-label-md text-primary font-bold flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[18px] text-primary">gavel</span>
            Dictamen Final del Supervisor
          </span>
          <span class="font-label-sm text-label-sm text-secondary font-mono font-bold">
            Firma Token #{{ docket.id }}-OK
          </span>
        </div>

        <div>
          <label class="font-label-sm text-label-sm text-on-surface-variant block mb-1 font-semibold">
            Observaciones Técnicas y Dictamen Oficial:
          </label>
          <textarea
            v-model="supervisorNotes"
            rows="3"
            class="w-full p-2.5 rounded-xl bg-surface-container-lowest text-on-surface font-body-sm text-body-sm focus:outline-none focus:ring-2 focus:ring-primary/20 border border-surface-container resize-none"
            placeholder="Indique notas complementarias para el propietario y el Sello QR público..."
          ></textarea>
        </div>

        <!-- ACCIONES TRIPARTITAS: APROBAR / MODIFICAR / RECHAZAR -->
        <div class="flex flex-col gap-2 pt-1">
          <!-- 1. BOTÓN PRINCIPAL VERDE: APROBAR & EMITIR QR -->
          <button
            type="button"
            :disabled="isResolving"
            class="w-full py-3 px-4 rounded-xl bg-secondary hover:bg-on-secondary-container transition-all text-on-secondary font-label-lg text-label-lg font-bold flex items-center justify-center gap-2 shadow-md cursor-pointer disabled:opacity-50"
            @click="emit('resolve', 'approve', supervisorNotes)"
          >
            <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
            <span>{{ isResolving ? 'Procesando en DB...' : 'Aprobar Dictamen y Emitir QR Oficial' }}</span>
          </button>

          <div class="grid grid-cols-2 gap-2">
            <!-- 2. BOTÓN ÁMBAR: SOLICITAR MODIFICACIÓN -->
            <button
              type="button"
              :disabled="isResolving"
              class="py-2.5 px-3 rounded-xl bg-tertiary-fixed text-on-tertiary-fixed hover:bg-tertiary-fixed-dim transition-colors font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-xs cursor-pointer disabled:opacity-50"
              @click="emit('resolve', 'modify', supervisorNotes)"
            >
              <span class="material-symbols-outlined text-[18px]">published_with_changes</span>
              <span>Pedir Modificación (48hs)</span>
            </button>

            <!-- 3. BOTÓN ROJO: RECHAZAR SOLICITUD -->
            <button
              type="button"
              :disabled="isResolving"
              class="py-2.5 px-3 rounded-xl bg-error-container text-on-error-container hover:bg-error/20 transition-colors font-label-md text-label-md font-bold flex items-center justify-center gap-1.5 shadow-xs cursor-pointer disabled:opacity-50"
              @click="emit('resolve', 'reject', supervisorNotes)"
            >
              <span class="material-symbols-outlined text-[18px]">cancel</span>
              <span>Rechazar Solicitud</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Si no hay seleccionado -->
    <div v-else class="p-6 rounded-2xl bg-surface-container-lowest border border-surface-container text-center text-on-surface-variant text-xs">
      Seleccione un expediente de la lista para auditar sus evidencias y emitir resolución.
    </div>
  </div>
</template>
