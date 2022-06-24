<template>
  <div>
    <div class="container">
      <b-row class="page-title-container">
        <b-col>
          <h1 class="page-title">Expense Categories</h1>
        </b-col>
        <!-- There's nothing to link to anyway so let's just hardcode this for now -->
        <b-col> <p class="text-right">User Management > Expense Categories</p> </b-col>
      </b-row>

      <div>
        <b-table striped hover :items="categories">
          <template #cell(name)="data">
            <b class="text-info" @click="edit(data.item)">{{ data.item.name }}</b>
          </template>

          <template #cell(created_at)="data">
            {{ displayReadableTS(data) }}
          </template>
        </b-table>

        <b-button @click="create">Add Category</b-button>
      </div>
    </div>
    <b-modal
      id="modal-category-form"
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
              label-for="in-category-name"
              v-bind:description="form.errors.name[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-category-name"
                v-model="form.name"
                placeholder="Travel/Bills/etc."
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Description </b-col>
          <b-col sm="8">
            <b-form-input
              v-model="form.description"
              placeholder="Describe what the kind of expense this is for."
            ></b-form-input>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<style>
/* Work-around for that weird modal overlay with excessively high z-index */
#modal-category-form {
  z-index: 1060;
}
</style>

<script>
import moment from "moment";
const baseForm = {
  name: "",
  description: "",
  title: "Add Category",
  action: "Save",
  errors: {
    name: [],
    description: [],
  },
};

export default {
  data() {
    return {
      categories: [],
      currentCategory: baseForm,
      form: baseForm,
    };
  },
  async mounted() {
    await this.refreshCategories();
  },
  methods: {
    async refreshCategories() {
      this.categories = (await axios.get("/api/expense-categories")).data;
    },
    displayReadableTS(data) {
      const ts = data.item.created_at;
      return moment(ts).format("YYYY-MM-DD");
    },
    create() {
      this.form = baseForm;
      this.$bvModal.show("modal-category-form");
    },
    edit(category) {
      this.currentCategory = category;
      this.form = {
        ...category,
        title: "Update Category",
        action: "Update",
        errors: {
          name: [],
          description: [],
        },
      };

      this.$bvModal.show("modal-category-form");
    },
    async store() {
      await this.execModificationFn(async () => {
        await axios.post("/api/expense-categories", this.form);
      });
    },
    async update() {
      await this.execModificationFn(async () => {
        await axios.put(
          `/api/expense-categories/${this.currentCategory.name}`,
          this.form
        );
      });
    },
    async remove() {
      await this.execModificationFn(async () => {
        await axios.delete(`/api/expense-categories/${this.form.name}`);
      });
    },
    async execModificationFn(fn) {
      try {
        await fn();
        await this.refreshCategories();
        this.$bvModal.hide("modal-category-form");
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
