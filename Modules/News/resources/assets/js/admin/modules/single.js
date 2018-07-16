import axios from 'axios'
import _ from 'lodash'

function initialState () {
  return {
    item: {
      id: null,
      title: null,
      description: null,
      type: null,
      audiance: true,
      date_of_publication: null,
      attachment: null,
      cover_image: null,
      images: [],
      uploaded_images: [],
      password: null,
      cost: null,
      things_to_select: null,
      author: null,
      writers: [],
      lekhak: null
    },
    usersAll: [],
    things_to_selectEnum: [ { value: 'apple', label: 'Apple' }, { value: 'ball', label: 'Ball' }, { value: 'cat', label: 'Cat' }, { value: 'dog', label: 'Dog' } ],
    loading: false
  }
}

const getters = {
  item: state => state.item,
  loading: state => state.loading,
  usersAll: state => state.usersAll,
  things_to_selectEnum: state => state.things_to_selectEnum
}

const actions = {
  storeData ({ commit, state, dispatch }) {
    commit('setLoading', true)
    dispatch('Alert/resetState', null, { root: true })

    return new Promise((resolve, reject) => {
      let params = new FormData()

      for (let fieldName in state.item) {
        let fieldValue = state.item[fieldName]
        if (typeof fieldValue !== 'object') {
          params.set(fieldName, fieldValue)
        } else {
          if (fieldValue && typeof fieldValue[0] !== 'object') {
            params.set(fieldName, fieldValue)
          } else {
            for (let index in fieldValue) {
              params.set(fieldName + '[' + index + ']', fieldValue[index])
            }
          }
        }
      }

      params.set('audiance', state.item.audiance ? 1 : 0)
      if (state.item.attachment === null) {
        params.delete('attachment')
      }
      if (state.item.cover_image === null) {
        params.delete('cover_image')
      }
      params.set('uploaded_images', state.item.uploaded_images.map(o => o['id']))
      if (!_.isEmpty(state.item.things_to_select) && typeof state.item.things_to_select === 'object') {
        params.set('things_to_select', state.item.things_to_select.value)
      }
      if (_.isEmpty(state.item.author)) {
        params.set('author_id', '')
      } else {
        params.set('author_id', state.item.author.id)
      }
      if (_.isEmpty(state.item.writers)) {
        params.delete('writers')
      } else {
        for (let index in state.item.writers) {
          params.set('writers[' + index + ']', state.item.writers[index].id)
        }
      }
      if (_.isEmpty(state.item.lekhak)) {
        params.set('lekhak_id', '')
      } else {
        params.set('lekhak_id', state.item.lekhak.id)
      }

      axios.post('/api/v1/news', params)
        .then(response => {
          commit('resetState')
          resolve()
        })
        .catch(error => {
          let message = error.response.data.message || error.message
          let errors = error.response.data.errors

          dispatch(
            'Alert/setAlert',
            { message: message, errors: errors, color: 'danger' },
            { root: true })

          reject(error)
        })
        .finally(() => {
          commit('setLoading', false)
        })
    })
  },
  updateData ({ commit, state, dispatch }) {
    commit('setLoading', true)
    dispatch('Alert/resetState', null, { root: true })

    return new Promise((resolve, reject) => {
      let params = new FormData()
      params.set('_method', 'PUT')

      for (let fieldName in state.item) {
        let fieldValue = state.item[fieldName]
        if (typeof fieldValue !== 'object') {
          params.set(fieldName, fieldValue)
        } else {
          if (fieldValue && typeof fieldValue[0] !== 'object') {
            params.set(fieldName, fieldValue)
          } else {
            for (let index in fieldValue) {
              params.set(fieldName + '[' + index + ']', fieldValue[index])
            }
          }
        }
      }

      params.set('audiance', state.item.audiance ? 1 : 0)
      if (state.item.attachment === null) {
        params.delete('attachment')
      }
      if (state.item.cover_image === null) {
        params.delete('cover_image')
      }
      params.set('uploaded_images', state.item.uploaded_images.map(o => o['id']))
      if (!_.isEmpty(state.item.things_to_select) && typeof state.item.things_to_select === 'object') {
        params.set('things_to_select', state.item.things_to_select.value)
      }
      if (_.isEmpty(state.item.author)) {
        params.set('author_id', '')
      } else {
        params.set('author_id', state.item.author.id)
      }
      if (_.isEmpty(state.item.writers)) {
        params.delete('writers')
      } else {
        for (let index in state.item.writers) {
          params.set('writers[' + index + ']', state.item.writers[index].id)
        }
      }
      if (_.isEmpty(state.item.lekhak)) {
        params.set('lekhak_id', '')
      } else {
        params.set('lekhak_id', state.item.lekhak.id)
      }

      axios.post('/api/v1/news/' + state.item.id, params)
        .then(response => {
          commit('setItem', response.data.data)
          resolve()
        })
        .catch(error => {
          let message = error.response.data.message || error.message
          let errors = error.response.data.errors

          dispatch(
            'Alert/setAlert',
            { message: message, errors: errors, color: 'danger' },
            { root: true })

          reject(error)
        })
        .finally(() => {
          commit('setLoading', false)
        })
    })
  },
  fetchData ({ commit, dispatch }, id) {
    axios.get('/api/v1/news/' + id)
      .then(response => {
        commit('setItem', response.data.data)
      })

    dispatch('fetchUsersAll')
    dispatch('fetchUsersAll')
    dispatch('fetchUsersAll')
  },
  fetchUsersAll ({ commit }) {
    axios.get('/api/v1/users')
      .then(response => {
        commit('setUsersAll', response.data.data)
      })
  },
  setTitle ({ commit }, value) {
    commit('setTitle', value)
  },
  setAuthor_email ({ commit }, value) {
    commit('setAuthor_email', value)
  },
  setDescription ({ commit }, value) {
    commit('setDescription', value)
  },
  setType ({ commit }, value) {
    commit('setType', value)
  },
  setAudiance ({ commit }, value) {
    commit('setAudiance', value)
  },
  setDate_of_publication ({ commit }, value) {
    commit('setDate_of_publication', value)
  },
  setAttachment ({ commit }, value) {
    commit('setAttachment', value)
  },

  setCover_image ({ commit }, value) {
    commit('setCover_image', value)
  },

  setImages ({ commit }, value) {
    commit('setImages', value)
  },
  destroyImages ({ commit }, value) {
    commit('destroyImages', value)
  },
  destroyUploadedImages ({ commit }, value) {
    commit('destroyUploadedImages', value)
  },
  setPassword ({ commit }, value) {
    commit('setPassword', value)
  },
  setCost ({ commit }, value) {
    commit('setCost', value)
  },
  setThings_to_select ({ commit }, value) {
    commit('setThings_to_select', value)
  },
  setAuthor ({ commit }, value) {
    commit('setAuthor', value)
  },
  setWriters ({ commit }, value) {
    commit('setWriters', value)
  },
  setLekhak ({ commit }, value) {
    commit('setLekhak', value)
  },
  resetState ({ commit }) {
    commit('resetState')
  }
}

const mutations = {
  setItem (state, item) {
    state.item = item
  },
  setTitle (state, value) {
    state.item.title = value
  },
  setAuthor_email (state, value) {
    state.item.author_email = value
  },
  setDescription (state, value) {
    state.item.description = value
  },
  setType (state, value) {
    state.item.type = value
  },
  setAudiance (state, value) {
    state.item.audiance = value
  },
  setDate_of_publication (state, value) {
    state.item.date_of_publication = value
  },
  setAttachment (state, value) {
    state.item.attachment = value
  },
  setCover_image (state, value) {
    state.item.cover_image = value
  },
  setImages (state, value) {
    for (let i in value) {
      let images = value[i]
      if (typeof images === 'object') {
        state.item.images.push(images)
      }
    }
  },
  destroyImages (state, value) {
    for (let i in state.item.images) {
      if (i === value) {
        state.item.images.splice(i, 1)
      }
    }
  },
  destroyUploadedImages (state, value) {
    for (let i in state.item.uploaded_images) {
      let data = state.item.uploaded_images[i]
      if (data.id === value) {
        state.item.uploaded_images.splice(i, 1)
      }
    }
  },
  setPassword (state, value) {
    state.item.password = value
  },
  setCost (state, value) {
    state.item.cost = value
  },
  setThings_to_select (state, value) {
    state.item.things_to_select = value
  },
  setAuthor (state, value) {
    state.item.author = value
  },
  setWriters (state, value) {
    state.item.writers = value
  },
  setLekhak (state, value) {
    state.item.lekhak = value
  },
  setUsersAll (state, value) {
    state.usersAll = value
  },
  setThings_to_selectEnum (state, value) {
    state.things_to_selectEnum = value
  },
  setLoading (state, loading) {
    state.loading = loading
  },
  resetState (state) {
    state = Object.assign(state, initialState())
  }
}

export default {
  namespaced: true,
  state: initialState,
  getters,
  actions,
  mutations
}
