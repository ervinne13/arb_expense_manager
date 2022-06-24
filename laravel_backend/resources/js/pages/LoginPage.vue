<template>
  <div>
    <h1>Login</h1>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
      <b-form-group
        id="in-grp-email"
        label="Email address:"
        label-for="in-email"
        v-bind:description="loginError"
      >
        <b-form-input
          id="in-email"
          v-model="user.email"
          type="email"
          placeholder="john.doe@arbcalls.com"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group id="in-grp-pw" label="Password:" label-for="in-pw">
        <b-form-input
          id="in-pw"
          type="password"
          v-model="user.password"
          placeholder="********"
          required
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" variant="primary">Login</b-button>
    </b-form>
    <!-- <b-card class="mt-3" header="Form Data Result">
      <pre class="m-0">{{ user }}</pre>
    </b-card> -->
  </div>
</template>

<script>
import auth from "../auth.js";

export default {
  props: ["username"],
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      loginError: "",
      show: true,
    };
  },
  methods: {
    async onSubmit(event) {
      event.preventDefault();
      try {
        await auth.login(this.user.email, this.user.password);
        // this.$router.push("/");
        window.location.href = "/";
      } catch (e) {
        // Let's not put other details
        this.loginError =
          "Login failed, please check your credentials or check with your administrator";
      }
    },
    onReset(event) {
      event.preventDefault();
      // Reset our form values
      this.user.email = "";
      this.user.password = "";

      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },
  },
};
</script>
