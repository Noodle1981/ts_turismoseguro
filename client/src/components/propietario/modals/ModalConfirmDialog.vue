<script setup lang="ts">
import type { ConfirmDialogState } from '../../../types/propietario'

defineProps<{
  dialog: ConfirmDialogState
}>()

const emit = defineEmits<{
  (e: 'confirm'): void
  (e: 'cancel'): void
}>()
</script>

<template>
  <div
    v-if="dialog.isOpen"
    class="fixed inset-0 z-50 bg-primary/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto animate-in fade-in duration-150"
    @click.self="emit('cancel')"
  >
    <div
      class="bg-surface-container-lowest rounded-2xl shadow-2xl max-w-md w-full p-space-lg flex flex-col gap-space-md border border-surface-container my-auto animate-in zoom-in-95 duration-150"
      role="dialog"
      aria-modal="true"
      :aria-label="dialog.title"
    >
      <!-- Icono e Indicador de Tipo -->
      <div class="flex items-start gap-space-md">
        <div
          class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
          :class="{
            'bg-secondary/15 text-secondary': !dialog.type || dialog.type === 'confirm',
            'bg-rose-100 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300': dialog.type === 'danger',
            'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300': dialog.type === 'alert',
          }"
        >
          <span class="material-symbols-outlined text-[26px]" aria-hidden="true">
            {{
              dialog.icon ||
              (dialog.type === 'danger'
                ? 'warning'
                : dialog.type === 'alert'
                  ? 'info'
                  : 'help')
            }}
          </span>
        </div>

        <div class="flex flex-col gap-1 min-w-0">
          <h3 class="font-headline-sm text-headline-sm text-primary font-bold tracking-tight">
            {{ dialog.title }}
          </h3>
          <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed whitespace-pre-line">
            {{ dialog.message }}
          </p>
        </div>
      </div>

      <!-- Botones de Acción Oficiales -->
      <div class="flex items-center justify-end gap-space-sm pt-space-xs border-t border-surface-container">
        <button
          v-if="dialog.type !== 'alert'"
          type="button"
          class="px-space-md py-space-sm rounded-lg bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg transition-colors cursor-pointer font-semibold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
          @click="emit('cancel')"
        >
          {{ dialog.cancelText || 'Cancelar' }}
        </button>

        <button
          type="button"
          class="inline-flex items-center justify-center gap-space-xs px-space-lg py-space-sm rounded-lg font-label-lg text-label-lg transition-all shadow-sm font-bold cursor-pointer focus-visible:outline-none focus-visible:ring-2"
          :class="
            dialog.type === 'danger'
              ? 'bg-rose-600 hover:bg-rose-700 text-white focus-visible:ring-rose-500/40'
              : 'bg-secondary hover:bg-on-secondary-container text-on-secondary focus-visible:ring-secondary/40'
          "
          @click="emit('confirm')"
        >
          <span
            v-if="dialog.type !== 'alert'"
            class="material-symbols-outlined text-[18px]"
            aria-hidden="true"
          >
            check_circle
          </span>
          <span>{{ dialog.confirmText || (dialog.type === 'alert' ? 'Entendido' : 'Confirmar') }}</span>
        </button>
      </div>
    </div>
  </div>
</template>
