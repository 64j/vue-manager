<template>
  <div>
    <ActionsButtons @action="action"/>

    <form name="mutate" v-show="loading">

      <TitleView :title="title" :icon="icon"/>

      <div class="container-fluid container-body px-4">

        <div class="row form-row mb-1">
          <label class="col-md-3 col-lg-2">{{ $t('role_name') }}</label>
          <div class="col-md-9 col-lg-10">
            <div class="form-control-name">
              <input v-model="data.name" type="text" maxlength="50" class="form-control" onchange="documentDirty=true;"/>
            </div>
            <small class="form-text text-danger hide" id="savingMessage"></small>
          </div>
        </div>

        <div class="row form-row mb-3">
          <label class="col-md-3 col-lg-2">{{ $t('resource_description') }}</label>
          <div class="col-md-9 col-lg-10">
            <input v-model="data.description" type="text" maxlength="255" class="form-control" onchange="documentDirty=true;"/>
          </div>
        </div>

        <div class="row">
          <template v-for="(category, i) in meta.categories">
            <div v-if="category" :key="`c` + i" class="col-6 col-md-4 col-lg-3">
              <div v-for="(categoryItem, k) in category" :key="k" class="pb-3">
                <h5 class="mb-3">{{ categoryItem.lang ? $t(categoryItem.lang) : categoryItem.title }}</h5>
                <div v-for="(item, j) in categoryItem.items" :key="j">
                  <div class="form-check">
                    <input v-model="data[j]" type="checkbox" class="form-check-input" :id="j" :false-value="0" :true-value="1" :disabled="item.disabled">
                    <label class="form-check-label" :for="j">{{ item.lang ? $t(item.lang) : item.title }}</label>
                  </div>
                </div>
              </div>
            </div>
            <hr v-else :key="`s-` + i" class="bg-opacity-50 bg-secondary">
          </template>
        </div>

      </div>

    </form>

  </div>
</template>

<script>
import ActionsButtons from '@/components/ActionsButtons'
import TitleView from '@/components/Title'
import i18n from '@/i18n'
import http from '@/utils/http'

export default {
  name: 'RoleIndex',
  components: { ActionsButtons, TitleView },
  data () {
    return {
      controller: 'Role',
      icon: 'fa fa-legal',
      loading: false,
      data: {
        id: this.$route.params && this.$route.params.id || null,
        name: ''
      },
      meta: {
        categories: []
      }
    }
  },
  computed: {
    title () {
      return (this.data.name ? this.data.name : i18n.global.t('role_title')) + (this.data.id ? ' <small>(' + this.data.id + ')</small>' : '')
    }
  },
  mounted () {
    this.$emit('titleTab', {
      icon: this.icon,
      title: ''
    })
    this.read()
  },
  methods: {
    action (action) {
      switch (action) {
        case 'save':
          this.loading = false
          if (this.data.id) {
            this.update()
          } else {
            this.create()
          }
          break

        case 'cancel':
          this.$emit('toTab', { name: 'RoleList' })
          break

        case 'delete':
          this.delete()
          break
      }
    },
    create() {
      http.post(this.controller + '@create', this.data).then(this.setData)
    },
    read() {
      http.post(this.controller + '@read', this.data).then(result => {
        this.setData(result)
      })
    },
    update() {
      http.post(this.controller + '@update', this.data).then(result => {
        this.setData(result)
      })
    },
    delete() {
      if (confirm(i18n.global.t('confirm_delete_role'))) {
        http.post(this.controller + '@delete', { id: this.data.id }).then(result => {
          if (result) {
            this.action('cancel')
          }
        })
      }
    },
    setData(result) {
      this.data = result.data
      this.meta = result.meta
      this.$emit('titleTab', this.title)
      this.loading = true
    }
  }
}
</script>

<style scoped>

</style>
