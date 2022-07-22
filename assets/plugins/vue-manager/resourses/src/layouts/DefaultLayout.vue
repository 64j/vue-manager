<template>
  <div class="app position-relative d-flex flex-nowrap flex-column h-100 w-100" v-if="loading">
    <notifications style="top: .5rem"/>
    <div class="app-header flex-grow-0 position-relative border-bottom">
      <MenuView ref="Menu"/>
    </div>
    <div class="app-body flex-grow-1 d-flex flex-nowrap position-relative">
      <div class="app-tree col-auto overflow-hidden">
        <Tree ref="Tree"/>
      </div>
      <div class="app-main col d-flex flex-nowrap flex-column bg-light">
        <MultiTabs ref="MultiTabs"/>
      </div>
    </div>
  </div>
</template>

<script>
import MenuView from '@/components/Menu'
import Tree from '@/components/Tree'
import MultiTabs from '@/components/MultiTabs'

export default {
  name: 'DefaultLayout',
  components: { MenuView, Tree, MultiTabs },
  data() {
    return {
      loading: false
    }
  },
  created () {
    this.loading = true
  }
}
</script>

<style scoped>
.app-header { z-index: 2; height: 3rem; background: var(--bs-dark); border-color: var(--bs-dark) !important; }
.app-body { z-index: 1; height: calc(100% - 3rem); }
.app-tree { width: 20rem; }
.app-main { width: calc(100% - 20rem); }
</style>
