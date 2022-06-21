<template>
  <div>
    <h1>Demo Home {{ user.name }}</h1>
    <b-card class="mt-3" header="Current user">
      <pre class="m-0">{{ user }}</pre>
    </b-card>
  </div>
</template>

<script>
import auth from "@/js/auth";

export default {
  data() {
    return {
      user: { name: "" },
    };
  },
  async mounted() {
    const user = await auth.getCurrentUser();
    this.user = user;
  },
};
</script>
