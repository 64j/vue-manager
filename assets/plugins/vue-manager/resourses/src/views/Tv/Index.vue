<template>
  <div>
    <h1>Tv</h1>

    <ActionsButtons/>

    <Tabs :tabs="['Tab 1', 'Tab 2', 'Tab 3', 'Tab 4']">
      <template v-slot:0> Content 1</template>
      <template v-slot:1> Content 2</template>
      <template v-slot:2> Content 3</template>
      <template v-slot:3> Content 4</template>
    </Tabs>

  </div>
</template>

<script>
import ActionsButtons from '@/components/ActionsButtons'
import Tabs from '@/components/Tabs'

export default {
  name: 'TvIndex',
  components: { ActionsButtons, Tabs },
  data() {
    return {
      data: {
        id: null,
        name: ''
      }
    }
  }
}
</script>

<style scoped>

</style>
