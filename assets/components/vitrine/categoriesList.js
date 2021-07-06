import Vue from 'vue';
import CategoriesList from './CategoriesList.vue';

new Vue({
  el: '#categoriesList',
  delimiters: ['${', '}$'],
  components: {
    CategoriesList
  },
  data: {
    categories: []
  },
  mounted() {
    this.onCallCategories();
  },
  methods: {
    onCallCategories: function () {
      fetch(
        '/api/categories?h_order=0',
        {
          method: 'GET',
        }
      ).then(
        response => response.text()
      ).then(
        value => {
          this.categories = JSON.parse(value).data;
        }
      );
    }

  },
})

