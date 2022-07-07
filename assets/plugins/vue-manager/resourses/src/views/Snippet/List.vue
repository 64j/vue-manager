<template>
  <Panel
    :data="data"
    :actions="actions"
    :search-input="true"
    link-name="SnippetIndex"
    link-icon="fa fa-code"
    :txt-new="$t('new_snippet')"
    :txt-help="$t('snippet_management_msg')"
    @action="action"
  />
</template>

<script>
import http from '@/utils/http'
import Panel from '@/components/Panel'
import i18n from '@/i18n'

export default {
  name: 'SnippetList',
  components: { Panel },
  data () {
    this.element = 'SnippetIndex'
    this.controller = 'Snippet@list'

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
          break
      }
    }
  }
}
</script>
