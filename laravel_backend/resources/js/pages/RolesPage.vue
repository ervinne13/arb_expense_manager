<template>
  <div>
    <div>
      <h1>Roles</h1>

      <div>
        <b-table striped hover :items="roles"></b-table>

        <b-button v-b-modal.modal-role-form>Add Role</b-button>
      </div>
    </div>
    <b-modal
      id="modal-role-form"
      ref="modal"
      v-bind:title="form.title"
      v-bind:ok-title="form.action"
      @ok="handleAction"
    >
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
export default {
  data() {
    return {
      roles: [
        { expense_category: "Cat A", total: "$100.00" },
        { expense_category: "Cat B", total: "$80.00" },
      ],
      form: {
        code: "",
        name: "",
        title: "Create new Role",
        action: "Save",
        errors: {
          name: [],
          description: [],
        },
      },
    };
  },
  async mounted() {
    await this.refreshRoles();
  },
  async onSubmit() {
    console.log(this.form);
  },
  methods: {
    async refreshRoles() {
      this.roles = (await axios.get("/api/roles")).data;
    },
    async handleAction(event) {
      event.preventDefault();

      if (this.form.action === "Save") {
        try {
          await axios.post("/api/roles", this.form);
          await this.refreshRoles();
          this.$bvModal.hide("modal-role-form");
        } catch (e) {
          if (e.response) {
            this.form.errors = e.response.data.errors;
          }
        }
      }
    },
  },
};
</script>
