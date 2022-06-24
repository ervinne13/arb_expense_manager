<template>
  <div id="app" v-if="isUserLoggedIn">
    <VdDashboard
      pageBackground="#f8f8f8"
      sidebarHeaderHeight="175px"
      sidebarWidth="300px"
      headerBackground="white"
      sidebarBackground="white"
    >
      <template v-slot:main-content>
        <router-view />
      </template>
      <template v-slot:header-content>
        <HeaderItems></HeaderItems>
      </template>
      <template v-slot:sidebar-header>
        <SidebarHeader></SidebarHeader>
      </template>
      <template v-slot:sidebar-content>
        <SidebarItems></SidebarItems>
      </template>
    </VdDashboard>
  </div>
  <div id="app" v-else>
    <router-view />
  </div>
</template>

<style>
.vd-header-container {
  padding: 0 !important;
}
.page-title {
  font-size: xx-large;
}
.page-title-container {
  margin-bottom: 10px;
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
};
</script>
