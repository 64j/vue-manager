<template>
  <div>

    <TitleView :title="title" :icon="icon" :message="$t('role_management_msg')"/>

    <TableView
      :data="data"
      :columns="columns"
      :link-name="index"
      table-class="table-sm"
      @getData="get">
      <router-link :to="{ name: index, params: { id: '' } }" class="btn btn-success btn-sm">
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
    this.icon = 'fa fa-legal'
    return {
      url: '/roles',
      index: 'RoleIndex',
      data: null,
      columns: {
        icon: {
          title: '',
          value: '<i class="' + this.icon + '"/>',
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
      },
      pagination: null
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
    this.get()
    this.$el.addEventListener('click', (e) => {
      if (e.target.classList.contains('remove')) {
        this.del(e.target.closest('tr').dataset.id)
      }
    })
  },
  methods: {
    get (id) {
      http.get(this.$router.resolve({ query: { p: id } }).fullPath).then(result => {
        this.data = result.data || null
      })
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
