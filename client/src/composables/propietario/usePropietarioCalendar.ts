import { ref, computed, type Ref } from 'vue'
import { useConfirmDialog } from './useConfirmDialog'
import {
  AVAILABLE_YEARS,
  AVAILABLE_MONTHS,
  WEEK_DAY_DEFS,
  SEPTEMBER_DAYS_OLIVOS,
  SEPTEMBER_DAYS_REFUGIO,
  OCTOBER_DAYS_OLIVOS,
  OCTOBER_DAYS_REFUGIO,
} from '../../data/mockPropietarioData'
import type { CalendarDay, PropietarioOferta, NewBookingPayload } from '../../types/propietario'


export function usePropietarioCalendar(
  selectedOffer: Ref<PropietarioOferta>,
  selectedYear: Ref<number>,
  selectedMonth: Ref<number>,
  customBookedDays: Ref<Record<string, { title: string; subtitle: string; icon: string; modality: string }>>,
  internalBlocks: Ref<Record<string, { type: 'familiar' | 'mantenimiento' | 'externo'; title: string; subtitle: string; icon: string }>>,
  specialEventBookings: Ref<Record<string, { eventSlug: string; title: string; specialRate: number; allowsOvernight: boolean }>>,
  showBookingModal: Ref<boolean>,
  newBooking: Ref<NewBookingPayload>
) {

  const availableYears = AVAILABLE_YEARS
  const availableMonths = AVAILABLE_MONTHS
  const weekDayDefs = WEEK_DAY_DEFS

  const showScheduleModal = ref(false)

  // Meses de respaldo en memoria mutables para la sesión
  const septOlivos = ref<CalendarDay[]>(JSON.parse(JSON.stringify(SEPTEMBER_DAYS_OLIVOS)))
  const septRefugio = ref<CalendarDay[]>(JSON.parse(JSON.stringify(SEPTEMBER_DAYS_REFUGIO)))
  const octOlivos = ref<CalendarDay[]>(JSON.parse(JSON.stringify(OCTOBER_DAYS_OLIVOS)))
  const octRefugio = ref<CalendarDay[]>(JSON.parse(JSON.stringify(OCTOBER_DAYS_REFUGIO)))

  const prevMonth = () => {
    if (selectedMonth.value === 1) {
      if (selectedYear.value > 2026) {
        selectedMonth.value = 12
        selectedYear.value--
      }
    } else {
      selectedMonth.value--
    }
  }

  const nextMonth = () => {
    if (selectedMonth.value === 12) {
      if (selectedYear.value < 2028) {
        selectedMonth.value = 1
        selectedYear.value++
      }
    } else {
      selectedMonth.value++
    }
  }

  // Métricas reactivas al filtro de mes/año y a la oferta seleccionada
  const currentMetrics = computed(() => {
    const base = selectedOffer.value.metrics
    const isOlivos = selectedOffer.value.id === 8

    // Octubre 2026 (Mes Actual)
    if (selectedMonth.value === 10 && selectedYear.value === 2026) {
      return {
        jornadas: isOlivos ? 14 : 10,
        jornadasDiff: isOlivos ? '+3 jornadas vs. mes pasado' : '+2 jornadas vs. mes pasado',
        ocupacion: isOlivos ? 78 : 65,
        ingresosEstimados: isOlivos ? 630000 : 520000,
        ingresosCobrado: isOlivos ? 490000 : 410000,
        reputacion: 0,
        resenasCount: 0,
      }
    }

    // Septiembre 2026 (Mes Pasado Concluido)
    if (selectedMonth.value === 9 && selectedYear.value === 2026) {
      return {
        jornadas: isOlivos ? 11 : 8,
        jornadasDiff: '100% concluidas en Septiembre',
        ocupacion: isOlivos ? 72 : 60,
        ingresosEstimados: isOlivos ? 550000 : 420000,
        ingresosCobrado: isOlivos ? 550000 : 420000,
        reputacion: 0,
        resenasCount: 0,
      }
    }

    // Otros meses / Proyecciones
    const factor = (selectedMonth.value % 4) + 1
    return {
      ...base,
      jornadas: 8 + factor * 2,
      jornadasDiff: `Histórico ${selectedMonth.value}/${selectedYear.value}`,
      ocupacion: 50 + factor * 8,
      ingresosEstimados: Math.round(base.ingresosEstimados * (0.7 + factor * 0.1)),
      ingresosCobrado: Math.round(base.ingresosCobrado * (0.7 + factor * 0.1)),
    }
  })

  const toggleActiveDay = (dayId: number) => {
    const active = selectedOffer.value.schedule.activeDays
    const index = active.indexOf(dayId)
    if (index >= 0) {
      if (active.length === 1) {
        const { showAlert } = useConfirmDialog()
        showAlert({
          title: 'Configuración Requerida',
          message: 'Debe quedar al menos un día habilitado para la oferta.',
          icon: 'warning',
        })
        return
      }
      active.splice(index, 1)
    } else {
      active.push(dayId)
    }
  }

  const setSchedulePreset = (preset: 'all' | 'thu-sun' | 'fri-sun') => {
    if (preset === 'all') {
      selectedOffer.value.schedule.activeDays = [1, 2, 3, 4, 5, 6, 0]
    } else if (preset === 'thu-sun') {
      selectedOffer.value.schedule.activeDays = [4, 5, 6, 0]
    } else if (preset === 'fri-sun') {
      selectedOffer.value.schedule.activeDays = [5, 6, 0]
    }
  }

  const calculateHoursDuration = (start: string, end: string) => {
    const [h1, m1] = (start || '10:00').split(':').map(Number)
    const [h2, m2] = (end || '20:00').split(':').map(Number)
    let diff = (h2 * 60 + (m2 || 0)) - (h1 * 60 + (m1 || 0))
    if (diff < 0) diff += 24 * 60
    const hours = Math.floor(diff / 60)
    return `${hours}`
  }

  // Cálculo de columna inicial del mes
  const firstDayOffset = computed(() => {
    const jsDay = new Date(selectedYear.value, selectedMonth.value - 1, 1).getDay()
    return jsDay === 0 ? 7 : jsDay
  })

  // Cuadrícula reactiva de días computados
  const calendarDays = computed<CalendarDay[]>(() => {
    const isOlivos = selectedOffer.value.id === 8
    const offerId = selectedOffer.value.id
    const activeDays = selectedOffer.value.schedule?.activeDays || [4, 5, 6, 0]
    const yr = selectedYear.value
    const mo = selectedMonth.value

    let baseDays: CalendarDay[] = []
    if (yr === 2026 && mo === 9) {
      baseDays = isOlivos ? septOlivos.value : septRefugio.value
    } else if (yr === 2026 && mo === 10) {
      baseDays = isOlivos ? octOlivos.value : octRefugio.value
    } else {
      const totalDays = new Date(yr, mo, 0).getDate()
      baseDays = Array.from({ length: totalDays }, (_, idx) => {
        const dNum = idx + 1
        const dStr = `${yr}-${String(mo).padStart(2, '0')}-${String(dNum).padStart(2, '0')}`
        const isPastDate = dStr < '2026-10-10'
        return {
          day: dNum,
          dateStr: dStr,
          status: isPastDate ? ('past' as const) : ('available' as const),
          isPast: isPastDate,
        }
      })
    }

    return baseDays.map((d) => {
      if (d.isPast || d.status === 'past') return d
      if (d.status === 'booked') return d

      const key = `${offerId}_${d.dateStr}`
      const customB = customBookedDays.value[key]
      if (customB) {
        return {
          ...d,
          status: 'booked' as const,
          title: customB.title,
          subtitle: customB.subtitle,
          icon: customB.icon,
        }
      }

      if (d.status === 'maintenance' || (d.status === 'blocked' && d.title !== 'Bloqueado')) {
        return d
      }

      const internalBlk = internalBlocks.value[key]
      if (internalBlk) {
        const blkStatus =
          internalBlk.type === 'externo'
            ? ('external_booking' as const)
            : internalBlk.type === 'mantenimiento'
              ? ('maintenance' as const)
              : ('blocked' as const)

        return {
          ...d,
          status: blkStatus,
          title: internalBlk.title,
          subtitle: internalBlk.subtitle,
          icon: internalBlk.icon,
        }
      }

      const special = specialEventBookings.value[key]
      if (special) {
        return {
          ...d,
          status: 'special_event' as const,
          title: special.title,
          subtitle: `$${special.specialRate.toLocaleString()} • ${special.allowsOvernight ? 'Con Pernocte' : 'Diurno'}`,
          icon: special.allowsOvernight ? 'bed' : 'festival',
          specialEventSlug: special.eventSlug,
          specialRate: special.specialRate,
          allowsOvernight: special.allowsOvernight,
        }
      }

      const [year, month, dayNum] = d.dateStr.split('-').map(Number)
      const dayOfWeek = new Date(year, month - 1, dayNum).getDay()

      if (!activeDays.includes(dayOfWeek)) {
        return {
          ...d,
          status: 'unconfigured' as const,
          title: 'Bloqueado',
          subtitle: undefined,
          icon: 'block',
        }
      }

      if (d.status === 'unconfigured') {
        return {
          ...d,
          status: 'available' as const,
          title: undefined,
          subtitle: undefined,
          icon: undefined,
        }
      }

      return d
    })
  })

  const toggleCellState = async (dayNumber: number) => {
    const { showConfirm, showAlert } = useConfirmDialog()
    const item = calendarDays.value.find((d) => d.day === dayNumber)
    if (!item) return

    if (item.isPast || item.status === 'past') {
      await showAlert({
        title: 'Fecha Concluida',
        message: `El día ${dayNumber} pertenece a una fecha pasada ya concluida y no puede ser modificada.`,
        icon: 'history',
      })
      return
    }

    if (item.status === 'booked') {
      await showAlert({
        title: 'Turno Reservado con Seña',
        message: `El día ${dayNumber} se encuentra reservado para ${item.title || 'Inquilino'} (${item.subtitle || 'Verificado'}).\n\nLas reservas vigentes no pueden liberarse directamente desde el calendario. Debe gestionarse desde la sección "Registro de Inquilinos".`,
        icon: 'verified_user',
      })
      return
    }

    if (item.status === 'unconfigured') {
      const dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
      const [year, month, dayNum] = item.dateStr.split('-').map(Number)
      const dayOfWeek = new Date(year, month - 1, dayNum).getDay()
      const nameOfDay = dayNames[dayOfWeek]

      const confirmEnable = await showConfirm({
        title: 'Habilitar Día en la Oferta',
        message: `El día ${dayNumber} (${nameOfDay}) figura "Bloqueado" porque los ${nameOfDay}s no están habilitados en la Franja Diurna de tu propiedad.\n\n¿Deseas habilitar los ${nameOfDay}s para que este día pase a estar DISPONIBLE para alquileres?`,
        confirmText: 'Sí, Habilitar Día',
        cancelText: 'Mantener Bloqueado',
        icon: 'calendar_month',
      })

      if (confirmEnable) {
        if (!selectedOffer.value.schedule.activeDays.includes(dayOfWeek)) {
          selectedOffer.value.schedule.activeDays.push(dayOfWeek)
        }
      }
      return
    }

    // Si el día está disponible, abrir directamente el Modal de Reserva con la fecha preseleccionada
    if (item.status === 'available') {
      newBooking.value.date = item.dateStr
      const [y, m, d] = item.dateStr.split('-').map(Number)
      const endD = new Date(y, m - 1, d + 2)
      const endYStr = endD.getFullYear()
      const endMStr = String(endD.getMonth() + 1).padStart(2, '0')
      const endDStr = String(endD.getDate()).padStart(2, '0')
      newBooking.value.endDate = `${endYStr}-${endMStr}-${endDStr}`

      showBookingModal.value = true
      return
    }

    // Si el día está bloqueado, en mantenimiento o reserva particular externa:
    // Modal con confirmación que pregunta si desea pasarlo a disponibilidad
    const monthNames = [
      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ]
    const mName = monthNames[selectedMonth.value - 1] || 'este mes'
    const statusLabel =
      item.status === 'external_booking'
        ? 'Reserva Particular (Externa)'
        : item.status === 'maintenance'
          ? 'Mantenimiento'
          : item.title || 'Bloqueo Privado'

    const confirmLiberar = await showConfirm({
      title: '¿Pasar Fecha a Disponible?',
      message: `El día ${dayNumber} de ${mName} actualmente figura como "${statusLabel}".\n\n¿Deseas liberar esta fecha para que quede DISPONIBLE para alquileres?`,
      confirmText: 'Sí, Pasar a Disponible',
      cancelText: 'Cancelar',
      icon: 'event_available',
    })

    if (!confirmLiberar) return

    // Limpiar bloqueo interno si existía
    const offerId = selectedOffer.value.id
    const key = `${offerId}_${item.dateStr}`
    delete internalBlocks.value[key]

    // Limpiar en arrays base
    const isOlivos = selectedOffer.value.id === 8
    const baseDays =
      selectedMonth.value === 9
        ? isOlivos
          ? septOlivos.value
          : septRefugio.value
        : isOlivos
          ? octOlivos.value
          : octRefugio.value
    const rawItem = baseDays.find((d) => d.day === dayNumber)
    if (rawItem) {
      rawItem.status = 'available'
      rawItem.title = undefined
      rawItem.subtitle = undefined
      rawItem.icon = undefined
    }

    try {
      const token = localStorage.getItem('auth_token')
      if (token) {
        await fetch(`/api/v1/client/properties/${selectedOffer.value.id}/availability/toggle-date`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            Authorization: `Bearer ${token}`,
          },
          body: JSON.stringify({
            date: item.dateStr,
            status: 'available',
            notes: 'Liberado a disponibilidad',
          }),
        })
      }
    } catch (e) {
      console.warn('Sync offline / demo mode:', e)
    }
  }

  return {
    selectedYear,
    selectedMonth,
    availableYears,
    availableMonths,
    weekDayDefs,
    showScheduleModal,
    prevMonth,
    nextMonth,
    currentMetrics,
    toggleActiveDay,
    setSchedulePreset,
    calculateHoursDuration,
    firstDayOffset,
    calendarDays,
    toggleCellState,
  }
}
