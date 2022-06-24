<template>
  <Panel
    :data="data"
    :actions="actions"
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
    return {
      url: '/templates',
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
    http.get(this.url).then(result => this.data = result.data)
  },
  methods: {
    action(action, item, category) {
      switch (action) {
        case 'copy':
          alert(action + ' ' + item.id)
          break;

        case 'delete':
          delete category.items[item.id]
          break;
      }
    },
  }
}
</script>
