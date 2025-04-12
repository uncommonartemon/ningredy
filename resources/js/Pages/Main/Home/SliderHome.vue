<template>
    <div 
    v-if="slider.length"
    id="slider-home">
        <swiper-container
        :parallax="true"
        ref="bgSwiper" class="background-slider">
            <swiper-slide v-for="slide in SliderByOrder"
            :style="{backgroundImage: `url('/storage/${slide.image}')`}"
            class="background-slide">

            </swiper-slide>
        </swiper-container>
        <div class="slider-block">
            <div class="left-side">
                <div ref="first_text" class="first-text">
                    {{ scroll }}
                    {{ sliderText }}
                </div>
                <div class="second-text">
                
                </div>
            </div>
            <div v-if="true" class="center">
                <swiper-container class="slider"
                    ref="swiper"
                    @swiper-slidechange="onSlideChange"
                    :slides-per-view="1"
                    :space-between="200"
                    :loop="true"
                    slideActiveClass="active-main-slide"
                    >
                    <swiper-slide 
                    class="slide" v-for="(slide, index) in SliderByOrder" :key="index">
                        <img :src="'/storage/' + slide.image" />
                    </swiper-slide>
                </swiper-container>
            </div>
            <div class="right-side">
                <div class="buttons-control">
                    <div ref="button_prev" @mouseenter="hoverButtonPrev = true" @mouseleave="hoverButtonPrev = false" 
                    :style="{backgroundColor: hoverButtonPrev ? styles.contrast_2 : none}"
                    @click="prevSlide()" class="prev-slide">
                        prev.
                    </div>
                    <div ref="button_next" @mouseenter="hoverButtonNext = true" @mouseleave="hoverButtonNext = false"  
                    :style="{backgroundColor: hoverButtonNext ? styles.contrast_2 : none}"
                    @click="nextSlide()" class="next-slide">
                        next
                    </div>
                </div>
            </div>
            <div class="nav-slider">
                
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { register } from 'swiper/element/bundle';
import { gsap } from "gsap";
register();
export default {
    data() {
        return {
            actualSlide: 0,
            thumbsSwiper: null,
            styles: this.$page.props.styles,
            hoverButtonPrev: false,
            hoverButtonNext: false,
            sliderText: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        }
    },
    props: {
        slider: Array,
        scroll: Number,
    },
    components: {
       
    },
    methods: {
        initSlider() {
            const tl = gsap.timeline()
            const button_prev = this.$refs.button_prev
            const button_next = this.$refs.button_next
            const first_text = this.$refs.first_text
            const swiper = this.$refs.swiper
            tl.set(
                first_text, {
                    rotate: -130,
                    x: -500,
                    y: 100
                },
            )
            tl.set(swiper, {
                scale: 0, 
                y: 300
            })
            tl.set(button_next, {
                rotate: 180,
                x: '200%',
            })
            tl.set(button_prev, {
                rotate: -180,
                x: '200%'
            })
            tl.to(
                first_text, {
                    rotate: 0,
                    duration: 0.5,
                    x: 0,
                    y: 0,
                    ease: "power3.out"
                }, '<'
            )
            tl.to(swiper, {
                scale: 1, 
                y: 0,
                ease: "power3.out"
            }, '<')
            tl.to(button_next, {
                rotate: '-=180',
                ease: "power3.out",
                duration: 0.5,
                x: 0,
            }, '<')

            tl.to(button_prev, {
                rotate: 0, 
                delay: 0.1,
                x: 0,
                ease: "power3.out",
                duration: 0.5
            },'<')
        },  
        prevSlide() {
            this.$refs.swiper.swiper.slidePrev();
        },
        nextSlide() {
            this.$refs.swiper.swiper.slideNext();
        },
        onSlideChange(event) {
            const swiper = this.$refs.swiper.swiper;
            const activeIndex = swiper.realIndex;
            const bgSwiper = this.$refs.bgSwiper.swiper;
            bgSwiper.slideTo(activeIndex);
        }
    },
    watch: {

    },
    computed: {
        length() {
            return this.slider.length
        },
        SliderByOrder() {
            return [...this.slider].sort((a, b) => a.order - b.order);
        }
    },
    mounted() {
        // console.log(typeof(this.slider[0].order));
        //register();
        this.$refs.swiper.addEventListener('swiperslidechange', (event) => {this.onSlideChange(event)}),
        this.initSlider()
    }, 
    beforeDestroy() {
        this.$refs.swiper.removeEventListener('swiperslidechange')
    },
    created() {

    },
}
</script>

<style lang="sass" scoped>
#slider-home
    min-height: 100vh
    width: 100%
    position: relative
    display: flex
    justify-content: center
    align-items: center
    .background-slider
        pointer-events: none
        position: absolute
        width: 100%
        height: 100%
        background-repeat: no-repeat
        background-position: center
        background-size: cover
        filter: blur(20px) brightness(30%)
        z-index: 1
        .background-slide
            width: 100%
            height: 100%
            background-repeat: no-repeat
            background-position: center
            //background-attachment: fixed
            background-size: cover
            filter: blur(10px) brightness(50%)
    .slider-block
        display: flex
        height: 100%
        width: 100%
        justify-content: center
        align-items: center
        z-index: 2
        display: grid
        gap: 20px
        grid-template-columns: 1fr minmax(20px, 2fr) 150px
        .left-side
            display: flex
            width: 400px
            max-height: 400px   
            height: 400px
            align-items: center
            width: 100%
            position: relative
            .first-text
                display: flex
                letter-spacing: 2px
                line-height: 150%
                margin-left: 50px
                //height: 100%
                align-items: center
        .center
            display: flex
            box-sizing: border-box
            width: 100%
            align-items: center
            height: 100%
            .slider
                display: flex
                justify-content: center
                width: 100%
                height: 100%
                align-items: center
                cursor: grab
                .slide
                    display: flex
                    align-items: center
                    height: 100%
                    justify-content: center
                    img
                        margin: auto
                        height: auto
                        max-height: 100%
                &:active
                    cursor: grabbing
                .active-main-slide
                    align-items: center
                img
                    border-radius: 20px
                    width: 100%
        .right-side
            display: flex
            width: 100px
            position: relative
            padding-right: 25px
            z-index: 2
            background: inherit
            user-select: none
            .buttons-control
                display: grid
                flex-direction: column
                grid-template-rows: 100px 100px
                grid-template-columns: 100px
                gap: 20px
                .prev-slide, .next-slide
                    cursor: pointer
                    display: flex
                    align-items: center
                    justify-content: center
                    border: 1px solid white
                    border-radius: 10px
                    transition: background 0.3s ease
.swiper-wrapper
    display: flex
    align-items: center
</style>