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
      component: require('@redirection/js/index').default,
      meta: {
        label: 'Redirection List'
      }
    },
    {
      path: 'create',
      name: 'redirections_create',
      component: require('@redirection/js/components/RedirectionForm').default,
      meta: {
        label: 'Create Redirections'
      }
    },
    {
      path: ':id/edit',
      name: 'redirections_edit',
      component: require('@redirection/js/components/RedirectionForm').default,
      props: true,
      meta: {
        label: 'Edit Redirections'
      }
    }
  ]
}
