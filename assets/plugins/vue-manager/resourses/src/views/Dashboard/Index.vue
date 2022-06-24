<template>
  <div class="container-fluid py-2">
    <h1>{{ title }}</h1>
  </div>
</template>

<script>
import i18n from '@/i18n'

export default {
  name: 'DashboardIndex',
  computed: {
    title () {
      return i18n.global.t('home')
    }
  }
}
</script>

<style scoped>

</style>
