<template>
  <div>
    <b-card>
      <template slot="header">
        <h3 class="card-title">List of News</h3>
        <div class="card-options" v-if="this.$app.user.can('create news')">
          <b-button to="/news/create" variant="success" size="sm">
            <i class="fe fe-plus-circle"></i> Create News
          </b-button>
        </div>
      </template>
      <b-datatable ref="datasource"
                   @context-changed="onContextChanged"
                   search-route="admin.news.search"
                   delete-route="admin.news.destroy"
                   action-route="admin.news.batch_action" :actions="actions"
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
                 sort-by="news.created_at"
                 :sort-desc="true"
                 :busy.sync="isBusy"
        >
          <template slot="HEAD_checkbox" slot-scope="data"></template>
          <template slot="checkbox" slot-scope="row">
            <b-form-checkbox :value="row.item.id" v-model="selected"></b-form-checkbox>
          </template>
          <template slot="image" slot-scope="row">
            <router-link v-if="row.item.can_edit" :to="`/news/${row.item.slug}/edit`">
              <img :src="row.item.cover_image_thumbnail_url" :alt="row.item.title">
            </router-link>
            <img v-else :src="row.item.cover_image_thumbnail_url" :alt="row.item.title">
          </template>
          <template slot="title" slot-scope="row">
            <router-link v-if="row.item.can_edit" :to="`/news/${row.item.slug}/edit`" v-text="row.value"></router-link>
            <span v-else v-text="row.value"></span>
          </template>
          <template slot="status" slot-scope="row">
            <b-badge :variant="row.item.state">{{ $t(row.item.status_value) }}</b-badge>
          </template>
          <template slot="author" slot-scope="row">
            <span v-if="row.item.author">{{ row.item.author.name }}</span>
            <span v-else>{{ $t('labels.anonymous') }}</span>
          </template>
          <template slot="news.created_at" slot-scope="row">
            {{ row.item.created_at }}
          </template>
          <template slot="news.updated_at" slot-scope="row">
            {{ row.item.updated_at }}
          </template>
          <template slot="actions" slot-scope="row">
            <b-button size="sm" variant="success" :href="$app.route('admin.news.show', { news: row.item.slug})" target="_blank" v-b-tooltip.hover :title="$t('buttons.preview')" class="mr-1">
              <i class="fe fe-eye"></i>
            </b-button>
            <b-button v-if="row.item.can_edit" size="sm" variant="primary" :to="`/news/${row.item.slug}/edit`" v-b-tooltip.hover :title="$t('buttons.edit')" class="mr-1">
              <i class="fe fe-edit"></i>
            </b-button>
            <b-button v-if="row.item.can_delete" size="sm" variant="danger" v-b-tooltip.hover :title="$t('buttons.delete')" @click.stop="onDelete(row.item.slug)">
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
  name: 'PostList',
  data () {
    return {
      isBusy: false,
      selected: [],
      fields: [
        { key: 'checkbox' },
        { key: 'image', label: 'Cover Image' },
        { key: 'title', label: 'Title', sortable: true },
        { key: 'status', label: 'Status', 'class': 'text-center' },
        { key: 'author', label: 'Author', sortable: true },
        { key: 'news.created_at', label: 'Created At', 'class': 'text-center', sortable: true },
        { key: 'news.updated_at', label: 'Updated At', 'class': 'text-center', sortable: true },
        { key: 'actions', label: 'Actions', 'class': 'nowrap' }
      ],
      actions: {
        destroy: 'Delete',
        publish: 'Publish',
        waiting: 'Waiting',
        pending: 'Pending'
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
    onDelete (slug) {
      this.$refs.datasource.deleteRow({ news: slug })
    },
    onBulkActionSuccess () {
      this.selected = []
    }
  }
}
</script>
