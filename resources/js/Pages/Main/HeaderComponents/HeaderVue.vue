<template>
    <div id="HeaderVue"
    v-if="true"
    ref="header"
    :style="{
        position: this.$page.url == '/' ? 'sticky' : 'sticky',
        backgroundColor: this.$page.url == '/' ? styles.dark_1 : styles.dark_1,
        borderBottom: '1px solid' + styles.dark_4,
    }"
    :class="isMounted && false ?  {'header-slam': windowTop > 10, 'header-unslam': windowTop <= 10} : ''"
    >
        <div class="header-block" ref="headerBlock" :style="{maxWidth : '1400px'}">
            <div class="header-left header-item">
                <Link
                :style="{color: styles.contrast_1}"
                class="header-logo" :href="'/'"><span ref="letter" v-for="letter in logo">{{ letter }}</span> </Link>
                
            </div>
            <div class="header-search header-item">
                <input ref='searchInput' id="header-search-input"
                :style="{
                    color: search ? styles.white : styles.white,
                    padding: search ? '10px 15px' : '10px 15px',
                    borderColor: search ? styles.dark_4 : styles.dark_4,
                    backgroundColor : searchColor && !search ? styles.dark_2 : 'inherit',
                    // opacity: search ? 1 : 0.8,
                }"
                @focus="searchColor = true"
                @blur="searchColor = false"
                @input="searchChange()" v-model="search" type="text">
                
                <HeaderSearch
                    ref="searchVue"
                    v-if="search"
                    :search="search"
                    :windowTop="windowTop"
                ></HeaderSearch>
            </div>
            <div
            @click="openCart"
            class="header-right header-item">
                <div v-if="totalQty > 0" class="totalQty">{{ totalQty }}</div>
                <img src="/public/assets/cart/bag2.png" alt="">
            </div>
            <Transition name="cart-anim">
                <HeaderCart
                    v-if="cart && false"
                    @updateEvent="cart = $event"
                >
                </HeaderCart>
            </Transition>
            <HeaderCartAlt 
            :qty="totalQty"
            ref="cart" @updateEvent="cart = $event"
            v-if="cart">
            </HeaderCartAlt>
        </div>
    </div>
</template>

<script>
import { nextTick } from "vue";
import { Link, router } from '@inertiajs/vue3';
import { gsap } from "gsap";
import HeaderCartAlt from './HeaderCartAlt.vue';
import HeaderSearch from './HeaderSearch.vue';

    export default {
        data() {
            return {
                cart: false,
                styles : this.$page.props.styles,
                windowTop: null,
                isMounted: false,
                search: null,
                searchColor: false,
                logo: 'Valkon'
            }
        },
        props: {
            categories: Array,
            productsBySearch: Array,
        },
        components: {
            Link,
            HeaderSearch,
            HeaderCartAlt,
        },
        watch: {

            // search(newVal) {
            //     if (newVal) {
            //         this.searchBool = true;
            //     } else {
            //         setTimeout(() => {
            //             this.searchBool = false;
            //         }, 500);
            //     }
            // }
        },
        methods: {
            initHeader() {
                let head = this.$refs.header
                let letter = this.$refs.letter
                let letters = Array.from(this.$refs.letter)
                let tl = gsap.timeline()
                tl.set(head, {
                    top: -200
                })
                tl.to(head,{
                    top: 0,
                    duration: 0.5
                })
                tl.fromTo(letters, 
                    { 
                        opacity: 0,
                        y: 50,
                        scale: 0.5 
                    },
                    { 
                        opacity: 1,
                        y: 0,
                        scale: 1,
                        duration: 0.4,
                        stagger: 0.03,
                        ease: "power3.out"
                    }
                )
                // tl.fromTo(this.$refs.searchInput,{
                //     width: 0
                // }, {
                //     width: 400
                // })
            },
            openCart() {
                if (this.cart) {
                    if (this.$refs.cart && typeof this.$refs.cart.animateClose === 'function') {
                        this.$refs.cart.animateClose();
                    }
                    setTimeout(() => {
                        this.cart = !this.cart;
                    }, 300); 
                } else {
                        this.cart = !this.cart;
                }
             },
            onScroll(e) {
                this.windowTop = window.scrollY
            },
            searchChange() {
                    router.visit('/search-result', {
                    method: 'get',
                    data: {search : this.search},
                    preserveState: true,
                    preserveScroll: true,
                    //only: ['searchProducts'],
                    //except: ['products']
                    // queryStringArrayFormat: 'brackets',
                    // onSuccess: () => {
                    //     this.searchResults = this.$page.props.searchProducts || [];
                    // },
                    })
            },  
            handleClickOutside(event) {
                if (this.$refs.searchVue && !this.$refs.searchVue.$el.contains(event.target) && event.target != this.$refs.searchInput) {
                    this.search = null;
                }
            },
        },
        computed: {  
            searchLive() {
                if (!this.search) {
                    setTimeout(()=>{
                          return false   
                    }, 2000)
                } else 
                return true
            },
            totalQty() {
                let total = 0
                if (Array.isArray(this.$page.props.cart)) {
                    this.$page.props.cart.forEach(item => {
                        total += item.qty;
                });
                }
                return total
            }, 
            inputStyle() {
                return {
                    backgroundColor: `rgba(${parseInt(this.styles.dark_1.slice(1, 3), 16)}, ${parseInt(this.styles.dark_1.slice(3, 5), 16)}, ${parseInt(this.styles.dark_1.slice(5, 7), 16)}, 0.5)`,
                };
            },
        },
        mounted() {
            this.onScroll()
            this.isMounted = true;
            window.addEventListener("scroll", this.onScroll);
            window.addEventListener('click', this.handleClickOutside);
            //Init
            // if(this.$page.url == '/') {
            //     this.initHeader
            // }
            this.initHeader()
        }, 
        beforeDestroy() {
            window.removeEventListener("scroll", this.onScroll);
            window.removeEventListener('click', this.handleClickOutside);
        },
    }
</script>

<style lang="sass" scoped>
#HeaderVue 
    height: 75px
    display: flex
    justify-content: center
    width: 100%
    position: sticky
    top: 0px
    z-index: 3
    border-radius: 0px 0px 0px 0px
    padding: 0px 30px
    .header-block
        display: grid
        grid-template-columns: 1fr 70% 1fr
        justify-content: space-between
        position: relative
        width: 100%
        align-items: center
        .header-item
            display: flex
            align-items: center
            justify-content: center
            position: relative
        .header-left
            display: flex
            justify-content: start
            cursor: pointer
            height: 100%
            position: relative
            .header-logo
                font-weight: bold
                font-size: 25px 
                letter-spacing: 3px
        .header-right
            cursor: pointer
            position: relative
            user-select: none
            display: flex
            justify-content: end
            .totalQty
                position: absolute
                right: 0
                bottom: 0
                font-size: 9px
                background: #ff686b
                padding: 2px
                font-weight: bold
                color: black
                border-radius: 50%
                width: 12px
                height: 12px
                display: flex
                align-items: center
                text-align: center
                justify-content: center
            img
                width: 26px
                animation: none !important
        .header-search
            input
                transition: all 0.3s ease
                background-color: inherit
                padding: 5px
                z-index: 3
                width: 400px
                border-width: 1px 
                border-style: solid
                border-radius: 20px

.cart-anim-enter-active, .cart-anim-leave-active
    transition: all 0.3s ease 
.cart-anim-enter-from, .cart-anim-leave-to 
    transform: translateX(400px)
.cart-anim-enter-to, .cart-anim-leave-from
    transform: translateX(0px) 

    
</style>