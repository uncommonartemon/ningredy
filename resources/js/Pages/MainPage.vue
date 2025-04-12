<template>
    <div id="MainPage" 
    :style="{
            backgroundColor: styles.dark_1,
            color: styles.white
        }">
        <HeaderVue :category='category'></HeaderVue>
        <slot />

    </div>
</template>

<script>
import HomePage from './Main/HomePage.vue';
import ProductsByCategory from './Main/ProductsByCategory.vue';
import HeaderVue from './Main/HeaderComponents/HeaderVue.vue'
    export default {
        data() {
            return {
                styles: this.$page.props.styles
            }
        },  
        components: {
            HeaderVue,
        },
        props: {
            categories : Array,
        },
        mounted() {
            console.log(this.$page.props.styles);
            console.log(this.categories);
            console.log(`main page : `);
        }
    }
</script>

<style lang="sass" scoped>
@import url('https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap')
* 
    padding: 0
    margin: 0
    box-sizing: border-box
a
    text-decoration: none
    color: white


input[type="number"]
    -moz-appearance: textfield
    -webkit-appearance: textfield
    appearance: textfield

#MainPage
    display: flex
    flex-direction: column
    font-family: Mulish
    min-height: 100vh
</style>