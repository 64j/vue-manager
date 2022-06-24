<template>
  <div class="dynamic-tab-pane-control tab-pane" :id="id+`Pane`">
    <div class="tab-row-container">
      <div class="tab-row">
        <template v-for="(tab, index) in tabs">
          <h2 :key="index" @mousedown="select(index)" class="tab" :class="{'selected': index === activeTab}" v-if="!tab.hidden">
            <i class="me-2" :class="tab.icon" v-if="tab.icon"></i>
            <span>{{ tab.title }}</span>
          </h2>
        </template>
      </div>
      <i class="fa fa-angle-left prev"></i>
      <i class="fa fa-angle-right next"></i>
    </div>
    <div v-for="(tab, index) in tabs" :key="index" v-show="index === activeTab" class="tab-page" :id="`tab`+tab.id">
      <component :is="getComponent(index, tab)" v-if="tab.component && !tab.hidden"/>
      <slot :name="tab.id" v-else/>
    </div>
  </div>
</template>

<script>
import { defineAsyncComponent } from 'vue'

export default {
  name: 'TabsView',
  props: {
    tabs: {
      type: Array,
      required: true
    },
    id: {
      type: String,
      required: true
    },
    active: {
      type: [String, Number]
    },
    history: {
      type: [Boolean, Number]
    }
  },
  data () {
    this.components = {}

    return {
      activeTab: parseInt(this.$props.history && this.$route.query[this.$props.id + 'Tab'] || this.$props.active || 0)
    }
  },
  created () {
    this.$watch('$route.query.' + this.$props.id + 'Tab', (tab) => {
      if (tab) {
        this.activeTab = parseInt(tab)
      }
    })
  },
  methods: {
    select (index) {
      this.activeTab = index
      if (this.$props.history) {
        const Tab = {}
        Tab[this.$props.id + 'Tab'] = index
        this.$router.push({ query: Tab })
      }
    },
    getComponent (index, tab) {
      if (index === this.activeTab) {
        if (!this.components[tab.id]) {
          this.components[tab.id] = defineAsyncComponent(tab.component)
        }
        return this.components[tab.id]
      }
      return null
    }
  }
}
</script>

<style>
.tab-row-container { overflow: hidden; position: relative; padding: 0 1.5rem; height: 2.5rem; }
.tab-row-container::before, .tab-row .tab::before { content: ""; position: absolute; left: 0; top: 2.35rem; right: 0; height: 2px; background: #ddd }
.tab-row-container .prev, .tab-row-container .next { position: absolute; top: 0; height: 100%; padding: 0 .5rem; display: inline-flex; align-items: center; cursor: pointer; }
.tab-row-container .prev { left: 0; }
.tab-row-container .next { right: 0; }
.tab-row { overflow: hidden; overflow-x: auto; position: relative; height: 4rem; display: flex; flex-wrap: nowrap; }
.tab-row .tab { position: relative; display: inline-flex; align-items: center; margin: 0 0 -2px; padding: .5rem 1rem; height: 2.5rem; font-size: 1rem; font-weight: 400; text-transform: uppercase; white-space: nowrap; cursor: pointer; }
.tab-row .tab::before { top: auto; bottom: 0; }
.tab-row .tab.selected::before { background-color: var(--bs-primary) }
.tab-row .tab.selected { text-shadow: 0 0; font-weight: 600; }
</style>
