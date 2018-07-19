<template>
  <div>
    <form @submit.prevent="onSubmit">
      <b-row>
        <b-col xl="8">
          <b-card>
            <h3 class="card-title" slot="header">{{ isNew ? 'Create Results' : 'Edit Results' }}</h3>

            <b-form-group
              label="Subject"
              label-for="subject"
              horizontal
              :label-cols="2"
              :feedback="feedback('subject')"
            >
              <b-form-input
                id="subject"
                name="subject"
                required
                placeholder="Please enter subject for this news"
                v-model="model.subject"
                :state="state('subject')"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              label="Faculty"
              label-for="faculty"
              horizontal
              :label-cols="2"
              :feedback="feedback('faculty')"
            >
              <b-form-input
                id="faculty"
                name="faculty"
                required
                placeholder="Please enter faculty for this result eg. Computer Engineering"
                v-model="model.faculty"
                :state="state('faculty')"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              label="Semester"
              label-for="semester"
              horizontal
              :label-cols="2"
            >
              <b-select
                id="type"
                name="type"
                v-model="model.type"
                placeholder= "Select the type of news"
                :options="options.semesters"
                :multiple="false"
              >
              </b-select>
            </b-form-group>

            <b-form-group
              label="Status"
              label-for="status"
              horizontal
              :label-cols="2"
            >
              <b-select
                id="status"
                name="status"
                v-model="model.status"
                placeholder= "Select the type of news"
                :options="options.status"
                :multiple="false"
              >
              </b-select>
            </b-form-group>
          </b-card>
        </b-col>
        <b-col xl="4">
          <b-card>
            <h3 class="card-title" slot="header">Save</h3>
            <b-row slot="footer">
              <b-col md>
                <b-button type="submit" variant="success" size="sm" class="float-right"
                          :disabled="pending"
                          v-if="isNew || this.$app.user.can('edit results')">
                  {{ isNew ? 'Create' : 'Update' }}
                </b-button>
              </b-col>
              <b-col md>
                <b-button to="/results" exact variant="danger" size="sm">
                  Back
                </b-button>
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
  name: 'SemesterResultForm',
  mixins: [form],
  data () {
    return {
      options: {
        semesters: {
          1: 'First Semester',
          2: 'Second Semester',
          3: 'Third Semester',
          4: 'Fourth Semester',
          5: 'Fifth Semester',
          6: 'Sixth Semester',
          7: 'Seventh Semester',
          8: 'Eight Semester'
        },
        status: {
          0: 'Pending',
          1: 'Waiting',
          2: 'Published'
        }
      },
      modelName: 'news',
      resourceRoute: 'result.semester',
      listPath: '/results',
      model: {
        subject: null,
        semester: null,
        faculty: null,
        result_data: null,
        status: null
      }
    }
  },
  methods: {
  }
}
</script>
