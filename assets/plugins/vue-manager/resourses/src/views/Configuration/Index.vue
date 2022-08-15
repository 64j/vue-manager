<template>
  <div>
    <ActionsButtons @action="action"/>

    <form name="mutate" v-show="loading">

      <TitleView :title="$t('settings_title')" :icon="data.icon"/>

      <Tabs
        id="Configuration"
        :tabs="[
          { id: 'Site_settings', title: $t('settings_site') },
          { id: 'furl_settings', title: $t('settings_furls') },
          { id: 'user_settings', title: $t('settings_users') },
          { id: 'manager_settings', title: $t('settings_ui') },
          { id: 'security_settings', title: $t('settings_security') },
          { id: 'filemanager_settings', title: $t('settings_misc') },
          { id: 'filebrowser_settings', title: $t('settings_KC') },
        ]">
        <template #Site_settings>
          <div class="container-fluid container-body pt-3">

            <div class="row form-row mb-1">
              <label class="col-md-3 col-lg-2">
                {{ $t('sitestatus_title') }}
                <br>
                <small>[(site_status)]</small>
              </label>
              <div class="col-md-9 col-lg-10">
                <label>
                  <input v-model="$store.state.Settings.config.site_status" type="radio" value="1" class="form-check-input">
                  {{ $t('online') }}
                </label>
                <br>
                <label>
                  <input v-model="$store.state.Settings.config.site_status" type="radio" value="0" class="form-check-input">
                  {{ $t('offline') }}
                </label>
              </div>
            </div>

            <div class="row form-row mb-1">
              <label class="col-md-3 col-lg-2">
                {{ $t('sitename_title') }}
                <br>
                <small>[(site_name)]</small>
              </label>
              <div class="col-md-9 col-lg-10">
                <input v-model="$store.state.Settings.config.site_name" type="text" maxlength="255" class="form-control">
                <div class="small text-muted">{{ $t('sitename_message') }}</div>
              </div>
            </div>

            <div class="row form-row mb-1">
              <label class="col-md-3 col-lg-2">
                {{ $t('emailsender_title') }}
                <br>
                <small>[(emailsender)]</small>
              </label>
              <div class="col-md-9 col-lg-10">
                <input v-model="$store.state.Settings.config.emailsender" type="text" maxlength="255" class="form-control">
                <div class="small text-muted">{{ $t('emailsender_message') }}</div>
              </div>
            </div>

            <div class="row form-row mb-1">
              <label class="col-md-3 col-lg-2">
                {{ $t('sitestart_title') }}
                <br>
                <small>[(site_start)]</small>
              </label>
              <div class="col-md-9 col-lg-10">
                <input v-model="$store.state.Settings.config.site_start" type="text" maxlength="255" class="form-control">
                <div class="small text-muted">{{ $t('sitestart_message') }}</div>
              </div>
            </div>

            <div class="row form-row mb-1">
              <label class="col-md-3 col-lg-2">
                {{ $t('errorpage_title') }}
                <br>
                <small>[(error_page)]</small>
              </label>
              <div class="col-md-9 col-lg-10">
                <input v-model="$store.state.Settings.config.error_page" type="text" maxlength="255" class="form-control">
                <div class="small text-muted">{{ $t('errorpage_message') }}</div>
              </div>
            </div>

          </div>
        </template>

        <template #furl_settings>
          <div class="container-fluid container-body pt-3">

          </div>
        </template>

        <template #user_settings>
          <div class="container-fluid container-body pt-3">

          </div>
        </template>

        <template #manager_settings>
          <div class="container-fluid container-body pt-3">

          </div>
        </template>

        <template #security_settings>
          <div class="container-fluid container-body pt-3">

          </div>
        </template>

        <template #filemanager_settings>
          <div class="container-fluid container-body pt-3">

          </div>
        </template>

        <template #filebrowser_settings>
          <div class="container-fluid container-body pt-3">

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
import i18n from '@/i18n'

export default {
  name: 'ConfigurationIndex',
  components: { ActionsButtons, TitleView, Tabs },
  data () {
    return {
      controller: 'Configuration',
      icon: 'fa fa-cogs',
      loading: false,
      data: {},
      meta: {}
    }
  },
  computed: {
    title () {
      return i18n.global.t('settings_title')
    }
  },
  mounted () {
    this.$emit('titleTab', {
      icon: this.icon,
      title: i18n.global.t('settings_title')
    })
    this.read()
  },
  methods: {
    action () {

    },
    read () {
      this.setData({})
      // http.post(this.controller + '@read', this.data).then(result => {
      //   this.setData(result)
      // })
    },
    setData (result) {
      this.data = result.data || {}
      this.meta = result.meta || {}
      this.loading = true
    }
  }
}
</script>

<style scoped>

</style>
