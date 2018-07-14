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
      component: require('@meta/js/components/MetaList').default,
      meta: {
        label: 'Meta List'
      }
    },
    {
      path: 'create',
      name: 'metas_create',
      component: require('@meta/js/components/MetaForm').default,
      meta: {
        label: 'Create Meta'
      }
    },
    {
      path: ':id/edit',
      name: 'metas_edit',
      component: require('@meta/js/components/MetaForm').default,
      props: true,
      meta: {
        label: 'Meta Edit'
      }
    }
  ]
}
