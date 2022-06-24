<template>
  <div v-if="this.isUserLoggedIn">
    <b-navbar toggleable="lg" type="dark" variant="dark">
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-item href="/"> Welcome to Expense Manager </b-nav-item>
          <b-nav-item @click="logout()"> Logout </b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>

<style>
.navbar {
  background: rgba(158, 158, 158, 0.03);
  padding: 1.5em !important;
}

.nav-item {
  justify-content: flex-end;
}
</style>

<script>
import auth from "@/js/auth";

export default {
  data() {
    return {
      user: { name: "" },
      isUserLoggedIn: this.user,
    };
  },
  async mounted() {
    this.user = auth.getCurrentUser();
    this.isUserLoggedIn = !!this.user;
  },
  methods: {
    login() {
      this.$router.push("/login");
    },
    async logout() {
      auth.logout();
      this.isUserLoggedIn = false;
      //   this.$router.push("/login");
      window.location.href = "/login";
    },
  },
};
</script>
