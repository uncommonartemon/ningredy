<template>
    <div
    :style="{backgroundColor: styles.dark_2}"
    id="cart-item">
        <div class="item-image">
            <img :src="'/storage/' + item.images[0]" alt="">
        </div>
        <div class="item-info">
            <div class="item-title">
                {{ item.title.en }}
            </div>
            <div class="item-price">
               <span v-if="item.price" :style="item.discount ? {textDecoration : 'line-through'} : {}">{{ item.price }} Kč</span>
               <span v-if="item.discount">{{ item.discount }} Kč</span>
            </div>
        </div>
        <div class="item-buttons">
            <div @click="deleteItem(item.id)" class="item-delete">
                <button :style="{backgroundColor : styles.dark_4}">
                   <div :style="{backgroundColor : styles.white}" class="delete-cross1"></div>
                   <div :style="{backgroundColor : styles.white}" class="delete-cross2"></div>
                </button>
            </div>
            <div :style="{backgroundColor : styles.dark_2}" class="item-counter">
                <span :style="hoveredMinus" @click="removeQty(item.id)" @mouseleave="hoverMinus=false" @mouseenter="hoverMinus = true ">-</span>
                <span 
                class="qty">
                    {{ qty }}
                </span>
                <span :style="hoveredPlus" @click="addQty(item.id)" @mouseleave="hoverPlus=false" @mouseenter="hoverPlus = true">+</span>
            </div>
    
        </div>
    </div>
</template>

<script>
import { router, Link } from '@inertiajs/vue3';
    export default {
        data() {
            return {
                styles: this.$page.props.styles,
                hoverMinus: false,
                hoverPlus: false,
            }
        },
        props : {
            item : Object,
            qty: Number,
        },
        computed: {
            hoveredMinus() {
                return {backgroundColor : this.hoverMinus ? this.styles.contrast_2 : ""}
            },
            hoveredPlus() {
                return {backgroundColor : this.hoverPlus ? this.styles.contrast_2 : ""}
            },
            takeQty() {
                
            },
        },
        methods: {
            addQty(id) {
                router.visit(`/cart/addQty/${id}`, {
                    method: 'patch',
                    onSuccess: () => {
                        console.log('Product added to cart');
                    },
                    preserveScroll: true, 
                    preserveState: true 
                });
            },
            removeQty(id) {
                router.visit(`/cart/removeQty/${id}`, {
                    method: 'patch',
                    onSuccess: () => {
                        console.log('');
                    },
                    preserveScroll: true, 
                    preserveState: true 
                })
            },  
            deleteItem(id) {
                router.visit(`/cart/delete/${id}`, {
                    method: 'delete', 
                    onSuccess: () => {
                        console.log('Product deleted')
                    },
                    preserveScroll: true,
                    preserveState: true 
                })
            },
        }
    }
</script>

<style lang="sass" scoped>
#cart-item
    display: flex
    padding: 10px 20px
    border-radius: 10px
    gap: 10px
    display: grid
    grid-template-columns: 1fr 2fr 1fr
    margin-top: 15px
    .item-image
        display: flex
        align-items: center
        justify-content: start
        img
            height: 90px
            max-width: 100px
            border-radius: 5px
    .item-info
        display: flex
        flex-direction: column
        height: 100%
        gap: 5px
        .item-title
            font-size: 14px
        .item-price
            font-size: 13px
            display: flex
            flex-direction: column
    .item-buttons
        display: flex
        flex-direction: column
        justify-content: space-between
        align-items: end
        padding: 0px 0px 5px
        .item-counter
            width: 100%
            border: 1px solid grey
            display: grid
            grid-template-columns: 1fr 1fr 1fr
            border-radius: 10px
            user-select: none
            span
                display: flex
                text-align: center
                align-items: center
                justify-content: center
                padding: 2px 0px
                font-size: 15px
                &:first-of-type, &:last-of-type
                    display: flex
                    vertical-align: center
                    position: relative
                    top: 0px   
                    font-size: 18px
                    cursor: pointer
                &:first-of-type
                    border-radius: 9px 0px 0px 9px
                &:last-of-type
                    border-radius: 0px 9px 9px 0px  
        .item-delete
            cursor: pointer
            button
                width: 21px
                height: 21px
                cursor: pointer
                position: relative
                border: 0px
                border-radius: 4px
                .delete-cross1, .delete-cross2
                    height: 2px
                    background-color: grey
                    width: 15px
                    position: absolute
                    top: 10px
                    right: 3px
                .delete-cross1
                    transform: rotate(45deg)
                .delete-cross2
                    transform: rotate(-45deg)

.qty 
    transition: transform 0.2s, opacity 0.2s

.qty.animate 
    transform: scale(1.5)
    opacity: 0.5

            
</style>