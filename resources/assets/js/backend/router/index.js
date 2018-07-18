import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '../containers/Full'

// Views
import Search from '../views/Search'
import Dashboard from '../views/Dashboard'

Vue.use(Router)

export function createRouter (base, i18n) {
  return new Router({
    // mode: 'history',
    base: base,
    linkActiveClass: 'open active',
    scrollBehavior: () => ({y: 0}),
    routes: [
      {
        path: '/',
        redirect: '/dashboard',
        name: 'home',
        component: Full,
        meta: {
          label: i18n.t('labels.frontend.titles.administration')
        },
        children: [
          {
            path: 'search',
            name: 'search',
            component: Search,
            meta: {
              label: i18n.t('labels.search')
            }
          },
          {
            path: 'dashboard',
            name: 'dashboard',
            component: Dashboard,
            meta: {
              label: i18n.t('labels.backend.titles.dashboard')
            }
          },
          require('@blog/js/admin/routes').default,
          require('@user/js/admin/routes').default,
          require('@role/js/admin/routes').default,
          require('@meta/js/admin/routes').default,
          require('@redirection/js/admin/routes').default,
          require('@contact/js/admin/routes').default,
          require('@result/js/admin/routes').default,
          require('@news/js/admin/routes').default
        ]
      }
    ]
  })
}
