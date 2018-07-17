export default {
  path: 'posts',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Post'
  },
  children: [
    {
      path: '/',
      name: 'posts',
      component: require('@blog/js/admin/components/PostList').default,
      meta: {
        label: 'Post List'
      }
    },
    {
      path: 'create',
      name: 'posts_create',
      component: require('@blog/js/admin/components/PostForm').default,
      meta: {
        label: 'Create Post'
      }
    },
    {
      path: ':id/edit',
      name: 'posts_edit',
      component: require('@blog/js/admin/components/PostForm').default,
      props: true,
      meta: {
        label: 'Edit Post'
      }
    }
  ]
}
