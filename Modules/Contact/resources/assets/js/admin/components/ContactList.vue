<template>
  <div>
    <b-card>
      <template slot="header">
        <h3 class="card-title">Contact Index</h3>
      </template>
      <b-datatable ref="datasource"
                   @context-changed="onContextChanged"
                   search-route="admin.contact.search"
                   delete-route="admin.contact.destroy"
                   action-route="admin.contact.batch_action" :actions="actions"
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
                 sort-by="contacts.created_at"
                 :sort-desc="true"
                 :busy.sync="isBusy"
        >
          <template slot="HEAD_checkbox" slot-scope="data"></template>
          <template slot="checkbox" slot-scope="row">
            <b-form-checkbox :value="row.item.id" v-model="selected"></b-form-checkbox>
          </template>
          <template slot="title" slot-scope="row">
            <span v-text="row.value"></span>
          </template>
          <template slot="subject" slot-scope="row">
            <span v-text="row.value"></span>
          </template>
          <template slot="email" slot-scope="row">
            <span v-text="row.value"></span>
          </template>
          <template slot="contacts.created_at" slot-scope="row">
            {{ row.item.created_at }}
          </template>
          <template slot="contacts.updated_at" slot-scope="row">
            {{ row.item.updated_at }}
          </template>
          <template slot="actions" slot-scope="row">
            <b-button size="sm" variant="success" v-b-modal="'contentModel' + row.item.id" target="_blank" v-b-tooltip.hover :title="$t('buttons.preview')" class="mr-1">
              <i class="fe fe-eye"></i>
            </b-button>

            <b-modal :id="'contentModel'+ row.item.id" title="Contents">
              <p class="my-4">{{ row.item.content }}!</p>
            </b-modal>

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
  name: 'ContactList',
  data () {
    return {
      isBusy: false,
      selected: [],
      fields: [
        { key: 'checkbox' },
        { key: 'sender_name', label: 'Sender Name', sortable: true },
        { key: 'email', label: 'Email Address', sortable: true },
        { key: 'subject', label: 'Subject', sortable: true },
        { key: 'content', label: 'Content', sortable: false },
        { key: 'contacts.created_at', label: this.$t('labels.created_at'), 'class': 'text-center', sortable: true },
        { key: 'contacts.updated_at', label: this.$t('labels.updated_at'), 'class': 'text-center', sortable: true },
        { key: 'actions', label: this.$t('labels.actions'), 'class': 'nowrap' }
      ],
      actions: {
        delete: 'Delete'
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
      this.$refs.datasource.deleteRow({ contact: id })
    },
    onBulkActionSuccess () {
      this.selected = []
    }
  }
}
</script>
