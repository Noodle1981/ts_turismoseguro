import { ref } from 'vue'
import type { ConfirmDialogState } from '../../types/propietario'

const dialogState = ref<ConfirmDialogState>({
  isOpen: false,
  title: '',
  message: '',
  confirmText: 'Confirmar',
  cancelText: 'Cancelar',
  type: 'confirm',
})

let resolvePromise: ((value: boolean) => void) | null = null

export function useConfirmDialog() {
  const showConfirm = (options: {
    title: string
    message: string
    confirmText?: string
    cancelText?: string
    type?: 'confirm' | 'danger'
    icon?: string
  }): Promise<boolean> => {
    dialogState.value = {
      isOpen: true,
      title: options.title,
      message: options.message,
      confirmText: options.confirmText || 'Confirmar',
      cancelText: options.cancelText || 'Cancelar',
      type: options.type || 'confirm',
      icon: options.icon,
    }

    return new Promise((resolve) => {
      resolvePromise = resolve
    })
  }

  const showAlert = (options: {
    title: string
    message: string
    confirmText?: string
    icon?: string
  }): Promise<boolean> => {
    dialogState.value = {
      isOpen: true,
      title: options.title,
      message: options.message,
      confirmText: options.confirmText || 'Entendido',
      type: 'alert',
      icon: options.icon || 'info',
    }

    return new Promise((resolve) => {
      resolvePromise = resolve
    })
  }

  const handleConfirm = () => {
    dialogState.value.isOpen = false
    if (resolvePromise) {
      resolvePromise(true)
      resolvePromise = null
    }
  }

  const handleCancel = () => {
    dialogState.value.isOpen = false
    if (resolvePromise) {
      resolvePromise(false)
      resolvePromise = null
    }
  }

  const closeDialog = () => {
    handleCancel()
  }

  return {
    dialogState,
    showConfirm,
    showAlert,
    handleConfirm,
    handleCancel,
    closeDialog,
  }
}
