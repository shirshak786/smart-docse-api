export default {
  path: 'contacts',
  component: {
    render (c) {
      return c('router-view')
    }
  },
  meta: {
    label: 'Contact'
  },
  children: [
    {
      path: '/',
      name: 'contacts',
      component: require('@contact/js/admin/components/ContactList').default,
      meta: {
        label: 'Contact List'
      }
    }
  ]
}
