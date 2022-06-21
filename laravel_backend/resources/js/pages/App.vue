<template>
  <div>
    <router-link to="/">Home</router-link>
    <router-link to="/users">Users</router-link>

    <passport-clients></passport-clients>
    <passport-authorized-clients></passport-authorized-clients>
    <passport-personal-access-tokens></passport-personal-access-tokens>

    <!-- Dynamic view based on routing system here -->
    <router-view></router-view>
  </div>
</template>

<script>
export default {};
</script>
