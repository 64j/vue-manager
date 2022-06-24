<template>
  <div>

    <TitleView :title="title" :icon="icon" :message="$t('access_permissions_introtext')"/>

    <Tabs
      id="permissions"
      :tabs="[
        { id: 'users', title: $t('web_access_permissions_user_groups') },
        { id: 'resources', title: $t('access_permissions_resource_groups') },
        { id: 'relations', title: $t('access_permissions_links') }
      ]">

      <template #users>
        <div class="container-fluid py-3">
          <div class="alert alert-warning">
            {{ $t('access_permissions_users_tab') }}
          </div>
          <div class="fw-bolder mb-1">{{ $t('access_permissions_add_user_group') }}</div>
          <div class="input-group">
            <input type="text" class="form-control">
            <button class="btn btn-success">{{ $t('submit') }}</button>
          </div>
        </div>
      </template>

      <template #resources>
        <div class="container-fluid py-3">
          <div class="alert alert-warning">
            {{ $t('access_permissions_resources_tab') }}
          </div>
          <div class="fw-bolder mb-1">{{ $t('access_permissions_add_resource_group') }}</div>
          <div class="input-group">
            <input type="text" class="form-control">
            <button class="btn btn-success">{{ $t('submit') }}</button>
          </div>
        </div>
      </template>

      <template #relations>
        <div class="container-fluid py-3">
          <div class="alert alert-warning">
            {{ $t('access_permissions_links_tab') }}
          </div>
        </div>
      </template>

    </Tabs>

  </div>
</template>

<script>
import TitleView from '@/components/Title'
import Tabs from '@/components/Tabs'
import i18n from '@/i18n'

export default {
  name: 'WebUserPermissionsIndex',
  components: { TitleView, Tabs },
  data () {
    return {
      icon: 'fa fa-male'
    }
  },
  computed: {
    title () {
      return i18n.global.t('web_access_permissions')
    }
  },
  mounted () {
    this.$emit('titleTab', {
      icon: this.icon,
      title: this.title
    })
  }
}
</script>

<style scoped>

</style>
