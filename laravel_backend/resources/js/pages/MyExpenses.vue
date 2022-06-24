<template>
  <div>
    <b-row class="page-title-container">
      <b-col>
        <h1 class="page-title">My Expenses</h1>
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <div>
          <b-table striped hover :items="categories" :fields="fields">
            <template #cell(amount)="data">
              <b>{{ data.item.amount.toFixed(2) }}</b>
            </template>
          </b-table>
        </div>
      </b-col>
      <b-col>
        <div v-if="categories.length > 0">
          <cat-expns-pie :categories="categories"></cat-expns-pie>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fields: [
        {
          key: "category",
          label: "Category",
          thClass: "text-left",
          tdClass: "text-left",
        },
        {
          key: "amount",
          label: "Total",
          thClass: "text-right",
          tdClass: "text-right",
        },
      ],
      categories: [],
      chartOptions: {
        hoverBorderWidth: 20,
      },
    };
  },
  async mounted() {
    await this.refreshData();
  },
  methods: {
    async refreshData() {
      this.categories = (await axios.get("/api/expenses/by-category")).data;
    },
    randomColor() {
      const r = Math.floor(Math.random() * 255);
      const g = Math.floor(Math.random() * 255);
      const b = Math.floor(Math.random() * 255);
      return "rgb(" + r + "," + g + "," + b + ")";
    },
  },
};
</script>
