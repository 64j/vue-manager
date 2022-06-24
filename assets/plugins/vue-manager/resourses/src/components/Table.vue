<template>
  <div class="container-fluid" :class="{ 'container-loading': loading }">
    <div v-if="!data" class="text-center p-4">
      <i class="fa fa-spinner fa-spin"></i>
    </div>
    <template v-else>
      <div class="row mb-3">
        <div class="col">
          <slot/>
        </div>
        <div class="col-auto" v-if="modeSearch || modeList">
          <div class="input-group input-group-sm">
            <input class="form-control form-control-sm" name="search" type="text" value="" :placeholder="$t('search')">
            <a class="btn btn-outline-secondary" :title="$t('search')">
              <i class="fa fa-search"></i>
            </a>
            <a class="btn btn-outline-secondary" :title="$t('reset')">
              <i class="fa fa-refresh"></i>
            </a>
            <a class="btn btn-outline-secondary" :title="$t('list_mode')">
              <i class="fa fa-table"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="overflow-auto p-0">
          <table class="table table-hover" :class="tableClass">
            <thead v-if="columns">
            <tr>
              <th v-for="(column, i) in columns" :key="i" class="text-nowrap">
                {{ column['title'] }}
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, i) in data" :key="i" :data-id="item['id']">
              <template v-if="columns">
                <td v-for="(column, j) in columns" :key="j" :class="column['class'] || ''">
                  <template v-if="!item[j] && column['value']">
                    <router-link v-if="column['link']" :to="{ name: linkName, params: { id : item['id'] } }">
                      <span v-html="value(column['value'], item[j])"/>
                    </router-link>
                    <span v-else v-html="value(column['value'], item[j])"></span>
                  </template>
                  <router-link v-else-if="column['link']" :to="{ name: linkName, params: { id : item['id'] } }">
                    <span v-html="item[j]"/>
                  </router-link>
                  <span v-else v-html="item[j]"/>
                </td>
              </template>
              <template v-else>
                <td v-for="(value, j) in item" :key="j">
                  <span v-html="value"></span>
                </td>
              </template>
            </tr>
            </tbody>
          </table>
        </div>

        <div v-if="pages && pagination.total">
          <ul class="pagination">
            <li class="page-item" :class="{ 'disabled' : page <= 1 }">
              <a class="page-link" href="javascript:" @click="paginationPrev">
                <span>&laquo;</span>
              </a>
            </li>

            <li class="page-item" v-for="(number, i) in pages" :key="i" :class="{ 'active' : number === page }">
              <span class="page-link" v-if="!number">&hellip;</span>
              <a class="page-link" href="javascript:" v-else @click="currentPage(number)">{{ number }}</a>
            </li>

            <li class="page-item" :class="{ 'disabled' : pagination.total <= page }">
              <a class="page-link" href="javascript:" @click="paginationNext">
                <span>&raquo;</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import i18n from '@/i18n'

export default {
  name: 'TableView',
  props: {
    data: {
      type: [null, Array, Object],
      default: null
    },
    columns: {
      type: [null, Array, Object],
      default: null
    },
    pagination: {
      type: [null, Object],
      default: null
    },
    linkName: {
      type: String
    },
    tableClass: {
      type: String
    },
    modeSearch: {
      type: Boolean,
      default: false
    },
    modeList: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      path: this.$route.path,
      current: this.$route.query['p']
    }
  },
  computed: {
    pages () {
      let pages = null
      if (this.pagination) {
        const q = Math.ceil(this.pagination.total / this.pagination.limit)
        if (q > 1) {
          pages = []
          for (let i = 0; i < q; i++) {
            pages.push(i + 1)
          }
        }
      }
      return pages
    },
    page () {
      if (this.$route.query['p']) {
        const queryPage = this.$route.query['p']
        const { total } = this.pagination
        return parseInt(queryPage > total ? total : (queryPage < 1 ? 1 : queryPage))
      }
      return 1
    }
  },
  watch: {
    '$route.fullPath' () {
      if (this.path === this.$route.path && this.current !== this.$route.query['p']) {
        this.current = this.$route.query['p']
        this.$emit('getData', this.current)
      }
    }
  },
  methods: {
    value (column, value) {
      if (typeof value === 'undefined') {
        return column
      } else if (column[value]) {
        if (typeof column[value] === 'object') {
          if (column[value]['lang']) {
            return i18n.global.t(column[value]['lang'])
          } else {
            return column[value]['value'] || ''
          }
        } else {
          return column[value]
        }
      } else {
        return value
      }
    },
    paginationPrev () {
      const page = this.page - 1
      if (page > 1) {
        this.$router.push({ query: { p: page } })
      } else {
        this.$router.push({ query: {} })
      }
    },
    paginationNext () {
      const page = this.page + 1
      this.$router.push({ query: { p: page } })
    },
    currentPage (page) {
      if (this.page !== page) {
        if (page > 1) {
          this.$router.push({ query: { p: page } })
        } else {
          this.$router.push({ query: {} })
        }
      }
    }
  }
}
</script>

<style scoped>
.container-loading { position: relative; opacity: .5 }
.container-loading::before { content: ""; left: 0; top: 0; right: 0; bottom: 0; position: absolute; z-index: 9; }
.table th:first-child, .table td:first-child { padding-left: 1.25rem }
.table th:last-child, .table td:last-child { padding-right: 1.25rem }
.pagination .disabled { user-select: none }
</style>
