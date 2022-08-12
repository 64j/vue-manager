<template>
  <li
    :id="`node` + node.id"
    :class="{
      'parent' : node['isfolder'],
      'opened': node.children,
      'hidemenu': node.hidemenu,
      'active': this.$route.name === 'DocumentIndex' && parseInt(this.$route.params.id.toString()) === node.id
    }"
  >
    <a
      class="d-block ps-4 position-relative"
      @click="$emit('action', 'click', node)"
    >
      <span v-if="node['isfolder']" class="toggle d-inline-flex justify-content-center text-center" @click.stop="$emit('action', 'toggle', node)">
        <i v-if="node.children" class="fa fa-angle-down"/>
        <i v-else class="fa fa-angle-right"/>
      </span>

      <i
        class="icon me-2"
        :class="icon(node)"
      >
        <i v-if="node['isPrivate']" class="fa fa-lock text-danger position-relative"/>
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
import i18n from '@/i18n'

export default {
  name: 'TreeNode',
  props: {
    node: Object
  },
  methods: {
    icon (node) {
      let icon = ''

      switch (true) {
        case !!node['isfolder']:
          if (node.children) {
            icon = 'fa fa-folder-open'
          } else {
            icon = 'fa fa-folder'
          }
          break

        case node.id === this.$store.state['Settings'].config['site_start']:
          icon = 'fa fa-home'
          break

        case node.id === this.$store.state['Settings'].config['error_page']:
          icon = 'fa fa-exclamation-triangle'
          break

        case node['contenttype'] !== 'text/html':
          icon = 'far fa-file-code'
          break

        default:
          icon = 'far fa-file'
          break
      }

      return icon
    },
    title (node) {
      let title = ''
      title += i18n.global.t('pagetitle') + ': ' + node.pagetitle
      title += '\n' + i18n.global.t('id') + ': ' + node.id
      title += '\n' + i18n.global.t('resource_opt_menu_title') + ': ' + node.menutitle
      title += '\n' + i18n.global.t('resource_opt_menu_index') + ': ' + node.menuindex
      title += '\n' + i18n.global.t('alias') + ': ' + (node.alias || '-')
      title += '\n' + i18n.global.t('template') + ': ' + node.templatename
      title += '\n' + i18n.global.t('publish_date') + ': ' + (node.pub_date ? (new Date(node.pub_date * 1000)).toLocaleString() : '')
      title += '\n' + i18n.global.t('unpublish_date') + ': ' + (node.unpub_date ? (new Date(node.unpub_date * 1000)).toLocaleString() : '')
      title += '\n' + i18n.global.t('page_data_web_access') + ': ' + (node.privateweb ? i18n.global.t('private') : i18n.global.t('public'))
      title += '\n' + i18n.global.t('page_data_mgr_access') + ': ' + (node.privatemgr ? i18n.global.t('private') : i18n.global.t('public'))
      title += '\n' + i18n.global.t('resource_opt_richtext') + ': ' + (node.richtext ? i18n.global.t('yes') : i18n.global.t('no'))
      title += '\n' + i18n.global.t('page_data_searchable') + ': ' + (node.searchable ? i18n.global.t('yes') : i18n.global.t('no'))
      title += '\n' + i18n.global.t('page_data_cacheable') + ': ' + (node.searchable ? i18n.global.t('yes') : i18n.global.t('no'))

      return title
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
a { z-index: 0; padding-top: .1rem; padding-bottom: .1rem; white-space: nowrap; text-decoration: none; color: var(--bs-gray-500); text-overflow: ellipsis; }
a::after, li > a::after { content: ""; z-index: -1; position: absolute; left: -100%; top: 0; right: 0; bottom: 0; transition: .2s }
a:hover::after, li.active > a::after { background-color: var(--bs-gray-800) }
a > i { width: 1em; font-size: 1.08em; text-align: center }
a > i > i { float: left; margin: -.75rem 0 0 0; font-size: 0.75em; }
a .toggle { display: inline-block; margin-left: -1.5rem; width: 1.5rem; cursor: pointer; }
a .toggle i { padding: .125rem; width: 1.25rem; height: 1.25rem; font-size: 1em; border-radius: 50%; transition: .2s }
a .toggle i:hover { background-color: var(--bs-gray-600) }
a .icon { color: var(--bs-gray-300); }
a .icon:hover { cursor: pointer; color: var(--bs-white) }
a .title { color: #7cb2dc; cursor: pointer; transition: .2s }
a .title:hover { color: #a2d4fb }
li.hidemenu > a .title { color: var(--bs-gray-400) }
li.hidemenu > a .title:hover { color: var(--bs-white); }
</style>
