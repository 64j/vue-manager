<template>
  <div class="panel">
    <div class="p-3">
      <div class="input-group input-group-sm">
        <router-link :to="{ name: linkName, params: { id : '' } }" class="btn btn-success">
          <i class="fa fa-plus"/>
          {{ txtNew }}
        </router-link>
        <input type="text" class="form-control" :placeholder="$t('element_filter_msg')" @keyup="filter">
        <a v-if="txtHelp" class="btn btn-outline-primary" @click="msg=!msg">{{ $t('help') }}</a>
      </div>
      <div class="m-0 mt-2 alert alert-info" v-html="txtHelp" v-if="msg"/>
    </div>
    <div v-if="!data" class="text-center p-4">
      <i class="fa fa-spinner fa-spin"></i>
    </div>
    <ul v-else>
      <li v-for="category in data" :key="'category-' + category.id">
        <template v-if="Object.values(category.items).filter(v => !v.hidden).length">
          <a class="px-3 py-2 bg-secondary bg-opacity-10 border-top border-bottom text-decoration-none text-muted">
            <span class="h5 m-0 me-2">{{ category.name }} </span>
            <small>({{ category.id }})</small>
          </a>
          <ul class="pb-2">
            <template v-for="item in category.items">
              <li v-if="!item.hidden"
                  :key="'item-' + item.id"
                  class="row m-0 px-3 align-items-center border-bottom">
                <router-link
                  :to="{ name: linkName, params: { id: item.id } }"
                  class="col py-1 ps-0 text-decoration-none user-select-none"
                  :class="[item.disabled ? 'text-danger text-opacity-75': '']"
                >
                  <i :class="linkIcon"></i>
                  <i class="fa fa-lock text-danger" v-if="item.locked"></i>
                  {{ item.name }}
                  <span class="small">({{ item.id }})</span>
                  <span class="ms-3 small text-dark" v-html="item.description"/>
                </router-link>

                <div v-if="actions" class="col-auto p-0">
                  <i v-for="(action, k) in actions"
                     :key="`item-` + item.id + `action-` + k"
                     :class="[action.values ? action.values[item[k]].icon : action.icon]"
                     class="ms-2"
                     :title="[action.values ? action.values[item[k]].title : action.title]"
                     @click="$emit('action', k, item, category)"
                  />
                </div>

              </li>
            </template>
          </ul>
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'PanelView',
  props: {
    data: {
      type: [null, Object, Array],
      required: true
    },
    linkName: {
      type: String,
      required: true
    },
    linkIcon: {
      type: String
    },
    txtNew: {
      type: String
    },
    txtHelp: {
      type: String
    },
    actions: {
      type: Object
    }
  },
  data() {
    return {
      msg: false
    }
  },
  methods: {
    filter(event) {
      const filter = event.target.value.toLowerCase()
      if (filter.length) {
        for (let i in this.data) {
          let category = this.data[i]
          for (let l in category.items) {
            let item = category.items[l]
            if (~item.name.toLowerCase().indexOf(filter)) {
              delete(item.hidden)
            } else {
              item.hidden = true
            }
          }
        }
      } else {
        for (let i in this.data) {
          let category = this.data[i]
          for (let l in category.items) {
            let item = category.items[l]
            delete(item.hidden)
          }
        }
      }
    }
  }
}
</script>

<style scoped>
.panel ul, .panel > ul > li, .panel > ul > li > a { margin: 0; padding: 0; display: block; }
</style>
