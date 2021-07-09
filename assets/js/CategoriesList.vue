<template>
  <b-navbar-nav>
    <b-nav-item v-for="category in categories" :key="category.id" >{{ category.name }}</b-nav-item>
    <!--<b-nav-item href="#" disabled>Disabled</b-nav-item>-->
  </b-navbar-nav>
</template>

<script>
export default {
  name: "categories-list",
  delimiters: ["${", "}$"],
  props: {
    categories: Array,
  },
};
</script>
