<template>
  <div>
    <div>
      <b-navbar type="dark" variant="dark">
        <b-navbar-nav>
          <b-nav-item href="#">
            <h3>Welcome {{ user.name }}</h3>
          </b-nav-item>
          <b-nav-item v-if="user.name" right>
            <a href="javascript:void(0)" @click="logout()" class="nav-item nav-link ml-3"
              >Logout</a
            >
          </b-nav-item>

          <b-nav-item v-else right>
            <a href="javascript:void(0)" @click="login()" class="nav-item nav-link ml-3"
              >Login</a
            >
          </b-nav-item>
        </b-navbar-nav>
      </b-navbar>
    </div>
    <router-view></router-view>
  </div>
</template>

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
    this.user = await auth.getCurrentUser();
  },
  methods: {
    login() {
      this.$router.push("/login");
    },
    async logout() {
      await this.axios.post("/api/logout");
      this.$router.push("/login");
    },
  },
};
</script>
