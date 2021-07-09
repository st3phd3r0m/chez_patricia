import Vue from 'vue';
import CategoriesList from './CategoriesList.vue';

import { BootstrapVue, BootstrapVueIcons, NavbarPlugin } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(BootstrapVueIcons)
Vue.use(NavbarPlugin)

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

