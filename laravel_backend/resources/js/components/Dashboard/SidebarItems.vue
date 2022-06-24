<template>
  <div class="margin-top">
    <router-link to="/">
      <button class="button is-medium is-fullwidth is-rounded is-white margin-btn">
        <span class="icon is-medium">
          <i class="mdi mdi-24px mdi-home"></i>
        </span>
        <span>Dashboard</span>
      </button>
    </router-link>

    <div v-if="user.role_code == 'admin'">
      <h1>User Management</h1>
      <router-link to="/roles">
        <button class="button is-small is-fullwidth is-rounded is-white margin-btn">
          <span class="icon is-small">
            <i class="mdi mdi-18px mdi-information"></i>
          </span>
          <span>Roles</span>
        </button>
      </router-link>
      <router-link to="/users">
        <button class="button is-small is-fullwidth is-rounded is-white margin-btn">
          <span class="icon is-small">
            <i class="mdi mdi-18px mdi-information"></i>
          </span>
          <span>Users</span>
        </button>
      </router-link>

      <h1>Expense Management</h1>
      <router-link to="/expense-categories">
        <button class="button is-small is-fullwidth is-rounded is-white margin-btn">
          <span class="icon is-small">
            <i class="mdi mdi-18px mdi-information"></i>
          </span>
          <span>Expense Categories</span>
        </button>
      </router-link>
      <router-link to="/expenses">
        <button class="button is-small is-fullwidth is-rounded is-white margin-btn">
          <span class="icon is-small">
            <i class="mdi mdi-18px mdi-information"></i>
          </span>
          <span>Expenses</span>
        </button>
      </router-link>
    </div>
  </div>
</template>

<style>
.router-link-exact-active > button {
  background-color: #44b783 !important;
  color: white !important;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2) !important;
  margin-bottom: 15px;
}
.button {
  justify-content: left !important;
}
.margin-top {
  margin-top: 2rem;
}
.margin-btn {
  margin-bottom: 10px;
}
</style>

<script>
import auth from "@/js/auth";

export default {
  data() {
    return {
      user: { role_code: "" },
    };
  },
  async mounted() {
    this.user = auth.getCurrentUser();
  },
};
</script>
