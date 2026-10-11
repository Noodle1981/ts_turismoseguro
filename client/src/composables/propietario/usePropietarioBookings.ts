import { ref, computed, type Ref } from 'vue'
import type { PropietarioOferta, NewBookingPayload } from '../../types/propietario'

// Estado compartido a nivel módulo para persistencia de reservas y bloqueos
const customBookedDays = ref<
  Record<string, { title: string; subtitle: string; icon: string; modality: string }>
>({})

const internalBlocks = ref<
  Record<string, { type: 'familiar' | 'mantenimiento' | 'externo'; title: string; subtitle: string; icon: string }>
>({})

export function usePropietarioBookings(
  selectedOffer: Ref<PropietarioOferta>,
  selectedYear: Ref<number>,
  selectedMonth: Ref<number>
) {
  const showBookingModal = ref(false)

  const newBooking = ref<NewBookingPayload>({
    modality: 'diurno',
    blockType: 'familiar',
    tenantName: '',
    tenantDni: '',
    tenantPhone: '',
    tenantCity: 'rawson',
    date: '2026-10-25',
    endDate: '2026-10-27',
    nightStart: '20:00',
    nightEnd: '05:00',
    overnightType: 'camas',
    reason: 'Cumpleaños familiar / infantil',
    adults: 30,
    children: 10,
    insurance: 'mensual',
    notes: '',
    sena: '$0',
    saldo: '$0',
  })

  const totalGuests = computed(() => {
    if (newBooking.value.modality === 'bloqueo') return 0
    return (Number(newBooking.value.adults) || 0) + (Number(newBooking.value.children) || 0)
  })

  const isOverCapacity = computed(() => {
    if (newBooking.value.modality === 'bloqueo') return false
    const cap = newBooking.value.modality === 'multidia' ? 20 : 50
    return totalGuests.value > cap
  })

  const openInternalBlockModal = () => {
    const mStr = String(selectedMonth.value).padStart(2, '0')
    const defaultDay = '15'
    newBooking.value.modality = 'bloqueo'
    newBooking.value.blockType = 'familiar'
    newBooking.value.reason = 'Uso Familiar'
    newBooking.value.notes = 'Reunión familiar / Asado privado'
    newBooking.value.date = `${selectedYear.value}-${mStr}-${defaultDay}`
    newBooking.value.endDate = ''
    showBookingModal.value = true
  }

  const handleAddBooking = () => {
    const offerId = selectedOffer.value.id

    // CASO A: BLOQUEO / INDISPONIBILIDAD (Familiar, Mantenimiento o Reserva Externa)
    if (newBooking.value.modality === 'bloqueo') {
      if (!newBooking.value.date) {
        window.alert('Por favor seleccione una fecha a bloquear.')
        return
      }

      const isFamiliar = newBooking.value.blockType === 'familiar'
      const isExterno = newBooking.value.blockType === 'externo'

      const title = isExterno
        ? 'Reserva Particular (Externa)'
        : isFamiliar
          ? newBooking.value.reason || 'Uso Familiar'
          : 'Mantenimiento'

      const subtitle = isExterno
        ? newBooking.value.notes || 'Alquiler pactado fuera de plataforma'
        : newBooking.value.notes || (isFamiliar ? 'Bloqueo Privado' : 'Trabajos en predio')

      const icon = isExterno ? 'no_crash' : isFamiliar ? 'cottage' : 'construction'

      // Soporte para rango de fechas
      const [startY, startM, startD] = newBooking.value.date.split('-').map(Number)
      const current = new Date(startY, startM - 1, startD)
      const end = newBooking.value.endDate
        ? new Date(
            Number(newBooking.value.endDate.split('-')[0]),
            Number(newBooking.value.endDate.split('-')[1]) - 1,
            Number(newBooking.value.endDate.split('-')[2])
          )
        : new Date(startY, startM - 1, startD)

      while (current <= end) {
        const yStr = current.getFullYear()
        const mStr = String(current.getMonth() + 1).padStart(2, '0')
        const dStr = String(current.getDate()).padStart(2, '0')
        const dateKey = `${offerId}_${yStr}-${mStr}-${dStr}`

        internalBlocks.value[dateKey] = {
          type: newBooking.value.blockType,
          title,
          subtitle,
          icon,
        }

        current.setDate(current.getDate() + 1)
      }

      // Sincronizar mes y año en vista
      selectedYear.value = startY
      selectedMonth.value = startM
      showBookingModal.value = false

      if (isExterno) {
        window.alert(
          `¡Fecha(s) bloqueada(s) con éxito!\n\n` +
            `Estado: "Reserva Particular (Fuera de Plataforma)"\n` +
            `Constancia Legal: La fecha figura NO DISPONIBLE para alquiler en Turismo Seguro San Juan. Ni la plataforma ni el seguro emitido amparan eventos o incidentes acordados por fuera del sistema.`
        )
      } else {
        window.alert(`¡Fecha(s) bloqueada(s) con éxito como ${isFamiliar ? 'Uso Familiar' : 'Mantenimiento'}!`)
      }
      return
    }

    // CASO B: RESERVA DE INQUILINO (Diurno, Multidía o Fiesta)
    if (!newBooking.value.tenantName || !newBooking.value.tenantDni) {
      window.alert('Por favor complete el nombre y DNI del inquilino titular.')
      return
    }

    const initials = newBooking.value.tenantName
      .split(' ')
      .map((p) => p[0])
      .join('')
      .substring(0, 2)
      .toUpperCase()

    let timeStr = '10:00 a 20:00 hs'
    let dateDisplay = newBooking.value.date
    let iconName = 'event_available'

    if (newBooking.value.modality === 'multidia') {
      timeStr = `Estadía Completa (${newBooking.value.overnightType === 'camas' ? 'Camas' : newBooking.value.overnightType === 'carpas' ? 'Carpas/Acampe' : 'Camas + Carpas'})`
      dateDisplay = `${newBooking.value.date} al ${newBooking.value.endDate}`
      iconName = newBooking.value.overnightType === 'carpas' ? 'camping' : 'bed'

      // Bloquear todas las fechas intermedias en el calendario
      const [y1, m1, d1] = newBooking.value.date.split('-').map(Number)
      const [y2, m2, d2] = newBooking.value.endDate.split('-').map(Number)
      const cur = new Date(y1, m1 - 1, d1)
      const end = new Date(y2, m2 - 1, d2)
      while (cur <= end) {
        const yStr = cur.getFullYear()
        const mStr = String(cur.getMonth() + 1).padStart(2, '0')
        const dStr = String(cur.getDate()).padStart(2, '0')
        const k = `${offerId}_${yStr}-${mStr}-${dStr}`
        customBookedDays.value[k] = {
          title: newBooking.value.tenantName,
          subtitle: `Estadía • ${newBooking.value.overnightType === 'carpas' ? 'Carpa' : 'Pernocte'}`,
          icon: iconName,
          modality: 'multidia',
        }
        cur.setDate(cur.getDate() + 1)
      }
    } else if (newBooking.value.modality === 'fiesta_nocturna') {
      timeStr = `Nocturno (${newBooking.value.nightStart} a ${newBooking.value.nightEnd} hs)`
      iconName = 'nightlife'
      const k = `${offerId}_${newBooking.value.date}`
      customBookedDays.value[k] = {
        title: newBooking.value.tenantName,
        subtitle: `Fiesta Nocturna (${newBooking.value.nightStart}-${newBooking.value.nightEnd})`,
        icon: 'nightlife',
        modality: 'fiesta_nocturna',
      }
    } else {
      // Diurno estándar
      const k = `${offerId}_${newBooking.value.date}`
      customBookedDays.value[k] = {
        title: newBooking.value.tenantName,
        subtitle: 'Turno Diurno',
        icon: 'verified',
        modality: 'diurno',
      }
    }

    selectedOffer.value.tenants.unshift({
      id: `INQ-${Math.floor(10 + Math.random() * 90)}`,
      name: newBooking.value.tenantName,
      initials: initials || 'IN',
      dni: newBooking.value.tenantDni,
      phone: newBooking.value.tenantPhone || '+54 9 264 500-0000',
      date: dateDisplay,
      time: timeStr,
      guestsText: `${totalGuests.value} personas (${newBooking.value.adults} adultos, ${newBooking.value.children} niños)`,
      guestsAdults: newBooking.value.adults,
      guestsChildren: newBooking.value.children,
      eventType: newBooking.value.reason,
      insuranceBadge: 'Póliza TS Activa #9482',
      sena: newBooking.value.sena,
      saldo: `${newBooking.value.saldo} en tranquera`,
      history: 'Validado in situ • Aprobado por anfitrión',
      status: 'confirmed',
    })

    // Sincronizar mes en vista
    const [startY, startM] = newBooking.value.date.split('-').map(Number)
    selectedYear.value = startY
    selectedMonth.value = startM

    showBookingModal.value = false
    window.alert(
      `¡Reserva confirmada con éxito!\n\nModalidad: ${newBooking.value.modality === 'multidia' ? 'Estadía Multidía con Pernocte' : newBooking.value.modality === 'fiesta_nocturna' ? 'Fiesta / Evento Nocturno' : 'Jornada Diurna'}.\nFechas aseguradas y bloqueadas en el almanaque.`
    )
  }

  return {
    showBookingModal,
    customBookedDays,
    internalBlocks,
    newBooking,
    totalGuests,
    isOverCapacity,
    openInternalBlockModal,
    handleAddBooking,
  }
}
