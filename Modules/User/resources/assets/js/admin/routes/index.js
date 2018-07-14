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
      component: require('@user/admin/components/UserList').default,
      meta: {
        label: 'User List'
      }
    },
    {
      path: 'create',
      name: 'users_create',
      component: require('@user/admin/components/UserForm').default,
      meta: {
        label: 'Create User'
      }
    },
    {
      path: ':id/edit',
      name: 'users_edit',
      component: require('@user/admin/components/UserForm').default,
      props: true,
      meta: {
        label: 'Edit User'
      }
    }
  ]
}
