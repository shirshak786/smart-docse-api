export default {
  path: 'news',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Users'
  },
  children: [
    {
      path: '/',
      name: 'news.index',
      component: require('@user/js/admin/components/NewsList').default,
      meta: {
        label: 'User List'
      }
    },
    {
      path: 'create',
      name: 'news.create',
      component: require('@user/js/admin/components/NewsForm').default,
      meta: {
        label: 'Create User'
      }
    },
    {
      path: ':id/edit',
      name: 'news.edit',
      component: require('@user/js/admin/components/NewsForm').default,
      props: true,
      meta: {
        label: 'Edit User'
      }
    }
  ]
}
