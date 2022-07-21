<template>
  <li class="node-tree">
    <span class="label">{{ node.pagetitle }}</span>

    <ul v-if="node.children && node.children.length">
      <node v-for="child in node.children" :key="child.id" :node="child"/>
    </ul>
  </li>
</template>

<script>
export default {
  name: 'TreeNode',
  props: {
    node: Object
  }
}
</script>

<style scoped>

</style>
