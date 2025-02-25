<template lang="">
    <div id="AdminMain">
        <AdminHeader></AdminHeader>
        <AdminDashboard v-if="$page.url=='/admin'"></AdminDashboard>
        <AdminCategories :categories="categories" v-if="$page.url=='/admin/categories'"></AdminCategories>
        <AdminOrders v-if="$page.url=='/admin/orders'"></AdminOrders>
        <AdminProducts :attributes='attributes' :products='products' v-if="$page.url=='/admin/products'"></AdminProducts>
        <AdminCreateProduct :categories='categories' :fillable='fillable' :attributes='attributes'  v-if='$page.url=="/admin/products/create"'></AdminCreateProduct>
        <AdminCreateProduct :categories='categories' :attributes='attributes' :product='product' v-if="$page.url.startsWith('/admin/products/edit/')"></AdminCreateProduct>
    </div>
</template>
<script>
import AdminCategories from './Admin/AdminCategories.vue';
import AdminDashboard from './Admin/AdminDashboard.vue';
import AdminHeader from './Admin/Components/AdminHeader.vue'
import AdminOrders from './Admin/AdminOrders.vue'
import AdminProducts from './Admin/AdminProducts.vue'
import AdminCreateProduct from './Admin/Components/AdminCreateProduct.vue'

export default {
    components: {
        AdminHeader,
        AdminCategories,
        AdminDashboard,
        AdminOrders,
        AdminCreateProduct,
        AdminProducts,
    },
    data() {
        return {
            white: '#d9e9cf',
            bacgroundItemDark: '#2B2B2B',
            lightDark: '#2E2E2E'
        }
    },
    provide(){
        return {
        }
    },
    mounted() {
        console.log(this.$page.props.styles);
        console.log(this.$page.url);
    },
    props: {
        fillable: Array,
        categories: Array,
        products: Array,
        attributes: Array,
        attributes_grouped: Array,
        product: Array,
        
    },
    computed: {
        styles() {
            return this.$page.props.styles
        }
    },
}
</script>
<style lang="sass">
@import url('https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap')
* 
    padding: 0
    margin: 0
    box-sizing: border-box
a
    text-decoration: none
    color: white
    font-family: Mulish 

input
    outline: none

#AdminMain
    background-color: #232323
    overflow: hidden
    min-height: 100vh
    color: #FFFFFF
    font-family: Mulish
    letter-spacing: 1px
    display: flex
    flex-direction: column


.input-radius
    border-radius: 15px

::-webkit-scrollbar
    width: 10px
    height: 10px
::-webkit-scrollbar-track
    background: #D9E9CF
    border-radius: 10px
    padding: 2px
::-webkit-scrollbar-thumb
    background: #1F1F1F
    border-radius: 10px
    border: 1px solid #D9E9CF
</style>