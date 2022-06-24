<template>
  <div class="main w-100 h-100 d-flex flex-nowrap bg-dark">
    <div class="col-auto sidebar p-5 bg-dark bg-opacity-75 text-white text-opacity-75">
      <form @submit.prevent="submit">

        <div class="mb-3">
          <label class="mb-1">{{ $t('valid_hostnames_title') }}</label>
          <div class="position-relative parent-input-list">
            <input v-model="data.host" @focus="listOpen" @blur="listClose" type="text" class="form-control form-control-lg rounded-0 bg-transparent text-white" :class="{'border-danger': isErrors}">
            <div class="input-list position-absolute w-100 bg-light text-dark">
              <div v-for="(host, k) in hosts" :key="k" class="px-3 py-2 d-flex align-items-center justify-content-between" @mousedown="listSelect">
                <span>{{ host }}</span>
                <i class="fa fa-remove text-danger float-end" @mousedown.stop="listRemoveItem" />
              </div>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="mb-1">{{ $t('user') }}</label>
          <input v-model="data.username" type="text" class="form-control form-control-lg rounded-0 bg-transparent text-white" :class="{'border-danger': isErrors}">
        </div>

        <div class="mb-3">
          <label class="mb-1">{{ $t('password') }}</label>
          <input v-model="data.password" type="password" class="form-control form-control-lg rounded-0 bg-transparent text-white" :class="{'border-danger': isErrors}">
        </div>

        <div class="row">
          <div class="col">
            <div class="form-check">
              <input v-model="data.rememberme" type="checkbox" class="form-check-input" id="rememberme" :false-value="0" :true-value="1">
              <label class="form-check-label" for="rememberme">{{ $t('remember_username') }}</label>
            </div>
          </div>
          <div class="col-auto">
            <button class="btn btn-success rounded-0" type="submit">{{ $t('login_button') }}</button>
          </div>
        </div>

        <div class="errors text-danger py-3 text-center" v-show="isErrors">{{ $t('login_processor_unknown_user') }}</div>

      </form>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'
import i18n from '@/i18n'
import store from '@/store'

export default {
  name: 'AuthLogin',
  data () {
    return {
      isErrors: false,
      data: {
        ajax: 1,
        username: '',
        password: '',
        rememberme: 1,
        host: localStorage['EVO.HOST'] || location.origin + '/'
      },
      hosts: localStorage['EVO.HOSTS'] && JSON.parse(localStorage['EVO.HOSTS']) || {}
    }
  },
  methods: {
    submit () {

      this.isErrors = false

      try {
        this.data.host = (new URL(this.data.host)).origin + '/'
      } catch (e) {
        this.isErrors = true
      }

      http.baseUrl = this.data.host

      http.post('/auth/login', this.data).then(result => {
        if (result['token']) {
          localStorage.setItem('x-access-token', result['token'])
          http.settings().then(result => {
            if (!result.error) {
              if (this.data.rememberme) {
                if (!this.hosts[this.data.host]) {
                  this.hosts[this.data.host] = this.data.host
                }
                localStorage['EVO.HOSTS'] = JSON.stringify(this.hosts)
                localStorage['EVO.HOST'] = this.data.host
              }
              store.dispatch('Settings/set', result.data).then(settings => {
                if (result.data.config['lang_code']) {
                  i18n.global.locale.value = settings.config['lang_code']
                }
                this.$router.push({ name: 'DashboardIndex' })
              })
            } else {
              this.isErrors = true
            }
          })
        }
      })
    },
    listOpen(event) {
      event.currentTarget.parentElement.classList.add('active')
    },
    listClose(event) {
      event.currentTarget.parentElement.classList.remove('active')
    },
    listSelect(event) {
      this.data.host = event.target.innerText
    },
    listRemoveItem(event) {
      let host = event.target.parentElement.innerText
      for (let i in this.hosts) {
        if (this.hosts[i] === host) {
          delete this.hosts[i]
        }
      }
      if (this.data.host === host) {
        this.data.host = ''
      }
      if (localStorage['EVO.HOST'] === host) {
        localStorage['EVO.HOST'] = ''
      }
      localStorage['EVO.HOSTS'] = JSON.stringify(this.hosts)
    }
  }
}
</script>

<style scoped>
.main { background: url("https://picsum.photos/1600/900") 50% 50% no-repeat; background-size: cover }
.sidebar { width: 30rem; max-width: 100%; }
.active .input-list { display: block; }
.input-list { display: none; }
.input-list > div:hover { background: var(--bs-primary); color: var(--bs-light); }
</style>
