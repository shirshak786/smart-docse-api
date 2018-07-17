<template>
  <div>
    <form @submit.prevent="onSubmit">
      <b-row>
        <b-col xl="10">
          <b-card>
            <h3 class="card-title" slot="header">{{ isNew ? 'Create News' : 'Edit News' }}</h3>

            <b-form-group
              label="Title"
              label-for="title"
              horizontal
              :label-cols="2"
              :feedback="feedback('title')"
            >
              <b-form-input
                id="title"
                name="title"
                required
                placeholder="Please enter title for this news"
                v-model="model.title"
                :state="state('title')"
              ></b-form-input>
            </b-form-group>

            <b-form-group
              label="Content"
              label-for="body"
              required
              horizontal
              :label-cols="2"
            >
              <p-richtexteditor
                name="content"
                v-model="model.content"
              ></p-richtexteditor>
            </b-form-group>

            <b-form-group
              label="Types"
              label-for="types"
              horizontal
              :label-cols="2"
            >
              <b-select
                id="type"
                name="type"
                v-model="model.type"
                placeholder= "Select the type of news"
                :options="options.types"
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

            <b-form-group
              label="Cover Image"
              label-for="cover_image"
              horizontal
              :label-cols="2"
              :feedback="feedback('cover_image')"
            >
              <div class="media">
                <img class="mr-2" v-if="showData.cover_image_link" :src="showData.cover_image_link" alt="">

                <div class="media-body">
                  <h6>{{ $t('labels.upload_image') }}</h6>
                  <b-form-file
                    id="cover_image"
                    name="cover_image"
                    ref="coverImageInput"
                    placeholder="Choose Image"
                    v-model="model.cover_image"
                    :state="state('cover_image')"
                    v-b-tooltip.hover
                    title="Cover Image (PNG, JPEG, GIF)"
                  ></b-form-file>
                  <b-button variant="danger" class="d-block mt-1" v-if="model.cover_image" @click.prevent="deleteCoverImage">Delete This Image</b-button>
                </div>
              </div>
            </b-form-group>

            <b-form-group
              label="Published At"
              label-for="published_date"
              horizontal
              :label-cols="2"
            >
              <b-input-group>
                <p-datetimepicker
                  id="published_date"
                  name="published_date"
                  v-model="model.published_date"
                ></p-datetimepicker>
                <b-input-group-append>
                  <b-input-group-text data-toggle>
                    <i class="fe fe-calendar"></i>
                  </b-input-group-text>
                  <b-input-group-text data-clear>
                    <i class="fe fe-x-circle"></i>
                  </b-input-group-text>
                </b-input-group-append>
              </b-input-group>
            </b-form-group>

            <b-row slot="footer">
              <b-col md>
                <b-button to="/news" exact variant="danger" size="sm">
                  Back
                </b-button>
              </b-col>
              <b-col md>
                <b-button type="submit" variant="success" size="sm" class="float-right"
                          :disabled="pending"
                          v-if="isNew || this.$app.user.can('edit news')">
                  {{ isNew ? 'Create' : 'Update' }}
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
  name: 'NewsForm',
  mixins: [form],
  data () {
    return {
      options: {
        types: {
          0: 'KU Related',
          1: 'DOCSE Related',
          2: 'DOCSE First Semester',
          3: 'DOCSE Second Semester',
          4: 'DOCSE Third Semester',
          5: 'DOCSE Fourth Semester',
          6: 'DOCSE Fifth Semester',
          7: 'DOCSE Sixth Semester',
          8: 'DOCSE Seventh Semester',
          9: 'DOCSE Eight Semester',
          10: 'KUCC'
        },
        status: {
          0: 'Pending',
          1: 'Waiting',
          2: 'Published'
        }
      },
      modelName: 'news',
      resourceRoute: 'news',
      listPath: '/news',
      model: {
        title: null,
        content: null,
        status: null,
        type: null,
        cover_image: null,
        published_date: null
      }
    }
  },
  methods: {
    deleteCoverImage () {
      this.$refs.coverImageInput.reset()
      this.model.cover_image = null
    }
  }
}
</script>
