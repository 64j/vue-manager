<template>
  <div>

    <ActionsButtons @action="action"/>

    <form name="mutate" v-show="loading">

      <TitleView :title="title" icon="fa fa-code" :message="$t('snippet_msg')"/>

      <Tabs
        id="snippet"
        :tabs="[
          { id: 'Snippet', title: $t('settings_general') },
          { id: 'Config', title: $t('settings_config') },
          { id: 'Props', title: $t('settings_properties') },
          { id: 'DocBlock', title: $t('information') }
        ]">
        <!-- General -->
        <template #Snippet>
          <div class="container-fluid container-body pt-3">
            <div class="form-group">

              <div class="row form-row mb-1">
                <label class="col-md-3 col-lg-2">{{ $t('snippet_name') }}</label>
                <div class="col-md-9 col-lg-10">
                  <div class="form-control-name clearfix">
                    <input v-model="data.name" type="text" maxlength="100" class="form-control form-control-lg" onchange="documentDirty=true;"/>
                      <label v-if="$store.state.Settings.permissions['save_role']" :title="$t('lock_snippet_msg')">
                        <input v-model="data.locked" type="checkbox" :false-value="0" :true-value="1"/>
                        <i class="fa fa-lock" :class="[data.locked ? 'text-danger' : 'text-muted']"></i>
                      </label>
                  </div>
                  <small class="form-text text-danger hide" id="savingMessage"></small>
                </div>
              </div>

              <div class="row form-row mb-1">
                <label class="col-md-3 col-lg-2">{{ $t('snippet_desc') }}</label>
                <div class="col-md-9 col-lg-10">
                  <input v-model="data.description" type="text" maxlength="255" class="form-control" onchange="documentDirty=true;"/>
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
                  <input v-model="data.newcategory" type="text" maxlength="45" class="form-control" onchange="documentDirty=true;"/>
                </div>
              </div>

            </div>

            <div v-if="$store.state.Settings.permissions['save_role']">

              <div v-if="$store.state.Settings.user.role === 1" class="form-row mb-1">
                <div class="form-check">
                  <input v-model="data.disabled" type="checkbox" class="form-check-input" id="disabled" :false-value="0" :true-value="1">
                  <label class="form-check-label" for="disabled">{{ $t('disabled') }}</label>
                </div>
              </div>

              <div class="form-row mb-1">
                <div class="form-check">
                  <input v-model="data.parse_docblock" type="checkbox" class="form-check-input" id="parse_docblock" :false-value="0" :true-value="1">
                  <label class="form-check-label" for="parse_docblock">{{ $t('parse_docblock') }}</label>
                </div>
                <small v-if="data.parse_docblock" class="text-danger d-block" v-html="$t('parse_docblock_msg')"/>
              </div>

            </div>

            <!-- PHP text editor start -->
            <div class="navbar-editor mt-3 mb-1">
              <span>{{ $t('snippet_code') }}</span>
            </div>

          </div>

          <div class="section-editor">
            <textarea v-model="data.snippet" class="form-control" rows="20" wrap="soft" onchange="documentDirty=true;"/>
          </div>

        </template>

        <!-- Config -->
        <template #Config>
          <div class="container-fluid container-body py-3">
            <div class="form-group">
              <a href="javascript:;" class="btn btn-sm btn-primary" onclick="setDefaults(this);return false;">{{ $t('set_default_all') }}</a>
            </div>
            <div id="displayparamrow">
              <div id="displayparams"></div>
            </div>
          </div>
        </template>

        <!-- Properties -->
        <template #Props>
          <div class="container-fluid container-body py-3">
            <div class="form-group">
              <div class="row form-row">
                <label class="col-md-3 col-lg-2">{{ $t('import_params') }}</label>
                <div class="col-md-9 col-lg-10">
                  <select name="moduleguid" class="form-control" onchange="documentDirty=true;">
                    <option>&nbsp;</option>
                  </select>
                  <small class="form-text text-muted">{{ $t('import_params_msg') }}</small>
                </div>
              </div>
            </div>
            <div class="form-group">
              <a href="javascript:;" class="btn btn-sm btn-primary" onclick='tpSnippet.pages[1].select();showParameters(this);return false;'>{{ $t('update_params') }}</a>
            </div>
          </div>
          <!-- HTML text editor start -->
          <div class="section-editor clearfix">
            <textarea v-model="data.properties" dir="ltr" class="form-control" rows="20" onChange="showParameters(this);documentDirty=true;"/>
          </div>
          <!-- HTML text editor end -->
        </template>

        <!-- docBlock Info -->
        <template #DocBlock>
          <div class="container-fluid container-body py-3">
            $docBlockList
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
  name: 'SnippetIndex',
  components: { ActionsButtons, TitleView, Tabs },
  data () {
    this.controller = 'Snippet'
    this.icon = 'fa fa-code'

    return {
      loading: false,
      data: {
        id: this.$route.params && this.$route.params.id || null,
        name: '',
        locked: 0,
        description: '',
        category: 0,
        newcategory: null,
        disabled: 0,
        parse_docblock: 1,
        snippet: ''
      }
    }
  },
  computed: {
    title () {
      return (this.data.name ? this.data.name : i18n.global.t('new_snippet')) + (this.data.id ? ' <small>(' + this.data.id + ')</small>' : '')
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
          this.$emit('toTab', { name: 'ElementsIndex', query: { resourcesTab: 3 } })
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
      if (confirm(i18n.global.t('confirm_delete_snippet'))) {
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

      for (let i in result.meta.events || {}) {
        this.events[i] = Array.isArray(result.meta.events[i]) ? result.meta.events[i].join('') : result.meta.events[i]
      }
      this.$emit('titleTab', this.title)
      this.loading = true
    }
  }
}
</script>

<style scoped>
.form-control-name { position: relative; }
.form-control-name label { position: absolute; right: .8rem; top: .4rem; font-size: 1.5rem; cursor: pointer; }
.form-control-name label input { opacity: 0 }
</style>
