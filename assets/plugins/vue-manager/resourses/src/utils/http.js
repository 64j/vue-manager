import store from '@/store'
import router from '@/router'
import { toRaw } from 'vue'

export default {
  baseUrl: localStorage['EVO.HOST'] || '',

  setUrl () {
    return this.baseUrl + 'assets/plugins/vue-manager/'
  },

  setBody (body) {
    if (!body) {
      return null
    }
    body = body || {}
    if (typeof body !== 'string') {
      body = JSON.stringify(toRaw(body))
    }
    return body
  },

  setHeaders (headers) {
    return Object.assign({
      'Cache': 'no-cache',
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'x-access-token': localStorage['EVO.TOKEN'] || ''
    }, headers || {})
  },

  handlerResponse (response) {
    if (response.ok) {
      return response.json()
    }

    if (response.status !== 404) {
      if (location.hash !== '#/login') {
        store.dispatch('Settings/del').then(() => {
          store.dispatch('MultiTabs/delAllTabs').then(() => {
            router.push({ name: 'AuthLogin' })
          })
        })
      }
    }

    return {}
  },

  handlerCatch (error) {
    return {
      errors: [error.message || '']
    }
  },

  fetch (method, url, body) {
    if (method === 'get') {
      body = null
    } else {
      body = {
        method: url,
        params: body || []
      }
    }
    return fetch(this.setUrl(), {
      method: method,
      body: this.setBody(body || ''),
      headers: this.setHeaders(),
      credentials: 'same-origin'
    }).then(this.handlerResponse).catch(this.handlerCatch)
  },

  get (url) {
    return this.fetch('get', url)
  },

  post (url, body) {
    return this.fetch('post', url, body)
  },

  patch (url, body) {
    return this.fetch('patch', url, body)
  },

  put (url, body) {
    return this.fetch('put', url, body)
  },

  delete (url, body) {
    return this.fetch('delete', url, body)
  },

  options (url, body) {
    return this.fetch('options', url, body)
  },

  login (data) {
    this.baseUrl = data.host

    const body = new FormData()
    for (const i in data) {
      body.append(i, data[i])
    }

    return fetch(this.baseUrl + 'manager/processors/login.processor.php', {
      method: 'post',
      body: body,
      credentials: 'same-origin'
    }).then((res) => {
      if (res.status === 404) {
        return {
          errors: [
            {
              code: 404,
              message: 'Not found'
            }
          ]
        }
      }

      if (/text\/html/.test(res.headers.get('content-type'))) {
        return res.text()
      }

      return res.json()
    }).catch(this.handlerCatch)
  },

  settings (callback) {
    return this.get('/settings').then(callback)
  }
}
