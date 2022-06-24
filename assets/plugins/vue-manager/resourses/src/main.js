import { createApp } from 'vue'
import App from './App'
import router from './router'
import store from './store'
import i18n from './i18n'
import Notifications from '@kyvg/vue3-notification'
import '@fortawesome/fontawesome-free/css/all.css'

const app = createApp(App)

store.dispatch('Settings/get').then(settings => {
  if (settings.user.role) {
    i18n.global.locale.value = settings.config['lang_code']
  }
  app.mixin({
    methods: {
      hasPermissions (permissions) {
        if (typeof permissions === 'object') {
          return permissions.some((v) => this.$store.state['Settings'].permissions[v])
        } else {
          return !!this.$store.state['Settings'].permissions[permissions]
        }
      },
      config (key) {
        return this.$store.state['Settings'].config[key] || null
      },
      user (key) {
        return this.$store.state['Settings'].user[key] || null
      }
    },
    // unmounted () {
    //   console.log('unMounted: ' + this.$.type.name)
    // }
  })
  app.use(store)
  app.use(router)
  app.use(i18n)
  app.use(Notifications)
  app.mount('#app')
})

