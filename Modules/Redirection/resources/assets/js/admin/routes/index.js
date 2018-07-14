export default {
  path: 'redirections',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Redirecction'
  },
  children: [
    {
      path: '/',
      name: 'redirections',
      component: import('@redirection/js/admin/index'),
      meta: {
        label: 'Redirection List'
      }
    },
    {
      path: 'create',
      name: 'redirections_create',
      component: import('@redirection/js/admin/components/RedirectionForm'),
      meta: {
        label: 'Create Redirections'
      }
    },
    {
      path: ':id/edit',
      name: 'redirections_edit',
      component: import('@redirection/js/admin/components/RedirectionForm'),
      props: true,
      meta: {
        label: 'Edit Redirections'
      }
    }
  ]
}
