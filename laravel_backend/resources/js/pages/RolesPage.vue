<template>
  <div>
    <div class="container">
      <b-row class="page-title-container">
        <b-col>
          <h1 class="page-title">Roles</h1>
        </b-col>
        <!-- There's nothing to link to anyway so let's just hardcode this for now -->
        <b-col> <p class="text-end">User Management > Roles</p> </b-col>
      </b-row>

      <div>
        <b-table striped hover :items="roles">
          <template #cell(name)="data">
            <b class="text-info" @click="edit(data.item)">{{ data.item.name }}</b>
          </template>

          <template #cell(created_at)="data">
            {{ displayReadableTS(data) }}
          </template>
        </b-table>

        <b-button @click="create">Add Role</b-button>
      </div>
    </div>
    <b-modal
      id="modal-role-form"
      ref="modal"
      v-bind:title="form.title"
      v-bind:ok-title="form.action"
    >
      <template #modal-footer="{ cancel }">
        <b-button size="sm" @click="cancel()"> Cancel </b-button>
        <b-button size="sm" variant="success" @click="handleAction()">
          {{ form.action }}
        </b-button>
        <b-button v-if="form.name" left size="sm" variant="danger" @click="remove">
          Delete
        </b-button>
      </template>
      <b-container>
        <b-row>
          <b-col> Display Name </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-role-name"
              v-bind:description="form.errors.name[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-role-name"
                v-model="form.name"
                placeholder="Auditor/User/etc"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Description </b-col>
          <b-col sm="8">
            <b-form-input
              v-model="form.description"
              placeholder="What the role will be used for"
            ></b-form-input>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<style>
/* Work-around for that weird modal overlay with excessively high z-index */
#modal-role-form {
  z-index: 1060;
}
</style>

<script>
import moment from "moment";
const baseForm = {
  name: "",
  description: "",
  title: "Add Role",
  action: "Save",
  errors: {
    name: [],
    description: [],
  },
};

export default {
  data() {
    return {
      roles: [],
      currentRole: baseForm,
      form: baseForm,
    };
  },
  async mounted() {
    await this.refreshRoles();
  },
  methods: {
    async refreshRoles() {
      this.roles = (await axios.get("/api/roles")).data;
    },
    displayReadableTS(data) {
      const ts = data.item.created_at;
      return moment(ts).format("YYYY-MM-DD");
    },
    create() {
      this.form = baseForm;
      this.$bvModal.show("modal-role-form");
    },
    edit(role) {
      this.currentRole = role;
      this.form = {
        ...role,
        title: "Update Role",
        action: "Update",
        errors: {
          name: [],
          description: [],
        },
      };

      this.$bvModal.show("modal-role-form");
    },
    async store() {
      await this.execModificationFn(async () => {
        await axios.post("/api/roles", this.form);
      });
    },
    async update() {
      await this.execModificationFn(async () => {
        await axios.put(`/api/roles/${this.currentRole.name}`, this.form);
      });
    },
    async remove() {
      await this.execModificationFn(async () => {
        await axios.delete(`/api/roles/${this.form.name}`);
      });
    },
    async execModificationFn(fn) {
      try {
        await fn();
        await this.refreshRoles();
        this.$bvModal.hide("modal-role-form");
      } catch (e) {
        if (e.response) {
          if (e.response.data && e.response.data.errors) {
            this.form.errors = {
              ...baseForm.errors,
              ...e.response.data.errors,
            };
          } else if (e.response.data) {
            this.form.errors.name = [e.response.data];
          }
        }
      }
    },
    async handleAction(event) {
      event && event.preventDefault();

      if (this.form.action === "Save") {
        await this.store();
      } else if (this.form.action == "Update") {
        await this.update();
      }
    },
  },
};
</script>
