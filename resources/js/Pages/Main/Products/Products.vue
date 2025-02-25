<template>
    <div id="Products">
        <div class="content">
            <div class="content-bar">
                <ProductsSortPanel
                @update-sort="updateSort"
                @update-grid="updateGrid"
                :category="category"
                ></ProductsSortPanel>
            </div>
            <div class="content-main">
                <FilterGrid
                @getFilterBy="getFilterBy"
                :filter="filter"
                :categories="categories"
                :attributes="attributes"
                ></FilterGrid>
            <div class="products-grid">
                <div 
                :class="gridType == 'columns' ? 'grid-columns' : 'grid-rows'"
                class="products-items">
                
                        <TransitionGroup
                        name="prod"
                        >
                            <ProductCard
                            :style='{animationDelay : `${0.05 * index}s`}'
                            v-for="(product, index) in products.data"
                            class="product-card"
                            :product="product"
                            :key="product.id" 
                            :gridType="gridType"
                            />
                        </TransitionGroup>
    
                </div>
                <ProductPagination 
                class="pagination"
                :filterBy="filterBy"
                :products="products"></ProductPagination>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import { router, Link } from '@inertiajs/vue3';
import ProductPagination from './ProductsPagination.vue'
import ProductsSortPanel from './ProductsSortPanel.vue';
import ProductCard from './ProductCard.vue'
import FilterGrid from '../Filter/FilterGrid.vue'

    export default {
        data() {
            return {
                filterBy: {},
                buttonShow : false,
                styles: this.$page.props.styles,
                windowTop: null,
                gridType: 'columns',
            }
        },
        components: {
            ProductPagination,
            ProductsSortPanel, 
            ProductCard,
            FilterGrid,
        },
        props: {
                category: Object,
                products: Array,
                filter: Object,
                attributes: Array,
        },
        methods: {
            onScroll(e) {
                this.windowTop = window.top.scrollY 
            },
            updateGrid(data) {
                this.gridType = data
            },
            updateSort(data) {
                this.filterBy.sortBy = data
            },
            getFilterBy(filterBy) {
                this.filterBy = filterBy
            },
        },
        watch: {
            filterBy: {
                handler() {
                    router.visit(`/category/${this.category.id}` , {
                        method: 'get',
                        data: this.filterBy,
                        preserveState: true ,
                        preserveScroll: true ,

                    })
                },
                deep: true,
            }
        },
        mounted() {
            window.addEventListener("scroll", this.onScroll);
        }, 
        beforeDestroy() {
            window.removeEventListener("scroll", this.onScroll)
        },
    }
</script>

<style lang="sass" scoped>
#Products
    display: flex
    width: 100%
    justify-content: center
    padding: 0px 30px
    padding-bottom: 50px
    .content
        width: 100%
        display: flex
        flex-direction: column
        max-width: 1400px
        .content-bar
            width: 100%
        .content-main
            justify-content: space-between
            display: flex
            width: 100%
            gap: 30px
            .products-grid
                display: flex
                flex-direction: column
                gap: 50px
                max-width: calc(100% - 250px - 30px)
                width: 100%
                align-items: center
                .products-items
                    justify-content: space-between
                    display: grid
                    width: 100%
                    margin-bottom: 30px
                    position: relative 

.pagination
    transition: all 0.3s ease

.grid-columns
    justify-content: space-between
    display: grid
    grid-template-columns: repeat(auto-fill, 250px)
    row-gap: 5%

.grid-rows
    display: grid
    grid-template-columns: 1fr
    gap: 10px
    width: 100%
    min-width: 100%

.prod-move
    transition: all 0.3s ease
.prod-enter-active
    transition: all 0.3s ease 
.prod-leave-active
    transition: all 0.3s ease
    position: absolute
    display: none
.prod-enter-from, .prod-leave-to
    opacity: 0
    transform: translateX(-20px)
.prod-enter-to, .prod-leave-from
    opacity: 1
    transform: translateX(0px)
    position: relative

</style>