<template>
  <div class="container">
    <h1>Roles</h1>

    <div>
      <b-table striped hover :items="roles"></b-table>

      <b-button v-b-modal.modal-form right variant="success">Add Role</b-button>
    </div>

    <b-modal id="modal-form" title="BootstrapVue">
      <div>
        <b-row>
          <b-col> Display Name </b-col>
          <b-col>
            <b-form-input v-model="form.name" placeholder="Role Code"></b-form-input>
          </b-col>
        </b-row>

        <b-form-input v-model="form.name" placeholder="Role Name"></b-form-input>
        <div class="mt-2">Value: {{ form.code }}</div>
      </div>
    </b-modal>
  </div>
</template>

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
      },
    };
  },
  async mounted() {
    const roles = (await axios.get("/api/roles")).data;
    console.log(roles);
    this.roles = roles;
  },
};
</script>
