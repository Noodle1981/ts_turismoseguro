<script setup lang="ts">
import type { PropietarioOferta, Tenant } from '../../../types/propietario'

defineProps<{
  selectedOffer: PropietarioOferta
  tenantTab: 'proximos' | 'pasados'
}>()

const emit = defineEmits<{
  (e: 'update:tenantTab', tab: 'proximos' | 'pasados'): void
  (e: 'openDetail', tenant: Tenant): void
  (e: 'rate', payload: { tenant: Tenant; rating: 'up' | 'down' }): void
}>()
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
              v-for="tenant in selectedOffer.tenants"
              :key="tenant.id"
              class="hover:bg-surface-container-low/60 transition-colors"
            >
              <td class="py-space-md px-space-md whitespace-nowrap">
                <div class="flex flex-col">
                  <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.date }}</span>
                  <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.time }}</span>
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

              <!-- Columna Criterio Anfitrión -->
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
                    v-else-if="tenant.ownerRating === 'down'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
                    title="Inquilino observado / No conforme"
                  >
                    <span class="material-symbols-outlined text-[15px]">thumb_down</span>
                    <span>No Conforme</span>
                  </div>

                  <div
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
                    title="Sin historial previo / Inquilino nuevo"
                  >
                    <span class="material-symbols-outlined text-[15px] text-outline">horizontal_rule</span>
                    <span>Sin historial</span>
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
                      title="Marcar como Buen Inquilino (Pulgar arriba)"
                      aria-label="Marcar como Buen Inquilino"
                    >
                      <span class="material-symbols-outlined text-[14px]">thumb_up</span>
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
                      title="Marcar como No Conforme (Pulgar abajo)"
                      aria-label="Marcar como No Conforme"
                    >
                      <span class="material-symbols-outlined text-[14px]">thumb_down</span>
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
            <tr v-if="!selectedOffer.tenants || selectedOffer.tenants.length === 0">
              <td colspan="6" class="py-space-xl text-center">
                <div class="flex flex-col items-center justify-center gap-space-xs py-space-md text-on-surface-variant">
                  <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-secondary mb-1">
                    <span class="material-symbols-outlined text-[28px]" aria-hidden="true">event_available</span>
                  </div>
                  <span class="font-headline-sm text-headline-sm text-primary font-bold">Sin reservas próximas agendadas</span>
                  <p class="font-body-sm text-body-sm max-w-md text-on-surface-variant">
                    Cuando confirmes una reserva desde el calendario o por consulta directa, aparecerá registrada aquí con su constancia de identidad y póliza activa.
                  </p>
                </div>
              </td>
            </tr>
          </template>

          <!-- Vista de Alquileres Pasados (Historial) -->
          <template v-else>
            <tr
              v-for="tenant in selectedOffer.pastTenants"
              :key="tenant.id"
              class="hover:bg-surface-container-low/60 transition-colors"
            >
              <td class="py-space-md px-space-md whitespace-nowrap">
                <div class="flex flex-col">
                  <div class="flex items-center gap-1.5">
                    <span class="font-label-lg text-label-lg text-primary font-bold">{{ tenant.date }}</span>
                    <span
                      class="px-1.5 py-0.5 rounded text-[10px] bg-surface-container-high text-on-surface-variant font-bold"
                    >
                      Concluido
                    </span>
                  </div>
                  <span class="text-on-surface-variant font-label-sm text-label-sm">{{ tenant.time }}</span>
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

              <!-- Columna Criterio Anfitrión -->
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
                    v-else-if="tenant.ownerRating === 'down'"
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error-container/40 text-error font-label-sm text-label-sm font-bold border border-error/20"
                    title="Inquilino no conforme / Observado"
                  >
                    <span class="material-symbols-outlined text-[15px]">thumb_down</span>
                    <span>No Conforme</span>
                  </div>

                  <div
                    v-else
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-sm text-label-sm font-bold border border-surface-container-high"
                    title="Sin historial previo / Inquilino nuevo"
                  >
                    <span class="material-symbols-outlined text-[15px] text-outline">horizontal_rule</span>
                    <span>Sin historial</span>
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
                      title="Marcar como Buen Inquilino (Pulgar arriba)"
                      aria-label="Marcar como Buen Inquilino"
                    >
                      <span class="material-symbols-outlined text-[14px]">thumb_up</span>
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
                      title="Marcar como No Conforme (Pulgar abajo)"
                      aria-label="Marcar como No Conforme"
                    >
                      <span class="material-symbols-outlined text-[14px]">thumb_down</span>
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
            <tr v-if="!selectedOffer.pastTenants || selectedOffer.pastTenants.length === 0">
              <td colspan="6" class="py-space-xl text-center">
                <div class="flex flex-col items-center justify-center gap-space-xs py-space-md text-on-surface-variant">
                  <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant mb-1">
                    <span class="material-symbols-outlined text-[28px]" aria-hidden="true">history_toggle_off</span>
                  </div>
                  <span class="font-headline-sm text-headline-sm text-primary font-bold">Sin historial de alquileres previos</span>
                  <p class="font-body-sm text-body-sm max-w-md text-on-surface-variant">
                    Aquí se archivarán los alquileres completados para consultar constancias, calificaciones y el comportamiento de los huéspedes.
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
