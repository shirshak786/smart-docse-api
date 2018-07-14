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
      component: import('@role/js/admin/components/RoleList'),
      meta: {
        label: 'Role Index'
      }
    },
    {
      path: 'create',
      name: 'roles_create',
      component: import('@role/js/admin/components/RoleForm'),
      meta: {
        label: 'Create Role'
      }
    },
    {
      path: ':id/edit',
      name: 'roles_edit',
      component: import('@role/js/admin/components/RoleForm'),
      props: true,
      meta: {
        label: 'Edit Role'
      }
    }
  ]
}
