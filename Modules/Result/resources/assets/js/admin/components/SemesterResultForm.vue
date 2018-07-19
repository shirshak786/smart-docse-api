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
                v-model="model.semester"
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

      <b-card>
        <div class="card-header">
          <h3 class="card-title">Results List</h3>
        </div>

        <div class="table-responsive ">
          <b-button-group class="mt-4 mb-4">
            <b-button variant="success" @click="add_result_item(1)">Add For 1 Student</b-button>
            <b-button variant="danger" @click="add_result_item(10)">Add For 10 Student</b-button>
            <b-button variant="warning" @click="add_result_item(50)">Add For 50 Student</b-button>
            <b-button variant="danger" @click="add_result_item(50)">Add For 100 Student</b-button>
          </b-button-group>

          <table class="table">
            <thead>
              <tr>
                <th>Index</th>
                <th>Name</th>
                <th>Roll</th>
                <th>Marks</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(data,index) in model.result_data" :key="index">
                <td>
                  {{ index + 1 }}
                </td>
                <td>
                  <b-form-input
                    :id="'result_data.'+index + '.name'"
                    :name="'result_data.'+index + '.name'"
                    required
                    placeholder="Enter Name"
                    v-model="data.name"
                    :state="state('result_data.'+index+'.name')"
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input
                    :id="'result_data.'+index + '.roll'"
                    :name="'result_data.'+index + '.roll'"
                    required
                    placeholder="Enter Roll Number"
                    v-model="data.roll"
                    :state="state('result_data.'+index+'.roll')"
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input
                    :id="'result_data.'+index + '.marks'"
                    :name="'result_data.'+index + '.marks'"
                    required
                    placeholder="Enter Marks"
                    v-model="data.marks"
                    :state="state('result_data.'+index+'.marks')"
                  ></b-form-input>
                </td>
                <td>
                  <b-button size="sm" variant="danger" v-b-tooltip.hover title="Remove this" @click.stop="onDeleteResultItem(index)">
                    <i class="fe fe-trash"></i>
                  </b-button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </b-card>
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
      modelName: 'result',
      resourceRoute: 'result.semester',
      listPath: '/results',
      model: {
        subject: null,
        semester: null,
        faculty: null,
        result_data: [],
        status: null
      }
    }
  },
  methods: {
    add_result_item (no) {
      for (let i = 0; i < no; i++) {
        this.model.result_data.push({
          name: null,
          marks: null,
          roll: null
        })
      }
    },
    onDeleteResultItem (index) {
      this.model.result_data.splice(index, 1)
    }
  }
}
</script>
