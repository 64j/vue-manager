<template>
  <li
    :id="`node` + node.id"
    :class="{
      'parent' : node.isfolder,
      'opened': node.children,
      'hidemenu': node.hidemenu,
      'active': this.$route.name === 'DocumentIndex' && parseInt(this.$route.params.id.toString()) === node.id
    }"
  >
    <a
      class="d-block ps-4 position-relative"
      @click="$emit('action', 'click', node)"
    >
      <span v-if="node.isfolder" class="toggle d-inline-flex justify-content-center text-center" @click.stop="$emit('action', 'toggle', node)">
        <i v-if="node.children" class="fa fa-angle-down"/>
        <i v-else class="fa fa-angle-right"/>
      </span>

      <i
        class="icon me-2"
        :class="icon(node)"
      >
        <i v-if="node.isPrivate" class="fa fa-lock text-danger position-relative"/>
      </i>

      <span
        class="title"
        :class="{'text-danger': !node.published}"
        :title="title(node)">
        {{ node.pagetitle }}
      </span>

      <small class="ms-2">({{ node.id }})</small>
    </a>

    <ul v-if="node.children && node.children.length">
      <tree-node v-for="child in node.children" :key="child.id" :node="child" @action="action"/>
    </ul>
  </li>
</template>

<script>

export default {
  name: 'TreeNode',
  props: {
    node: Object
  },
  methods: {
    icon (node) {
      let icon = ''

      switch (true) {
        case !!node.isfolder:
          if (node.children) {
            icon = 'fa fa-folder-open'
          } else {
            icon = 'fa fa-folder'
          }
          break

        case node.id === this.$store.state.Settings.config['site_start']:
          icon = 'fa fa-home'
          break

        case node.id === this.$store.state.Settings.config['error_page']:
          icon = 'fa fa-exclamation-triangle'
          break

        case node.contenttype !== 'text/html':
          icon = 'far fa-file-code'
          break

        default:
          icon = 'far fa-file'
          break
      }

      return icon
    },
    title (node) {
      return node.title.replace(/&#13;/g, '\n')
    },
    action (action, node) {
      this.$emit('action', action, node)
    }
  }
}
</script>

<style scoped>
ul, li { list-style: none; margin: 0; padding: 0; cursor: default; }
ul { padding-left: 1.5rem; }
ul > li > a { padding-left: 2rem !important; }
li > a { z-index: 0; white-space: nowrap; text-decoration: none; color: var(--bs-gray-300); text-overflow: ellipsis; }
li > a:hover::after, li.active > a::after { content: ""; z-index: -1; position: absolute; left: -100%; top: 0; right: 0; bottom: 0; background-color: var(--bs-gray-800) }
li > a > i { width: 1em; font-size: 1.08em; text-align: center }
li > a > i > i { float: left; margin: -.75rem 0 0 0; font-size: 0.75em; }
li > a .toggle { display: inline-block; margin-left: -1.5rem; width: 1.5rem; cursor: pointer; }
li > a .toggle i { padding: .125rem; width: 1.25rem; height: 1.25rem; font-size: 1em; border-radius: 50%; }
li > a .toggle i:hover { background-color: var(--bs-gray-600) }
li > a .icon:hover { cursor: pointer; color: var(--bs-white) }
li > a .title { color: #7cb2dc; cursor: pointer }
li > a .title:hover { color: #a2d4fb }
li.hidemenu > a .title { color: var(--bs-gray-400) }
li.hidemenu > a .title:hover { color: var(--bs-white); }
</style>
