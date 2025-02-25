<template>
    <div
    :style="{backgroundColor : styles.darkBlue, height : '100vh'}"
    :class="{'cart-anim-up' : windowTop > 10, 'cart-anim-down' : windowTop <= 10} "
    id="HeaderCart">
        <div
        :style="{
            height: '75px'
        }"
        :class="true ? false : {'header-slam' : windowTop > 10, 'header-unslam' : windowTop <= 10}"
        class="cart-head">
            <p> Cart (<span>{{ totalQty }}</span>) :</p>
            <div @click="handleClose()" class="close-cart">
                <div :class="{'cross-anim1' : closeCross}"
                :style="{backgroundColor: styles.light}" class="close-cross1"></div>
                <div 
                :class="{'cross-anim2' : closeCross}"
                 :style="{backgroundColor: styles.light}"  class="close-cross2"></div>
            </div>
        </div>
        <div 
         :class="{'content-padding-' : windowTop > 10, 'content-padding' : windowTop <= 10}"
         class="cart-content">
            <div class="content-head">
                <div class="total">{{ totalQty }} products for {{ totalPrice }} Kč</div>
                <button
                :style="{backgroundColor : styles.darkBlue2, color : styles.light}"
                @click="clearCart">clear</button>
            </div>
            <div class="cart-items">
                <TransitionGroup 
                    name="cart-items-anim" 
                    >
                        <HeaderCartItem 
                            v-for="(item, index) in cart"
                            :key="item.product.id"
                            :item="item.product"
                            :qty="item.qty"
                            >
                        </HeaderCartItem>
                </TransitionGroup>
                <div class="cart-bottom">
                    <button
                    :style="{backgroundColor : styles.darkBlue, color: styles.light}"
                    class="checkout">
                        checkout
                    </button>
                </div>
            </div>
        </div>
        <div class="cart-bottom">
            <button
            :style="{backgroundColor : styles.darkBlue, color: styles.light}"
            class="checkout">
                checkout
            </button>
        </div>
    </div>
</template>

<script>
import { router, Link } from '@inertiajs/vue3';
import HeaderCartItem from './HeaderCartItem.vue'
export default {
    components : {
        HeaderCartItem
    },
    data() {
        return {
            styles: this.$page.props.styles,
            closeCross: false,
            windowTop: null,
            firstRender: false,
        };
    },
    methods: {
        clearCart() {
            router.visit('/cart/clear', {
                method: 'delete',
                onSuccess: () => {
                    this.cart = [];
    
                },
                preserveState: true,
                preserveScroll: true, 
            });
        },
        total_products() {
            // for (let item in cart) {
            //     this.totalQuantity += cart[item].qty
            //     this.totalPrice += cart[item].product.price * cart[item].qty
            // }
        },
  
        handleClose() {
            this.$emit('updateEvent', false);
            this.closeCross = true
        },
        onScroll(e) {
            this.windowTop = window.top.scrollY 
        }
    },
    computed: {
        cart() {
            return this.$page.props.cart;
        },
        totalQty() {
            let qtyProducts = 0
            for (let item in this.$page.props.cart) {
                qtyProducts += this.cart[item].qty
            }
            return qtyProducts
        },
        totalPrice() {
            let price = 0
            try {
                for (let item of this.$page.props.cart) {
                item.product.discount ? price += item.product.discount * item.qty : price += item.product.price * item.qty
            }
            } catch (error) {
             console.log(error);   
            }
            return price
        }
    },
    mounted() {
        this.onScroll()
        window.addEventListener("scroll", this.onScroll);
        console.log(this.$page.props.cart);
    }, 
    updated() {

    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.onScroll)
    },
};
</script>

<style lang="sass" scoped>
#HeaderCart
    width: 400px
    position: fixed
    right: 0px
    top: 0px
    height: 100vh
    display: flex
    flex-direction: column
    border-left: 1px solid grey
    .cart-head 
        display: flex
        justify-content: space-between
        border-bottom: 1px solid grey
        align-items: center
        padding: 0px 20px
        p
            font-size: 20px
        .close-cart
            width: 35px
            height: 35px
            position: relative
            cursor: pointer
            .close-cross1, .close-cross2
                height: 2px
                position: absolute
                background-color: grey
                width: 20px
                top: 18px
                right: 4px
            .close-cross1
                transform: rotate(45deg)
            .close-cross2
                transform: rotate(-45deg)
    .cart-content  
        display: flex
        flex-direction: column
        gap: 20px
        padding: 30px 20px
        top: 75px
        overflow-y: auto
        width: 100%
        .content-head
            width: 100%
            display: flex
            align-items: center
            justify-content: space-between
            padding-left: 5px
            .total
                display: flex
                color: grey
                font-size: 13px
            button
                outline: none
                border: 0px 
                height: 23px
                display: flex
                align-items: center
                cursor: pointer
                justify-content: center
                text-align: center
                border-radius: 10px
                padding: 15px 15px
                font-weight: bold
        .cart-items 
            flex-direction: column
            gap: 15px
            padding-bottom: 100px
            .cart-bottom
                position: absolute
                display: flex
                align-items: center
                bottom: -80px
                .checkout
                    width: 100%
                    height: 35px
                    border-radius: 15px
                    border: 1px solid grey
                    outline: none
                    display: none

    .cart-bottom
        display: flex
        width: 100%
        justify-content: center
        align-items: center
        .checkout
            width: 100%
            height: 50px
            border: 0px
            border-top: 1px solid grey
            border-bottom: 1px solid grey
            border-right: 1px solid grey
            position: absolute
            bottom: 0px
            cursor: pointer
            font-size: 13px
            text-transform: uppercase
            font-weight: bold

.cross-anim1
    animation: cross-anim1 0.3s ease forwards

.cross-anim2
    animation: cross-anim2 0.3s ease forwards

@keyframes cross-anim1
    0%
        transform: rotate(45deg)
    100%
        transform: rotate(90deg)
@keyframes cross-anim2
    0%
        transform: rotate(-45deg)
    100%
        transform: rotate(-90deg)

.header-slam
    animation: slam 0.3s ease forwards

.header-unslam
    animation: unslam 0.3s ease forwards 
        
@keyframes unslam
    0%
        height: 70px
        margin-bottom: 1px
        min-height: 70px
        //border-radius: 0px 0px 20px 20px
        border-width: 1px
    100%    
        height: 90px
        min-height: 90px
        border-radius: 0px 0px 0px 0px

@keyframes slam
    0%
       height: 90px
       min-height: 90px
       border-radius: 0px 0px 0px 0px
       border-width: 1px
    100%
        height: 70px 
        min-height: 70px
        //border-radius: 0px 0px 20px 20px
        border-width: 1px

.content-padding-
    animation: padding- 0.3s ease forwards
.content-padding
    animation: padding 0.3s ease forwards
@keyframes padding-
    0%
        padding: 30px 20px
        gap: 20px
        //border-radius: 0px
        //border: 0px solid grey
        //top: 60px 
        //transform: translateX(0px)
    50%
        //border: 0px solid grey
    100%
        padding: 20px 20px 
        gap: 15px
        //border-radius: 30px
        //top: 80px
        //border: 1px solid grey
        //transform: translateX(-20px)
@keyframes padding 
    0%
        padding: 20px 20px
        //top: 80px
        gap: 15px
        // border-radius: 30px
        //border: 1px solid grey
        
    50%
        //border: 0px solid grey
    100%
        padding: 30px 20px
        //top: 90px
        gap: 20px
        // border-radius: 0px

.fade-right-up
    animation: fade-right-up 0.3s ease forwards 0.1s
.fade-right-down
    animation: fade-right-down 0.3s ease forwards
.cart-items-anim-move
    transition: all 0.3s ease 
.cart-items-anim-enter-active, .cart-items-anim-leave-active
    transition: all 0.3s ease
    overflow: hidden
.cart-items-anim-enter-from
    opacity: 0
    transform: translateY(50px)
.cart-items-anim-enter-to
    opacity: 1
    transform: translateY(0px)
.cart-items-anim-leave-from
    opacity: 1
    transform: translateX(0px)
.cart-items-anim-leave-to
    opacity: 0
    transform: translateX(150px)
.cart-items-anim-leave-active
    position: absolute

</style>