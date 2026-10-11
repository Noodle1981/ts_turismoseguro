<script setup lang="ts">
import { ref, computed } from 'vue'
import type { PropietarioOferta, Tenant } from '../../../types/propietario'

const props = defineProps<{
  selectedOffer: PropietarioOferta
  tenantTab: 'proximos' | 'pasados'
}>()

const emit = defineEmits<{
  (e: 'update:tenantTab', tab: 'proximos' | 'pasados'): void
  (e: 'openDetail', tenant: Tenant): void
  (e: 'rate', payload: { tenant: Tenant; rating: 'up' | 'down' | 'follow_up' | 'rejected' }): void
}>()

// Filtro de modalidad
type ModalityFilter = 'all' | 'diurno' | 'pernocte' | 'fiesta_nocturna' | 'finde_completo' | 'evento_especial'
const selectedModality = ref<ModalityFilter>('all')

const modalityOptions = [
  { value: 'all', label: 'Todas las modalidades', icon: 'filter_list' },
  { value: 'diurno', label: 'Turno Diurno', icon: 'wb_sunny' },
  { value: 'pernocte', label: 'Pernocte', icon: 'bed' },
  { value: 'fiesta_nocturna', label: 'Fiesta Nocturna', icon: 'nightlife' },
  { value: 'evento_especial', label: 'Evento Especial', icon: 'stars' },
] as const

const filterByModality = (list?: Tenant[]) => {
  if (!list) return []
  if (selectedModality.value === 'all') return list
  return list.filter((t) => (t.modality || 'diurno') === selectedModality.value)
}

const filteredProximos = computed(() => filterByModality(props.selectedOffer?.tenants))
const filteredPasados = computed(() => filterByModality(props.selectedOffer?.pastTenants))

const getModalityMeta = (modality?: string) => {
  switch (modality) {
    case 'pernocte':
      return { label: 'Pernocte', icon: 'bed', bgClass: 'bg-primary/10 text-primary border-primary/20' }
    case 'fiesta_nocturna':
      return { label: 'Fiesta Nocturna', icon: 'nightlife', bgClass: 'bg-purple-900/15 text-purple-700 dark:text-purple-300 border-purple-500/20' }
    case 'evento_especial':
      return { label: 'Evento Especial', icon: 'stars', bgClass: 'bg-amber-500/15 text-amber-700 dark:text-amber-400 border-amber-500/30' }
    case 'finde_completo':
      return { label: 'Finde Completo', icon: 'date_range', bgClass: 'bg-teal-600/15 text-teal-700 dark:text-teal-300 border-teal-500/20' }
    case 'diurno':
    default:
      return { label: 'Turno Diurno', icon: 'wb_sunny', bgClass: 'bg-secondary/15 text-secondary border-secondary/20' }
  }
}
</script>

<template>
  <section
    id="registro-inquilinos"
    class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col gap-space-md border border-surface-container scroll-mt-20"
  >
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-space-sm">
      <div>
        <h2 class="font-headline-md text-headline-md text-primary font-bold">
          {{ tenantTab === 'proximos' ? 'Próximos Alquileres Agendados' : 'Registro de Alquileres Pasados (Historial)' }}
        </h2>
        <span class="font-body-sm text-body-sm text-on-surface-variant">
          {{
            tenantTab === 'proximos'
              ? 'Listado de inquilinos confirmados, seguros vigentes y control de turnos'
              : 'Historial verificado de inquilinos con turnos concluidos, pólizas finalizadas y constancias'
          }}
        </span>
      </div>

      <!-- Pestañas de Alquileres: Próximos vs Pasados -->
      <div class="flex items-center bg-surface-container-low rounded-lg p-1 border border-surface-container shrink-0">
        <button
          type="button"
          class="px-space-md py-1.5 rounded font-label-sm text-label-sm font-bold cursor-pointer transition-all flex items-center gap-1.5"
          :class="
            tenantTab === 'proximos'
              ? 'bg-surface-container-lowest text-primary shadow-xs'
              : 'text-on-surface-variant hover:text-on-surface'
          "
          @click="emit('update:tenantTab', 'proximos')"
        >
          <span class="material-symbols-outlined text-[16px]">upcoming</span>
          <span>Próximos Alquileres</span>
          <span
            class="px-1.5 py-0.2 rounded-full text-[10px] bg-secondary-container text-on-secondary-container font-mono"
          >
            {{ selectedOffer.tenants?.length || 0 }}
          </span>
        </button>

        <button
          type="button"
          class="px-space-md py-1.5 rounded font-label-sm text-label-sm font-bold cursor-pointer transition-all flex items-center gap-1.5"
          :class="
            tenantTab === 'pasados'
              ? 'bg-surface-container-lowest text-primary shadow-xs'
              : 'text-on-surface-variant hover:text-on-surface'
          "
          @click="emit('update:tenantTab', 'pasados')"
        >
          <span class="material-symbols-outlined text-[16px]">history</span>
          <span>Alquileres Pasados</span>
          <span
            class="px-1.5 py-0.2 rounded-full text-[10px] bg-surface-container-highest text-on-surface-variant font-mono"
          >
            {{ selectedOffer.pastTenants?.length || 0 }}
          </span>
        </button>
      </div>
    </div>

    <!-- Barra de Filtros por Modalidad (Diurno, Pernocte, Fiestas, Especiales) -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 border-b border-surface-container/60">
      <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider pr-1 flex items-center gap-1 shrink-0">
        <span class="material-symbols-outlined text-[14px]">tune</span>
        Modalidad:
      </span>
      <button
        v-for="opt in modalityOptions"
        :key="opt.value"
        type="button"
        @click="selectedModality = opt.value"
        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold cursor-pointer transition-all shrink-0"
        :class="
          selectedModality === opt.value
            ? 'bg-primary text-on-primary shadow-xs font-bold'
            : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container hover:text-on-surface border border-surface-container'
        "
      >
        <span class="material-symbols-outlined text-[14px]">{{ opt.icon }}</span>
        <span>{{ opt.label }}</span>
      </button>
    </div>

    <!-- Tabla Responsiva -->
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm">
            <th class="py-space-sm px-space-md rounded-l-lg font-bold">Fecha &amp; Horario</th>
            <th class="py-space-sm px-space-md font-bold">Inquilino Verificado</th>
            <th class="py-space-sm px-space-md font-bold">Criterio Anfitrión</th>
            <th class="py-space-sm px-space-md font-bold">Asistentes &amp; Evento</th>
            <th class="py-space-sm px-space-md font-bold">Póliza / Seguro Homologado</th>
            <th class="py-space-sm px-space-md rounded-r-lg text-right font-bold">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-surface-container-high font-body-sm text-body-sm text-on-surface">
          <!-- Vista de Próximos Alquileres -->
          <template v-if="tenantTab === 'proximos'">
            <tr
              v-for="tenant in filteredProximos"
              :key="tenant.id"
              class="hover:bg-surface-container-low/60 transition-colors"
            >
              <td class="py-space-md px-space-md whitespace-nowrap">
                <div class="flex flex-col gap-1">
                  <div class="flex items-center gap-1.5">
                    <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.date }}</span>
                    <!-- Badge de Modalidad Histórica / Contratada -->
                    <span
                      class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold border"
                      :class="getModalityMeta(tenant.modality).bgClass"
                    >
                      <span class="material-symbols-outlined text-[12px]">{{ getModalityMeta(tenant.modality).icon }}</span>
                      {{ getModalityMeta(tenant.modality).label }}
                    </span>
                  </div>
                  <span class="text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ tenant.time }}
                  </span>
                </div>
              </td>

              <td class="py-space-md px-space-md">
                <div>
                  <div class="flex items-center gap-1">
                    <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.name }}</span>
                    <span
                      class="material-symbols-outlined text-secondary text-[16px]"
                      title="DNI Verificado Renaper"
                    >
                      verified
                    </span>
                  </div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant">
                    DNI {{ tenant.dni }} • San Juan
                  </span>
                </div>
              </td>

              <!-- Columna Criterio Anfitrión (Histórico para Próximos Alquileres) -->
              <td class="py-space-md px-space-md whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <div
                    v-if="tenant.ownerRating === 'up'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold border border-secondary/20"
                    title="Inquilino recomendado con historial previo positivo"
                  >
                    <span class="material-symbols-outlined text-[15px]">thumb_up</span>
                    <span>Buen Inquilino</span>
                  </div>

                  <div
                    v-else-if="tenant.ownerRating === 'follow_up'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 font-label-sm text-label-sm font-bold border border-amber-500/30"
                    title="Inquilino bajo observación / Seguimiento preventivo"
                  >
                    <span class="material-symbols-outlined text-[15px]">visibility</span>
                    <span>En Observación</span>
                  </div>

                  <div
                    v-else-if="tenant.ownerRating === 'down'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
                    title="Inquilino no conforme en turnos anteriores"
                  >
                    <span class="material-symbols-outlined text-[15px]">thumb_down</span>
                    <span>No Conforme</span>
                  </div>

                  <div
                    v-else-if="tenant.ownerRating === 'rejected'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-950/20 text-red-600 dark:text-red-400 font-label-sm text-label-sm font-bold border border-red-600/40"
                    title="Inquilino con restricción: No Aceptar / Bloqueado por anfitriones"
                  >
                    <span class="material-symbols-outlined text-[15px]">block</span>
                    <span>No Aceptar</span>
                  </div>

                  <div
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
                    title="Sin alquileres previos en la Red Turismo Seguro"
                  >
                    <span class="material-symbols-outlined text-[15px] text-outline">horizontal_rule</span>
                    <span>Sin historial</span>
                  </div>

                  <!-- Globito de Notas Históricas (Lectura en Próximos) -->
                  <button
                    v-if="tenant.historicalObservations && tenant.historicalObservations.length > 0"
                    type="button"
                    @click="emit('openDetail', tenant)"
                    class="w-7 h-7 rounded-full bg-secondary/10 text-secondary hover:bg-secondary hover:text-on-secondary flex items-center justify-center transition-all cursor-pointer relative"
                    title="Ver observaciones previas de anfitriones"
                  >
                    <span class="material-symbols-outlined text-[15px]">chat_bubble</span>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-secondary text-on-secondary text-[9px] font-bold rounded-full flex items-center justify-center">
                      {{ tenant.historicalObservations.length }}
                    </span>
                  </button>
                </div>
              </td>

              <td class="py-space-md px-space-md">
                <div class="flex flex-col">
                  <span class="font-label-md text-label-md text-primary font-bold">{{ tenant.guestsText }}</span>
                  <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.eventType }}</span>
                </div>
              </td>

              <td class="py-space-md px-space-md">
                <span
                  class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary-container text-on-secondary-container font-label-sm text-label-sm font-bold"
                >
                  <span class="material-symbols-outlined text-[14px]">shield</span>
                  {{ tenant.insuranceBadge }}
                </span>
              </td>

              <td class="py-space-md px-space-md text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1">
                  <a
                    :href="`https://wa.me/${tenant.phone.replace(/[^0-9]/g, '')}`"
                    target="_blank"
                    class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary hover:bg-secondary hover:text-on-secondary flex items-center justify-center transition-all cursor-pointer"
                    title="Contactar por WhatsApp"
                    aria-label="Contactar por WhatsApp"
                  >
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                  </a>
                  <button
                    type="button"
                    class="px-space-sm py-1 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-sm text-label-sm transition-colors cursor-pointer font-bold"
                    @click="emit('openDetail', tenant)"
                  >
                    Ficha
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!filteredProximos || filteredProximos.length === 0">
              <td colspan="6" class="py-space-xl text-center">
                <div class="flex flex-col items-center justify-center gap-space-xs py-space-md text-on-surface-variant">
                  <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-secondary mb-1">
                    <span class="material-symbols-outlined text-[28px]" aria-hidden="true">event_available</span>
                  </div>
                  <span class="font-headline-sm text-headline-sm text-primary font-bold">Sin reservas próximas con este criterio</span>
                  <p class="font-body-sm text-body-sm max-w-md text-on-surface-variant">
                    No encontramos alquileres agendados para la modalidad seleccionada.
                  </p>
                </div>
              </td>
            </tr>
          </template>

          <!-- Vista de Alquileres Pasados (Historial) -->
          <template v-else>
            <tr
              v-for="tenant in filteredPasados"
              :key="tenant.id"
              class="hover:bg-surface-container-low/60 transition-colors"
            >
              <td class="py-space-md px-space-md whitespace-nowrap">
                <div class="flex flex-col gap-1">
                  <div class="flex items-center gap-1.5">
                    <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.date }}</span>
                    <!-- Badge de Modalidad Histórica -->
                    <span
                      class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold border"
                      :class="getModalityMeta(tenant.modality).bgClass"
                    >
                      <span class="material-symbols-outlined text-[12px]">{{ getModalityMeta(tenant.modality).icon }}</span>
                      {{ getModalityMeta(tenant.modality).label }}
                    </span>
                    <span
                      class="px-1.5 py-0.5 rounded text-[10px] bg-surface-container-high text-on-surface-variant font-bold"
                    >
                      Concluido
                    </span>
                  </div>
                  <span class="text-on-surface-variant font-label-sm text-label-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ tenant.time }}
                  </span>
                </div>
              </td>

              <td class="py-space-md px-space-md">
                <div>
                  <div class="flex items-center gap-1">
                    <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.name }}</span>
                    <span
                      class="material-symbols-outlined text-secondary text-[16px]"
                      title="DNI Verificado Renaper"
                    >
                      verified
                    </span>
                  </div>
                  <span class="font-label-sm text-label-sm text-on-surface-variant">
                    DNI {{ tenant.dni }} • San Juan
                  </span>
                </div>
              </td>

              <!-- Columna Criterio Anfitrión (Alquileres Pasados) -->
              <td class="py-space-md px-space-md whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <div
                    v-if="tenant.ownerRating === 'up'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold border border-secondary/20"
                    title="Inquilino recomendado con historial positivo"
                  >
                    <span class="material-symbols-outlined text-[15px]">thumb_up</span>
                    <span>Buen Inquilino</span>
                  </div>

                  <div
                    v-else-if="tenant.ownerRating === 'follow_up'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 font-label-sm text-label-sm font-bold border border-amber-500/30"
                    title="Inquilino en seguimiento / observación"
                  >
                    <span class="material-symbols-outlined text-[15px]">visibility</span>
                    <span>Observar</span>
                  </div>

                  <div
                    v-else-if="tenant.ownerRating === 'down'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
                    title="Inquilino no conforme"
                  >
                    <span class="material-symbols-outlined text-[15px]">thumb_down</span>
                    <span>No Conforme</span>
                  </div>

                  <div
                    v-else-if="tenant.ownerRating === 'rejected'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-950/20 text-red-600 dark:text-red-400 font-label-sm text-label-sm font-bold border border-red-600/40"
                    title="Restricción estricta: No Aceptar"
                  >
                    <span class="material-symbols-outlined text-[15px]">block</span>
                    <span>No Aceptar</span>
                  </div>

                  <div
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
                    title="Sin calificar aún para este turno"
                  >
                    <span class="material-symbols-outlined text-[15px] text-outline">horizontal_rule</span>
                    <span>Sin calificar</span>
                  </div>

                  <div
                    class="flex items-center gap-0.5 bg-surface-container-low p-0.5 rounded-lg border border-surface-container"
                  >
                    <button
                      type="button"
                      @click="emit('rate', { tenant, rating: 'up' })"
                      class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                      :class="
                        tenant.ownerRating === 'up'
                          ? 'bg-secondary text-on-secondary shadow-2xs'
                          : 'text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'
                      "
                      title="Buen Inquilino (Pulgar arriba)"
                      aria-label="Buen Inquilino"
                    >
                      <span class="material-symbols-outlined text-[14px]">thumb_up</span>
                    </button>
                    <button
                      type="button"
                      @click="emit('rate', { tenant, rating: 'follow_up' })"
                      class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                      :class="
                        tenant.ownerRating === 'follow_up'
                          ? 'bg-amber-500 text-white shadow-2xs'
                          : 'text-on-surface-variant hover:text-amber-600 hover:bg-surface-container-high'
                      "
                      title="En Observación / Seguimiento"
                      aria-label="En Observación"
                    >
                      <span class="material-symbols-outlined text-[14px]">visibility</span>
                    </button>
                    <button
                      type="button"
                      @click="emit('rate', { tenant, rating: 'down' })"
                      class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                      :class="
                        tenant.ownerRating === 'down'
                          ? 'bg-error text-on-error shadow-2xs'
                          : 'text-on-surface-variant hover:text-error hover:bg-surface-container-high'
                      "
                      title="No Conforme (Pulgar abajo)"
                      aria-label="No Conforme"
                    >
                      <span class="material-symbols-outlined text-[14px]">thumb_down</span>
                    </button>
                    <button
                      type="button"
                      @click="emit('rate', { tenant, rating: 'rejected' })"
                      class="w-6 h-6 rounded flex items-center justify-center transition-colors cursor-pointer"
                      :class="
                        tenant.ownerRating === 'rejected'
                          ? 'bg-red-700 text-white shadow-2xs'
                          : 'text-on-surface-variant hover:text-red-700 hover:bg-surface-container-high'
                      "
                      title="No Aceptar / Bloquear estadía"
                      aria-label="No Aceptar"
                    >
                      <span class="material-symbols-outlined text-[14px]">block</span>
                    </button>
                  </div>
                </div>
              </td>

              <td class="py-space-md px-space-md">
                <div class="flex flex-col">
                  <span class="font-label-md text-label-md text-primary font-bold">{{ tenant.guestsText }}</span>
                  <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.eventType }}</span>
                </div>
              </td>

              <td class="py-space-md px-space-md">
                <div class="flex flex-col gap-0.5">
                  <span
                    class="inline-flex items-center gap-1 px-space-xs py-0.5 rounded-full bg-secondary/15 text-secondary font-label-sm text-label-sm font-bold w-fit"
                  >
                    <span class="material-symbols-outlined text-[14px]">shield</span>
                    {{ tenant.insuranceBadge }}
                  </span>
                  <span class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px] text-secondary">verified</span>
                    Turno Cumplido y Auditado
                  </span>
                </div>
              </td>

              <td class="py-space-md px-space-md text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1">
                  <!-- Globito de Comentario / Observación para Alquileres Pasados -->
                  <button
                    type="button"
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all cursor-pointer"
                    :class="
                      tenant.ownerObservation
                        ? 'bg-secondary/15 text-secondary hover:bg-secondary hover:text-on-secondary'
                        : 'bg-surface-container text-on-surface-variant hover:text-secondary hover:bg-surface-container-high'
                    "
                    :title="tenant.ownerObservation ? `Ver/Editar observación: &quot;${tenant.ownerObservation}&quot;` : 'Agregar observación privada para este turno'"
                    @click="emit('openDetail', tenant)"
                  >
                    <span class="material-symbols-outlined text-[17px]">
                      {{ tenant.ownerObservation ? 'chat_bubble' : 'add_comment' }}
                    </span>
                  </button>

                  <button
                    type="button"
                    class="inline-flex items-center gap-1 px-space-sm py-1 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-sm text-label-sm transition-colors cursor-pointer font-bold"
                    @click="emit('openDetail', tenant)"
                  >
                    <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                    Constancia
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!filteredPasados || filteredPasados.length === 0">
              <td colspan="6" class="py-space-xl text-center">
                <div class="flex flex-col items-center justify-center gap-space-xs py-space-md text-on-surface-variant">
                  <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant mb-1">
                    <span class="material-symbols-outlined text-[28px]" aria-hidden="true">history_toggle_off</span>
                  </div>
                  <span class="font-headline-sm text-headline-sm text-primary font-bold">Sin registros pasados con este criterio</span>
                  <p class="font-body-sm text-body-sm max-w-md text-on-surface-variant">
                    No encontramos alquileres pasados archivados para la modalidad seleccionada.
                  </p>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </section>
</template>
