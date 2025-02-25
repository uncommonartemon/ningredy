<template>
    <div
    id="HeaderCartAlt">
        <div ref="cartHeader"
        :style="{backgroundColor: styles.dark_2, border: '2px solid ' + styles.dark_4}"
        class="header-cart">
            Cart: 
            <b ref="qty" :style="{color : styles.contrast_1}">{{ qty }}</b> items totaling - <b :style="{color : styles.contrast_1}">
                <span ref="priceLetters" v-for="(letter,index) in priceCart.toFixed(0).toString()" :key="index">{{ letter }}</span>Kč
            
            </b>
            <s v-if="priceCart < discountCart" :style="{color : priceCart < discountCart ? 'grey' : none}">{{ discountCart }} Kč</s>
        </div>
        <div
        v-show="qty > 0"
        :style="{backgroundColor : styles.dark_2, border: '2px solid ' + styles.dark_4}"
        ref="cartItems"
        class="headerCartItems">
           <TransitionGroup appear
                name="item-anim" :key="index"
            >  <HeaderCartItemAlt
                :style="{ transitionDelay: 0.2 + (0.05 * index) + 's' }"
                v-for="(item, index) in cart"
                :key="item.product.id"
                :item="item.product"
                :index="index"
                :qty="item.qty"
                >
                </HeaderCartItemAlt>
           </TransitionGroup>
        </div>
        <div
        v-show="qty > 0"
        ref="cartButton"
        class="checkout-button">
            <button
            @mouseover="hoverButton = true"
            @mouseleave="hoverButton = false"
            :style="{'--hover-bg': styles.dark_4,backgroundColor : hoverButton ? styles.dark_4 : styles.dark_2, color: styles.white, border: '2px solid ' + styles.dark_4 }"
            >
                checkout
            </button>
        </div>
    </div>
</template>

<script>
import { gsap } from "gsap";
import HeaderCartItemAlt from './HeaderCartItemAlt.vue'
import { TextPlugin } from 'gsap/TextPlugin'
gsap.registerPlugin(TextPlugin)
    export default {
        data() {
            return {
                hoverButton: false,
                styles: this.$page.props.styles,
                products: this.$page.props.cart,
                calm: true,
            }
        },
        props: {
            qty: Number,
        },
        components: {
            HeaderCartItemAlt
        },
        computed: {
            cart() {
                return this.$page.props.cart;
            },
            priceCart() {
                return this.$page.props.cart.reduce((acc, item) => acc + ((item.product.discount ? item.product.discount : item.product.price) * item.qty), 0)
            },
            discountCart() {
                return this.$page.props.cart.reduce((acc, item) => acc + (item.product.price * item.qty), 0)
            }
        },
        methods: {
            openItems() {
            this.calm = false;
            const tl = gsap.timeline({
                onComplete: () => {
                    this.calm = true
                }
            });

            tl.fromTo(
                this.$refs.cartItems,
                { height: '0px', opacity: 0 },
                { height: 'auto', opacity: 1, duration: 0.3, ease: "power1.in" }
            )
            .fromTo(
                this.$refs.cartHeader, 
                { x: 300, opacity: 0 },
                { x: 0, opacity: 1, duration: 0.2, ease: "power1.out" },
                0.2 // задержка начала этой анимации относительно начала timeline
            )
            .fromTo(
                this.$refs.cartButton, 
                { x: 300, opacity: 0 },
                { x: 0, opacity: 1, duration: 0.2, ease: "power1.out" },
                0.3 // задержка начала этой анимации относительно начала timeline
            );
            },
            animHeaderLetters() {
                gsap.fromTo(
                    this.$refs.priceLetters, 
                    {opacity: 0, scale: 1.5},
                    {opacity: 1, scale: 1, duration: 0.3, stagger: 0.05, ease: "power1.out" } 
                ),
                gsap.fromTo(
                    this.$refs.qty, 
                    {scale: 1.5},
                    {scale: 1, duration: 0.3, ease: "power1.out" }
                )
            },
            animateClose() {
                
                gsap.fromTo(
                    [this.$refs.cartHeader], 
                    {x : 0, opacity : 1},
                    {x : 300, opacity : 0 , duration: 0.2 , ease: "power1.out" },
                ), 
                gsap.fromTo(
                    this.$refs.cartItems,
                    { x: 0, opacity: 1 },
                    { x: 300, opacity: 0, duration: 0.2, delay: 0.05, ease: "power1.in" }
                );
                gsap.fromTo(
                    [this.$refs.cartButton], 
                    {x : 0, opacity : 1},
                    {x : 300, opacity : 0, duration: 0.2 ,delay: 0.1,ease: "power1.out" },
                )
            }

        },
        mounted() {
            this.openItems();
        },
    }
</script>

<style lang="sass" scoped>
#HeaderCartAlt
    position: fixed
    width: 400px
    right: 25px
    display: flex
    flex-direction: column
    top: 100px
    gap: 10px
    user-select: none
    .header-cart
        padding: 25px
        border-radius: 20px
    .checkout-button
        width: 100%
        button
            width: 100%
            padding: 20px
            border-radius: 20px
            letter-spacing: 1px
            text-transform: uppercase
            cursor: pointer
            transition: all 0.3s ease
                
    .headerCartItems
        display: flex
        display: grid
        grid-auto-flow: row
        width: 100% 
        gap: 15px
        padding: 20px 15px
        border-radius: 20px
    

.item-anim-move,
.item-anim-enter-active,
.item-anim-leave-active
    transition: all 0.3s ease 
.item-anim-enter-from,
.item-anim-leave-to
    opacity: 0
    transform: translateX(300px)
.item-anim-leave-active
    position: absolute
</style>