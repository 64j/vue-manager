import diff from '@/utils/diff'

const state = {
  keys: [],
  values: []
}
const mutations = {
  ADD_MULTI_TAB_VALUE: (state, tab) => {
    if (state.values.some((v) => {
      if (v.name === tab.name && (v.meta.groupTab || !v.meta.groupTab && diff(v.params, tab.params))) {
        for (let i in v) {
          if (tab[i]) {
            v[i] = tab[i]
          }
        }
        return true
      }
      return false
    })) {
      return
    }
    state.values.push(
      Object.assign({}, tab, {
        icon: tab.meta.icon || '',
        class: tab.meta.class || '',
        title: (typeof tab.meta.title !== 'undefined' ? tab.meta.title : (tab.name || 'no-name')) + ' ' + (!tab.meta.groupTab && tab.params && tab.params.id ? tab.params.id : '')
      })
    )
  },
  ADD_MULTI_TAB_KEY: (state, tab) => {
    const key = tab.path
    if (state.keys.includes(key)) return
    if (!tab.meta['noCache']) {
      state.keys.push(key)
    }
  },
  DEL_MULTI_TAB_VALUE: (state, tab) => {
    for (const [i, v] of state.values.entries()) {
      if (v.path === tab.path) {
        state.values.splice(i, 1)
        break
      }
    }
  },
  DEL_MULTI_TAB_KEY: (state, tab) => {
    const index = state.keys.indexOf(tab.path)
    index > -1 && state.keys.splice(index, 1)
  },
  DEL_ALL_MULTI_TAB_KEYS: state => {
    state.keys = []
  },
  DEL_ALL_MULTI_TAB_VALUES: state => {
    state.values = []
  },
  REPLACE_MULTI_TAB_VALUE: (state, { route, tab }) => {
    for (const [i, v] of state.values.entries()) {
      if (v.path === route.path) {
        state.values.splice(i, 1, tab)
        break
      }
    }
  }
}
const actions = {
  addTab ({ commit }, tab) {
    commit('ADD_MULTI_TAB_VALUE', tab)
    commit('ADD_MULTI_TAB_KEY', tab)
  },
  delTab ({ dispatch, state }, tab) {
    return new Promise(resolve => {
      dispatch('delTabValue', tab)
      dispatch('delTabKey', tab)
      resolve({
        values: [...state.values],
        keys: [...state.keys]
      })
    })
  },
  delTabValue ({ commit }, tab) {
    commit('DEL_MULTI_TAB_VALUE', tab)
  },
  delTabKey ({ commit }, tab) {
    commit('DEL_MULTI_TAB_KEY', tab)
  },
  delAllTabs ({ dispatch, state }, tab) {
    return new Promise(resolve => {
      dispatch('delAllKeysTabs', tab)
      dispatch('delAllValuesTabs', tab)
      resolve({
        keys: [...state.keys],
        values: [...state.values]
      })
    })
  },
  delAllKeysTabs ({ commit, state }) {
    return new Promise(resolve => {
      commit('DEL_ALL_MULTI_TAB_KEYS')
      resolve([...state.keys])
    })
  },
  delAllValuesTabs ({ commit, state }) {
    return new Promise(resolve => {
      commit('DEL_ALL_MULTI_TAB_VALUES')
      resolve([...state.values])
    })
  },
  replaceTab ({ commit }, { route, tab }) {
    return new Promise(resolve => {
      //dispatch('delTabKey', route)
      commit('REPLACE_MULTI_TAB_VALUE', { route, tab })
      resolve({
        tab: tab
      })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
