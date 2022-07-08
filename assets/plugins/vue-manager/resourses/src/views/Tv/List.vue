<template>
  <Panel
    :data="data"
    :actions="actions"
    :search-input="true"
    link-name="TvIndex"
    link-icon="fa fa-list-alt"
    :txt-new="$t('new_tmplvars')"
    :txt-help="$t('tmplvars_management_msg')"
    @action="action"
  />
</template>

<script>
import http from '@/utils/http'
import Panel from '@/components/Panel'
import i18n from '@/i18n'

export default {
  name: 'TvList',
  components: { Panel },
  data () {
    this.element = 'TvIndex'
    this.controller = 'Tv@list'

    return {
      data: null,
      actions: {
        copy: {
          icon: 'far fa-clone fa-fw'
        },
        delete: {
          icon: 'fa fa-trash fa-fw text-danger'
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
          if (confirm(i18n.global.t('confirm_delete_tmplvars'))) {
            http.post(this.controller + '@delete', item).then(result => {
              if (result) {
                delete category.items[item.id]
                this.$root.$refs.Layout.$refs.MultiTabs.closeTab(this.$router.resolve({ name: this.element, params: { id: item.id } }))
              }
            })
          }
          break
      }
    }
  }
}
</script>
