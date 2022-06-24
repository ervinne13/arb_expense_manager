<template>
  <div>
    <h1>Roles</h1>

    <div>
      <b-table striped hover :items="roles"></b-table>
    </div>
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
    };
  },
};
</script>
