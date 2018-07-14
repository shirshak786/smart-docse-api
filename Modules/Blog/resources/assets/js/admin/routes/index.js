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
      component: import('@blog/js/admin/components/PostList'),
      meta: {
        label: 'Post List'
      }
    },
    {
      path: 'create',
      name: 'posts_create',
      component: import('@blog/js/admin/components/PostForm'),
      meta: {
        label: 'Create Post'
      }
    },
    {
      path: ':id/edit',
      name: 'posts_edit',
      component: import('@blog/js/admin/components/PostForm'),
      props: true,
      meta: {
        label: 'Edit Post'
      }
    }
  ]
}
