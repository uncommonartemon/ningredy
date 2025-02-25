<template>
    <div
    :style="{color: styles.white}"
    id="ProductById">
        <div class="product-block">
            <div class="product-left">
                <div class="product-img">
                    <!-- <img :src="`/storage/${product.images[0]}`" alt=""> -->
                    <swiper   
                    :modules="modules"
                    :slides-per-view="1"
                    :space-between="50"
                    v-if="product.images && product.images.length">
                        <swiper-slide v-for="(image, index) in product.images" :key="index">
                             <img :src="`/storage/${image}`" alt="Product Image" />
                        </swiper-slide>
                        <div class="swiper-pagination" slot="pagination"></div>
                    </swiper>
                </div>
            </div>
            <div class="product-right">
                <div class="product-info">
                    <div class="product-title info-item">{{ product.title.en }}</div>
                    <div class="product-price info-item">
                        <p class="without-dicount">{{ product.price }}</p>
                        <p class="discount">{{ product.discount }}</p>
                    </div>
                    <button class="add">Buy</button>
                    <div v-if="product.category" class="product-category info-item">{{ product.category.name.en }}</div>
                    <div v-if="product.brand" class="product-brand info-item">{{ product.brand }}</div>
                    <div v-if="product.type" class="product-type info-item">{{ product.type }}</div>
                    <div class="product-attributes">
                        <div v-for="attribute in product.attributes" :key="key" class="product-attribute">
                            <div v-for="(value, key) in attribute">
                                {{key}} : {{ value }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script >
import { defineAsyncComponent } from 'vue';
import MainPage from '../MainPage.vue'
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

    export default {
        data() {
            return {
                styles: this.$page.props.styles,
            }
        },
        layout: defineAsyncComponent(() => import('../MainPage.vue')),
        props: {
            product: Object
        },
        components: {
            Swiper,
            SwiperSlide
        },
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
                img
                    width: 100%
                    border-radius: 20px
        .product-right
            .product-info
                display: flex
                flex-direction: column
</style>