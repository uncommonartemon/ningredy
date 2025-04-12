<template>
    <div
    :style="{color: styles.white}"
    id="ProductById">
        <div v-if="product" class="product-block">
            <div class="product-left">
                <div class="product-img">
                    <swiper-container class="slider" ref="slider1"
                        :slides-per-view="1" :loop="true" 
                        :space-between="200" slideActiveClass="product-active-slide">
                        <swiper-slide class="slide" v-for="(img, index) in (product.images ? product.images : Array(8).fill(''))" :key="index">
                            <img :src="`/storage/${img}`" alt="">
                        </swiper-slide>
                    </swiper-container>
                    <swiper-container class="pagination" 
                    :free-mode="false"
                    :slide-to-clicked-slide="true"
                    :space-between="20"
                    slideActiveClass="active-pagination"
                    slides-per-view="auto" 
                    :watch-slides-progress="true">
                        <swiper-slide  class="slide2" v-for="(slide2, index2) in (product.images ? product.images : Array(8).fill(''))" :key="index2">
                            <img :style="{borderColor: styles.contrast_2}" :src="`/storage/${slide2}`" alt="">
                        </swiper-slide>   
                    </swiper-container>
                </div>
            </div>
            <div class="product-right">
                <div class="product-info">
                    <div class="product-title info-item">{{ product.title.en }}</div>
                    <div class="product-price info-item">
                        <p 
                        :style="{
                            order : product.discount ? 2 : 1,
                            textDecoration:  product.discount ? 'line-through' : true,
                            backgroundColor: product.discount ? none : styles.contrast_1,
                            padding: product.discount? '0px' : '5px'
                        }"
                        class="without-dicount">
                            {{ product.price }}{{ settings.currency }}
                        </p>
                        <p v-if="product.discount" 
                        :style="{backgroundColor: styles.contrast_1, color: styles.black}" class="discount">
                            {{ product.discount }}{{ settings.currency }}
                        </p>
                    </div>
                    <button
                        :style="{
                            backgroundColor: styles.dark_2,
                            border: `1px solid ${styles.dark_4}`,
                            color: styles.white

                            }"
                    class="add">Buy</button>
                    {{ product.categories }}
                    <div v-if="product.category " class="product-category info-item">Category : {{ product.category.name_en }}</div>
                    <div v-if="product.brand" class="product-brand info-item">Brand : {{ product.brand }}</div>
                    <div v-if="product.type" class="product-type info-item">Type : {{ product.type }}</div>
                    <div class="product-attributes">
                        <div 
                        :style="{
                            border: `1px solid ${styles.contrast_1}`,
                            }"
                        v-for="attribute in product.attributes" :key="key" class="product-attribute">
                            <ProductAttribute
                            :attribute_key="key" :value="value"
                            v-for="(value, key) in attribute">
                            </ProductAttribute>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script >
import { register } from 'swiper/element/bundle';
import { defineAsyncComponent } from 'vue';
import ProductAttribute from './Atoms/ProductAttribute.vue'
register()
    export default {
        data() {
            return {
                styles: this.$page.props.styles,
                settings: this.$page.props.settings
            }
        },
        layout: defineAsyncComponent(() => import('../MainPage.vue')),
        props: {
            product: {
                type: Object, 
                default() {
                    return {
                        title: {
                            en: 'title'
                        },
                        price: 'price',
                        discount: 'discount',
                        images: [],
                        brand: 'brand',
                        type: 'type',
                    }
                }
            }
        },
        components: {
            ProductAttribute,
        },
        mounted() {

        },
        computed: {
            productImages() {
                
            }
        }
    }
</script>

<style lang="sass" scoped>
#ProductById
    display: flex
    justify-content: center
    width: 100%
    min-height: 100vh
    .product-block
        max-width: 1400px
        display: grid
        grid-template-columns: minmax(300px,500px) minmax(300px, 500px)
        justify-content: center
        overflow: hidden
        gap: 3%
        width: 100%
        padding: 50px 0
        .product-left
            width: 100%
            .product-img
                width: 100% 
                display: flex
                flex-direction: column
                gap: 30px
                .slider
                    width: 100%
                    cursor: grab
                    &:active
                        cursor: grabbing
                    .product-active-slide
                        display: flex
                    .slide
                        width: 100%
                        max-height: 500px
                        justify-content: center
                        align-items: center
                        img
                            max-height: 500px
                            border-radius: 20px
                            max-width: 100%
                .pagination
                    width: 100%
                    height: 200px
                    cursor: grab
                    &:active
                        cursor: grabbing
                    .slide2
                        max-width: auto
                        width: 75px
                        max-height: 75px
                        justify-content: center
                        display: flex
                        border-width: 0px 
                        border-style: solid
                        border-radius: 5px
                        img
                            height: 100%
                            border-radius: 5px
                            width: 100%
                            border-width: 0px 
                            border-style: solid  
                    .active-pagination
                        img
                            border-width: 2px
            
        .product-right
            font-size: 17px
            
            .product-info
                display: flex
                flex-direction: column
                gap: 15px
                .add
                    padding: 10px
                    border-radius: 10px
                    cursor: pointer
                    font-size: 16px
                    text-transform: lowercase
                    width: 300px
                    font-weight: bold
                .product-attribute, .info-item
                    text-transform: capitalize
                .info-item
                    width: auto
                .product-title
                    font-weight: bold
                .product-price
                    display: flex
                    gap: 5px
                    p
                        width: auto
                        font-weight: bold
                        display: flex
                        justify-content: center
                        align-items: center
                        text-align: center
                        border-radius: 5px
                        padding: 5px 10px
                .product-attributes
                    display: flex
                    gap: 10px
                    flex-wrap: wrap
                    .product-attribute
                        padding: 5px
                        border-radius: 5px       
</style>