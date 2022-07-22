import { createRouter, createWebHashHistory } from 'vue-router'
import nprogress from 'nprogress'
import store from '@/store'

import('nprogress/nprogress.css')

const routes = [
  {
    path: '/',
    name: 'DashboardIndex',
    component: () => import('@/views/Dashboard/Index'),
    meta: {
      fixTab: true,
      layout: 'DefaultLayout',
      title: '',
      icon: 'fa fa-home',
      class: 'tab-home'
    }
  },
  {
    path: '/document/:id?',
    name: 'DocumentIndex',
    component: () => import('@/views/Document/Index')
  },
  {
    path: '/document/:id/list',
    name: 'DocumentList',
    component: () => import('@/views/Document/List')
  },
  {
    path: '/elements',
    name: 'ElementsIndex',
    component: () => import('@/views/Elements/Index'),
    meta: {
      groupTab: true
    }
  },
  {
    path: '/template/:id?',
    name: 'TemplateIndex',
    component: () => import('@/views/Template/Index')
  },
  {
    path: '/tv/:id?',
    name: 'TvIndex',
    component: () => import('@/views/Tv/Index')
  },
  {
    path: '/chunk/:id?',
    name: 'ChunkIndex',
    component: () => import('@/views/Chunk/Index')
  },
  {
    path: '/snippet/:id?',
    name: 'SnippetIndex',
    component: () => import('@/views/Snippet/Index')
  },
  {
    path: '/plugin/:id?',
    name: 'PluginIndex',
    component: () => import('@/views/Plugin/Index')
  },
  {
    path: '/module/:id?',
    name: 'ModuleIndex',
    component: () => import('@/views/Module/Index')
  },
  {
    path: '/module/exec/:id?',
    name: 'ModuleExec',
    component: () => import('@/views/Module/Exec')
  },
  {
    path: '/users',
    name: 'UserList',
    component: () => import('@/views/User/List')
  },
  {
    path: '/user/:id?',
    name: 'UserIndex',
    component: () => import('@/views/User/Index')
  },
  {
    path: '/web-users',
    name: 'WebUserList',
    component: () => import('@/views/WebUser/List')
  },
  {
    path: '/web-user/:id?',
    name: 'WebUserIndex',
    component: () => import('@/views/WebUser/Index')
  },
  {
    path: '/roles',
    name: 'RoleList',
    component: () => import('@/views/Role/List')
  },
  {
    path: '/role/:id?',
    name: 'RoleIndex',
    component: () => import('@/views/Role/Index')
  },
  {
    path: '/user-permissions',
    name: 'UserPermissionsIndex',
    component: () => import('@/views/UserPermissions/Index')
  },
  {
    path: '/web-user-permissions',
    name: 'WebUserPermissionsIndex',
    component: () => import('@/views/WebUserPermissions/Index')
  },
  {
    path: '/clear-cache',
    name: 'ClearCacheIndex',
    component: () => import('@/views/Cache/Index')
  },
  {
    path: '/search',
    name: 'SearchIndex',
    component: () => import('@/views/Search/Index')
  },
  {
    path: '/configuration',
    name: 'ConfigurationIndex',
    component: () => import('@/views/Configuration/Index')
  },
  {
    path: '/schedules',
    name: 'SchedulesIndex',
    component: () => import('@/views/Schedules/Index')
  },
  {
    path: '/event-logs',
    name: 'EventLogList',
    component: () => import('@/views/EventLog/List')
  },
  {
    path: '/event-log/:id',
    name: 'EventLogIndex',
    component: () => import('@/views/EventLog/Index')
  },
  {
    path: '/system-log',
    name: 'SystemLogIndex',
    component: () => import('@/views/SystemLog/Index')
  },
  {
    path: '/system-info',
    name: 'SystemInfoIndex',
    component: () => import('@/views/SystemInfo/Index')
  },
  {
    path: '/help',
    name: 'HelpIndex',
    component: () => import('@/views/Help/Index')
  },
  {
    path: '/login',
    name: 'AuthLogin',
    component: () => import('@/views/Auth/Login'),
    meta: {
      layout: 'BlankLayout',
      noTab: true
    }
  },
  {
    path: '/logout',
    name: 'AuthLogout',
    redirect: '/login'
  },
  {
    path: '/auth/password/change',
    name: 'AuthPasswordChange',
    component: () => import('@/views/Auth/PasswordChange')
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'PageNotFoundIndex',
    component: () => import('@/views/PageNotFound/Index')
  },
  {
    path: '/redirect',
    component: () => import('@/views/Redirect/Index'),
    hidden: true,
    children: [
      {
        path: '/redirect/:path(.*)',
        component: () => import('@/views/Redirect/Index')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  nprogress['start']()
  if (store.state['Settings'].user.role) {
    if (to?.redirectedFrom?.name === 'AuthLogout') {
      store.dispatch('Settings/del').then(() => {
        store.dispatch('MultiTabs/delAllTabs').then(() => {
          next({ name: 'AuthLogin' })
        })
      })
    } else if (to.name === 'AuthLogin') {
      next('/')
    } else {
      next()
    }
  } else if (!store.state['Settings'].user.role) {
    if (to.name !== 'AuthLogin') {
      store.dispatch('Settings/del').then(() => {
        store.dispatch('MultiTabs/delAllTabs').then(() => {
          next({ name: 'AuthLogin' })
        })
      })
    } else {
      next()
    }
  } else {
    next()
  }
})

router.afterEach(() => {
  nprogress['done']()
})

router.onError((handler) => {
  console.log('error:', handler)
})

export default router
