<template>
  <div>

    <TitleView :title="title" :icon="icon" :message="$t('web_user_management_msg')"/>

    <TableView
      :data="data"
      :columns="columns"
      :pagination="meta?.pagination || {}"
      :mode-search="true"
      :mode-list="true"
      :loading="loading"
      :link-name="element"
      table-class="table-sm"
      @getData="list">
      <router-link :to="{ name: element, params: { id: '' } }" class="btn btn-success btn-sm">
        <i class="fa fa-plus"></i>
        {{ $t('new_web_user') }}
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
  name: 'WebUserList',
  components: { TitleView, TableView },
  data () {
    return {
      controller: 'WebUser',
      element: 'WebUserIndex',
      icon: 'fa fa-user',
      data: null,
      meta: null,
      columns: {
        icon: {
          title: i18n.global.t('icon'),
          value: '<i class="fa fa-user"/>',
          link: true
        },
        username: {
          title: i18n.global.t('name'),
          link: true
        },
        fullname: {
          title: i18n.global.t('user_full_name')
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
      return i18n.global.t('web_user_management_title')
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
      http.post(this.controller + '@list').then(this.setData)
    },
    setData(result) {
      this.data = result.data
      this.meta = result.meta
      this.loading = false
    }
  }
}
</script>

<style scoped>

</style>
