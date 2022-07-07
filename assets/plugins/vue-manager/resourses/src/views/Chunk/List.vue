<template>
  <Panel
    :data="data"
    :actions="actions"
    :search-input="true"
    link-name="ChunkIndex"
    link-icon="fa fa-th-large"
    :txt-new="$t('new_htmlsnippet')"
    :txt-help="$t('htmlsnippet_management_msg')"
    @action="action"
  />
</template>

<script>
import http from '@/utils/http'
import Panel from '@/components/Panel'
import i18n from '@/i18n'

export default {
  name: 'ChunkList',
  components: { Panel },
  data () {
    this.element = 'ChunkIndex'
    this.controller = 'Chunk'

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
    this.list()
  },
  methods: {
    action (action, item, category) {
      switch (action) {
        case 'copy':
          http.post(this.controller + '@copy', item).then(result => {
            if (result) {
              this.list()
            }
          })
          break

        case 'delete':
          http.post(this.controller + '@delete', item).then(result => {
            if (result) {
              delete category.items[item.id]
              this.$root.$refs.Layout.$refs.MultiTabs.closeTab(this.$router.resolve({ name: this.element, params: { id: item.id } }))
            }
          })
          break

        case 'disabled':
          item.disabled = item.disabled ? 0 : 1
          http.post(this.controller + '@update', { id: item.id, disabled: item.disabled })
          break
      }
    },
    list() {
      http.post(this.controller + '@list', { categories: true }).then(result => this.data = result.data)
    }
  }
}
</script>
