export default {
  path: 'metas',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Meta'
  },
  children: [
    {
      path: '/',
      name: 'metas',
      component: import('@meta/js/admin/components/MetaList'),
      meta: {
        label: 'Meta List'
      }
    },
    {
      path: 'create',
      name: 'metas_create',
      component: import('@meta/js/admin/components/MetaForm'),
      meta: {
        label: 'Create Meta'
      }
    },
    {
      path: ':id/edit',
      name: 'metas_edit',
      component: import('@meta/js/admin/components/MetaForm'),
      props: true,
      meta: {
        label: 'Meta Edit'
      }
    }
  ]
}
