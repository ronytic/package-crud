import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import VModal from 'vue-js-modal';
import CrudListing from './components/CrudListing.vue';

Vue.use(VModal);
Vue.use(BootstrapVue);

new Vue({
  el: '#app-package-crud',
  components: { CrudListing },
  data: {
    filter: '',
    show: true,
    crud: {
      id: '',
      name: '',
      description: '',
      code: '',
      status: 'ENABLED',
    },
    addError: {
      name: null,
      status: null,
    },
    action: 'Add',
  },
  methods: {
    reload() {
      this.$refs.listing.dataManager([{
        field: 'updated_at',
        direction: 'desc',
      }]);
    },
    edit(data) {
      this.crud.uuid = data.uuid;
      this.crud.name = data.name;
      this.crud.description = data.description;
      this.crud.code = data.code;
      this.crud.status = data.status;
      this.crud.id = data.id;
      this.action = 'Edit';
      this.$refs.modal.show();
    },
    validateForm() {
      if (this.crud.name === '' || this.crud.name === null) {
        this.submitted = false;
        this.addError.name = ['The name field is required'];
        return false;
      }
      return true;
    },
    onSubmit(evt) {
      evt.preventDefault();
      this.submitted = true;
      if (this.validateForm()) {
        this.addError.name = null;
        if (this.action === 'Add') {
          ProcessMaker.apiClient.post('package-crud/crud', {
            name: this.crud.name,
            description: this.crud.description,
            code: this.crud.code,
            status: this.crud.status,
          })
            .then(() => {
              this.reload();
              ProcessMaker.alert('Record successfully added ', 'success');
              this.crud.name = '';
              this.crud.description = '';
              this.crud.code = '';
              this.crud.status = 'active';
            })
            .catch((error) => {
              if (error.response.status === 422) {
                this.addError = error.response.data.errors;
              }
            })
            .finally(() => {
              this.submitted = false;
              this.$refs.modal.hide();
            });
        } else {
          ProcessMaker.apiClient.put(`package-crud/crud/${this.crud.uuid}`, {
            name: this.crud.name,
            description: this.crud.description,
            code: this.crud.code,
            status: this.crud.status,
          })
            .then(() => {
              this.reload();
              ProcessMaker.alert('Record successfully updated ', 'success');
              this.crud.name = '';
              this.crud.description = '';
              this.crud.code = '';
              this.crud.status = 'active';
            })
            .catch((error) => {
              if (error.response.status === 422) {
                this.addError = error.response.data.errors;
              }
            })
            .finally(() => {
              this.submitted = false;
              this.$refs.modal.hide();
              this.action = 'create';
            });
        }
      }
    },
    clearForm() {
      this.action = 'Add';
      this.id = '';
      this.addError.name = null;
      this.crud.name = '';
      this.crud.code = '';
      this.crud.description = '';
    },
  },
});
