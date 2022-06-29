<template>
  <div class="menu h-100 row m-0" @click.stop="click">
    <div class="col col-start h-100">
      <ul class="nav h-100">
        <li>
          <router-link :to="{ name: 'DashboardIndex' }" class="link-home">
            <span class="logo"></span>
            Evolution
          </router-link>
        </li>
        <li class="parent"
            v-if="hasPermissions(['edit_template', 'edit_snippet', 'edit_chunk', 'edit_plugin'])">
          <a>{{ $t('elements') }}</a>
          <ul>
            <li v-if="hasPermissions('edit_template')" @mouseenter="getSubMenu('Template@list', list.templates)">
              <router-link :to="{ name: 'ElementsIndex', query: { resourcesTab: 0 } }">
                <i class="fa fa-newspaper"></i> {{ $t('manage_templates') }}
              </router-link>
              <ul v-if="list.templates.length">
                <li @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'TemplateIndex', params: { id: '' } }">
                    <i class="fa fa-plus fa-fw"></i>
                    {{ $t('new_template') }}
                  </router-link>
                </li>
                <li v-for="item in list.templates" :key="'item-template-' + item.id" @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'TemplateIndex', params: { id: item.id } }" :class="{'fst-italic': item.locked}">
                    {{ item.name }}
                    <small class="ms-1">({{ item.id }})</small>
                  </router-link>
                </li>
              </ul>
            </li>
            <li v-if="hasPermissions('edit_snippet')" @mouseenter="getSubMenu('Tv@list', list.tvs)">
              <router-link :to="{ name: 'ElementsIndex', query: { resourcesTab: 1 } }">
                <i class="fa fa-list-alt"></i> {{ $t('tmplvars') }}
              </router-link>
              <ul v-if="list.tvs.length">
                <li @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'TvIndex', params: { id: '' } }">
                    <i class="fa fa-plus fa-fw"></i>
                    {{ $t('new_tmplvars') }}
                  </router-link>
                </li>
                <li v-for="item in list.tvs" :key="'item-tv-' + item.id" @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'TvIndex', params: { id: item.id } }" :class="{'fst-italic': item.locked}">
                    {{ item.name }}
                    <small class="ms-1">({{ item.id }})</small>
                  </router-link>
                </li>
              </ul>
            </li>
            <li v-if="hasPermissions('edit_chunk')" @mouseenter="getSubMenu('Chunk@list', list.chunks)">
              <router-link :to="{ name: 'ElementsIndex', query: { resourcesTab: 2 } }">
                <i class="fa fa-th-large"></i> {{ $t('manage_htmlsnippets') }}
              </router-link>
              <ul v-if="list.chunks.length">
                <li @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'ChunkIndex', params: { id: '' } }">
                    <i class="fa fa-plus fa-fw"></i>
                    {{ $t('new_htmlsnippet') }}
                  </router-link>
                </li>
                <li v-for="item in list.chunks" :key="'item-chunk-' + item.id" @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'ChunkIndex', params: { id: item.id } }" :class="{'fst-italic': item.locked, 'text-danger opacity-50': item.disabled}">
                    {{ item.name }}
                    <small class="ms-1">({{ item.id }})</small>
                  </router-link>
                </li>
              </ul>
            </li>
            <li v-if="hasPermissions('edit_snippet')" @mouseenter="getSubMenu('Snippet@list', list.snippets)">
              <router-link :to="{ name: 'ElementsIndex', query: { resourcesTab: 3 } }">
                <i class="fa fa-code"></i> {{ $t('manage_snippets') }}
              </router-link>
              <ul v-if="list.snippets.length">
                <li @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'SnippetIndex', params: { id: '' } }">
                    <i class="fa fa-plus fa-fw"></i>
                    {{ $t('new_snippet') }}
                  </router-link>
                </li>
                <li v-for="item in list.snippets" :key="'item-snippet-' + item.id" @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'SnippetIndex', params: { id: item.id } }" :class="{'fst-italic': item.locked, 'text-danger opacity-50': item.disabled}">
                    {{ item.name }}
                    <small class="ms-1">({{ item.id }})</small>
                  </router-link>
                </li>
              </ul>
            </li>
            <li v-if="hasPermissions('edit_plugin')" @mouseenter="getSubMenu('Plugin@list', list.plugins)">
              <router-link :to="{ name: 'ElementsIndex', query: { resourcesTab: 4 } }">
                <i class="fa fa-plug"></i> {{ $t('manage_plugins') }}
              </router-link>
              <ul v-if="list.plugins.length">
                <li @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'PluginIndex', params: { id: '' } }">
                    <i class="fa fa-plus fa-fw"></i>
                    {{ $t('new_plugin') }}
                  </router-link>
                </li>
                <li v-for="item in list.plugins" :key="'item-plugin-' + item.id" @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'PluginIndex', params: { id: item.id } }" :class="{'fst-italic': item.locked, 'text-danger opacity-50': item.disabled}">
                    {{ item.name }}
                    <small class="ms-1">({{ item.id }})</small>
                  </router-link>
                </li>
              </ul>
            </li>
            <li v-if="hasPermissions('edit_module')" @mouseenter="getSubMenu('Module@list', list.modules)">
              <router-link :to="{ name: 'ElementsIndex', query: { resourcesTab: 5 } }">
                <i class="fa fa-cubes"></i> {{ $t('modules') }}
              </router-link>
              <ul v-if="list.modules.length">
                <li @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'ModuleIndex', params: { id: '' } }">
                    <i class="fa fa-plus fa-fw"></i>
                    {{ $t('new_module') }}
                  </router-link>
                </li>
                <li v-for="item in list.modules" :key="'item-module-' + item.id" @mouseenter="subMenuEnter">
                  <router-link :to="{ name: 'ModuleIndex', params: { id: item.id } }" :class="{'fst-italic': item.locked, 'text-danger opacity-50': item.disabled}">
                    {{ item.name }}
                    <small class="ms-1">({{ item.id }})</small>
                  </router-link>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="parent" v-if="hasPermissions('exec_module')" @mouseenter="getSubMenu('Module@list', list.modules)">
          <a>{{ $t('modules') }}</a>
          <ul v-if="list.modules.length">
            <li v-for="item in list.modules" :key="'item-module-exec-' + item.id" @mouseenter="subMenuEnter">
              <router-link :to="{ name: 'ModuleExec', params: { id: item.id } }" :class="{'fst-italic': item.locked, 'text-danger opacity-50': item.disabled}">
                <i class="fa fa-cube"></i>
                {{ item.name }}
              </router-link>
            </li>
          </ul>
        </li>
        <li class="parent"
            v-if="hasPermissions('edit_user', 'edit_web_user', 'edit_role', 'access_permissions', 'web_access_permissions')">
          <a>{{ $t('users') }}</a>
          <ul>
            <li v-if="hasPermissions('edit_user')">
              <router-link :to="{ name: 'UserList' }">
                <i class="fa fa-user-circle"></i> {{ $t('user_management_title') }}
              </router-link>
            </li>
            <li v-if="hasPermissions('edit_web_user')">
              <router-link :to="{ name: 'WebUserList' }">
                <i class="fa fa-user"></i> {{ $t('web_user_management_title') }}
              </router-link>
            </li>
            <li v-if="hasPermissions('edit_role')">
              <router-link :to="{ name: 'RoleList' }">
                <i class="fa fa-legal"></i> {{ $t('role_management_title') }}
              </router-link>
            </li>
            <li v-if="hasPermissions('access_permissions')">
              <router-link :to="{ name: 'UserPermissionsIndex' }">
                <i class="fa fa-universal-access"></i> {{ $t('manager_permissions') }}
              </router-link>
            </li>
            <li v-if="hasPermissions('web_access_permissions')">
              <router-link :to="{ name: 'WebUserPermissionsIndex' }">
                <i class="fa fa-male"></i> {{ $t('web_permissions') }}
              </router-link>
            </li>
          </ul>
        </li>
        <li class="parent"
            v-if="hasPermissions('empty_cache')">
          <a>{{ $t('tools') }}</a>
          <ul>
            <li>
              <router-link :to="{ name: 'ClearCacheIndex' }">
                <i class="fa fa-recycle"></i> {{ $t('refresh_site') }}
              </router-link>
            </li>
            <li>
              <router-link :to="{ name: 'SearchIndex' }">
                <i class="fa fa-search"></i> {{ $t('search') }}
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="col-auto col-end h-100">
      <ul class="nav h-100">
        <li class="parent">
          <a>{{ user('username') }} <i class="fa fa-user-circle m-0 ms-2"></i></a>
          <ul>
            <li>
              <router-link :to="{ name: 'AuthPasswordChange' }">
                <i class="fa fa-lock"></i> {{ $t('change_password') }}
              </router-link>
            </li>
            <li>
              <router-link :to="{ name: 'AuthLogout' }">
                <i class="fa fa-sign-out"></i> {{ $t('logout') }}
              </router-link>
            </li>
          </ul>
        </li>
        <li class="parent"
            v-if="hasPermissions(['settings', 'view_eventlog', 'logs', 'help'])">
          <a><i class="fa fa-cogs m-0"></i></a>
          <ul>
            <li v-if="hasPermissions('settings')">
              <router-link :to="{ name: 'ConfigurationIndex' }">
                <i class="fa fa-sliders"></i> {{ $t('edit_settings') }}
              </router-link>
            </li>
            <li>
              <router-link :to="{ name: 'SchedulesIndex' }">
                <i class="fa fa-calendar"></i> {{ $t('site_schedule') }}
              </router-link>
            </li>
            <li v-if="hasPermissions('view_eventlog')">
              <router-link :to="{ name: 'EventLogList' }">
                <i class="fa fa-exclamation-triangle"></i> {{ $t('eventlog_viewer') }}
              </router-link>
            </li>
            <template v-if="hasPermissions('logs')">
              <li>
                <router-link :to="{ name: 'SystemLogIndex' }">
                  <i class="fa fa-user-secret"></i> {{ $t('view_logging') }}
                </router-link>
              </li>
              <li>
                <router-link :to="{ name: 'SystemInfoIndex' }">
                  <i class="fa fa-info-circle"></i> {{ $t('view_sysinfo') }}
                </router-link>
              </li>
            </template>
            <li v-if="hasPermissions('help')">
              <router-link :to="{ name: 'HelpIndex' }">
                <i class="fa fa-question-circle"></i> {{ $t('help') }}
              </router-link>
            </li>
            <li>
              <span class="justify-content-center text-muted small">{{ config('settings_version') }}</span>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import http from '@/utils/http'

export default {
  name: 'MenuView',
  data () {
    return {
      list: {
        templates: [],
        tvs: [],
        chunks: [],
        snippets: [],
        plugins: [],
        modules: []
      }
    }
  },
  mounted () {
    this.$el.querySelectorAll('ul.nav > li').forEach((i) => {
      i.addEventListener('mouseenter', (e) => {
        this.$el.querySelectorAll('ul.nav > li.hover').forEach(i => i.classList.remove('hover'))
        e.target.classList.add('hover')
      })
    })
    this.$el.querySelectorAll('ul.nav > li > ul > li').forEach(i => {
      i.addEventListener('mouseenter', (e) => {
        e.target.parentElement.querySelectorAll(':scope > li.hover').forEach(i => i.classList.remove('hover'))
        e.target.classList.add('hover')
      })
    })
    document.addEventListener('click', () => {
      const active = document.querySelector('.app-header.active')
      if (active) {
        active.classList.remove('active')
      }
    })
  },
  methods: {
    click (event) {
      if (event.target.classList.contains('link-home')) {
        this.$el.parentElement.classList.remove('active')
      } else if (event.target.closest('.menu ul.nav > li > a')) {
        this.$el.parentElement.classList.add('active')
      } else {
        this.$el.parentElement.classList.remove('active')
      }
    },
    getSubMenu (method, list) {
      for (let i in this.list) {
        if (this.list[i] !== list) {
          this.list[i] = []
        }
      }
      if (!list.length) {
        http.post(method).then(result => {
          if (result.data) {
            for (let i in result.data) {
              list.push(result.data[i])
            }
          }
        })
      }
    },
    subMenuEnter (e) {
      e.target.parentElement.querySelectorAll(':scope > li.hover').forEach(i => i.classList.remove('hover'))
      e.target.classList.add('hover')
    },
    hasPermissions (permissions) {
      if (typeof permissions === 'object') {
        return permissions.some((v) => this.$store.state['Settings'].permissions[v])
      } else {
        return !!this.$store.state['Settings'].permissions[permissions]
      }
    },
    config(key) {
      return this.$store.state['Settings'].config[key] || null
    },
    user(key) {
      return this.$store.state['Settings'].user[key] || null
    }
  }
}
</script>

<style scoped>
.menu { background: rgba(var(--bs-light-rgb), var(--bs-bg-opacity)); --bs-bg-opacity: .07; }
.menu ul.nav { display: flex; align-items: center; line-height: 1; }
.menu ul, .menu li { list-style: none; margin: 0; padding: 0; }
.menu li > a, .menu li > span { display: inline-flex; align-items: center; padding: .85rem 1rem; width: 100%; height: 100%; text-decoration: none; color: var(--bs-dark-rgb);cursor: pointer; user-select: none; }
.menu li > span { cursor: default; user-select: auto; }
.menu ul.nav > li { position: relative; z-index: 1; height: 100%; }
.app-header.active .menu ul.nav > li.hover > ul, .menu ul.nav > li > ul > li.hover > ul { display: block; }
.menu ul.nav > li > a:hover, .app-header.active .menu ul.nav > li > a.router-link-active { background: var(--bs-gray-800); color: var(--bs-white); }
.menu ul.nav > li > a.router-link-active { background: var(--bs-gray-700); }
.app-header.active .menu ul.nav > li.parent.hover > a { background: var(--bs-white); color: var(--bs-dark) }
.menu ul.nav > li > a { position: relative; z-index: 2; padding: 0 1.25rem; color: var(--bs-gray-200); }
.menu ul.nav > li ul { display: none; position: absolute; z-index: 1; top: 100%; padding: 0 0 .25rem; min-width: 20rem; max-height: calc(100vh - 3rem); background: var(--bs-white); box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15); border-radius: 0 0 .35rem .35rem; }
.menu ul.nav > li > ul { left: 0; }
.menu ul.nav > li > ul > li > ul { left: 100%; top: 0; overflow-y: auto; }
.menu .col-end ul.nav > li > ul { left: auto; right: 0; }
.menu ul.nav > li > ul li { border-bottom: 1px solid rgba(0, 0, 0, .05); }
.menu ul.nav > li > ul li:last-child { border: none; }
.menu ul.nav > li > ul > li > a.router-link-active { background: var(--bs-light) }
.menu ul.nav > li > ul li.hover > a { background: var(--bs-primary); color: var(--bs-white); }
.menu .fa { width: 1.3em; margin: 0 .5rem 0 0; line-height: .8; text-align: center; vertical-align: baseline; opacity: .85; }
.menu ul.nav > li > a > .fa { font-size: 1.4rem; }
.logo { margin: 0 2rem 0 .5rem; width: 0; height: 0; transform: scale(0.2); }
.logo::before { transform: none; content: ""; display: block; position: relative; z-index: 1; left: 50%; top: 30%; width: 120px; height: 120px; margin: -60px 0 0 -60px; border-radius: 50%; box-shadow: 5px 5px 0 0 rgb(234, 132, 82), 14px -7px 0 0 rgba(111, 163, 219, 0.7), -7px 11px 0 0 rgba(112, 193, 92, 0.74), -11px -7px 0 0 rgba(147, 205, 99, 0.78); animation: none }
/*.nprogress-busy .logo::before { transform: scale(0.2); animation: nprogress-spinner 2s linear infinite; }*/
.link-home { font-size: 1.125rem; text-transform: uppercase; letter-spacing: .05em; }
</style>

<style>
.app-header.active + .app-body::before { content: ""; position: absolute; z-index: 999; left: 0; top: 0; right: 0; bottom: 0; background: var(--bs-gray-dark); opacity: .15; }
</style>
