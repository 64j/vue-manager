<template>
  <div>

    <ActionsButtons @action="action"/>

    <form name="mutate" v-show="loading">

      <TitleView :title="title" icon="fa fa-code" :message="$t('template_msg')"/>

      <Tabs
        id="template"
        :tabs="[
          { id: 'Template', title: $t('settings_general') },
          { id: 'Tvs', title: $t('template_assignedtv_tab') },
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

        <template #Tvs>
          <div class="container-fluid container-body pt-3">
            <div class="form-group">
              <p>{{ $t('template_tv_msg') }}</p>

              <div class="row">
                <template v-if="Object.values(meta?.tvs?.selected || {}).length">
                  <hr class="bg-secondary m-0">
                  <Panel
                    :data="meta.tvs.selected"
                    class-name="px-0 mb-4"
                    link-name="TvIndex"
                    link-icon="fa fa-list-alt"
                    checkbox="checkbox"
                    :checkbox-checked="tvSelected"
                    :hidden-categories="true"
                    @action="action"
                  />
                </template>

                <p v-else class="text-danger">{{ $t('template_no_tv') }}</p>
              </div>

              <!--              <ul v-if="Object.values(meta?.tvs?.selected || {}).length" class="list-unstyled">-->
              <!--                <template v-for="category in meta.tvs.selected">-->
              <!--                  <li v-for="tv in category.items" :key="`tv`+tv.id" class="form-check">-->
              <!--                    <input type="checkbox" v-model="tvs" :id="`tv`+tv.id" :value="tv.id" class="form-check-input">-->
              <!--                    <label :for="`tv`+tv.id" class="form-check-label">-->
              <!--                      {{ tv.name }} <small>({{ tv.id }})</small> - {{ tv.caption }}-->
              <!--                      <a href="#">{{ $t('edit') }}</a>-->
              <!--                    </label>-->
              <!--                  </li>-->
              <!--                </template>-->
              <!--              </ul>-->

              <div class="row">
                <template v-if="Object.values(meta?.tvs?.unselected || {}).length">
                  <!--                  <hr class="bg-secondary">-->
                  <p class="m-0">{{ $t('template_notassigned_tv') }}</p>

                  <Panel
                    :data="meta.tvs.unselected"
                    class-name="px-0"
                    link-name="TvIndex"
                    link-icon="fa fa-list-alt"
                    :search-input="true"
                    checkbox="checkbox"
                    :checkbox-checked="tvSelected"
                    @action="action"
                  />
                </template>
              </div>

            </div>
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
import Panel from '@/components/Panel'
import http from '@/utils/http'
import i18n from '@/i18n'

export default {
  name: 'TemplateIndex',
  components: { ActionsButtons, TitleView, Tabs, Panel },
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
        id: this.$route.params && this.$route.params.id || null,
        locked: 0,
        templatename: '',
        description: '',
        category: 0,
        newcategory: null,
        selectable: 0,
        content: ''
      },
      meta: {},
      tvSelected: []
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
    this.read()
  },
  methods: {
    action (name, item) {
      switch (name) {
        case 'save':
          this.loading = false
          if (this.data.id) {
            this.update()
          } else {
            this.create()
          }
          break

        case 'delete':
          if (this.data.id) {
            this.delete()
          }
          break

        case 'cancel':
          this.$emit('toTab', { name: 'ElementsIndex', query: { resourcesTab: 0 } }, true)
          break

        case 'refresh':
          this.$emit('refreshTab', { name: 'ElementsIndex', query: { resourcesTab: 0 } })
          break

        case 'checkbox': {
          const index = this.tvSelected.indexOf(item.id)
          index > -1 && this.tvSelected.splice(index, 1) || this.tvSelected.push(item.id)
          break
        }
      }
    },
    create () {
      http.post(this.controller + '@create', { ...this.data, tvSelected: this.tvSelected }).then(result => {
        if (result.data.id) {
          this.$emit('replaceTab', { params: { id: result.data.id } })
        } else {
          this.setData(result)
          this.$emit('titleTab', this.title)
        }
        this.action('refresh')
        this.loading = true
      })
    },
    read () {
      http.post(this.controller + '@read', this.data).then(result => {
        this.setData(result)
        this.$emit('titleTab', this.title)
        this.loading = true
      })
    },
    update () {
      http.post(this.controller + '@update', { ...this.data, tvSelected: this.tvSelected }).then(result => {
        this.setData(result)
        this.$emit('titleTab', this.title)
        this.action('refresh')
        this.loading = true
      })
    },
    delete () {
      http.post(this.controller + '@delete', this.data).then(result => {
        if (result) {
          this.action('cancel')
        }
      })
    },
    setData (result) {
      this.data = result.data
      this.meta = result.meta

      if (this.meta?.tvs?.selected) {
        this.tvSelected = []
        for (const i in this.meta.tvs.selected) {
          for (const j in this.meta.tvs.selected[i].items) {
            const tv = this.meta.tvs.selected[i].items[j]
            this.tvSelected.push(tv.id)
          }
        }
      }

      if (this.meta?.tvs?.unselected) {
        for (const i in this.meta.tvs.unselected) {
          for (const j in this.meta.tvs.unselected[i].items) {
            let tv = this.meta.tvs.unselected[i].items[j]
            tv.prepend = '<input type="checkbox" name="assignedTv[]" value="' + tv.id + '" class="form-check-input me-2">'
          }
        }
      }

      for (let i in this.meta.events || {}) {
        this.events[i] = Array.isArray(this.meta.events[i]) ? this.meta.events[i].join('') : this.meta.events[i]
      }
    }
  }
}
</script>

<style scoped>
.form-control-name { position: relative; }
.form-control-name label { position: absolute; right: .8rem; top: .4rem; font-size: 1.5rem; cursor: pointer; }
.form-control-name label input { opacity: 0 }
</style>
