<template>
  <div class="h-100">
    <div class="flex-grow-0">
      <div class="multi-tabs overflow-hidden">
        <div class="pane d-flex flex-nowrap overflow-auto">
          <a v-for="(tab, i) in tabs"
             :key="i"
             :data-to="tab.fullPath"
             class="d-inline-flex align-items-center ps-3 pe-4 py-2 border-end border-dark text-decoration-none"
             :class="[(isActive(tab) ? 'active bg-light bg-opacity-10 text-white border-primary' : '') + ' ' + tab.class]"
             :title="titleTab(tab.title)"
             @click="clickTab(tab)"
             @dblclick="dblClickTab(tab)">
            <i v-if="tab.icon" :class="tab.icon" class="me-1 opacity-75"></i>
            <span v-html="tab.title"/>
            <i v-if="!tab.meta.fixTab" class="fa fa-close close d-inline-flex position-absolute h-100 align-items-center" @click.stop="closeTab(tab)"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="flex-grow-1 overflow-auto position-relative main-content">
      <div class="multi-tabs-panel">
        <router-view v-slot="{ Component }">
          <KeepAlive :include="keys">
            <component
              :key="key"
              :is="Component"
              @toTab="toTab"
              @closeTab="closeTab"
              @titleTab="setTitleTab"
              @replaceTab="replaceTab"
            />
          </KeepAlive>
        </router-view>
      </div>
      <div class="multi-tabs-panel-frames"></div>
    </div>
  </div>
</template>

<script>
import diff from '@/utils/diff'

export default {
  name: 'MultiTabsView',
  data () {
    return {
      tabs: this.$store.state.MultiTabs.values
    }
  },
  computed: {
    key () {
      return this.tabKey()
    },
    keys () {
      return this.$store.state.MultiTabs.keys
    }
  },
  watch: {
    '$route.fullPath' () {
      this.addTab()
    }
  },
  mounted () {
    this.initTabs()
    this.addTab()
  },
  methods: {
    tabKey (route) {
      return route && route.path || this.$route.path
    },
    isActive (tab) {
      const active = tab.name === this.$route.name && (tab.meta.groupTab || !tab.meta.groupTab && diff(tab.params, this.$route.params))
      if (active) {
        const title = tab.title && tab.title.replace(/<\/?[^>]+>/ig, '').trim() || ''
        document.title = (title && title + ' - ' || '') + this.$store.state.Settings.config['site_name'] + ' (EVO CMS Manager)'
      }
      return active
    },
    titleTab (title) {
      return title && title.replace(/<\/?[^>]+>/ig, '') || ''
    },
    clickTab (tab) {
      this.$router.push(tab.fullPath)
    },
    dblClickTab (tab) {
      this.$router.replace('/redirect' + tab.fullPath).then(() => {
        this.$store.dispatch('MultiTabs/delTabKey', tab)
      })
    },
    setTitleTab (data) {
      for (let i in this.tabs) {
        if (this.isActive(this.tabs[i])) {
          if (typeof data === 'string') {
            this.tabs[i].title = data
          } else {
            Object.assign(this.tabs[i], data)
          }
          break
        }
      }
    },
    route (route) {
      return route.meta['groupTab'] ? this.$router.resolve(route) : route
    },
    initTabs () {
      const fixTabs = this.filterFixTabs(this.$router.getRoutes())
      for (const route of fixTabs) {
        this.addTab(route)
      }
    },
    addTab (route) {
      route = route || this.$route
      if (route.name && !route.meta['noTab']) {
        this.$store.dispatch('MultiTabs/addTab', this.route(route)).then(() => {
          const key = this.tabKey(route)
          const panel = this.$el.querySelector('.multi-tabs-panel')
          const frames = this.$el.querySelector('.multi-tabs-panel-frames')
          let isFrame = false
          let frame = frames.querySelector('iframe[data-key="' + key + '"]')
          frames.querySelectorAll('iframe[data-key]').forEach(f => f.style.display = 'none')
          if (frame) {
            frame.style.display = ''
            isFrame = true
          }
          frame = panel.querySelector('iframe')
          if (frame) {
            frame.style.display = ''
            frame.dataset.key = key
            frames.appendChild(frame)
            isFrame = true
          }
          panel.style.display = isFrame ? 'none' : ''
          frames.style.display = isFrame ? '' : 'none'
        })
      }
    },
    closeTab (tab) {
      const key = this.tabKey(tab)
      const frames = this.$el.querySelector('.multi-tabs-panel-frames')
      frames.querySelectorAll('iframe[data-key="' + key + '"]').forEach(i => i.parentElement.removeChild(i))
      if (this.isActive(tab)) {
        this.toPrevTab(tab, () => this.$store.dispatch('MultiTabs/delTab', tab))
      } else {
        this.$store.dispatch('MultiTabs/delTab', tab)
      }
    },
    replaceTab(params) {
      const route = this.$route
      const tab = this.$router.resolve(params)
      this.$store.dispatch('MultiTabs/replaceTab', { route, tab }).then(({ tab }) => {
        this.$router.replace(tab).then(() => {
          this.$store.dispatch('MultiTabs/delTabKey', route)
        })
      })
    },
    toPrevTab(tab, callback) {
      const index = this.tabs.map(i => i.path).indexOf(tab.path) - 1
      const prevTab = this.tabs[index]
      if (prevTab) {
        return this.$router.push(prevTab.fullPath).then(callback)
      }
    },
    toLastTab (tabs, tab) {
      const latest = tabs.slice(-1)[0]
      if (latest) {
        return this.$router.push(latest.fullPath)
      } else {
        if (tab.name === 'DashboardIndex') {
          return this.$router.replace({ path: '/redirect' + tab.fullPath })
        } else {
          return this.$router.push('/')
        }
      }
    },
    toTab (to) {
      const route = this.$route
      this.$router.push(to).then(() => {
        this.closeTab(route)
      })
    },
    filterFixTabs (routes, basePath = '/') {
      let tabs = []
      routes.forEach(route => {
        if (route.meta && route.meta.fixTab) {
          const tagPath = this.$router.resolve(basePath, route.path)
          tabs.push(tagPath)
        }
        if (route.children) {
          const _tabs = this.filterFixTabs(route.children, route.path)
          if (_tabs.length >= 1) {
            tabs = [...tabs, ..._tabs]
          }
        }
      })
      return tabs
    }
  }
}
</script>

<style scoped>
.multi-tabs { background: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)); --bs-bg-opacity: .95; }
.multi-tabs a { position: relative; height: 2.2rem; cursor: pointer; user-select: none; color: var(--bs-gray-400); line-height: 1; }
.multi-tabs a:not(.active):hover { background-color: rgba(var(--bs-light-rgb), var(--bs-bg-opacity)) !important; --bs-bg-opacity: 0.05 !important; }
.multi-tabs a.active::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 3px; background: var(--bs-primary); }
.multi-tabs a > span { width: 6rem; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; line-height: .9; }
.multi-tabs .close { position: absolute; top: 0; right: 0; padding: 0 .6rem; height: 100%; font-size: .75rem; }
.multi-tabs .close:hover { color: var(--bs-danger) }
.multi-tabs-panel, .multi-tabs-panel-frames { height: calc(100% - 2.2rem); }
.multi-tabs a.tab-home { padding: 1rem !important; }
.multi-tabs a.tab-home > i { margin: 0 !important; }
.multi-tabs a.tab-home > span { width: 0; }
.main-content { height: calc(100% - 2.2rem); }
</style>
