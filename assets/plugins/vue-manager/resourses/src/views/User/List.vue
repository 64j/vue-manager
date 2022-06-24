<template>
  <div>

    <TitleView :title="title" icon="fa fa-user-circle" :message="$t('user_management_msg')"/>

    <TableView
      :data="data"
      :columns="columns"
      :pagination="pagination"
      :mode-search="true"
      :mode-list="true"
      :loading="loading"
      :link-name="index"
      table-class="table-sm"
      @getData="get">
      <router-link :to="{ name: index, params: { id: '' } }" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i>
        {{ $t('new_user') }}
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
  name: 'UserList',
  components: { TitleView, TableView },
  data () {
    this.icon = 'fa fa-user-circle'
    return {
      url: '/users',
      index: 'UserIndex',
      data: null,
      columns: {
        icon: {
          title: i18n.global.t('icon'),
          value: '<i class="' + this.icon + '"/>',
          link: true
        },
        username: {
          title: i18n.global.t('name'),
          link: true
        },
        fullname: {
          title: i18n.global.t('user_full_name')
        },
        role: {
          title: i18n.global.t('role')
        },
        email: {
          title: i18n.global.t('email')
        },
        lastlogin: {
          title: i18n.global.t('user_prevlogin')
        },
        logincount: {
          title: i18n.global.t('user_logincount')
        },
        blocked: {
          title: i18n.global.t('user_block'),
          value: {
            0: i18n.global.t('no'),
            1: i18n.global.t('yes')
          }
        },
        delete: {
          title: i18n.global.t('delete'),
          value: '<i class="fa fa-trash-alt remove text-danger"/>'
        }
      },
      pagination: null,
      loading: false
    }
  },
  computed: {
    title () {
      return i18n.global.t('user_management_title')
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
      this.loading = true
      http.get(this.$router.resolve({ query: { p: id } }).fullPath).then(result => {
        this.data = result.data || null
        this.pagination = result.meta.pagination || null
        this.loading = false
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
