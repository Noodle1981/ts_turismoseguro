<script setup lang="ts">
import { computed } from 'vue'
import { ShieldCheck, Clock, CheckCircle2, AlertCircle, XCircle } from 'lucide-vue-next'

const props = defineProps<{
  status: string
  certificateNumber?: string | null
}>()

const statusConfig = computed(() => {
  switch (props.status) {
    case 'verified':
      return {
        label: 'Verificado Oficial',
        desc: 'Inspección completada con éxito. Tu distintivo y sello QR están activos en el catálogo público.',
        color: 'text-safe-700 bg-safe-50 border-safe-200',
        step: 4,
        icon: ShieldCheck,
      }
    case 'in_review':
    case 'visited':
      return {
        label: 'Acta en Revisión por Supervisión',
        desc: 'El validador completó la inspección presencial y el supervisor está emitiendo el dictamen técnico.',
        color: 'text-brand-700 bg-brand-50 border-brand-200',
        step: 3,
        icon: Clock,
      }
    case 'assigned':
      return {
        label: 'Validador Asignado',
        desc: 'Se ha asignado un inspector técnico en tu departamento. Te contactarán para coordinar la visita in situ.',
        color: 'text-sky-700 bg-sky-50 border-sky-200',
        step: 2,
        icon: Clock,
      }
    case 'rejected':
      return {
        label: 'Observado / Rechazado',
        desc: 'El inmueble no cumple con las exigencias de seguridad requeridas por Turismo Seguro San Juan.',
        color: 'text-red-700 bg-red-50 border-red-200',
        step: 1,
        icon: XCircle,
      }
    default:
      return {
        label: 'Pendiente de Validación',
        desc: 'Solicitud enviada al equipo de supervisores para designar un validador zonal.',
        color: 'text-amber-700 bg-amber-50 border-amber-200',
        step: 1,
        icon: AlertCircle,
      }
  }
})
</script>

<template>
  <div class="p-4 sm:p-5 rounded-2xl border bg-white space-y-4 shadow-xs" :class="statusConfig.color">
    <div class="flex items-start justify-between gap-3">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" :class="statusConfig.color">
          <component :is="statusConfig.icon" class="w-6 h-6" />
        </div>
        <div>
          <span class="text-xs uppercase font-extrabold tracking-wider">Estado de Verificación</span>
          <h4 class="text-sm font-bold text-surface-900">{{ statusConfig.label }}</h4>
        </div>
      </div>

      <span
        v-if="certificateNumber"
        class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-safe-100 text-safe-800"
      >
        {{ certificateNumber }}
      </span>
    </div>

    <p class="text-xs text-surface-600 leading-relaxed">
      {{ statusConfig.desc }}
    </p>

    <!-- Mini Progress Stepper -->
    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-surface-500">
      <div class="flex items-center gap-1.5" :class="statusConfig.step >= 1 ? 'text-brand-600 font-bold' : ''">
        <CheckCircle2 class="w-3.5 h-3.5" />
        <span>1. Solicitud</span>
      </div>
      <div class="w-4 h-0.5 bg-slate-200"></div>
      <div class="flex items-center gap-1.5" :class="statusConfig.step >= 2 ? 'text-brand-600 font-bold' : ''">
        <CheckCircle2 class="w-3.5 h-3.5" />
        <span>2. Visita In Situ</span>
      </div>
      <div class="w-4 h-0.5 bg-slate-200"></div>
      <div class="flex items-center gap-1.5" :class="statusConfig.step >= 3 ? 'text-brand-600 font-bold' : ''">
        <CheckCircle2 class="w-3.5 h-3.5" />
        <span>3. Dictamen</span>
      </div>
      <div class="w-4 h-0.5 bg-slate-200"></div>
      <div class="flex items-center gap-1.5" :class="statusConfig.step >= 4 ? 'text-safe-600 font-bold' : ''">
        <ShieldCheck class="w-3.5 h-3.5" />
        <span>4. Sello QR</span>
      </div>
    </div>
  </div>
</template>
