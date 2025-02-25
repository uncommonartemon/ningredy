<template>
    <div 
    ref="cartItem"
    :style="{
      backgroundColor: styles.dark_2,
      border: '2px solid ' + styles.dark_4
    }"
    id="HeaderCartItemAlt">
        <div class="left-block">
            <img :src="`/storage/${item.images[0]}`" alt="">
            <div class="info-block">
                <p class="title">
                    {{ item.title.en }}
                </p>
                <p class="price">
                    <span v-if="item.discount" :style="{color:styles.contrast_1}" class="discount">{{ item.discount }}Kč</span>
                    <span :style="{
                        textDecoration : item.discount ? 'line-through' : 'none',
                        color : item.discount ? 'grey' : styles.contrast_1,    
                    }
                    ">{{ item.price }}Kč</span>
                </p>
            </div>
        </div>
        <div class="right-block">
            <div
            @click="deleteItem(item.id)"
            :style="{border: '1px solid ' + styles.dark_4}"
            class="remove-item">
                <div :style="{backgroundColor: styles.white}" class="cross-line"></div>
                <div :style="{backgroundColor: styles.white}" class="cross-line"></div>
            </div>
            <div class="counter">
                <p @click="removeQty(item.id)" :style="{border: '1px solid ' + styles.dark_4, cursor: 'pointer'}"><span class="minus">-</span></p>
                <p :style="{backgroundColor : styles.dark_4}"><span ref="qtyText">{{ qty }}</span></p>
                <p @click="addQty(item.id)" :style="{border: '1px solid ' + styles.dark_4, cursor: 'pointer'}"><span class="plus">+</span></p>
            </div>
        </div>
    </div>
</template>

<script>
import { gsap } from "gsap";
import { router, Link } from '@inertiajs/vue3';
    export default {
        data() {
            return { 
                styles: this.$page.props.styles
            }
        },
        props: {
            item: Object,
            index: Number,
            qty: Number,
        },
        methods: {
          openAnim() {
            gsap.fromTo(
                this.$refs.cartItem,
                { transform: 'translatex(200px)'},
                { transform: 'translatex(0px)', duration: 0.3, ease: "power1.out", delay: 0.1 * this.index }
            );
          },
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
        },
        mounted() {
            //this.openAnim()
        },
        watch: {
            qty() {
            gsap.fromTo(
                this.$refs.qtyText,
                { scale: 1.5, opacity: 0 },
                { scale: 1, opacity: 1, duration: 0.3, ease: "power1.out" }
            );
            }
        }
    }
</script>

<style lang="sass" scoped>
#HeaderCartItemAlt
    display: flex
    width: auto
    height: 100px
    border-radius: 15px
    display: flex
    align-items: center
    padding: 10px 10px
    justify-content: space-between
    .left-block
        height: 100%
        display: flex
        gap: 10px
        img
            height: 100%
            border-radius: 15px
        .info-block
            height: 100%
            padding: 0px
            display: flex
            flex-direction: column
            gap: 7px
            justify-content: center
            .title
                letter-spacing: 1px
            .price
                display: flex
                gap: 5px
                font-size: 14px
                align-items: center
                span
                    font-weight: bold
    .right-block
        display: flex
        flex-direction: column
        justify-content: space-between
        height: 100%
        align-items: end
        padding: 2px
        .remove-item
            width: 23px
            height: 23px
            border-radius: 5px
            display: flex
            align-items: center
            justify-content: center
            position: relative
            cursor: pointer
            &:hover
                .cross-line
                    &:nth-of-type(1)
                        height: 2px
                        transform: rotate(45deg)
                    &:nth-of-type(2)
                        transform: rotate(135deg)
                        height: 2px
            .cross-line
                position: absolute
                width: 13px
                height: 1px
                left: 4px
                transition: all 0.2s ease
                &:nth-of-type(1)
                    transform: rotate(-45deg)
                &:nth-of-type(2)
                    transform: rotate(45deg)
                    
        .counter
            display: flex
            gap: 3px
            p
                display: flex
                justify-content: center
                align-items: center
                text-align: center
                height: 23px
                width: 23px
                border-radius: 5px
                &:nth-of-type(2)
                    font-weight: bold
                    font-size: 13px
                .minus, .plus
                    cursor: pointer
                    position: relative
                    bottom: 1px
</style>