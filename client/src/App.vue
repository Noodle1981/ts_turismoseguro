<script setup lang="ts">
import { RouterView } from 'vue-router'
import AppHeader from './components/layout/AppHeader.vue'
import AppFooter from './components/layout/AppFooter.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-background text-on-surface antialiased">
    <AppHeader />
    <RouterView />
    <AppFooter />
  </div>
</template>
