<template>
    <div
    :style="{borderBottom: '2px solid ' + styles.dark_4}"
    id="FilterPrice">
        <div @click="show = !show" class="filterPriceShow">
            Price
            <div class="mark">
                <div :class="{'branch-anim1' : show, 'branch-reverse1' : !show}" class="mark-branch"></div>
                <div :class="{'branch-anim2' : show, 'branch-reverse2' : !show}" class="mark-branch"></div>
            </div>
        </div>
        <transition name="show-key">
            <div v-if="show" class="filterPriceContent">
            
            <div class="inputs">
                <div class="from">
                    <label for="input_from">From</label>
                    <input @change="priceUpdate"
                    :style="{backgroundColor : styles.dark_2, color: styles.white}"
                    v-model="from" type="number">
                </div>
                <div class="to">
                    <label for="input_to">To</label>
                    <input @change="priceUpdate"
                    :max="Math.max(...prices)"
                    :style="{backgroundColor : styles.dark_2, color: styles.white}"
                    v-model="to" id="to" type="number">
                </div>
            </div>
            <div class="range-block">
                <input class="range-min"
                @change="priceUpdate"
                min="0" :max="to" v-model="from" type="range">
                <input
                @change="priceUpdate"
                class="range-max" :min="from" :max="Math.max(...prices)" v-model="to" type="range">
                
            </div>
        </div>
        </transition>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: false,
                from: 0,
                to: Math.max(...this.prices),
                styles: this.$page.props.styles
            }
        },
        methods: {
            priceUpdate() {
                this.to > Math.max(...this.prices) ? this.to = Math.max(...this.prices) : false
                this.$emit('update-price', {from : this.from, to : this.to})
            }
        },
        props: {
            prices: Array
        }, 
        computed: {
            range() {
                return Math.round((this.to / Math.max(...this.prices)) * 100)
            }
        }
    }
</script>

<style lang="sass" scoped>
#FilterPrice
    display: flex
    flex-direction: column
    padding: 15px 0
    //border-bottom: 1px solid rgba(128, 128, 128, 0.5)
    .filterPriceShow
        display: flex
        cursor: pointer
        font-size: 18px
        justify-content: space-between
        font-weight: bold
        .mark
            position: relative
            .mark-branch
                width: 10px
                height: 2px
                background-color: grey
                position: absolute
                top: 10px
                transition: transform 0.5s ease
                &:nth-of-type(2)
                    right: 10px
                    transform: rotate(-45deg)
                &:nth-of-type(1)
                    right: 16px
                    transform: rotate(45deg)
    .filterPriceContent
        display: flex
        flex-direction: column
        gap: 20px
        .inputs
            margin-top: 20px
            display: flex
            gap: 10px
            .from, .to
                display: flex
                flex-direction: column
                gap: 5px
                input
                    outline: none
                    padding: 6px 8px
                    width: 100px
                    border-radius: 20px
                    border: 1px solid grey
                    font-size: 12px
                    &::-webkit-outer-spin-button, &::-webkit-inner-spin-button
                        display: none
                label
                    font-size: 12px
                    opacity: 0.7
        .range-block
            display: flex
            align-items: center
            width: 100% 
            position: relative 
            gap: 10px
            margin-bottom: 20px
            input
                top: 3px
                appearance: none
                background: transparent
                width: 100px
                z-index: 1
                &::-webkit-slider-runnable-track
                    height: 2px 
                    background: white
                &::-webkit-slider-thumb
                    appearance: none 
                    width: 13px 
                    height: 13px 
                    background: white
                    border-radius: 50% 
                    position: relative
                    top: -2px 
                    cursor: pointer 
                &::-webkit-slider-progress
                    height: 5px
                    background: white

.branch-anim1
    animation: rotateBranch1 0.5s ease forwards

.branch-anim2
    animation: rotateBranch2 0.5s ease forwards

.branch-reverse1
    animation: rotateBranch2 0.5s ease forwards

.branch-reverse2
    animation: rotateBranch1 0.5s ease forwards

@keyframes rotateBranch1
    0%
        transform: rotate(45deg)
    100%
        transform: rotate(-45deg)
        
@keyframes rotateBranch2
    0%
        transform: rotate(-45deg)
    100%
        transform: rotate(45deg)

.show-key-enter-active, .show-key-leave-active
    transition: opacity 0.2s ease, max-height 0.3s ease 
.show-key-enter-from, .show-key-leave-to
    max-height: 0px
    opacity: 0
.show-key-enter-to, .show-key-leave-from
    max-height: 200px
    opacity: 1

</style>