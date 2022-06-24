<template>
  <div>
    <iframe :src="src" class="w-100 h-100 overflow-auto border-0" />
  </div>
</template>

<script>
export default {
  name: 'ModuleIndex',
  data () {
    return {
      src: 'https://jsonplaceholder.typicode.com/users/' + this.$route.params.id
    }
  }
}
</script>
