import { ref, computed, watch, type Ref } from 'vue'
import { OFFICIAL_PROVINCIAL_EVENTS } from '../../data/mockPropietarioData'
import type { ProvincialEvent, PropietarioOferta } from '../../types/propietario'

// Estado compartido a nivel módulo para persistencia de vinculaciones
const officialProvincialEvents = ref<ProvincialEvent[]>(OFFICIAL_PROVINCIAL_EVENTS)
const specialEventBookings = ref<
  Record<string, { eventSlug: string; title: string; specialRate: number; allowsOvernight: boolean }>
>({
  '8_2026-11-19': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-11-20': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-11-21': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-11-22': { eventSlug: 'fns-2026', title: 'FNS 2026', specialRate: 85000, allowsOvernight: true },
  '8_2026-12-24': { eventSlug: 'navidad-2026', title: 'Navidad 2026', specialRate: 90000, allowsOvernight: true },
  '8_2026-12-25': { eventSlug: 'navidad-2026', title: 'Navidad 2026', specialRate: 90000, allowsOvernight: true },
  '8_2026-12-31': { eventSlug: 'ano-nuevo-2027', title: 'Año Nuevo 2027', specialRate: 110000, allowsOvernight: true },
  '8_2027-01-01': { eventSlug: 'ano-nuevo-2027', title: 'Año Nuevo 2027', specialRate: 110000, allowsOvernight: true },
})

export function useSpecialEvents(
  selectedOffer: Ref<PropietarioOferta>,
  selectedYear: Ref<number>,
  selectedMonth: Ref<number>
) {
  const showSpecialEventModal = ref(false)
  const specialEventForm = ref({
    selectedEventId: 1,
    startDate: '2026-11-19',
    endDate: '2026-11-22',
    rate: 85000,
    allowsOvernight: true,
  })

  const relevantEventsForOffer = computed(() => {
    const dept = selectedOffer.value?.department || ''
    return officialProvincialEvents.value.filter((evt) => {
      if (evt.impactDepartments.includes('TODOS')) return true
      return evt.impactDepartments.some((d) => dept.toLowerCase().includes(d.toLowerCase()))
    })
  })

  const currentMonthEvents = computed(() => {
    const targetYear = selectedYear.value
    const targetMonth = selectedMonth.value
    return relevantEventsForOffer.value.filter((evt) => {
      const [y1, m1] = evt.startDate.split('-').map(Number)
      const [y2, m2] = evt.endDate.split('-').map(Number)
      return (y1 === targetYear && m1 === targetMonth) || (y2 === targetYear && m2 === targetMonth)
    })
  })

  const selectedSpecialEventDef = computed(() => {
    return officialProvincialEvents.value.find((e) => e.id === specialEventForm.value.selectedEventId)
  })

  watch(
    () => specialEventForm.value.selectedEventId,
    (newId) => {
      const ev = officialProvincialEvents.value.find((e) => e.id === newId)
      if (ev) {
        specialEventForm.value.startDate = ev.startDate
        specialEventForm.value.endDate = ev.endDate
        const baseRate = selectedOffer.value?.tariffs?.eventoEspecial || 85000
        specialEventForm.value.rate = Math.round(baseRate * (ev.suggestedMultiplier / 1.5))
        specialEventForm.value.allowsOvernight = ev.allowsOvernight
      }
    }
  )

  const openSpecialEventModal = () => {
    const evts = relevantEventsForOffer.value
    if (evts.length > 0) {
      const ev = evts[0]
      specialEventForm.value.selectedEventId = ev.id
      specialEventForm.value.startDate = ev.startDate
      specialEventForm.value.endDate = ev.endDate
      const baseRate = selectedOffer.value?.tariffs?.eventoEspecial || 85000
      specialEventForm.value.rate = Math.round(baseRate * (ev.suggestedMultiplier / 1.5))
      specialEventForm.value.allowsOvernight = ev.allowsOvernight
    }
    showSpecialEventModal.value = true
  }

  const applySpecialEventToCalendar = () => {
    const evt = selectedSpecialEventDef.value
    if (!evt) return

    const [startY, startM, startD] = specialEventForm.value.startDate.split('-').map(Number)
    const [endY, endM, endD] = specialEventForm.value.endDate.split('-').map(Number)
    const offerId = selectedOffer.value.id

    const current = new Date(startY, startM - 1, startD)
    const end = new Date(endY, endM - 1, endD)

    while (current <= end) {
      const yStr = current.getFullYear()
      const mStr = String(current.getMonth() + 1).padStart(2, '0')
      const dStr = String(current.getDate()).padStart(2, '0')
      const dateKey = `${offerId}_${yStr}-${mStr}-${dStr}`

      specialEventBookings.value[dateKey] = {
        eventSlug: evt.slug,
        title: evt.shortTitle,
        specialRate: specialEventForm.value.rate,
        allowsOvernight: specialEventForm.value.allowsOvernight,
      }

      current.setDate(current.getDate() + 1)
    }

    if (selectedOffer.value?.tariffs) {
      selectedOffer.value.tariffs.eventoEspecial = specialEventForm.value.rate
    }
    selectedYear.value = startY
    selectedMonth.value = startM
    showSpecialEventModal.value = false
    window.alert(
      `¡Fechas vinculadas exitosamente a ${evt.title}!\n\nSe asignó tarifa especial de $${specialEventForm.value.rate.toLocaleString()} ARS ${specialEventForm.value.allowsOvernight ? '(con pernocte habilitado)' : '(jornada diurna)'}.`
    )
  }

  return {
    officialProvincialEvents,
    specialEventBookings,
    relevantEventsForOffer,
    currentMonthEvents,
    showSpecialEventModal,
    specialEventForm,
    selectedSpecialEventDef,
    openSpecialEventModal,
    applySpecialEventToCalendar,
  }
}
