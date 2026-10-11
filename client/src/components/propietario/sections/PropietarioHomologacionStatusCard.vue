<script setup lang="ts">
import { computed } from 'vue'
import type { PropietarioOferta } from '../../../types/propietario'

const props = defineProps<{
  offer: PropietarioOferta
}>()

const isPending = computed(() => {
  return props.offer.publicationStatus === 'draft' ||
    props.offer.verificationStatus === 'unverified' ||
    props.offer.verificationStatus === 'pending'
})

const vr = computed(() => props.offer.verificationRequest)

const isAssigned = computed(() => {
  return vr.value?.status === 'assigned' || !!vr.value?.validator
})

const validator = computed(() => vr.value?.validator)

const token = computed(() => vr.value?.securityToken || 'TS-PENDIENTE')

const copyToken = async () => {
  if (token.value && token.value !== 'TS-PENDIENTE') {
    try {
      await navigator.clipboard.writeText(token.value)
      alert(`Token de seguridad copiado al portapapeles: ${token.value}`)
    } catch {
      // fallback
    }
  }
}
</script>

<template>
  <div v-if="isPending" class="flex flex-col gap-4">
    <!-- BANNER PRINCIPAL DE ESTADO DEL TRÁMITE -->
    <div
      class="rounded-2xl p-5 sm:p-6 border transition-all shadow-xs relative overflow-hidden"
      :class="isAssigned
        ? 'bg-gradient-to-r from-emerald-50 via-surface-container-lowest to-surface-container-lowest dark:from-emerald-950/30 border-secondary/40'
        : 'bg-gradient-to-r from-amber-50 via-surface-container-lowest to-surface-container-lowest dark:from-amber-950/30 border-amber-500/40'"
    >
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        
        <!-- Estado e Info -->
        <div class="flex items-start gap-4 max-w-3xl">
          <div
            class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-xs"
            :class="isAssigned ? 'bg-secondary text-on-secondary' : 'bg-amber-500 text-amber-950'"
          >
            <span class="material-symbols-outlined text-[28px]">
              {{ isAssigned ? 'contact_phone' : 'hourglass_top' }}
            </span>
          </div>

          <div class="flex flex-col gap-1.5">
            <div class="flex flex-wrap items-center gap-2">
              <span
                class="px-2.5 py-0.5 rounded-full font-label-sm text-label-sm uppercase tracking-wider font-extrabold"
                :class="isAssigned
                  ? 'bg-secondary-fixed text-on-secondary-fixed'
                  : 'bg-amber-500/20 text-amber-800 dark:text-amber-300'"
              >
                {{ isAssigned ? 'Validador Designado' : 'Esperando asignación de validador cercano' }}
              </span>
              <span class="text-xs font-mono font-bold text-on-surface-variant">
                Trámite #{{ vr?.id ?? offer.id }}-TS/26
              </span>
            </div>

            <h2 class="font-headline-md text-headline-md text-primary font-bold">
              {{ isAssigned
                ? 'El validador matriculado se comunicará con usted'
                : 'Solicitud enviada a la Mesa de Supervisores de San Juan' }}
            </h2>

            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
              <template v-if="isAssigned">
                Para garantizar la seguridad de su predio y de los inquilinos, la visita técnica in situ será realizada por el inspector oficial detallado abajo. <strong>Al contactarlo, el validador deberá informarle su Token de Seguridad</strong> para verificar su identidad antes del ingreso.
              </template>
              <template v-else>
                Estamos asignando el inspector oficial más cercano en el departamento <strong>{{ offer.department }}</strong>. Una vez comisionado, verá aquí su credencial oficial, matrícula y datos de contacto directo.
              </template>
            </p>
          </div>
        </div>

        <!-- Token de Seguridad Oficial Box -->
        <div class="bg-surface-container-lowest p-4 rounded-xl border border-surface-container flex flex-col items-center justify-center text-center gap-1.5 shrink-0 min-w-[220px] shadow-sm">
          <div class="flex items-center gap-1 font-label-sm text-label-sm text-on-surface-variant uppercase font-bold">
            <span class="material-symbols-outlined text-[16px] text-secondary">verified_user</span>
            <span>Token Antifraude</span>
          </div>
          <div class="font-mono text-2xl font-black tracking-wider text-primary py-1 px-3 bg-surface-container rounded-lg border border-surface-container-high">
            {{ token }}
          </div>
          <p class="text-[11px] text-on-surface-variant max-w-[200px] leading-tight">
            El validador debe mencionarle este código al coordinar la visita.
          </p>
          <button
            type="button"
            class="text-[11px] font-bold text-secondary hover:underline cursor-pointer pt-1 flex items-center gap-1"
            @click="copyToken"
          >
            <span class="material-symbols-outlined text-[14px]">content_copy</span>
            <span>Copiar Token</span>
          </button>
        </div>

      </div>

      <!-- DETALLE DEL VALIDADOR ASIGNADO SI YA ESTÁ COMISIONADO -->
      <div
        v-if="isAssigned && validator"
        class="mt-5 pt-4 border-t border-secondary/20 grid grid-cols-1 md:grid-cols-3 gap-4 bg-surface-container-lowest/80 p-4 rounded-xl border"
      >
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shrink-0">
            {{ validator.name.charAt(0) }}
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Inspector Matriculado</span>
            <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ validator.name }}</span>
            <span class="text-xs text-on-surface-variant">Radicado en: {{ validator.origin_city ?? offer.department }}</span>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-primary shrink-0">
            <span class="material-symbols-outlined text-[20px]">badge</span>
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Documentación & Matrícula</span>
            <span class="text-xs font-bold text-on-surface">DNI {{ validator.dni ?? 'Oficial Habilitado' }}</span>
            <span class="text-xs text-secondary font-semibold">Credencial Activa San Juan Verificado</span>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-700 flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined text-[20px]">call</span>
          </div>
          <div class="flex flex-col">
            <span class="text-[11px] uppercase tracking-wider text-on-surface-variant font-bold">Contacto Oficial</span>
            <span class="text-xs font-bold text-primary">{{ validator.phone }}</span>
            <a
              :href="`https://wa.me/${validator.phone.replace(/[^0-9]/g, '')}?text=Hola%20${encodeURIComponent(validator.name)},%20te%20contacto%20por%20la%20verificaci%C3%B3n%20de%20${encodeURIComponent(offer.title)}`"
              target="_blank"
              class="text-xs text-secondary hover:underline font-bold flex items-center gap-1 pt-0.5"
            >
              <span class="material-symbols-outlined text-[14px]">chat</span> Enviar WhatsApp al Inspector
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- SECCIÓN INFORMATIVA: REQUISITOS OFICIALES Y PLANES DE SEGURO HOMOLOGADOS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      <!-- 1. Pólizas Homologadas Triunfo Seguros (Mensual / Anual) (5 cols) -->
      <div class="lg:col-span-5 bg-surface-container-lowest rounded-xl p-5 border border-surface-container shadow-xs flex flex-col justify-between gap-4">
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-secondary-fixed/50 text-on-secondary-fixed font-label-sm text-label-sm font-bold">
              <span class="material-symbols-outlined text-[16px] text-secondary">health_and_safety</span>
              Triunfo Seguros Oficial
            </span>
            <span class="text-xs font-mono font-bold text-on-surface-variant">Ley Prov. San Juan</span>
          </div>

          <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
            Póliza de Responsabilidad Civil
          </h3>
          <p class="text-xs text-on-surface-variant leading-relaxed">
            Para habilitar el predio y comenzar a publicar tarifas y recibir reservas, la propiedad debe contar con el microseguro homologado para alquileres diurnos.
          </p>

          <!-- Opciones de Cobertura -->
          <div class="flex flex-col gap-2 pt-1">
            <div class="p-3 rounded-xl bg-surface-container-low border border-surface-container flex items-center justify-between">
              <div class="flex flex-col">
                <span class="font-bold text-xs text-primary">Plan Mensual Temporada</span>
                <span class="text-[11px] text-on-surface-variant">Cobertura RC por 30 días para eventos familiares</span>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-surface-container text-primary font-mono text-xs font-extrabold">
                Por Jornada / Mes
              </span>
            </div>

            <div class="p-3 rounded-xl bg-surface-container-low border border-secondary/30 flex items-center justify-between">
              <div class="flex flex-col">
                <span class="font-bold text-xs text-secondary flex items-center gap-1">
                  Plan Anual Bonificado <span class="material-symbols-outlined text-[14px]">stars</span>
                </span>
                <span class="text-[11px] text-on-surface-variant">Protección integral 365 días + Sello Oro</span>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-secondary-fixed text-on-secondary-fixed font-mono text-xs font-extrabold">
                Anual 12 Meses
              </span>
            </div>
          </div>
        </div>

        <div class="pt-2 border-t border-surface-container flex items-center justify-between text-xs text-on-surface-variant">
          <span>Emitido tras la aprobación in situ</span>
          <span class="text-secondary font-bold flex items-center gap-0.5">
            <span class="material-symbols-outlined text-[14px]">verified</span> Sin adelantos previos
          </span>
        </div>
      </div>

      <!-- 2. Matriz de Requisitos Oficiales a Evaluar por el Validador (7 cols) -->
      <div class="lg:col-span-7 bg-surface-container-lowest rounded-xl p-5 border border-surface-container shadow-xs flex flex-col justify-between gap-4">
        <div class="flex flex-col gap-2">
          <div class="flex items-center justify-between pb-2 border-b border-surface-container">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[20px]">assignment_turned_in</span>
              <h3 class="font-headline-sm text-headline-sm text-primary font-bold">
                Criterios Oficiales de Auditoría In Situ
              </h3>
            </div>
            <span class="text-[11px] text-on-surface-variant font-medium">Auditoría Presencial</span>
          </div>

          <p class="text-xs text-on-surface-variant">
            Durante la visita, el validador matriculado cotejará las instalaciones físicas y completará el Acta Oficial con los siguientes ítems:
          </p>

          <!-- Lista de Items a Evaluar con enlaces de consulta -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
            <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[18px]">fence</span>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-primary">Cerco Perimetral de Pileta</span>
                <span class="text-[10px] text-on-surface-variant">Altura mín. 1.20m con traba de seguridad</span>
              </div>
            </div>

            <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[18px]">electric_bolt</span>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-primary">Disyuntor y Puesta a Tierra</span>
                <span class="text-[10px] text-on-surface-variant">Tablero eléctrico estanco certificado</span>
              </div>
            </div>

            <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[18px]">fire_extinguisher</span>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-primary">Matafuego ABC con Carga Vigente</span>
                <span class="text-[10px] text-on-surface-variant">Ubicado cerca del quincho/parrillero</span>
              </div>
            </div>

            <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[18px]">medical_services</span>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-primary">Botiquín de Primeros Auxilios</span>
                <span class="text-[10px] text-on-surface-variant">Elementos básicos y números de emergencia</span>
              </div>
            </div>

            <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[18px]">photo_camera</span>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-primary">Relevamiento Fotográfico GPS</span>
                <span class="text-[10px] text-on-surface-variant">Fotos geolocalizadas tomadas por el auditor</span>
              </div>
            </div>

            <div class="p-2.5 rounded-lg bg-surface-container-low border border-surface-container flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-[18px]">pin_drop</span>
              <div class="flex flex-col">
                <span class="text-xs font-bold text-primary">Coordenadas y Titularidad</span>
                <span class="text-[10px] text-on-surface-variant">Cotejo de ubicación real y DNI titular</span>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-2 border-t border-surface-container flex flex-wrap items-center justify-between gap-2 text-xs">
          <span class="text-on-surface-variant">¿Tenés dudas sobre las normas de seguridad?</span>
          <a
            href="https://turismo.sanjuan.gob.ar"
            target="_blank"
            class="text-secondary font-bold hover:underline flex items-center gap-1"
          >
            <span>Ver Protocolo de Seguridad Oficial</span>
            <span class="material-symbols-outlined text-[14px]">open_in_new</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
