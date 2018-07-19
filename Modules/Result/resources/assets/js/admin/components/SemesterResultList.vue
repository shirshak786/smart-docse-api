<template>
  <div>
    <b-card>
      <template slot="header">
        <h3 class="card-title">List of Semester Results</h3>
        <div class="card-options" v-if="this.$app.user.can('create results')">
          <b-button to="/results/create" variant="success" size="sm">
            <i class="fe fe-plus-circle"></i> Create Results
          </b-button>
        </div>
      </template>
      <b-datatable ref="datasource"
                   @context-changed="onContextChanged"
                   search-route="admin.result.semester.search"
                   delete-route="admin.result.semester.destroy"
                   action-route="admin.result.semester.batch_action" :actions="actions"
                   @bulk-action-success="onBulkActionSuccess">
        <b-table ref="datatable"
                 striped
                 bordered
                 show-empty
                 stacked="md"
                 no-local-sorting
                 :empty-text="$t('labels.datatables.no_results')"
                 :empty-filtered-text="$t('labels.datatables.no_matched_results')"
                 :fields="fields"
                 :items="dataLoadProvider"
                 sort-by="semester_results.created_at"
                 :sort-desc="true"
                 :busy.sync="isBusy"
        >
          <template slot="HEAD_checkbox" slot-scope="data"></template>
          <template slot="checkbox" slot-scope="row">
            <b-form-checkbox :value="row.item.id" v-model="selected"></b-form-checkbox>
          </template>
          <template slot="subject" slot-scope="row">
            <router-link v-if="row.item.can_edit" :to="`/results/${row.item.id}/edit`" v-text="row.value"></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="semester" slot-scope="row">
            <b-badge size="lg" variant="success">{{ $t(row.item.semester) }}</b-badge>
          </template>
          <template slot="faculty" slot-scope="row">
            <span>{{ row.item.faculty }}</span>
          </template>
          <template slot="semester_result.created_at" slot-scope="row">
            {{ row.item.created_at }}
          </template>
          <template slot="semester_result.updated_at" slot-scope="row">
            {{ row.item.updated_at }}
          </template>
          <template slot="status" slot-scope="row">
            <b-badge :variant="row.item.status_label_color">{{ row.item.status_label }}</b-badge>
          </template>
          <template slot="actions" slot-scope="row">
            <b-button size="sm" variant="success" :href="$app.route('admin.result.semester.show', { result: row.item.id})" target="_blank" v-b-tooltip.hover :title="$t('buttons.preview')" class="mr-1">
              <i class="fe fe-eye"></i>
            </b-button>
            <b-button v-if="row.item.can_edit" size="sm" variant="primary" :to="`/results/${row.item.id}/edit`" v-b-tooltip.hover :title="$t('buttons.edit')" class="mr-1">
              <i class="fe fe-edit"></i>
            </b-button>
            <b-button v-if="row.item.can_delete" size="sm" variant="danger" v-b-tooltip.hover :title="$t('buttons.delete')" @click.stop="onDelete(row.item.id)">
              <i class="fe fe-trash"></i>
            </b-button>
          </template>
        </b-table>
      </b-datatable>
    </b-card>
  </div>
</template>

<script>
export default {
  name: 'SemesterResultList',
  data () {
    return {
      isBusy: false,
      selected: [],
      fields: [
        { key: 'checkbox' },
        { key: 'subject', label: 'Subject', sortable: true },
        { key: 'semester', label: 'Semester', sortable: true },
        { key: 'semester_result.created_at', label: 'Created At', 'class': 'text-center', sortable: true },
        { key: 'semester_result.updated_at', label: 'Updated At', 'class': 'text-center', sortable: true },
        { key: 'status', label: 'Status', 'class': 'text-center' },
        { key: 'actions', label: 'Actions', 'class': 'nowrap' }
      ],
      actions: {
        destroy: 'Delete'
      }
    }
  },
  watch: {
    selected (value) {
      this.$refs.datasource.selected = value
    }
  },
  methods: {
    dataLoadProvider (ctx) {
      return this.$refs.datasource.loadData(ctx.sortBy, ctx.sortDesc)
    },
    onContextChanged () {
      return this.$refs.datatable.refresh()
    },
    onDelete (id) {
      try {
        this.$refs.datasource.deleteRow({ result: id })
      } catch (e) {
        throw e
      }
    },
    onBulkActionSuccess () {
      this.selected = []
    }
  }
}
</script>
