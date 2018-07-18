export default {
  path: 'result',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'News'
  },
  children: [
    {
      path: '/',
      name: 'result.index',
      component: require('@result/js/admin/components/SemesterResultList').default,
      meta: {
        label: 'News List'
      }
    },
    {
      path: 'create',
      name: 'result.create',
      component: require('@result/js/admin/components/SemesterResultForm').default,
      meta: {
        label: 'Create News'
      }
    },
    {
      path: ':id/edit',
      name: 'result.edit',
      component: require('@result/js/admin/components/SemesterResultForm').default,
      props: true,
      meta: {
        label: 'Edit '
      }
    }
  ]
}
