<template>
<div @click="openProduct(product.id)" class="template">
    <div 
    @mouseenter="hoverAll = true"
    @mouseleave="hoverAll = false"
    :style="{
        background: hoverAll 
        ? true
            : styles.dark_2,
            border: `2px solid ${styles.dark_4}`,
        ...(hoverAll ? {  boxShadow: `0px 0px 10px ${styles.contrast_1}`} : {}),
        // ...(hoverAll ? {transform: 'translateZ(0) scale(1.02)'} : {})
    }"
    :class="gridType == 'columns'? 'product-item-column' : 'product-item-row'">
        <div class="product-image">
            <img :src="'/storage/' + product.images[0]" alt="">
        </div>
        <div class="product-info">
            <div class="product-title">
                {{ product.title.en }}
            </div>
            <div v-if="gridType == 'rows'" class="product-brand">
                {{ product.brand }}
            </div>
            <div v-if="gridType == 'rows'" class="product-price">
                    <span v-if="product.discount != product.price" class="price" :style="product.discount && product.discount > 0 ? {textDecoration : 'line-through', order : 1} : {background : styles.contrast_1, color : styles.black}">
                        {{ Math.round(product.price) }} Kč
                    </span>
                    <span class="discount" v-if="product.discount" :style="{background : styles.contrast_1, color : styles.black, fontWeight : 700 }">
                        {{ Math.round(product.discount) }} Kč
                    </span>
                </div>
            <div class="product-info-bot">
                <div v-if="gridType == 'columns'" class="product-price">
                    <span v-if="product.discount != product.price" class="price" :style="product.discount && product.discount > 0 ? {textDecoration : 'line-through', order : 1} : {background : styles.contrast_1, color: styles.black, fontWeight : 'bold'}">
                        {{ Math.round(product.price) }} Kč
                    </span>
                    <span class="discount" v-if="product.discount" :style="{background : styles.contrast_1, color : styles.black, fontWeight : 700 }">
                        {{ Math.round(product.discount) }} Kč
                    </span>
                </div>
                <div class="product-buttons">
                    <button @click='addToCart(product.id)'
                    @mouseenter='hover = true'
                    @mouseleave="hover = false"
                    :style="{background: `linear-gradient(90deg, ${styles.contrast_1} 50%, ${styles.dark_4} 50%)`}"
                    class="button show" :href="`/product/${product.id}`">
                        <span
                        :class="hover ? 'fade-left' : 'unfade-left'"
                        :style="hover ? {color : styles.black} : {color : styles.white}"
                        >Add</span>
                        <span v-for="(letter, index) in text"
                        class="letter"
                        :key="index"
                        :style="{ animationDelay: (0.1 + (index * 0.05)) + 's', color: styles.black}">
                        {{ letter === ' ' ? '\u00A0' : letter }}
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
</template>

<script>
import { router, Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
    export default {
        data() {
            return {
                add: 'add to cart',
                styles: this.$page.props.styles, 
                hover: false,
                hoverAll: false,
            }
        },
        props: {
            product: Object,
            gridType: String,
        },
        methods: {
            addToCart(id) {
                router.visit(`/cart/add/${id}`, {
                    method: 'post',
                    onSuccess: () => {
                        console.log('Product added to cart');
                    },
                    preserveScroll: true, 
                    preserveState: true 
                });
            },
            openProduct(id) {
                router.visit(`/product/${id}`, {
                    method: 'get',

                })
            }
        },
        mounted() {
            console.log(this.gridType);
        },
        computed: {
            text() {
                return this.hover ? 'to cart' : ''
            }
        },  
    }   
</script>

<style lang="sass" scoped>

.product-item-column,
.product-item-row 
    will-change: transform
    backface-visibility: hidden
.template
    position: relative

.product-item-column
    padding: 25px
    border-radius: 20px
    height: 400px
    display: flex
    flex-direction: column
    justify-content: space-between
    gap: 10px
    max-width: 250px
    visibility: visible
    animation: opacityColumn 0.3s ease forwards 
    transition: all 0.3s ease
    cursor: pointer
    opacity: 1
    backface-visibility: visible
    will-change: transform
    -webkit-font-smoothing: antialised
    -webkit-font-smoothing: subpixel-antialiased
    &:hover
        //transform: scale(1.2)
        z-index: 2
        opacity: 0.99
        backface-visibility: hidden
    .product-image
        min-height: 60%
        user-select: none
        max-height: 60%
        display: flex
        align-items: center
        border-radius: 10px
        justify-content: center
        flex-direction: column
        img
            max-height: 100%
            max-width: 100%
            border-radius: 5px
    .product-info
        display: flex
        flex-direction: column
        justify-content: space-between
        height: 40%
        .product-title
            text-align: center  
            
        .product-info-bot
            display: flex
            flex-direction: column
            .product-price
                display: flex
                position: relative
                bottom: 15px
                gap: 3px
                span
                    border-radius: 5px
                    padding: 2px 8px
                    font-size: 14px
.product-buttons
    display: flex
    position: relative
    width: 100%
    display: flex
    align-items: center
    .button
        display: flex
        text-align: center
        justify-content: center
        align-items: center
        text-transform: lowercase
        font-size: 14px
        height: 35px
        cursor: pointer
        opacity: 1
        border: 0px
    .show
        width: 100%
        font-weight: bold
        border-radius: 10px
        position: relative
        background-size: 210% 100% !important
        background-position: 100% 0 !important
        transition: background-position 0.2s ease
        span
            font-weight: bold
            transition: color 0.5s ease
            &::first-of-type        
                &:first-letter
                    text-transform: uppercase  
        .letter
            opacity: 0
            display: inline-block
            animation: fadeIn 0.2s forwards
            position: relative
            transform: translateX(17px)
        &:hover
            background-position: 0 0 !important

.product-item-row
    width: 100%
    min-width: 100%
    display: flex
    height: 100px
    padding: 10px 50px 10px 20px
    border-radius: 20px
    gap: 15px
    justify-content: center
    animation: opacityRow 0.2s ease forwards 
    visibility: visible
    opacity: 1
    cursor: pointer
    transition: transform 0.1s ease
    backface-visibility: visible
    will-change: transform
    -webkit-font-smoothing: antialised
    -webkit-font-smoothing: subpixel-antialiased
    &:hover
        z-index: 2
        //transform: scalex(1.05)  scaleY(1.1)
        opacity: 0.99
    .product-image
        display: flex
        height: 100%
        align-items: center
        width: 100px
        justify-content: center
        img
            height: 100%
            border-radius: 5px
            max-width: 150px
    .product-info
        display: grid
        grid-template-columns: 2fr 1fr 2fr 2fr
        justify-content: space-between
        flex-grow: 1
        align-items: center
        .product-info-bot
            display: flex
            .product-buttons
                display: flex
                justify-content: end
                .button
                    width: 200px
    .product-price
        display: flex
        gap: 10px
        .discount, .price
            height: 25px
            display: flex
            justify-content: center
            text-align: center
            align-items: center
            font-size: 15px
            padding: 3px
            border-radius: 5px
    .product-right
        height: 100%
        flex-grow: 1

                        
@keyframes fadeIn
    from
        opacity: 0
    to
        opacity: 1
@keyframes opacityRow
    0%
        opacity: 0
    100%
        opacity: 1
@keyframes opacityColumn
    0%
        opacity: 0
    100%
        opacity: 1

.fade-left
    position: absolute
    animation: fade-left 0.3s ease forwards
.unfade-left
    position: absolute
    animation: unfade-left 0.3s ease forwards
    
@keyframes fade-left
    from 
        transform: translateX(0px) 
    to
        transform: translateX(-22px)
@keyframes unfade-left
    from 
        transform: translateX(-22px)
    to
        transform: translateX(0px) 

</style>