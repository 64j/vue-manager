<template>
  <div>

    <ActionsButtons @action="action"/>

    <form name="mutate" v-show="loading">

      <TitleView :title="title" icon="fa fa-code" :message="$t('template_msg')"/>

      <Tabs
        id="template"
        :tabs="[
          { id: 'Template', title: $t('settings_general') }
        ]">
        <template #Template>
          <div class="container-fluid container-body pt-3">
            <div class="form-group">
              <div class="row form-row mb-1">
                <label class="col-md-3 col-lg-2">
                  {{ $t('template_name') }}
                  <small v-if="data.id === $store.state.Settings.config['default_template']" class="text-danger d-block">{{ $t('defaulttemplate_title') }}</small>
                </label>
                <div class="col-md-9 col-lg-10">
                  <div class="form-control-name clearfix">
                    <input v-model="data.templatename" type="text" maxlength="100" class="form-control form-control-lg" onchange="documentDirty=true;">
                    <label v-if="$store.state.Settings.permissions['save_role']" :title="$t('lock_snippet_msg')">
                      <input v-model="data.locked" type="checkbox" :false-value="0" :true-value="1"/>
                      <i class="fa fa-lock" :class="[data.locked ? 'text-danger' : 'text-muted']"></i>
                    </label>
                  </div>
                  <small class="form-text text-danger hide" id='savingMessage'></small>
                </div>
              </div>
              <div class="row form-row mb-1">
                <label class="col-md-3 col-lg-2">{{ $t('template_desc') }}</label>
                <div class="col-md-9 col-lg-10">
                  <input v-model="data.description" type="text" maxlength="255" class="form-control" onchange="documentDirty=true;">
                </div>
              </div>
              <div class="row form-row mb-1">
                <label class="col-md-3 col-lg-2">{{ $t('existing_category') }}</label>
                <div class="col-md-9 col-lg-10">
                  <select v-model="data.category" class="form-select" onchange="documentDirty=true;">
                    <option v-for="category in $store.state.Settings.categories" :key="category.id" :value="category.id">
                      {{ category.category }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="row form-row mb-1">
                <label class="col-md-3 col-lg-2">{{ $t('new_category') }}</label>
                <div class="col-md-9 col-lg-10">
                  <input v-model="data.newcategory" type="text" maxlength="45" class="form-control" onchange="documentDirty=true;">
                </div>
              </div>
              <div class="form-check mb-1" v-if="$store.state.Settings.permissions['save_role']">
                <input v-model="data.selectable" type="checkbox" class="form-check-input" id="selectable" :false-value="0" true-value="1">
                <label class="form-check-label" for="selectable">{{ $t('template_selectable') }}</label>
              </div>
            </div>

            <!-- HTML text editor start -->
            <div class="navbar-editor mt-3 mb-1">
              <span>{{ $t('template_code') }}</span>
            </div>
          </div>

          <div class="section-editor">
            <textarea v-model="data.content" class="form-control" rows="20" wrap="soft" onchange="documentDirty=true;"/>
          </div>
        </template>

      </Tabs>

    </form>

  </div>
</template>

<script>
import ActionsButtons from '@/components/ActionsButtons'
import TitleView from '@/components/Title'
import Tabs from '@/components/Tabs'
import http from '@/utils/http'
import i18n from '@/i18n'

export default {
  name: 'TemplateIndex',
  components: { ActionsButtons, TitleView, Tabs },
  data () {
    this.controller = 'Template'
    this.icon = 'fa fa-code'

    this.events = {
      OnTempFormPrerender: '',
      OnTempFormRender: ''
    }

    return {
      loading: false,
      data: {
        id: null,
        locked: 0,
        templatename: '',
        description: '',
        category: 0,
        newcategory: null,
        selectable: 0,
        content: ''
      }
    }
  },
  computed: {
    title () {
      return (this.data.templatename ? this.data.templatename : i18n.global.t('new_template')) + (this.data.id ? ' <small>(' + this.data.id + ')</small>' : '')
    }
  },
  mounted () {
    this.$emit('titleTab', {
      icon: this.icon,
      title: ''
    })
    this.data.id = this.$route.params && this.$route.params.id || null
    if (this.data.id) {
      this.get()
    } else {
      this.$emit('titleTab', this.title)
      this.loading = true
    }
  },
  methods: {
    action (name) {
      switch (name) {
        case 'save':
          this.loading = false
          if (this.data.id) {
            http.post(this.controller + '@update', this.data).then(result => {
              this.data = result.data
              this.$emit('titleTab', this.title)
              this.loading = true
            })
          } else {
            http.post(this.controller + '@create', this.data).then(result => {
              if (result.data.id) {
                this.$emit('replaceTab', { params: { id: result.data.id } })
              } else {
                this.data = result.data
                this.$emit('titleTab', this.title)
              }
              this.loading = true
            })
          }
          break

        case 'delete':
          if (this.data.id) {
            http.post(this.controller + '@delete', this.data).then(result => {
              if (result) {
                this.action('cancel')
              }
            })
          }
          break

        case 'cancel':
          this.$emit('toTab', { name: 'ElementsIndex', query: { resourcesTab: 0 } })
          break
      }
    },
    get () {
      http.post(this.controller + '@read', this.data).then(result => {
        this.data = result.data
        for (let i in result.meta.events || {}) {
          this.events[i] = Array.isArray(result.meta.events[i]) ? result.meta.events[i].join('') : result.meta.events[i]
        }
        this.$emit('titleTab', this.title)
        this.loading = true
      })
    }
  }
}
</script>

<style scoped>
.form-control-name { position: relative; }
.form-control-name label { position: absolute; right: .8rem; top: .4rem; font-size: 1.5rem; cursor: pointer; }
.form-control-name label input { opacity: 0 }
</style>
