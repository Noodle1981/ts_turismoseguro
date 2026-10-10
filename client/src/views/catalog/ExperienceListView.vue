<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCatalog } from '../../composables/useCatalog'
import type { Experience, Department } from '../../types/catalog'
import BadgeVerified from '../../components/common/BadgeVerified.vue'
import {
  Compass,
  MapPin,
  Clock,
  MessageCircle,
  Loader2,
  AlertCircle,
  Sparkles,
} from 'lucide-vue-next'

const { getExperiences, getDepartments, isLoading, errorMessage } = useCatalog()

const experiences = ref<Experience[]>([])
const departments = ref<Department[]>([])
const selectedDepartment = ref('')
const selectedCategory = ref('')

const categories = ['Astronomía', 'Naturaleza', 'Cultural', 'Aventura', 'Gastronómica']

const loadExperiences = async () => {
  try {
    experiences.value = await getExperiences({
      department: selectedDepartment.value || undefined,
      category: selectedCategory.value || undefined,
    })
  } catch {}
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    maximumFractionDigits: 0,
  }).format(price)
}

onMounted(async () => {
  try {
    departments.value = await getDepartments()
  } catch {}
  loadExperiences()
})
</script>

<template>
  <div class="min-h-screen bg-surface-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
      <!-- Header -->
      <div class="border-b border-slate-200/80 pb-6">
        <div class="inline-flex items-center gap-1 text-xs text-brand-600 font-semibold uppercase tracking-wider mb-1">
          <Compass class="w-4 h-4 text-brand-600" />
          <span>Excursiones y Guías Habilitados</span>
        </div>
        <h1 class="text-3xl font-extrabold text-surface-900 font-display tracking-tight">
          Experiencias Auténticas de San Juan
        </h1>
        <p class="text-sm text-surface-500 mt-1">
          Desde el cielo de El Leoncito hasta el Valle de la Luna con prestadores turísticos certificados
        </p>
      </div>

      <!-- Quick Category Pills -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2">
        <button
          type="button"
          :class="[
            'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer',
            selectedCategory === '' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-surface-700 hover:bg-surface-50'
          ]"
          @click="selectedCategory = ''; loadExperiences()"
        >
          Todas las Categorías
        </button>
        <button
          v-for="cat in categories"
          :key="cat"
          type="button"
          :class="[
            'px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer',
            selectedCategory === cat ? 'bg-brand-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-surface-700 hover:bg-surface-50'
          ]"
          @click="selectedCategory = cat; loadExperiences()"
        >
          {{ cat }}
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="py-20 text-center flex flex-col items-center justify-center space-y-3">
        <Loader2 class="w-8 h-8 text-brand-600 animate-spin" />
        <p class="text-sm text-surface-500 font-medium">Cargando experiencias oficiales...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="errorMessage" class="p-6 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 text-red-700">
        <AlertCircle class="w-6 h-6 flex-shrink-0" />
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Grid de Experiencias -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="exp in experiences"
          :key="exp.id"
          class="group bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col"
        >
          <!-- Image -->
          <div class="relative aspect-16/10 overflow-hidden bg-surface-100">
            <img
              :src="exp.images && exp.images[0] ? exp.images[0].image_path : '/img/experiencies/placeholder.jpg'"
              :alt="exp.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
            />
            <div class="absolute top-3 left-3">
              <BadgeVerified size="sm" />
            </div>

            <div
              v-if="exp.featured"
              class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/90 backdrop-blur-xs text-white"
            >
              <Sparkles class="w-3 h-3" />
              <span>Imperdible</span>
            </div>

            <div class="absolute bottom-2 left-3 px-2.5 py-1 rounded-md bg-brand-950/70 backdrop-blur-xs text-[11px] font-semibold text-white uppercase tracking-wider">
              {{ exp.category }}
            </div>
          </div>

          <!-- Body -->
          <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
            <div>
              <div class="flex items-center gap-1.5 text-xs text-surface-500 font-medium mb-1">
                <MapPin class="w-3.5 h-3.5 text-brand-600 flex-shrink-0" />
                <span>{{ exp.main_location }}</span>
              </div>

              <h3 class="font-display font-bold text-surface-900 text-lg leading-snug group-hover:text-brand-600 transition-colors line-clamp-1">
                {{ exp.title }}
              </h3>

              <p class="text-xs text-surface-500 line-clamp-2 mt-2 leading-relaxed">
                {{ exp.short_description }}
              </p>

              <div class="flex items-center gap-3 mt-3 text-xs text-surface-600 font-medium border-t border-slate-100 pt-2">
                <div class="flex items-center gap-1">
                  <Clock class="w-4 h-4 text-surface-400" />
                  <span>{{ exp.duration }}</span>
                </div>
                <span class="text-surface-300">·</span>
                <span class="text-[11px] text-surface-500">Operador: {{ exp.operator_name }}</span>
              </div>
            </div>

            <!-- Price & Contact Action -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
              <div>
                <span class="text-xs text-surface-400 block font-medium">Por persona</span>
                <p class="font-display font-extrabold text-brand-900 text-lg leading-tight">
                  {{ formatPrice(exp.price_per_person) }}
                </p>
              </div>

              <a
                v-if="exp.operator_whatsapp"
                :href="`https://wa.me/${exp.operator_whatsapp.replace(/[^0-9]/g, '')}?text=Hola!%20Consulto%20por%20la%20experiencia%20${encodeURIComponent(exp.title)}%20en%20Turismo%20Seguro`"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-xs cursor-pointer"
              >
                <MessageCircle class="w-3.5 h-3.5" />
                <span>Consultar</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
