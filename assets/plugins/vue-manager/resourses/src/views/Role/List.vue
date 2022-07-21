<template>
  <div>

    <TitleView :title="title" :icon="icon" :message="$t('role_management_msg')"/>

    <TableView
      :data="data"
      :columns="columns"
      :link-name="element"
      table-class="table-sm"
      @getData="list">
      <router-link :to="{ name: element, params: { id: '' } }" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i>
        {{ $t('new_role') }}
      </router-link>
    </TableView>

  </div>
</template>

<script>
import TitleView from '@/components/Title'
import TableView from '@/components/Table'
import i18n from '@/i18n'
import http from '@/utils/http'

export default {
  name: 'RoleList',
  components: { TitleView, TableView },
  data () {
    return {
      controller: 'Role',
      element: 'RoleIndex',
      icon: 'fa fa-legal',
      data: null,
      meta: null,
      columns: {
        icon: {
          title: '',
          value: '<i class="fa fa-legal"/>',
          link: true
        },
        name: {
          title: i18n.global.t('role'),
          link: true
        },
        description: {
          title: i18n.global.t('description')
        },
        delete: {
          title: i18n.global.t('delete'),
          value: '<i class="fa fa-trash-alt remove text-danger"/>'
        }
      }
    }
  },
  computed: {
    title () {
      return i18n.global.t('role_management_title')
    }
  },
  mounted () {
    this.$emit('titleTab', {
      icon: this.icon,
      title: this.title
    })
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
      }
    },
    list() {
      http.post(this.controller + '@list').then(result => this.data = result.data)
    },
    del (id) {
      this.loading = true
      http.delete(this.$router.resolve({ name: this.index, params: { id: id } }).fullPath).then(result => {
        this.$notify({ type: 'error', text: JSON.stringify(result) })
        const route = this.$route
        this.$router.replace('/redirect' + route.fullPath).then(() => {
          this.$store.dispatch('MultiTabs/delTabKey', route)
        })
        this.loading = false
      })
    }
  }
}
</script>

<style scoped>

</style>
