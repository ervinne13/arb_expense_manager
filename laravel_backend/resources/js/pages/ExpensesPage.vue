<template>
  <div>
    <div class="container">
      <b-row class="page-title-container">
        <b-col>
          <h1 class="page-title">Expenses</h1>
        </b-col>
        <!-- There's nothing to link to anyway so let's just hardcode this for now -->
        <b-col> <p class="text-right">User Management > Expenses</p> </b-col>
      </b-row>

      <div>
        <b-table striped hover :items="expenses" :fields="fields">
          <template #cell(id)="data">
            <b class="text-info" @click="edit(data.item)">{{ data.item.id }}</b>
          </template>

          <template #cell(amount)="data">
            <b>{{ data.item.amount.toFixed(2) }}</b>
          </template>

          <template #cell(entry_date)="data">
            {{ displayReadableTS(data.item.entry_date) }}
          </template>

          <template #cell(created_at)="data">
            {{ displayReadableTS(data.item.created_at) }}
          </template>
        </b-table>

        <b-button @click="create">Add Expense</b-button>
      </div>
    </div>
    <b-modal
      id="modal-expense-form"
      size="lg"
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
      <b-container class="expense-form-container">
        <b-row>
          <b-col> Expense Category </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-category"
              v-bind:description="form.errors.category[0]"
              class="mb-0 text-danger"
            >
              <b-form-select
                id="in-category"
                v-model="form.category"
                :options="categoryNames"
                required
              ></b-form-select>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Amount $ </b-col>
          <b-col sm="8">
            <b-form-group
              type="number"
              label-for="in-amount"
              v-bind:description="form.errors.amount[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-amount"
                v-model="form.amount"
                placeholder="100.00"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Entry Date </b-col>
          <b-col sm="8">
            <b-form-group
              type="number"
              label-for="in-entry-date"
              v-bind:description="form.errors.entry_date[0]"
              class="mb-0 text-danger"
            >
              <b-form-datepicker
                id="in-entry-date"
                v-model="form.entry_date"
                class="mb-2"
              ></b-form-datepicker>
            </b-form-group>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<style>
/* Work-around for that weird modal overlay with excessively high z-index */
#modal-expense-form {
  z-index: 1060;
}

/* Work-around for the date picker inside modal */
.expense-form-container {
  min-height: 500px;
}
</style>

<script>
import moment from "moment";

const DATE_FORMAT = "YYYY-MM-DD";

const baseForm = {
  category: "",
  amount: "",
  entry_date: moment().format(DATE_FORMAT),
  title: "Add Expense",
  action: "Save",
  errors: {
    category: [],
    amount_in_cents: [],
    amount: [],
    entry_date: [],
  },
};

export default {
  data() {
    return {
      //   fields: ["id", "category", "amount", "entry_date", "created_at"],
      fields: [
        {
          key: "id",
          label: "ID",
          thClass: "text-right",
          tdClass: "text-right",
        },
        {
          key: "category",
          label: "Category",
          thClass: "text-left",
          tdClass: "text-left",
        },
        {
          key: "amount",
          label: "Amount",
          thClass: "text-right",
          tdClass: "text-right",
        },
        {
          key: "entry_date",
          label: "Entry Date",
          thClass: "text-left",
          tdClass: "text-left",
        },
        {
          key: "created_at",
          label: "Created At",
          thClass: "text-left",
          tdClass: "text-left",
        },
      ],
      expenses: [],
      categoryNames: [],
      form: { ...baseForm },
    };
  },
  async mounted() {
    await this.refreshData();
  },
  methods: {
    async refreshData() {
      this.categoryNames = (await axios.get("/api/expense-categories")).data.map(
        (ec) => ec.name
      );
      this.expenses = (await axios.get("/api/expenses")).data;
    },
    displayReadableTS(ts) {
      return moment(ts).format(DATE_FORMAT);
    },
    create() {
      this.form = { ...baseForm };
      this.$bvModal.show("modal-expense-form");
    },
    edit(expense) {
      console.log(expense);
      this.form = {
        ...expense,
        title: "Update Expense",
        action: "Update",
        errors: { ...baseForm.errors },
      };

      this.$bvModal.show("modal-expense-form");
    },
    async store() {
      await this.execModificationFn(async () => {
        await axios.post("/api/expenses", this.form);
      });
    },
    async update() {
      await this.execModificationFn(async () => {
        await axios.put(`/api/expenses/${this.form.id}`, this.form);
      });
    },
    async remove() {
      await this.execModificationFn(async () => {
        await axios.delete(`/api/expenses/${this.form.name}`);
      });
    },
    async execModificationFn(fn) {
      try {
        await fn();
        await this.refreshData();
        this.$bvModal.hide("modal-expense-form");
      } catch (e) {
        if (e.response) {
          if (e.response.data && e.response.data.errors) {
            this.form.errors = {
              ...baseForm.errors,
              ...e.response.data.errors,
            };
          } else if (e.response.data) {
            this.form.errors.category = [e.response.data];
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
