<template>
  <div>
    <ActionsButtons @action="action"/>

    <form name="mutate" v-show="loading">

      <TitleView :title="title" icon="fa fa-file" :message="$t('create_resource_title')"/>

      <Tabs
        id="doc"
        :tabs="[
          { id: 'General', title: $t('settings_general') }
        ]">

      </Tabs>

    </form>

  </div>
</template>

<script>
import ActionsButtons from '@/components/ActionsButtons'
import TitleView from '@/components/Title'
import Tabs from '@/components/Tabs'
import i18n from '@/i18n'
import http from '@/utils/http'

export default {
  name: 'DocumentIndex',
  components: { ActionsButtons, TitleView, Tabs },
  data () {
    this.controller = 'Document'
    this.icon = 'fa fa-file'

    return {
      loading: false,
      data: {
        id: this.$route.params && this.$route.params.id || null
      },
      meta: {}
    }
  },
  computed: {
    title () {
      return (this.data?.name ? this.data.name : i18n.global.t('new_resource')) + (this.data?.id ? ' <small>(' + this.data.id + ')</small>' : '')
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
    action () {

    },
    read() {
      http.post(this.controller + '@read', this.data).then(result => {
        this.setData(result)
      })
    },
    setData (result) {
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
