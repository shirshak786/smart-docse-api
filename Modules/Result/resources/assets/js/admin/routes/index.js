export default {
  path: 'results',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Result'
  },
  children: [
    {
      path: '/',
      name: 'result.index',
      component: require('@result/js/admin/components/SemesterResultList').default,
      meta: {
        label: 'Result List'
      }
    },
    {
      path: 'create',
      name: 'result.create',
      component: require('@result/js/admin/components/SemesterResultForm').default,
      meta: {
        label: 'Create Result'
      }
    },
    {
      path: ':id/edit',
      name: 'result.edit',
      component: require('@result/js/admin/components/SemesterResultForm').default,
      props: true,
      meta: {
        label: 'Edit Result'
      }
    }
  ]
}
