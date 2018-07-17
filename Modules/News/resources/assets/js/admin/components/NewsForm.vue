<template>
  <div>
    <form @submit.prevent="onSubmit">
      <b-row>
        <b-col xl="10">
          <b-card>
            <h3 class="card-title" slot="header">{{ isNew ? 'Create News' : 'Edit News' }}</h3>
            <b-row slot="footer">
              <b-col md>
                <b-button to="/news" exact variant="danger" size="sm">
                  Back
                </b-button>
              </b-col>
              <b-col md>

              </b-col>
            </b-row>
          </b-card>
        </b-col>
      </b-row>
    </form>
  </div>
</template>

<script>
import form from '@core/js/admin/mixins/form'

export default {
  name: 'NewsForm',
  mixins: [form],
  data () {
    return {
      modelName: 'news',
      resourceRoute: 'news',
      listPath: '/news',
      tags: [],
      model: {
      }
    }
  },
  methods: {
  }
}
</script>
