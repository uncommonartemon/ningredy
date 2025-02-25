<template>
    <div id="ProductsByCategory">
        <Products :category="category" :products="products" :filter="filter" :attributes="attributes"></Products>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { defineAsyncComponent } from 'vue';
import HeaderVue from './HeaderComponents/HeaderVue.vue';
import Products from './Products/Products.vue';
import MainPage from '../MainPage.vue'
    export default {
        layout: defineAsyncComponent(() => import('../MainPage.vue')),
        components: {
            HeaderVue,
            Products,
        }, 
        props: {
            category: Object,
            products: Array,
            filter: Object,
            attributes: Array,
       } ,
    }
</script>

<style lang="sass" scoped>

</style>