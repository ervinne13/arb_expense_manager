<template>
  <div>
    <div class="container">
      <b-row class="page-title-container">
        <b-col>
          <h1 class="page-title">Users</h1>
        </b-col>
        <!-- There's nothing to link to anyway so let's just hardcode this for now -->
        <b-col> <p class="text-right">User Management > Users</p> </b-col>
      </b-row>

      <div>
        <b-table striped hover :items="users">
          <template #cell(name)="data">
            <b class="text-info" @click="edit(data.item)">{{ data.item.name }}</b>
          </template>

          <template #cell(created_at)="data">
            {{ displayReadableTS(data) }}
          </template>
        </b-table>

        <b-button @click="create">Add User</b-button>
      </div>
    </div>
    <b-modal
      id="modal-user-form"
      ref="modal"
      v-bind:title="form.title"
      v-bind:ok-title="form.action"
    >
      <template #modal-footer="{ cancel }">
        <b-button size="sm" @click="cancel()"> Cancel </b-button>
        <b-button size="sm" variant="success" @click="handleAction()">
          {{ form.action }}
        </b-button>
        <b-button v-if="form.id" left size="sm" variant="danger" @click="remove">
          Delete
        </b-button>
      </template>
      <b-container>
        <b-row>
          <b-col> Name </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-name"
              v-bind:description="form.errors.name[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-name"
                v-model="form.name"
                placeholder="John Doe"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Email Address </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-email"
              v-bind:description="form.errors.email[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-email"
                v-model="form.email"
                placeholder="john.doe@arbcalls.com"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Role </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-role"
              v-bind:description="form.errors.role[0]"
              class="mb-0 text-danger"
            >
              <b-form-select
                id="in-role"
                v-model="form.role"
                :options="roleNames"
                required
              ></b-form-select>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="form.action == 'Save'">
          <b-col> Password </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-pw"
              v-bind:description="form.errors.password[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-pw"
                type="password"
                v-model="form.password"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row v-if="form.action == 'Save'">
          <b-col> Repeat Password </b-col>
          <b-col sm="8">
            <b-form-input
              type="password"
              v-model="form.password_confirmation"
            ></b-form-input>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<style>
/* Work-around for that weird modal overlay with excessively high z-index */
#modal-user-form {
  z-index: 1060;
}
</style>

<script>
import moment from "moment";
const baseForm = {
  name: "",
  email: "",
  role: "",
  password: "",
  password_confirmation: "",
  title: "Add User",
  action: "Save",
  errors: {
    name: [],
    email: [],
    role: [],
    password: [],
  },
};

export default {
  data() {
    return {
      roleNames: [],
      users: [],
      form: baseForm,
    };
  },
  async mounted() {
    await this.refreshData();
  },
  methods: {
    async refreshData() {
      this.roleNames = (await axios.get("/api/roles")).data.map((r) => r.name);
      this.users = (await axios.get("/api/users")).data;
      this.form.role = this.roleNames[0];
    },
    displayReadableTS(data) {
      const ts = data.item.created_at;
      return moment(ts).format("YYYY-MM-DD");
    },
    create() {
      this.form = { ...baseForm };
      this.$bvModal.show("modal-user-form");
    },
    edit(user) {
      this.form = {
        ...user,
        title: "Update User",
        action: "Update",
        errors: baseForm.errors,
      };

      this.$bvModal.show("modal-user-form");
    },
    async store() {
      await this.execModificationFn(async () => {
        await axios.post("/api/users", this.form);
      });
    },
    async update() {
      if (this.form.id <= 0) {
        throw new Error("Cannot update a user without id");
      }

      await this.execModificationFn(async () => {
        await axios.put(`/api/users/${this.form.id}`, this.form);
      });
    },
    async remove() {
      await this.execModificationFn(async () => {
        await axios.delete(`/api/users/${this.form.id}`);
      });
    },
    async execModificationFn(fn) {
      try {
        this.form.errors = { ...baseForm.errors };
        await fn();
        await this.refreshData();
        this.$bvModal.hide("modal-user-form");
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
