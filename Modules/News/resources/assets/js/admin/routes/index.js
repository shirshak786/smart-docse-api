export default {
  path: 'news',
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
      name: 'news.index',
      component: require('@news/js/admin/components/NewsList').default,
      meta: {
        label: 'News List'
      }
    },
    {
      path: 'create',
      name: 'news.create',
      component: require('@news/js/admin/components/NewsForm').default,
      meta: {
        label: 'Create News'
      }
    },
    {
      path: ':id/edit',
      name: 'news.edit',
      component: require('@news/js/admin/components/NewsForm').default,
      props: true,
      meta: {
        label: 'Edit News'
      }
    }
  ]
}
