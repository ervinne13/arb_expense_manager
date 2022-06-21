<template>
  <div>
    <b-form @submit="onSubmit" @reset="onReset" v-if="show">
      <b-form-group
        id="in-grp-email"
        label="Email address:"
        label-for="in-email"
        description="If you forgot your Email, please contact an administrator"
      >
        <b-form-input
          id="in-email"
          v-model="form.email"
          type="email"
          placeholder="john.doe@arbcalls.com"
          required
        ></b-form-input>
      </b-form-group>

      <b-form-group id="in-grp-pw" label="Password:" label-for="in-pw">
        <b-form-input
          id="in-pw"
          v-model="form.password"
          placeholder="********"
          required
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" variant="primary">Login</b-button>
    </b-form>
    <b-card class="mt-3" header="Form Data Result">
      <pre class="m-0">{{ form }}</pre>
    </b-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      //   rules: {
      //     password: [
      //       (v) => !!v || "Password is required",
      //       (v) => (v && v.length >= 8) || "This field must have atleast 8 characters",
      //       (v) =>
      //         (v && v.length <= 100) || "This field exceeds maximum allowed characters",
      //     ],
      //   },
      show: true,
    };
  },
  methods: {
    async onSubmit(event) {
      event.preventDefault();
      //   alert(JSON.stringify(this.form));

      //   const response = await this.$http.get("/api/v1/auth/login");
      //   console.log(response.data);
    },
    onReset(event) {
      event.preventDefault();
      // Reset our form values
      this.form.email = "";
      this.form.password = "";

      // Trick to reset/clear native browser form validation state
      this.show = false;
      this.$nextTick(() => {
        this.show = true;
      });
    },
  },
};
</script>
