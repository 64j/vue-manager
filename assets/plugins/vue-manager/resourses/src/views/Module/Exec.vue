<template>
  <div>
    <iframe :srcdoc="data" class="w-100 h-100 overflow-auto border-0" />
  </div>
</template>

<script>
import http from '@/utils/http'

export default {
  name: 'ModuleExec',
  data () {
    this.url = '/module-exec/'
    this.icon = 'fa fa-cube'

    return {
      data: null
    }
  },
  mounted () {
    this.$emit('titleTab', {
      icon: this.icon,
      title: ''
    })
    const id = this.$route.params && this.$route.params.id || null
    if (id) {
      this.get(id)
    } else {
      this.loading = true
    }
  },
  methods: {
    get (id) {
      http.get(this.url + id).then(result => {
        this.data = result.data.result || ''
        this.$emit('titleTab', result.data.title)
        this.loading = true
      })
    },
  }
}
</script>

<style scoped>

</style>
