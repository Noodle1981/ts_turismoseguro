<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  modelValue: boolean
  offerTitle: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (
    e: 'confirmPause',
    payload: {
      mode: 'indefinite' | 'vacation'
      startDate?: string
      endDate?: string
    }
  ): void
}>()

const mode = ref<'indefinite' | 'vacation'>('vacation')
const startDate = ref<string>('')
const endDate = ref<string>('')
const errorMsg = ref<string>('')

// Inicializar fechas tentativas al abrir
watch(
  () => props.modelValue,
  (isOpen) => {
    if (isOpen) {
      errorMsg.value = ''
      mode.value = 'vacation'
      const today = new Date()
      const nextWeek = new Date()
      nextWeek.setDate(today.getDate() + 15)
      startDate.value = today.toISOString().split('T')[0]
      endDate.value = nextWeek.toISOString().split('T')[0]
    }
  }
)

const handleConfirm = () => {
  if (mode.value === 'vacation') {
    if (!startDate.value || !endDate.value) {
      errorMsg.value = 'Por favor indique tanto la fecha de inicio como la de fin de vacaciones.'
      return
    }
    if (new Date(startDate.value) > new Date(endDate.value)) {
      errorMsg.value = 'La fecha de fin no puede ser anterior a la de inicio.'
      return
    }
  }

  emit('confirmPause', {
    mode: mode.value,
    startDate: mode.value === 'vacation' ? startDate.value : undefined,
    endDate: mode.value === 'vacation' ? endDate.value : undefined,
  })
  emit('update:modelValue', false)
}

const handleClose = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary/60 backdrop-blur-xs animate-in fade-in duration-200"
    role="dialog"
    aria-modal="true"
    aria-labelledby="vacation-modal-title"
    @click.self="handleClose"
  >
    <div
      class="w-full max-w-md bg-surface-container-lowest rounded-2xl shadow-2xl border border-surface-container overflow-hidden flex flex-col animate-in zoom-in-95 duration-200"
    >
      <!-- Cabecera Institucional Stitch -->
      <div class="p-space-md bg-surface-container-low border-b border-surface-container flex items-center justify-between">
        <div class="flex items-center gap-space-xs">
          <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-700 dark:text-amber-300 flex items-center justify-center">
            <span class="material-symbols-outlined text-[22px]">pause_circle</span>
          </div>
          <div>
            <h3 id="vacation-modal-title" class="font-headline-sm text-headline-sm text-primary font-bold">
              Pausar en Buscador
            </h3>
            <p class="font-label-sm text-label-sm text-on-surface-variant truncate max-w-[240px]">
              {{ offerTitle }}
            </p>
          </div>
        </div>
        <button
          type="button"
          aria-label="Cerrar modal"
          class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
          @click="handleClose"
        >
          <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
      </div>

      <!-- Cuerpo del Modal -->
      <div class="p-space-md flex flex-col gap-space-md">
        <!-- Opciones: Modo Vacaciones vs Pausa Indefinida -->
        <div class="flex flex-col gap-2">
          <label class="font-label-md text-label-md text-primary font-bold">
            Seleccione el motivo de la pausa:
          </label>

          <!-- Opción 1: Modo Vacaciones -->
          <label
            class="flex items-start gap-space-sm p-3 rounded-xl border cursor-pointer transition-all"
            :class="
              mode === 'vacation'
                ? 'bg-primary-container/10 border-primary shadow-2xs'
                : 'bg-surface-container-low border-surface-container hover:bg-surface-container'
            "
          >
            <input
              type="radio"
              v-model="mode"
              value="vacation"
              name="pauseMode"
              class="mt-1 text-primary focus:ring-primary"
            />
            <div class="flex flex-col">
              <span class="font-label-md text-label-md text-primary font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-secondary">beach_access</span>
                Modo Vacaciones / Receso Temporal
              </span>
              <span class="font-body-sm text-body-sm text-on-surface-variant text-[12px] leading-snug">
                Pausa el anuncio en el buscador solo durante las fechas seleccionadas. Luego se reactivará automáticamente.
              </span>
            </div>
          </label>

          <!-- Opción 2: Pausa Indefinida -->
          <label
            class="flex items-start gap-space-sm p-3 rounded-xl border cursor-pointer transition-all"
            :class="
              mode === 'indefinite'
                ? 'bg-primary-container/10 border-primary shadow-2xs'
                : 'bg-surface-container-low border-surface-container hover:bg-surface-container'
            "
          >
            <input
              type="radio"
              v-model="mode"
              value="indefinite"
              name="pauseMode"
              class="mt-1 text-primary focus:ring-primary"
            />
            <div class="flex flex-col">
              <span class="font-label-md text-label-md text-primary font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px] text-outline">visibility_off</span>
                Pausa Indefinida
              </span>
              <span class="font-body-sm text-body-sm text-on-surface-variant text-[12px] leading-snug">
                El predio no aparecerá en búsquedas hasta que vuelva a activar el interruptor manualmente.
              </span>
            </div>
          </label>
        </div>

        <!-- Campos de fechas para vacaciones -->
        <div v-if="mode === 'vacation'" class="p-3 rounded-xl bg-surface-container-low border border-surface-container flex flex-col gap-2.5 animate-in fade-in duration-150">
          <span class="font-label-sm text-label-sm text-primary font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px] text-tertiary">date_range</span>
            Rango de Fechas del Receso:
          </span>

          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1 font-semibold text-[11px]">
                Desde (Inicio)
              </label>
              <input
                v-model="startDate"
                type="date"
                class="w-full px-2.5 py-1.5 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-xs border border-surface-container focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>
            <div>
              <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1 font-semibold text-[11px]">
                Hasta (Reactivación)
              </label>
              <input
                v-model="endDate"
                type="date"
                class="w-full px-2.5 py-1.5 rounded-lg bg-surface-container-lowest text-primary font-body-sm text-xs border border-surface-container focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>
          </div>
        </div>

        <!-- Mensaje de Error si las fechas no son válidas -->
        <div v-if="errorMsg" class="p-2 rounded-lg bg-error-container text-on-error-container text-xs font-semibold flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">error</span>
          <span>{{ errorMsg }}</span>
        </div>

        <!-- Alerta Informativa sobre la Suscripción / Póliza de Seguro -->
        <div class="p-3 rounded-xl bg-surface-container text-on-surface-variant text-[11px] leading-relaxed flex items-start gap-2 border border-surface-container-high">
          <span class="material-symbols-outlined text-secondary text-[18px] shrink-0 mt-0.5">verified_user</span>
          <div>
            <strong class="text-primary block font-bold">Aviso sobre Póliza y Suscripción:</strong>
            Pausar la visibilidad no cancela su seguro ni su legajo habilitado. Las reservas ya confirmadas previas al receso se mantendrán vigentes.
          </div>
        </div>
      </div>

      <!-- Pie con Botones de Acción -->
      <div class="p-space-md bg-surface-container-low border-t border-surface-container flex items-center justify-end gap-2">
        <button
          type="button"
          class="px-3.5 py-2 rounded-xl text-xs font-bold text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer"
          @click="handleClose"
        >
          Cancelar
        </button>

        <button
          type="button"
          class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all shadow-xs cursor-pointer flex items-center gap-1.5"
          @click="handleConfirm"
        >
          <span class="material-symbols-outlined text-[16px]">check</span>
          <span>Confirmar Pausa</span>
        </button>
      </div>
    </div>
  </div>
</template>
