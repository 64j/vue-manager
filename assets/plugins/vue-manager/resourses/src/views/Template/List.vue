<template>
  <Panel
    :data="data"
    :actions="actions"
    :search-input="true"
    link-name="TemplateIndex"
    link-icon="fa fa-newspaper"
    :txt-new="$t('new_template')"
    :txt-help="$t('template_management_msg')"
    @action="action"
  />
</template>

<script>
import http from '@/utils/http'
import Panel from '@/components/Panel'

export default {
  name: 'TemplateList',
  components: { Panel },
  data () {
    this.controller = 'Template'

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
            }
          })
          break
      }
    },
    list() {
      http.post(this.controller + '@list', { categories: true }).then(result => this.data = result.data)
    }
  }
}
</script>
