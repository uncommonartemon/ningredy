<template>
    <section id="HomePage">
        <div
        :style="{
            maxWidth : styles.maxWidth,
            color: styles.white,
            }"
        class="content">
            <div class="left-grid">
                <div :style="{
                    backgroundColor: styles.dark_1
                }" v-if="categories.length"
                class="categories-items">
                    <Link
                    v-for="(category, index) in categories" :key="index"
                    :href="'/category/'+category.id" 
                    @mouseover="category_hover_name = category.name_en"
                    @mouseleave="category_hover_name = null"
                    class="category-item">
                        <div :style="{
                            color: styles.white,
                            backgroundColor: category_hover_name == category.name_en ? styles.dark_4 : styles.dark_2,
                            border: category_hover_name == category.name_en ? `1px solid ${styles.dark_1}` : `1px solid ${styles.dark_4}`,
                        }"
                        ref="category"
                        class="link-parent">
                            {{ category.name_en }}
                        </div>
                    </Link>
                </div>
                <div :style="{
                    backgroundColor: styles.dark_1
                }" class="categories-items" v-if="!categories.length">
                        <div
                        @mouseover="category_hover_name = category_fake"
                        @mouseleave="category_hover_name = null"
                        v-for="category_fake in 6" :index="category_fake" 
                        class="category-item">
                            <div :style="{
                            color: styles.white,
                            backgroundColor: category_hover_name == category_fake ? styles.dark_4 : styles.dark_2,
                            border: category_hover_name == category_fake ? `1px solid ${styles.dark_1}` : `1px solid ${styles.dark_4}`,
                        }"
                        ref="category"
                        class="link-parent">
                            category
                        </div>
                        </div>
                </div>
            </div>
            <div class="right-grid">
                <div ref="slider" class="slider-parent">
                    <SmallSlider v-if="settings.show.home_slider"
                    :styles="styles" :slider="slider"
                    ></SmallSlider>
                </div>
                <div class="home-block"
                v-for="(block, block_key, index) in contentBlocks" :key="index">
                    <h3 class="block-key">
                        {{ block_key }}
                    </h3>
                    <swiper-container
                    slides-per-view='auto'
                    :free-mode="true"
                    :space-between="10"
                    class="home-block-items">
                        <template v-if="block.length">
                            <swiper-slide class="slide" :ref="`slide_${index}`"
                            v-for="(product, index2) in block" :key="index2">
                                    <UniversalProductCard @hover-product="(data)=>product_hover_attributes = data"
                                    :product="product" :index="index2" :styles="this.$page.props.styles" :settings="settings"
                                    ></UniversalProductCard>
                            </swiper-slide>
                        </template>
                       <template v-if="!block.length">
                            <swiper-slide v-for="fake in 8" :key="fake" :ref="`slide_${fake}`" class="slide">
                                <UniversalProductCard :product="fake_product" :index="fake" :styles="styles" :settings="settings">
                                </UniversalProductCard> 
                            </swiper-slide>
                        </template>
                    </swiper-container>
                </div>
                <div class="attributes-parent">
                    <AttributeBlock :product_hover_attributes="product_hover_attributes" :styles="styles" :attributes="attributes"></AttributeBlock>
                </div>
            </div>
        </div>
        <Footer>
            
        </Footer>
    </section>
</template>

<script>
import {defineAsyncComponent} from 'vue'
import { Link } from '@inertiajs/vue3'
import HeaderVue from './HeaderComponents/HeaderVue.vue';
import LoadingVue from './Atoms/LoadingVue.vue';
import SliderHome from './Home/SliderHome.vue';
import Categories from './Home/Categories.vue';
import AttributeBlock from './Home/AttributeBlock.vue';
import SmallSlider from './Home/SmallSlider.vue';
import Footer from './Footer/Footer.vue';

import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'
import UniversalProductCard from './Atoms/UniversalProductCard.vue';
import { register } from 'swiper/element/bundle';

register();
gsap.registerPlugin(ScrollTrigger)

export default {
    data() {
        return {
            styles: this.$page.props.styles,
            settings: this.$page.props.settings,
            isAnimating: false,
            scroll: 0,
            crumb: true,
            paddingBlock: '20px',
            borderRadius: '20px', 
            category_hover_name: null,
            product_hover_attributes: null,
            category_from: {
                x: -500,
                y: 150,
                rotate: 90
            },
            product_from: {
                x: 1000,
            },
            fake_product: {
                title: {
                    en: 'title'
                },
                discount: 'discount',
                price: 'price'
            }
        }
    },
    layout: defineAsyncComponent(() => import('../MainPage.vue')),
    components: {
        Link,
        HeaderVue,
        LoadingVue,
        SliderHome,
        Categories,
        AttributeBlock, 
        SmallSlider,
        UniversalProductCard,
        Footer,
    },
    props: {
        categories: Array,
        slider: Array,
        release: Array,
        showCase: Array,
        attributes: Array,
        discounts: Array,
    }, 
    computed: {
        contentBlocks() {
           return {
            ...(this.settings.show.show_case ? {'Show Case' : this.showCase} : false ),
            ...( this.settings.show.best_discounts ? {'Best Discounts' : this.discounts } : false),
            ...(this.settings.show.new_releases ? {'New Releases' : this.release}: false),
           }
        }
    },
    methods: {
        handleWheel(e) {
            if (this.isAnimating) return;
            if (e.deltaY > 0) {
                this.scroll -= 1
                //this.triggerAnimationDown();
            }
            else if (e.deltaY < 0) {
                this.scroll += 1
                //this.triggerAnimationUp();
            }
        },
        initCategoryAnim() {
            const tl = gsap.timeline()
            const category = this.$refs.category
            tl.fromTo(category, {
                x: this.category_from.x,
                y: this.category_from.y,
                rotate: this.category_from.rotate,
                opacity: 0
            } , {
                y: 0,
                x: 0,
                rotate: 0,
                ease: 'power2.out',
                stagger: 0.05,
                opacity: 1
            }) 
        },
        initProductAnim() {
            let delay = 0
            if (this.$refs.slider) {
                gsap.fromTo(this.$refs.slider, {
                    x: 300,
                    opacity: 0
                },{
                    opacity: 1,
                    x: 0,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: this.$refs.slider,
                        toggleActions: 'play reverse restart reverse'
                    }
                }, delay += 0.1)
            }
            const long = Object.keys(this.contentBlocks) > 0 ? Object.keys(this.contentBlocks) : 9
            for (let index = 0; index < long; index++) {
                const product = this.$refs[`slide_${index}`]
                gsap.fromTo(product, {
                    opacity: 0,
                    x: 500,
                }, {
                    opacity: 1,
                    stagger: 0.02,
                    x: 0,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: product,
                        toggleActions: 'play reverse restart reverse'
                    }
                }, delay += 0.1)
                // tl.fromTo(product, {
                //     opacity: 0,
                //     x: 1000,
                // }, {
                //     opacity: 1,
                //     stagger: 0.02,
                //     x: 0,
                //     ease: 'power2.out',
                //     scrollTrigger: {
                //         trigger: product,
                //         //toggleActions: "play reverse reset reset",
                //     }
                // }, delay += 0.1)
            }
        }
    },
    mounted() {
        //document.body.style.overflow = 'hidden'
        register();
        window.addEventListener('wheel', this.handleWheel, {passive: false})
        this.initCategoryAnim()
        this.initProductAnim()
    }
}
</script>

<style lang="sass" scoped>
#HomePage
    display: flex
    flex-direction: column
    height: 100%
    overflow-y: none
    gap: 50px
    padding: 25px
    align-items: center
    .content
        display: grid
        grid-template-columns: 200px minmax(500px , 1200px)
        max-width: 1400px
        flex-direction: column
        width: 100%
        gap: 20px
        @media screen and (max-width: 768px)
            grid-template-columns: 1fr
        .left-grid
            display: flex
            width: 100%
            gap: 25px
            position: sticky
            flex-direction: column
            .categories-items
                border-radius: 20px
                display: flex
                flex-direction: column
                width: 100%
                gap: 10px
                transition: all 0.2s ease
                @media screen and (max-width: 768px)
                    display: grid
                    grid-template-columns: 1fr 1fr
                .category-item
                    width: 100%
                    .link-parent
                        width: 100%
                        height: 100%
                        padding: 15px 30px
                        border-radius: 15px
                        display: flex
                        cursor: pointer
                        color: black
                        font-weight: bold
                        text-transform: capitalize
                        text-align: center
                        justify-content: start
                        transition: padding 0.2s ease
                        padding-left: 15px
                        &:hover
                            padding-left: 20%
        .right-grid
            display: flex
            flex-direction: column
            width: 100%
            gap: 10px
            overflow: hidden
            .home-block
                width: 100%
                display: flex
                flex-direction: column
                border-radius: 20px
                gap: 10px
                .home-block-items
                    display: flex
                    gap: 20px
                    cursor: grab
                    &:active
                        cursor: grabbing
                    //overflow-x: scroll
                    .slide
                        width: auto
            .attributes-parent
                height: 500px
                        
                
                    
</style>