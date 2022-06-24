<template>
  <div class="p-3">
    <h1 class="title h4 m-0">
      <i v-if="icon" class="me-3" :class="icon"/>
      <span v-html="title"/>
      <i v-if="message" class="fa fa-question-circle position-relative tooltip-help ms-3 small" @click="show=!show"/>
    </h1>
    <div v-if="message" v-show="show" class="alert alert-info m-0 mt-3" v-html="message"/>
  </div>
</template>

<script>
export default {
  name: 'TitleView',
  props: {
    title: {
      type: String
    },
    icon: {
      type: String
    },
    message: {
      type: String
    }
  },
  data () {
    return {
      show: false
    }
  }
}
</script>

<style scoped>
.tooltip-help::before { opacity: .5; }
.tooltip-help:hover::before { opacity: .75; }
</style>
