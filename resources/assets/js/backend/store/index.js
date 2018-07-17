import Vue from 'vue'
import Vuex from 'vuex'

import NewsIndex from '@news/js/admin/modules/index'
import NewsSingle from '@news/js/admin/modules/single'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    NewsIndex,
    NewsSingle
  },
  strict: debug
})
