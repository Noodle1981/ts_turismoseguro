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
      return 'px-2 py-0.5 text-[11px] gap-1'
    case 'lg':
      return 'px-4 py-1.5 text-sm gap-2'
    default:
      return 'px-3 py-1 text-xs gap-1.5'
  }
})
</script>

<template>
  <div
    :class="[
      'inline-flex items-center font-bold text-secondary bg-surface-container-lowest/90 border border-secondary/40 backdrop-blur-md rounded-full shadow-xs tracking-wider uppercase select-none transition-all duration-200 hover:border-secondary hover:shadow-safe-glow cursor-default',
      sizeClasses,
    ]"
    :title="withTooltip ? 'Inspeccionado físicamente in situ bajo el protocolo oficial de Turismo Seguro San Juan' : undefined"
  >
    <span class="relative flex h-2 w-2">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
      <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
    </span>

    <span class="material-symbols-outlined text-[15px] text-secondary flex-shrink-0" style="font-variation-settings: 'FILL' 1;">
      verified
    </span>
    <span>Verificado In Situ</span>
    <span v-if="verifiedAt" class="text-[10px] text-on-surface-variant font-normal normal-case">
      • Insp: {{ verifiedAt }}
    </span>
  </div>
</template>
