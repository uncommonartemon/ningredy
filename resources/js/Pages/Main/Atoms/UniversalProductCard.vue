<template>
    <Link :href="product.id ? `/product/${product.id}` : `/product/template`"
    @mouseenter="hover_func(true)" @mouseleave="hover_func(false)"
    :style="{
        backgroundColor: hover ? styles.dark_4 : styles.dark_2,
        border: hover ? `1px solid ${styles.dark_1}` : `1px solid ${styles.dark_4}`,
    }"
    v-if="(product && product.images && product.images.length) || product.title.en == 'title'" id='universal'>
       <div
       :style="{
            maxHeight : hover ? '100%' : '60%',
            height: hover ? '100%' : '60%'
        }" v-if="product.images"
       class="img">
            <img v-if="product.images[0]" :src="`/storage/${product.images[0]}`" alt="">
       </div>
       <div v-if="product.title.en == 'title'" class="loading-block">
            <LoadingAnim :styles="styles" :settings="settings"></LoadingAnim>
       </div>
       <div
       :style="{ display: hover ? 'flex' : 'flex'}"
       class="product-info">
            <p ref="title" class="title">
                {{ product.title.en }}
            </p>
            <div class="prices">
                <p ref="discount" :style="{
                    color: styles.black,
                    backgroundColor: styles.contrast_1,
                }"
                v-if="product.discount" class="discount">
                    {{ product.discount }} {{ product.discount != 'discount' ? settings.currency : null }}
                </p>
                <p ref="price" :style="{
                    border: product.discount? 'none' : `none`,
                    textDecoration:  product.discount ? 'line-through' : 'none',
                    backgroundColor: product.discount? 'none' : 'none',
                    color: product.discount ? none : styles.white,

                }"
                class="price">
                    {{ product.price }} {{ product.price != 'price' ? settings.currency : null }}
                </p>
                
            </div>
       </div>
    </Link>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { gsap } from "gsap";
import LoadingAnim from './LoadingAnim.vue';
    export default {
        data() {
            return {
                hover: false,
                rotate: -70,
                x: 50,
                y: 30
            }
        },
        methods: {
            hover_func(bool) {
                this.hover = bool
                bool ? this.$emit('hover-product', this.product.attributes) : this.$emit('hover-product', []),
                bool ? this.leavingInfoAnim() : this.comeBackAnim()
            },
            leavingInfoAnim() {
                const tl = gsap.timeline()
                tl.fromTo(this.$refs.price, {
                        rotate: 0,
                        opacity: 1,
                        x: 0 ,
                        y: 0
                    }, {
                        rotate: this.rotate,
                        y: this.y,
                        x: this.x,
                        opacity: 0,
                        duration: 0.3,
                    }
                )
                tl.fromTo(this.$refs.discount, {
                    rotate: 0,
                    opacity: 1,
                    x: 0 ,
                    y: 0
                }, {
                    rotate: -this.rotate,
                    x: -this.x,
                    opacity: 0,
                    y: this.y,
                    duration: 0.3,
                }, '<')
                tl.fromTo(this.$refs.title, {
                    opacity: 1,
                }, {
                    opacity: 0,
                    duration: 0.2,
                }, '<')
            },
            comeBackAnim() {
                const tl = gsap.timeline()
                tl.fromTo(this.$refs.price, {y: this.y,x: this.x,rotate:this.rotate, opacity: 0}, {y: 0,opacity: 1,x: 0,rotate: 0, duration: 0.3})
                tl.fromTo(this.$refs.discount, {y: this.y, x: -this.x,rotate:-this.rotate, opacity: 0}, {y:0,opacity: 1,x: 0,rotate: 0, duration: 0.3}, '<')
                tl.fromTo(this.$refs.title, {opacity: 0}, {opacity: 1, duration: 0.2}, '<')
            }
        },
        props: {
            product: Object,
            index: Number,
            styles: Object,
            settings: Object,
        },
        components: {
            LoadingAnim
        }
    }
</script>

<style lang="sass" scoped>
#universal 
    display: flex
    height: 200px
    border-style: solid
    border-width: 1px
    border-radius: 10px
    padding: 10px 10px
    max-width: 300px
    min-width: 150px
    cursor: pointer
    flex-direction: column
    overflow: hidden
    gap: 10pxsf
    transition: background-color 0.2s ease, border 0.2s ease, width 0.2 ease
    &:active
        cursor: grabbing
    .img
        display: flex
        justify-content: center
        width: 100%
        align-items: center
        transition: max-height 0.2s ease 0.1s, height 0.2s ease
        img
            max-width: 100%
            height: 100%
            border-radius: 5px
            margin: auto
    .loading-block
        width: 100%
        display: flex
        justify-content: center
        height: 50%
        align-items: center
    .product-info
        display: flex
        flex-direction: column
        align-items: center
        gap: 5px
        height: 40% 
        justify-content: space-around
        .title
            font-size: 15px
            font-weight: bold
        .prices
            display: flex
            gap: 0px
            .price, .discount
                padding: 4px 6px
                border-radius: 5px
                font-size: 13px
                font-weight: bold
            .discount
                font-weight: bold

</style>