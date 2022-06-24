<template>
  <div>
    <pie-chart :data="chartData" :options="chartOptions"></pie-chart>
  </div>
</template>

<script>
// import PieChart from "@/js/charts/PieChart";
export default {
  name: "CategoryExpensePieComponent",
  // components: {
  //   PieChart,
  // },
  props: ["categories"],
  data() {
    return {
      chartOptions: {
        hoverBorderWidth: 20,
        animation: {
          duration: 0,
        },
        hover: {
          animationDuration: 0,
        },
        responsiveAnimationDuration: 0,
      },
      chartData: {
        hoverBackgroundColor: "red",
        hoverBorderWidth: 10,
        labels: [],
        datasets: [],
      },
    };
  },
  mounted() {
    this.chartData = {
      hoverBackgroundColor: "red",
      hoverBorderWidth: 10,
      labels: this.labelsFromCategories(),
      datasets: [this.dataSetFromCategories()],
    };
  },
  methods: {
    randomColor() {
      const r = Math.floor(Math.random() * 255);
      const g = Math.floor(Math.random() * 255);
      const b = Math.floor(Math.random() * 255);
      return "rgb(" + r + "," + g + "," + b + ")";
    },
    labelsFromCategories() {
      return (this.categories || []).map((c) => c.category);
    },
    dataSetFromCategories() {
      const self = this;
      return {
        backgroundColor: (this.categories || []).map((c) => self.randomColor()),
        data: (this.categories || []).map((c) => c.amount),
      };
    },
  },
};
</script>

<style>
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
