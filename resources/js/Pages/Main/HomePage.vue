<template>
    <div id="HomePage">
        <HeaderVue></HeaderVue>
        <div
        :style="{
            maxWidth : styles.maxWidth
            }"
        class="content">
            <div class="categories-grid">
                <div class="categories-items">
                    <Link
                    v-for="category in categories"
                    :href="'/category/'+category.id" 
                    class="category-item">
                        {{ category }}
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import HeaderVue from './HeaderComponents/HeaderVue.vue';
    export default {
        data() {
            return {
                styles: this.$page.props.styles
            }
        },
        components: {
            Link,
            HeaderVue
        },
        props: {
            categories: Array,

        }, 
        methods: {
            
        },
        mounted() {
            console.log(this.$page.props);
        }
    }
</script>

<style lang="sass" scoped>
#HomePage
    display: flex
    flex-direction: column
    .content
        display: flex
        flex-direction: column
        .categories-grid
            display: flex
            .categories-items
                display: flex
                .category-item
                    border: 1px solid black
                    display: flex
                    cursor: pointer
                    color: black
                    
</style>