import store from '@/store'
import router from '@/router'

export default {
  baseUrl: localStorage['EVO.HOST'] || '',

  setUrl (url) {
    if (!~url.indexOf('http')) {
      url = this.baseUrl + 'vue-manager/api/' + url.split('/').filter(v => v !== '').join('/')
    }
    return url
  },

  setBody (body) {
    if (!body) {
      return null
    }
    body = body || {}
    if (typeof body !== 'string') {
      body = JSON.stringify(body)
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
    if (response.status === 403) {
      if (location.hash !== '#/login') {
        store.dispatch('Settings/del').then(() => {
          store.dispatch('MultiTabs/delAllTabs').then(() => {
            router.push({ name: 'AuthLogin' })
          })
        })
      }
    }

    if (response.status === 404) {
      return {}
    }

    return response.json()
  },

  handlerCatch (error) {
    console.error(error.message)

    return {
      errors: [error.message || '']
    }
  },

  fetch (method, url, body) {
    return fetch(this.setUrl(url), {
      method: method,
      body: this.setBody(body || ''),
      headers: this.setHeaders(),
      //credentials: 'include'
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
      //credentials: 'include'
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
