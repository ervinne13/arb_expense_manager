<template>
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="collapse navbar-collapse">
        <div class="navbar-nav" v-if="isUserLoggedIn">
          <h5>Dashboard</h5>
          <a href="javascript:void(0)" @click="logout()" class="nav-item nav-link ml-3"
            >Logout</a
          >
        </div>
        <div v-else>
          <router-link to="/login">Login</router-link>
        </div>
      </div>
    </nav>
    <router-view> </router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isUserLoggedIn: this.user,
    };
  },
  async mounted() {
    this.user = await this.axios.get("/api/me");
  },
  methods: {
    async logout() {
      await this.axios.post("/api/logout");
      this.$router.push("/login");
    },
  },
};
</script>
