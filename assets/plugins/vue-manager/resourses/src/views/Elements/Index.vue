<template>
  <div>

    <TitleView :title="title" icon="fa fa-th"/>

    <Tabs
      id="resources"
      :activeTab="activeTab"
      :history="history"
      :tabs="tabs"/>

  </div>
</template>

<script>
import TitleView from '@/components/Title'
import Tabs from '@/components/Tabs'
import i18n from '@/i18n'

export default {
  name: 'ElementsIndex',
  components: { TitleView, Tabs },
  data () {
    return {
      activeTab: this.$route.query.resourcesTab || 0,
      history: true,
      tabs: [
        {
          id: 'Templates',
          title: i18n.global.t('templates'),
          icon: 'fa fa-newspaper',
          component: () => import('@/views/Template/List'),
          hidden: !this.$store.state.Settings.permissions['edit_template']
        },
        {
          id: 'Variables',
          title: i18n.global.t('tmplvars'),
          icon: 'fa fa-list-alt',
          component: () => import('@/views/Tv/List'),
          hidden: !this.$store.state.Settings.permissions['edit_template'] || !this.$store.state.Settings.permissions['edit_snippet'] || !this.$store.state.Settings.permissions['edit_chunk'] || !this.$store.state.Settings.permissions['edit_plugin']
        },
        {
          id: 'Chunks',
          title: i18n.global.t('htmlsnippets'),
          icon: 'fa fa-th-large',
          component: () => import('@/views/Chunk/List'),
          hidden: !this.$store.state.Settings.permissions['edit_chunk']
        },
        {
          id: 'Snippets',
          title: i18n.global.t('snippets'),
          icon: 'fa fa-code',
          component: () => import('@/views/Snippet/List'),
          hidden: !this.$store.state.Settings.permissions['edit_snippet']
        },
        {
          id: 'Plugins',
          title: i18n.global.t('plugins'),
          icon: 'fa fa-plug',
          component: () => import('@/views/Plugin/List'),
          hidden: !this.$store.state.Settings.permissions['edit_plugin']
        },
        {
          id: 'Modules',
          title: i18n.global.t('modules'),
          icon: 'fa fa-cubes',
          component: () => import('@/views/Module/List'),
          hidden: !this.$store.state.Settings.permissions['edit_module']
        }
      ],
    }
  },
  computed: {
    title () {
      return i18n.global.t('elements')
    }
  },
  mounted () {
    this.$emit('titleTab', {
      title: this.title,
      icon: 'fa fa-th'
    })
  }
}
</script>
