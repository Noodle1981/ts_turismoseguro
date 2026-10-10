<script setup lang="ts">
import { ref } from 'vue'

defineProps<{
  images: string[]
  mainTitle: string
  locationSubtitle: string
}>()

const activeIndex = ref(0)
</script>

<template>
  <section class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-space-xs mb-space-xl rounded-2xl overflow-hidden shadow-md bg-surface-container-lowest">
    <!-- Main Large Photo (4 cols) -->
    <div class="relative md:col-span-4 lg:col-span-4 h-[320px] sm:h-[420px] lg:h-[480px] group overflow-hidden bg-surface-container">
      <img
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
        :src="images[activeIndex] || images[0]"
        :alt="mainTitle"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-black/20 pointer-events-none"></div>
      <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between">
        <div class="space-y-1">
          <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary text-on-secondary font-label-sm text-label-sm shadow-md backdrop-blur-sm font-bold">
            <span class="material-symbols-outlined text-[15px]">fence</span>
            Pileta con Cerco de Seguridad Homologado
          </span>
          <p class="font-headline-sm text-headline-sm text-surface-container-lowest drop-shadow-md font-bold">
            {{ locationSubtitle }}
          </p>
        </div>
        <button
          type="button"
          class="bg-surface-container-lowest/90 hover:bg-surface-container-lowest text-primary px-3 py-1.5 rounded-lg font-label-sm text-label-sm shadow-md flex items-center gap-1.5 transition-all font-bold cursor-pointer"
        >
          <span class="material-symbols-outlined text-[16px]">photo_library</span>
          <span>Ver {{ images.length }} fotos</span>
        </button>
      </div>
    </div>

    <!-- Right 4 Thumbnail Mosaic (2 cols) -->
    <div class="md:col-span-4 lg:col-span-2 grid grid-cols-2 lg:grid-cols-1 gap-space-xs h-full">
      <button
        v-for="(img, idx) in images.slice(1, 5)"
        :key="idx"
        type="button"
        class="relative h-[155px] sm:h-[200px] lg:h-[116px] group overflow-hidden cursor-pointer"
        @click="activeIndex = idx + 1"
      >
        <img
          class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
          :src="img"
          alt="Foto secundaria quinta"
        />
        <div
          :class="[
            'absolute inset-0 transition-colors',
            activeIndex === idx + 1 ? 'border-2 border-secondary bg-transparent' : 'bg-primary/20 hover:bg-transparent'
          ]"
        ></div>
        <span class="absolute bottom-2 left-2 text-[11px] font-bold text-surface-container-lowest px-2 py-0.5 rounded bg-primary/70 backdrop-blur-sm">
          Foto {{ idx + 2 }}
        </span>
      </button>
    </div>
  </section>
</template>
