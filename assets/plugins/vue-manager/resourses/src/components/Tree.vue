<template>
  <div class="position-relative h-100">
    <div class="separator" @mousedown="resizeMousedown" @mouseup="resizeMouseup"/>
    <teleport to="body">
      <div class="resize-mask" @mousemove="resizeMousemove" @mouseup="resizeMouseup"/>
    </teleport>
    <div class="d-flex flex-column flex-wrap h-100">
      <div class="app-tree-header flex-grow-0">

      </div>
      <div class="app-tree-root flex-grow-1 overflow-hidden bg-dark text-white position-relative">
        <div v-if="loading" class="tree-loader text-center px-1 position-absolute">
          <i class="fa fa-spinner fa-spin"></i>
        </div>
        <div class="ps-4 py-2 pe-2 fw-bolder">{{ $store.state.Settings.config['site_name'] }}</div>
        <ul class="list-unstyled m-0">
          <tree-node
            v-for="node in data"
            :key="node.id"
            :node="node"
            @action="action"
          />
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import TreeNode from '@/components/TreeNode'

export default {
  name: 'TreeView',
  components: { TreeNode },
  data () {
    return {
      controller: 'Tree',
      loading: false,
      x: 0,
      elTree: null,
      elMain: null,
      key: 'widthSideBar',
      data: [],
      meta: []
    }
  },
  mounted () {
    this.elTree = document.querySelector('.app-tree')
    this.elMain = document.querySelector('.app-main')

    this.x = localStorage.getItem(this.key)
    if (this.x) {
      this.elTree.style.width = this.x + 'px'
      this.elMain.style.width = window.innerWidth - this.x + 'px'
    }
    this.get()
  },
  methods: {
    resizeMousedown () {
      document.body.classList.add('tree-resize')
      document.onselectstart = () => false
    },
    resizeMouseup () {
      document.body.classList.remove('tree-resize')
      document.onselectstart = () => null
      localStorage.setItem(this.key, this.x)
    },
    resizeMousemove (e) {
      this.x = Math.abs(e.clientX)
      if (5 > this.x) {
        document.body.classList.remove('tree-resize')
        localStorage.setItem(this.key, this.x)
        return
      }
      this.elTree.style.width = this.x + 'px'
      this.elMain.style.width = window.innerWidth - this.x + 'px'
    },
    get () {
      http.post(this.controller + '@get').then(result => {
        this.data = result.data
        this.meta = result.meta
      })
    },
    action(action, node) {
      switch (action) {
        case 'toggle':
          this.toggle(node)
          break;

        case 'click':
          this.click(node)
          break;
      }
    },
    toggle (node) {
      this.loading = true
      if (node.children) {
        http.post(this.controller + '@get', {
          parent: node.id,
          close: node.id
        }).then(() => {
          delete node.children
          this.loading = false
        })
      } else {
        http.post(this.controller + '@get', {
          parent: node.id
        }).then(result => {
          node.children = result.data
          this.loading = false
        })
      }
    },
    click (node) {
      this.$router.push({
        name: 'DocumentIndex',
        params: {
          id: node.id
        }
      })
    }
  }
}
</script>

<style scoped>
.separator { position: absolute; z-index: 10; top: 0; right: -2px; width: 5px; height: 100%; opacity: .05; cursor: col-resize; transition: opacity .1s }
.separator::before { content: ""; display: block; margin: 0 2px; width: 2px; height: 100%; background: var(--bs-primary) }
.separator:hover, .tree-resize .separator { opacity: 1; }
.resize-mask { display: none; position: absolute; z-index: 9; left: 0; top: 0; right: 0; bottom: 0; cursor: col-resize; }
.tree-resize .resize-mask { display: block; }
.app-tree-header { height: 2rem; background: rgba(var(--bs-dark-rgb), var(--bs-bg-opacity)); --bs-bg-opacity: .95; }
.app-tree-root { overflow-y: auto !important; height: calc(100% - 2rem); }
.tree-loader { z-index: 2; top: 0; right: 0; }
</style>
