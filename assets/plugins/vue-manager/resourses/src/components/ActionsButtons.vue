<template>
  <div class="actions-buttons position-fixed rounded-bottom bg-opacity-10 bg-secondary p-2">
    <div class="btn-group btn-group-sm">
      <button type="button" class="btn btn-light btn-outline-secondary shadow-none" @click="$emit('cancel', $event)" :title="$t('cancel')">
        <i class="fa fa-reply fa-fw"/>
        <span>{{ $t('cancel') }}</span>
      </button>
      <button type="button" class="btn btn-danger shadow-none" @click="$emit('delete', $event)" :title="$t('delete')">
        <i class="fa fa-trash-alt fa-fw"/>
        <span>{{ $t('delete') }}</span>
      </button>
      <button type="button" class="btn btn-success shadow-none" @click="$emit('save', $event)" :title="$t('save')">
        <i class="fa fa-save fa-fw"/>
        <span>{{ $t('save') }}</span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ActionsButtons'
}
</script>

<style scoped>
.actions-buttons { top: 5rem; right: 0; }
.btn span { display: none }
</style>
