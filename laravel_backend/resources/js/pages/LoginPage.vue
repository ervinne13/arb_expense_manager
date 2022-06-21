<template>
  <div>
    <h1>Login</h1>

    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
      <b-form-group
        id="in-grp-email"
        label="Email address:"
        label-for="in-email"
        description="If you forgot your Email, please contact an administrator"
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
    <b-card class="mt-3" header="Form Data Result">
      <pre class="m-0">{{ user }}</pre>
    </b-card>
  </div>
</template>

<script>
import auth from "../auth.js";

export default {
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      show: true,
    };
  },

  methods: {
    async onSubmit(event) {
      event.preventDefault();
      const loginResp = await this.axios.post("/api/login", this.user);

      if (loginResp.status === 200) {
        const user = await auth.getCurrentUser();
        console.log(user);
        this.$router.push("/");
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
