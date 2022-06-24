import { createStore } from 'vuex'

let modules = {}

try {
  const modulesFiles = require.context('./modules', true, /\.js$/)
  modules = modulesFiles.keys().reduce((modules, modulePath) => {
    const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1')
    const value = modulesFiles(modulePath)
    modules[moduleName] = value.default
    return modules
  }, {})
} catch (e) {
  const modulesFiles = import.meta.globEager('./modules/*.js')
  Object.entries(modulesFiles).forEach(([path, definition]) => {
    const moduleName = path.split('/').pop().replace(/\.\w+$/, '')
    modules[moduleName] = definition.default
  })
}

export default createStore({
  state: {},
  getters: {},
  mutations: {},
  actions: {},
  modules
})
