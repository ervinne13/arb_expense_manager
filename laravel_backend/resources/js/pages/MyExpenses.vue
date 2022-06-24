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
          <b-table striped hover :items="items"></b-table>
        </div>
      </b-col>
      <b-col> Pie Chart here </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      items: [
        { expense_category: "Cat A", total: "$100.00" },
        { expense_category: "Cat B", total: "$80.00" },
      ],
    };
  },
};
</script>
