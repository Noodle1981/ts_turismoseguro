<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(
  defineProps<{
    size?: 'sm' | 'md' | 'lg'
    withTooltip?: boolean
    verifiedAt?: string
    variant?: 'pill' | 'card'
  }>(),
  {
    size: 'md',
    withTooltip: true,
    variant: 'pill',
  }
)

const sizeClasses = computed(() => {
  switch (props.size) {
    case 'sm':
      return 'px-2 py-0.5 text-xs gap-1'
    case 'lg':
      return 'px-4 py-2 text-sm sm:text-base gap-2'
    default:
      return 'px-3 py-1 text-xs sm:text-sm gap-1.5'
  }
})
</script>

<template>
  <div
    :class="[
      'inline-flex items-center font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-700 rounded-full shadow-safe-glow ring-1 ring-white/40 tracking-wide select-none backdrop-blur-sm transition-all duration-300 hover:scale-105 cursor-default',
      sizeClasses,
    ]"
    :title="withTooltip ? 'Propiedad inspeccionada físicamente in situ por el equipo de Turismo Seguro' : undefined"
  >
    <!-- Pulso indicador de vigencia de auditoría -->
    <span class="relative flex h-2 w-2">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
      <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
    </span>

    <!-- Icono de escudo verificado SVG (Cero emojis) -->
    <svg class="w-4 h-4 text-emerald-100 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
      <path
        fill-rule="evenodd"
        d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zm3.707 6.763a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1-0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
        clip-rule="evenodd"
      />
    </svg>
    <span>Verificado TS</span>
  </div>
</template>
