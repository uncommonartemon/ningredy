<template>
    <div
    @mouseenter="handleMouseEnter"
    @mouseleave="removeMouse"
    :style="elemtStyles"
    id="header-search-item">
        <div :style="{
            gridArea: areas
        }" class="main">
            <div class="img">
                <img :src="`/storage/${product.images[0]}`" alt="">
            </div>
            <div v-if="true" class="product-info">
                <div class="product-title">{{ product.title.en }}</div>
                <!-- <div class="product-brand">{{ product.category.name.en }}</div> -->
                <div class="product-price">
                    <span :style="{backgroundColor : styles.contrast_1, color : styles.black}" v-if="product.discount > 0" class="discount">{{ product.discount }}Kč</span>
                    <span :style="{textDecoration: product.discount ? 'line-through' : none}" class="price">{{ product.price }}Kč</span>
                </div>
            </div>
        </div>
        <div v-if="showAdded" class="added"
        :style="{color : styles.white}">
            <div
            :style="{backgroundColor: styles.dark_2}"
            class="product-attributes">
                <div class="attribute product-category tag-block">
                    <span  :style="{}" class="tag"><span ref="letter" v-for="letter in textCategory">{{ letter }}</span></span>
                </div>
                <div class="attribute product-brand tag-block">
                    <span  :style="{}" class="tag"><span ref="letter" v-for="letter in textBrand">{{ letter }}</span></span>

                </div>
                <div v-for="(attribute, index) in product.attributes" :key="index" class="attribute tag-block">
                    <div v-for="(value, key) in attribute" :key="key">
                        <span 
                        class="tag key"><span ref="letter" v-for="letter in key">{{ letter }}</span></span><span ref="letter">:</span> <span
                        :style="{}"
                        class="tag value"><span ref="letter" v-for="letter in value">{{ letter }}</span></span>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>

<script>
import gsap from 'gsap';
    export default {
        data() {
            return {
                hover: false,
                showAdded: false,
                showAddedDelay: 200,
                styles: this.$page.props.styles,
                side: 90,
                textCategory: `Category: ${this.product.category.name.en}`,
                textBrand: `Brand: ${this.product.brand}` ,
                stagger: 0,
            }
        },
        props: {
            product: Object,
        },
        mounted() {
            // console.log(typeof(this.product.attributes));
        },
        computed: {
            elemtStyles() {
                return {
                    border: '1px solid ' + this.styles.dark_4,
                    // backgroundImage: `linear-gradient(${this.side}deg, ${this.styles.dark_1} 50%, ${this.styles.dark_2} 50%)`,
                    backgroundImage: this.styles.dark_2,
                    backgroundSize: '200% 200%',    
                    backgroundPosition: this.hover ? '0% 0%' : '100% 100%'
                }
            },

        },
        methods: {
            letterAnim() {
                //console.log(this.$refs);
                const tl = gsap.timeline()
                const ref = this.$refs.letter
                tl.from(
                    ref, {
                        opacity: 0, stagger: 0.005
                    }
                )
                tl.to(
                    ref,{
                        opacity: 1, duration: 2,
                        ease: 'power2.out',
                        //stagger: 0.03
                    }
                )
            },
            handleMouseEnter(event) {
                setTimeout(() => {
                    this.hover ? this.showAdded = true : true;
                    this.$nextTick(() => {
                        this.showAdded ? this.letterAnim() : false;
                    });
                }, this.showAddedDelay);
                this.hover = true;
            },
            removeMouse() {
                // return
                this.showAdded = false
                this.hover = false
            },
        },
    }
</script>

<style lang="sass" scoped>
#header-search-item
    width: 100%
    display: flex
    padding: 15px
    border-radius: 20px
    width: 150px
    max-height: 194px
    min-width: 150px
    // height: 200px
    transition: all 0.3s ease
    cursor: pointer
    justify-content: space-between
    align-items: center
    &:hover
        transform: scale(1.1)
        width: 300px
        margin: 0 10px 0 10px
        .main   
            width: 48%
            justify-content: start
            .img
                width: 100%
                opacity: 1
            .product-info
                width: 100%
    .border
        height: 100%
        width: 1px
    .main
        display: flex
        flex-direction: column
        gap: 15px
        height: 100%
        width: 100%
        transition: width 0.3s ease 0.1s
        .img
            display: flex
            width: 100%
            align-items: center
            justify-content: center
            height: 60%
            opacity: 1
            //transition: width 0.3s ease 0.2s
            img
                border-radius: 10px
                max-height: 100px
                max-width: 100px
                width: auto
                object-fit: contain

        .product-info
            display: flex
            height: 40%
            flex-direction: column
            gap: 10px
            text-align: center
            opacity: 1
            font-size: 13px
            //transition: width 0.3s ease 
            width: 100%
            .product-title, .product-price, .product-category
                font-size: 14px
            .product-title
                text-wrap: nowrap
                text-align: center
                font-size: 13px
            .product-price
                display: flex
                align-items: center
                justify-content: center
                font-size: 12px
                gap: 5px 
                .discount
                    padding: 3px 6px
                    border-radius: 5px
                    font-weight: bold
    .added
        width: 52%
        font-size: 12px
        display: flex
        flex-direction: column
        //justify-content: center
        height: 100%
        .key span:first-child 
            text-transform: uppercase
        .tag
            border-radius: 3px
            padding: 2px
            margin: 2px 0px
        .product-attributes
            justify-content: start
            border-radius: 10px
            padding: 3px
            display: flex
            flex-direction: column
            gap: 4px
            overflow-y: auto
            overflow-block: visible
            flex: 1
            .tag-block
                border-radius: 3px
            .attribute
                .key
                    &::first-letter
                        text-transform: uppercase
</style>