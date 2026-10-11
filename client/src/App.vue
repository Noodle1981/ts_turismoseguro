<script setup lang="ts">
import { computed } from 'vue'
import { RouterView, useRoute } from 'vue-router'
import AppHeader from './components/layout/AppHeader.vue'
import AppFooter from './components/layout/AppFooter.vue'

const route = useRoute()
const showLayout = computed(() => !route.meta?.hideLayout)
</script>

<template>
  <div class="min-h-screen flex flex-col bg-background text-on-surface antialiased">
    <AppHeader v-if="showLayout" />
    <RouterView />
    <AppFooter v-if="showLayout" />
  </div>
</template>
