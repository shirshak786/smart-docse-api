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
      component: require('@role/admin/components/RoleList').default,
      meta: {
        label: 'Role Index'
      }
    },
    {
      path: 'create',
      name: 'roles_create',
      component: require('@role/admin/components/RoleForm').default,
      meta: {
        label: 'Create Role'
      }
    },
    {
      path: ':id/edit',
      name: 'roles_edit',
      component: require('@role/admin/components/RoleForm').default,
      props: true,
      meta: {
        label: 'Edit Role'
      }
    }
  ]
}
