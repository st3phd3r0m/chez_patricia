<template>
  <div>
    <div class="category" v-for="category in categories" :key="category.id">
        <p>{{ category.name }}</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "categories-list",
  delimiters: ["${", "}$"],
  props: {
    categories: Array,
  }
};
</script>
