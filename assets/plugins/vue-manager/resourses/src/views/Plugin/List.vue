<template>
  <Panel
    :data="data"
    :actions="actions"
    link-name="PluginIndex"
    link-icon="fa fa-plug"
    :txt-new="$t('new_plugin')"
    :txt-help="$t('plugin_management_msg')"
    @action="action"
  />
</template>

<script>
import http from '@/utils/http'
import Panel from '@/components/Panel'
import i18n from '@/i18n'

export default {
  name: 'PluginList',
  components: { Panel },
  data () {
    this.controller = 'Plugin@list'

    return {
      data: null,
      actions: {
        copy: {
          icon: 'far fa-clone fa-fw',
          title: i18n.global.t('duplicate')
        },
        disabled: {
          values: {
            0: {
              icon: 'far fa-times-circle text-danger',
              title: i18n.global.t('disabled')
            },
            1: {
              icon: 'far fa-check-circle text-success',
              title: i18n.global.t('enabled')
            }
          }
        },
        delete: {
          icon: 'fa fa-trash fa-fw text-danger',
          title: i18n.global.t('delete')
        }
      }
    }
  },
  mounted () {
    http.post(this.controller, { categories: true }).then(result => this.data = result.data)
  },
  methods: {
    action (action, item, category) {
      switch (action) {
        case 'copy':
          alert(action + ' ' + item.id)
          break

        case 'delete':
          delete category.items[item.id]
          break

        case 'disabled':
          item.disabled = item.disabled ? 0 : 1
          break
      }
    }
  }
}
</script>
