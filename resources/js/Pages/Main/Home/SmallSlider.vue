<template>
    <div id="small-slider">
        <swiper-container
        :slides-per-view="1" :loop="true"
        :space-between="200"
        class="swiper">
            <swiper-slide class="slide"
            v-for="(slide) in sliderByOrder">
                <img :src="`/storage/${slide.image}`" alt="">
            </swiper-slide>   
        </swiper-container>
    </div>
</template>

<script>
import { register } from 'swiper/element/bundle';

register();
export default {
    computed: {
        sliderByOrder() {
            this.slider.length ? [...this.slider].sort((a,b) => a.order - b.order) : Array(6).fill(1)
        }
    },
    props: {
        slider: Array,
        styles: Object,
    },
    mounted() {
        register();
    } 
}
</script>

<style lang="sass" scoped>

#small-slider
    width: 100%
    .swiper
        width: 100%
        cursor: grab
        &:active
            cursor: grabbing
        .slide
            background-size: cover
            background-repeat: no-repeat
            background-position: center
            border-radius: 20px
            @media screen and (max-width: 768px)
                width: 100% !important
            img
                width: 100%
                object-fit: cover
                border-radius: 20px
                height: 200px
</style>