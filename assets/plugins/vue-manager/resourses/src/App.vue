<template>
  <component :is="layout" ref="Layout"/>
</template>

<script>
import { defineAsyncComponent } from 'vue'

export default {
  name: 'App',
  data () {
    return {
      Layouts: {}
    }
  },
  computed: {
    key () {
      return this.getKey()
    },
    layout() {
      if (this.$store.state.Settings.user.role) {
        return defineAsyncComponent(() => import('@/layouts/DefaultLayout'))
      } else {
        return defineAsyncComponent(() => import('@/layouts/BlankLayout'))
      }
    }
  },
  methods: {
    getKey (route) {
      route = route || this.$route
      return route.path
      //return route.meta['groupTab'] || route.name === 'DashboardIndex' ? route.name : route.path
    }
  }
}
</script>

<style>
@import './assets/scss/bootstrap.scss';
:root {
  --bs-blue: #1976d2;
  --bs-primary: #1976d2;
  --bs-success: #5cb85c;
  --bs-success-rgb: 92, 184, 92;
  --nprogress-color: #ffc107;
  --nprogress-z-index: 1031;
}
a { color: var(--bs-blue); }
input:focus, select:focus, textarea:focus { box-shadow: none !important }
html { font-size: 0.7777rem; }
body { font-size: 13px; }
html, body, #app { width: 100%; height: 100%; overflow: hidden; }
.nav-tabs .nav-link { cursor: pointer; }
#nprogress .spinner { display: none !important }
#nprogress .bar { /*background: #ffc107 !important;*/ opacity: .5 !important; }
#nprogress .peg { /*box-shadow: 0 0 10px #ffc107, 0 0 5px #ffc107 !important;*/ }
</style>
