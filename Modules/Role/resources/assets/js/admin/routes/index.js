export default {
  path: 'roles',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Roles'
  },
  children: [
    {
      path: '/',
      name: 'roles',
      component: require('@role/js/components/RoleList').default,
      meta: {
        label: 'Role Index'
      }
    },
    {
      path: 'create',
      name: 'roles_create',
      component: require('@role/js/components/RoleForm').default,
      meta: {
        label: 'Create Role'
      }
    },
    {
      path: ':id/edit',
      name: 'roles_edit',
      component: require('@role/js/components/RoleForm').default,
      props: true,
      meta: {
        label: 'Edit Role'
      }
    }
  ]
}
