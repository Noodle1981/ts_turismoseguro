<script setup lang="ts">
import { ref, watch } from 'vue'
import type { Tenant } from '../../../types/propietario'

const props = defineProps<{
  modelValue: boolean
  selectedTenant: Tenant | null
  isPast?: boolean
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
  (e: 'rate', payload: { tenant: Tenant; rating: 'up' | 'down' | 'follow_up' | 'rejected' }): void
  (e: 'saveObservation', payload: { tenant: Tenant; observation: string }): void
}>()

const isEditingObservation = ref(false)
const observationText = ref('')
const observationFeedback = ref(false)

watch(
  () => props.selectedTenant,
  (t) => {
    if (t) {
      observationText.value = t.ownerObservation || ''
      isEditingObservation.value = false
    }
  },
  { immediate: true }
)

const handleSaveObservation = () => {
  if (!props.selectedTenant) return
  emit('saveObservation', { tenant: props.selectedTenant, observation: observationText.value.trim() })
  isEditingObservation.value = false
  observationFeedback.value = true
  setTimeout(() => {
    observationFeedback.value = false
  }, 2500)
}

const close = () => {
  emit('update:modelValue', false)
}
</script>

<template>
  <div
    v-if="modelValue && selectedTenant"
    class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4"
    @click.self="close"
  >
    <div
      class="bg-surface-container-lowest rounded-xl shadow-xl max-w-lg w-full p-space-lg flex flex-col gap-space-md border border-surface-container animate-in fade-in duration-200"
    >
      <div class="flex items-center justify-between pb-space-xs border-b border-surface-container">
        <div class="flex items-center gap-space-xs">
          <span class="material-symbols-outlined text-secondary text-[24px]">assignment_ind</span>
          <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Ficha Integral del Inquilino</h3>
        </div>
        <button
          type="button"
          aria-label="Cerrar ficha del inquilino"
          class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface-variant flex items-center justify-center cursor-pointer"
          @click="close"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div
        class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between border border-surface-container"
      >
        <div>
          <h4 class="font-headline-sm text-headline-sm text-primary font-bold">{{ selectedTenant.name }}</h4>
          <span class="font-body-sm text-body-sm text-on-surface-variant">DNI {{ selectedTenant.dni }}</span>
        </div>
        <div class="text-right">
          <span
            class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold"
          >
            <span class="material-symbols-outlined text-[14px]">verified</span> Validado
          </span>
        </div>
      </div>

      <div class="space-y-space-xs font-body-sm text-body-sm text-on-surface">
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Fecha de ingreso:</span>
          <strong class="text-primary font-bold">{{ selectedTenant.date }}</strong>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Modalidad contratada:</span>
          <span
            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold border"
            :class="{
              'bg-primary/10 text-primary border-primary/20': selectedTenant.modality === 'pernocte',
              'bg-purple-900/15 text-purple-700 dark:text-purple-300 border-purple-500/20': selectedTenant.modality === 'fiesta_nocturna',
              'bg-amber-500/15 text-amber-700 dark:text-amber-400 border-amber-500/30': selectedTenant.modality === 'evento_especial',
              'bg-secondary/15 text-secondary border-secondary/20': !selectedTenant.modality || selectedTenant.modality === 'diurno',
            }"
          >
            <span class="material-symbols-outlined text-[13px]">
              {{
                selectedTenant.modality === 'pernocte' ? 'bed' :
                selectedTenant.modality === 'fiesta_nocturna' ? 'nightlife' :
                selectedTenant.modality === 'evento_especial' ? 'stars' : 'wb_sunny'
              }}
            </span>
            {{
              selectedTenant.modality === 'pernocte' ? 'Pernocte' :
              selectedTenant.modality === 'fiesta_nocturna' ? 'Fiesta Nocturna' :
              selectedTenant.modality === 'evento_especial' ? 'Evento Especial' : 'Turno Diurno'
            }}
          </span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Turno pactado (horario):</span>
          <span class="text-primary font-mono text-xs">{{ selectedTenant.time }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Motivo del evento:</span>
          <span class="text-primary">{{ selectedTenant.eventType }}</span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Cantidad asistentes:</span>
          <strong class="text-secondary font-bold">{{ selectedTenant.guestsText }}</strong>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Póliza asegurada:</span>
          <span class="text-secondary font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">shield</span>
            {{ selectedTenant.insuranceBadge }}
          </span>
        </div>
        <div class="flex justify-between py-1 border-b border-surface-container">
          <span class="text-on-surface-variant">Estado de cobertura:</span>
          <span class="text-secondary font-bold flex items-center gap-1">
            <span class="material-symbols-outlined text-[15px]">verified_user</span>
            {{ selectedTenant.status === 'completed' ? 'Turno Finalizado & Póliza Rendida' : 'Cobertura Activa y Asistida' }}
          </span>
        </div>
      </div>

      <div class="p-space-sm rounded-lg bg-surface-container flex flex-col gap-1">
        <span class="font-label-sm text-label-sm text-on-surface font-bold">
          Historial en Red Turismo Seguro:
        </span>
        <p class="font-body-sm text-body-sm text-secondary font-semibold">
          {{ selectedTenant.history }}
        </p>
      </div>

      <!-- Criterio Anfitrión en la Ficha -->
      <div
        class="p-space-sm rounded-lg bg-surface-container-low border border-surface-container flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2"
      >
        <div>
          <span class="font-label-sm text-label-sm text-primary font-bold block">
            {{ isPast ? 'Tu Criterio sobre este Alquiler:' : 'Reputación Histórica del Inquilino:' }}
          </span>
          <span class="text-xs text-on-surface-variant">
            {{ isPast ? 'Puntúa el comportamiento del huésped en este turno.' : 'Basado en los alquileres pasados en la red.' }}
          </span>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
          <button
            v-if="isPast"
            type="button"
            @click="emit('rate', { tenant: selectedTenant, rating: 'up' })"
            class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
            :class="
              selectedTenant.ownerRating === 'up'
                ? 'bg-secondary text-on-secondary shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'
            "
          >
            <span class="material-symbols-outlined text-[16px]">thumb_up</span>
            <span>Buen Inquilino</span>
          </button>
          <button
            v-if="isPast"
            type="button"
            @click="emit('rate', { tenant: selectedTenant, rating: 'follow_up' })"
            class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
            :class="
              selectedTenant.ownerRating === 'follow_up'
                ? 'bg-amber-500 text-white shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-amber-600 hover:bg-surface-container-high'
            "
          >
            <span class="material-symbols-outlined text-[16px]">visibility</span>
            <span>Observar</span>
          </button>
          <button
            v-if="isPast"
            type="button"
            @click="emit('rate', { tenant: selectedTenant, rating: 'down' })"
            class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
            :class="
              selectedTenant.ownerRating === 'down'
                ? 'bg-error text-on-error shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-error hover:bg-surface-container-high'
            "
          >
            <span class="material-symbols-outlined text-[16px]">thumb_down</span>
            <span>No Conforme</span>
          </button>
          <button
            v-if="isPast"
            type="button"
            @click="emit('rate', { tenant: selectedTenant, rating: 'rejected' })"
            class="px-2.5 py-1 rounded-lg text-xs font-bold flex items-center gap-1 transition-all cursor-pointer"
            :class="
              selectedTenant.ownerRating === 'rejected'
                ? 'bg-red-700 text-white shadow-xs'
                : 'bg-surface-container text-on-surface-variant hover:text-red-700 hover:bg-surface-container-high'
            "
          >
            <span class="material-symbols-outlined text-[16px]">block</span>
            <span>No Aceptar</span>
          </button>

          <!-- Si es Próximo Alquiler, solo muestra el badge de reputación histórica -->
          <div v-else>
            <div
              v-if="selectedTenant.ownerRating === 'up'"
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold border border-secondary/20"
            >
              <span class="material-symbols-outlined text-[15px]">thumb_up</span>
              <span>Buen Inquilino</span>
            </div>
            <div
              v-else-if="selectedTenant.ownerRating === 'follow_up'"
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 font-label-sm text-label-sm font-bold border border-amber-500/30"
            >
              <span class="material-symbols-outlined text-[15px]">visibility</span>
              <span>En Observación / Seguimiento</span>
            </div>
            <div
              v-else-if="selectedTenant.ownerRating === 'down'"
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
            >
              <span class="material-symbols-outlined text-[15px]">thumb_down</span>
              <span>No Conforme</span>
            </div>
            <div
              v-else-if="selectedTenant.ownerRating === 'rejected'"
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-950/20 text-red-600 dark:text-red-400 font-label-sm text-label-sm font-bold border border-red-600/40"
            >
              <span class="material-symbols-outlined text-[15px]">block</span>
              <span>No Aceptar / Restringido</span>
            </div>
            <div
              v-else
              class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
            >
              <span class="material-symbols-outlined text-[15px]">horizontal_rule</span>
              <span>Sin historial previo</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Observación del Anfitrión (Globito de comentario / lectura) -->
      <div class="p-space-sm rounded-lg bg-surface-container-low border border-surface-container flex flex-col gap-2">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-1.5">
            <span class="material-symbols-outlined text-primary text-[18px]">chat_bubble</span>
            <span class="font-label-sm text-label-sm text-primary font-bold">
              {{ isPast ? 'Observación del Turno (Privada Anfitrión):' : 'Observaciones Registradas de Anfitriones:' }}
            </span>
          </div>
          <button
            v-if="isPast && !isEditingObservation"
            type="button"
            @click="isEditingObservation = true"
            class="text-xs text-secondary hover:underline font-bold flex items-center gap-0.5 cursor-pointer"
          >
            <span class="material-symbols-outlined text-[14px]">edit_note</span>
            <span>{{ selectedTenant.ownerObservation ? 'Editar nota' : 'Agregar nota' }}</span>
          </button>
        </div>

        <!-- Modo Edición para Alquileres Pasados -->
        <div v-if="isPast && isEditingObservation" class="flex flex-col gap-2">
          <textarea
            v-model="observationText"
            rows="2"
            maxlength="1000"
            placeholder="Ej: Entregó la quinta impecable, respetó aforo y normas de sonido..."
            class="w-full text-xs p-2 rounded-lg bg-surface-container border border-surface-container-high focus:outline-none focus:ring-1 focus:ring-primary text-on-surface"
          ></textarea>
          <div class="flex items-center justify-end gap-2">
            <button
              type="button"
              @click="isEditingObservation = false"
              class="px-2.5 py-1 rounded text-xs text-on-surface-variant hover:bg-surface-container cursor-pointer"
            >
              Cancelar
            </button>
            <button
              type="button"
              @click="handleSaveObservation"
              class="px-3 py-1 rounded bg-secondary text-on-secondary text-xs font-bold shadow-xs hover:bg-secondary/90 cursor-pointer flex items-center gap-1"
            >
              <span class="material-symbols-outlined text-[14px]">save</span>
              <span>Guardar Observación</span>
            </button>
          </div>
        </div>

        <!-- Modo Lectura para Pasados -->
        <div v-else-if="isPast">
          <p v-if="selectedTenant.ownerObservation" class="text-xs text-on-surface italic bg-surface-container/60 p-2 rounded-md border border-surface-container">
            "{{ selectedTenant.ownerObservation }}"
          </p>
          <span v-else class="text-xs text-on-surface-variant">Sin observaciones redactadas para este turno.</span>
          <span v-if="observationFeedback" class="text-xs text-secondary font-bold block pt-1">
            ✓ Observación guardada correctamente.
          </span>
        </div>

        <!-- Modo Lectura para Próximos Alquileres (historial de observaciones previas) -->
        <div v-else>
          <div v-if="selectedTenant.historicalObservations && selectedTenant.historicalObservations.length > 0" class="flex flex-col gap-1.5">
            <div
              v-for="(obs, idx) in selectedTenant.historicalObservations"
              :key="idx"
              class="text-xs text-on-surface bg-surface-container/60 p-2 rounded-md border border-surface-container flex items-start gap-1.5"
            >
              <span class="material-symbols-outlined text-secondary text-[14px] shrink-0 mt-0.5">comment</span>
              <span class="italic">"{{ obs }}"</span>
            </div>
          </div>
          <span v-else class="text-xs text-on-surface-variant">
            {{ selectedTenant.ownerRating === 'none' ? 'Inquilino nuevo sin alquileres previos registrados.' : 'Sin notas previas registradas.' }}
          </span>
        </div>
      </div>

      <div class="flex items-center justify-end gap-space-sm pt-space-xs">
        <button
          type="button"
          class="w-full py-space-sm rounded-lg bg-primary text-on-primary font-label-lg text-label-lg hover:bg-primary-container transition-colors font-bold cursor-pointer"
          @click="close"
        >
          Cerrar Ficha
        </button>
      </div>
    </div>
  </div>
</template>
