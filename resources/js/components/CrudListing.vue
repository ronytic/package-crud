<template>
  <div class="data-table">
    <vuetable
      :data-manager="dataManager"
      :sort-order="sortOrder"
      :css="css"
      bordered="true"
      :api-mode="false"
      :fields="fields"
      :data="data"
      data-path="data"
      pagination-path="meta"
      @vuetable:pagination-data="onPaginationData"
    >
      <template
        slot="actions"
        slot-scope="props"
      >
        <div class="actions">
          <div class="popout">
            <b-btn
              v-b-tooltip.hover
              variant=""
              data-action="Edit"
              data-toggle="modal"
              data-target="#sampleModal"
              title="Edit"
              @click="onAction('edit-item', props.rowData, props.rowIndex)"
            >
              <i class="fas fa-edit" />
            </b-btn>
            <b-btn
              v-b-tooltip.hover
              variant="danger"
              title="Remove"
              @click="onAction('remove-item', props.rowData, props.rowIndex)"
            >
              <i class="fas fa-trash-alt" />
            </b-btn>
          </div>
        </div>
      </template>
    </vuetable>
    <pagination
      ref="pagination"
      single="Sample"
      plural="Samples"
      :per-page-select-enabled="true"
      @changePerPage="changePerPage"
      @vuetable-pagination:change-page="onPageChange"
    />
  </div>
</template>

<script>
import datatableMixin from './common/mixins/datatable';

export default {
  mixins: [datatableMixin],
  props: ['filter'],

  data() {
    return {
      orderBy: 'name',
      // Our listing of samples
      sortOrder: [
        {
          field: 'name',
          sortField: 'name',
          direction: 'asc',
        },
      ],
      fields: [
        {
          title: 'Name',
          name: 'name',
          sortField: 'name',
        },
        {
          title: 'Description',
          name: 'description',
          sortField: 'description',
        },
        {
          title: 'Code',
          name: 'code',
          sortField: 'code',
        },
        {
          title: 'Status',
          name: 'status',
          sortField: 'status',
          callback: 'formatStatus',
        },
        {
          title: 'Created at',
          name: 'created_at',
          sortField: 'created_at',
        },
        {
          name: '__slot:actions',
          title: '',
        },
      ],
    };
  },
  methods: {
    formatStatus(status) {
      const stat = status.toLowerCase();
      const bubbleColor = {
        active: 'text-success',
        inactive: 'text-danger',
        draft: 'text-warning',
        archived: 'text-info',
      };
      return `<i class="fas fa-circle ${bubbleColor[stat]} small"></i> ${stat
        .charAt(0)
        .toUpperCase()}${stat.slice(1)}`;
    },
    onAction(action, data) {
      switch (action) {
        case 'edit-item':
          this.$parent.edit(data);
          break;
        case 'remove-item':
          ProcessMaker.confirmModal(
            'Caution!',
            `Are you sure to delete <b>'${data.name}'</b>?`,
            '',
            () => {
              ProcessMaker.apiClient.delete(`package-crud/crud/${data.uuid}`).then(() => {
                ProcessMaker.alert(`${data.name} has been deleted`, 'warning');
                this.$emit('reload');
              });
            },
          );
          break;
        default:
          break;
      }
    },
    fetch() {
      this.loading = true;
      // change method sort by sample
      this.orderBy = this.orderBy === 'name' ? 'name' : this.orderBy;
      // Load from our api client
      ProcessMaker.apiClient
        .get(
          `package-crud/crud?page=${this.page}&per_page=${this.perPage}&filter=${this.filter}&order_by=${this.orderBy}&order_direction=${this.orderDirection}`,
        )
        .then((response) => {
          this.data = this.transform(response.data);
          this.loading = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped></style>
