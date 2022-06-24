<template>
  <div v-if="this.isUserLoggedIn">
    <b-navbar toggleable="lg" type="dark" variant="dark">
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="ml-auto">
          <b-nav-item href="/"> Welcome to Expense Manager </b-nav-item>
          <b-nav-item v-b-modal.modal-chng-pw-form> Change Password </b-nav-item>
          <b-nav-item @click="logout()"> Logout </b-nav-item>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>

    <b-modal
      id="modal-chng-pw-form"
      ref="modal"
      title="Change Password"
      ok-title="Change"
      @ok="changePassword"
    >
      <b-container>
        <b-row>
          <b-col> Current Password </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-pw"
              v-bind:description="form.errors.current_password[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-pw"
                type="password"
                v-model="form.current_password"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> New Password </b-col>
          <b-col sm="8">
            <b-form-group
              label-for="in-pw"
              v-bind:description="form.errors.password[0]"
              class="mb-0 text-danger"
            >
              <b-form-input
                id="in-pw"
                type="password"
                v-model="form.password"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>

        <b-row>
          <b-col> Repeat Password </b-col>
          <b-col sm="8">
            <b-form-input
              type="password"
              v-model="form.password_confirmation"
            ></b-form-input>
          </b-col>
        </b-row>
      </b-container>
    </b-modal>
  </div>
</template>

<style>
#modal-chng-pw-form {
  z-index: 1060;
}

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

// Snake case for laravel standard
const baseForm = {
  current_password: "",
  password: "",
  password_confirmation: "",
  errors: {
    current_password: [],
    password: [],
    password_confirmation: [],
  },
};

export default {
  data() {
    return {
      user: { name: "" },
      form: { ...baseForm },
      isUserLoggedIn: this.user,
    };
  },
  async mounted() {
    this.user = auth.getCurrentUser();
    this.isUserLoggedIn = !!this.user;
  },
  methods: {
    async changePassword(event) {
      event && event.preventDefault();
      try {
        await axios.post("/api/me/password", this.form);
        await this.logout();
      } catch (e) {
        if (e.response) {
          if (e.response.data && e.response.data.errors) {
            this.form.errors = {
              ...baseForm.errors,
              ...e.response.data.errors,
            };
          } else if (e.response.data) {
            this.form.errors.current_password = [e.response.data];
          }
        }
      }
    },
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
