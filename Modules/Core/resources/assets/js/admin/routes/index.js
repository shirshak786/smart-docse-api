export default {
  path: 'users',
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
      name: 'users',
      component: import('@user/js/admin/components/UserList'),
      meta: {
        label: 'User List'
      }
    },
    {
      path: 'create',
      name: 'users_create',
      component: import('@user/js/admin/components/UserForm'),
      meta: {
        label: 'Create User'
      }
    },
    {
      path: ':id/edit',
      name: 'users_edit',
      component: import('@user/js/admin/components/UserForm'),
      props: true,
      meta: {
        label: 'Edit User'
      }
    }
  ]
}
