<template>
    <div
    :style="{backgroundColor: styles.dark_2}"
    id="filterGrid">
        <div
        
        class="filter-items">
            <div class="filter-info"
            :style="{borderBottom: '2px solid ' + styles.dark_4}">
                <p :style="{color : styles.white}" class="key">Total products : {{ totalProducts }}</p>
            </div>
            
            <FilterPrice
            @update-price="updatePrice"
            :prices="filter.price.map(price => Number(price))"
            ></FilterPrice>
            <div v-for="(values, key) in filter" :style="{
                ...(key == 'price' ? {display : 'none'} : {}),
                borderBottom: '2px solid ' + styles.dark_4, 
            }" 
            class="filter-item">
               <div class="key"
                @click="show[key] = !show[key]"
               :style="{color : styles.white}">
                    <span>{{ key }}</span>
                    <div class="mark">
                        <div :class="{'branch-anim1' : show[key], 'branch-reverse1' : !show[key]}" class="mark-branch"></div>
                        <div :class="{'branch-anim2' : show[key], 'branch-reverse2' : !show[key]}" class="mark-branch"></div>
                    </div>
                </div>
                <transition name='show-key'>
                    <div v-if="show[key]"
                    class="values-items">
                        <div
                        :style="{ animationDelay: `${index * 0.05}s` }"
                        v-for="(value, index) in values" class="value">
                            <input 
                            v-if="value != null && value != 'null'"
                            :id="'checkbox_'+key+'_'+index"
                            :value="value"
                            v-model="filterBy.filter_updated[key]"
                            type="checkbox">
                            <div v-if="value != null" class="custom-checkbox"><div :style="{backgroundColor : styles.contrast_2}" class="custom-checked"></div></div>
                            <label 
                            :style="{color : styles.white}"
                            :for="'checkbox_'+key+'_'+index">
                                {{ value }}
                            </label>
                        </div>
                    </div>
                </transition>
            </div>
            <div
            v-for="(attribute,key) in attributes" 
            :style="{borderBottom: '2px solid ' + styles.dark_4}"
            class="filter-item">
                <div
                @click="show[key] = !show[key]"
                :style="{color:styles.white}"
                class="key">
                    <span>{{ attribute.key }}</span>
                    <div class="mark">
                        <div :class="{'branch-anim1' : show[key], 'branch-reverse1' : !show[key]}" class="mark-branch"></div>
                        <div :class="{'branch-anim2' : show[key], 'branch-reverse2' : !show[key]}" class="mark-branch"></div>
                    </div>
                </div>
                <transition name='show-key'>
                <div v-if="show[key]" class="values-items">
                    <div 
                    :style="{ animationDelay: `${index * 0.05}s` }"
                    v-for="(value,index) in attribute.values" class="value">
                        <input :id="'checkbox_'+attribute.key+'_'+index" v-model="filterBy.filter_attributes[attribute.key]"
                        :value="value"  v-if="value != null" type="checkbox">
                        <div class="custom-checkbox"><div :style="{backgroundColor : styles.contrast_2}" class="custom-checked"></div></div>
                        <label :for="'checkbox_'+attribute.key+'_'+index">
                            {{ value }}
                        </label>
                    </div>
                </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import FilterPrice from "./FilterPrice.vue";
    export default {
        data() {
            return {
                styles: this.$page.props.styles,
                show: {},
                windowTop : null,
                total_products: this.$page.props.data.total_products,
                filterBy: {
                    filter_updated: {},
                    filter_attributes: {},
                    price: {},
                    sortBy: '',
                }
            }
        },
        methods: {
            updatePrice(price) {
                this.filterBy.price = price
            }
        },
        props: {
            filter: Object,
            categories: Array,
            attributes: Array,
        },
        components: {
            FilterPrice,
        },
        mounted() {
            for (let key in this.filter) {
                this.show[key] = false;
                this.filterBy.filter_updated[key] = [] 
            }
            for (let attribute of this.attributes) {
                this.show[attribute.key] = false
                this.filterBy.filter_attributes[attribute.key] = []
            }
        },
        watch: {
            filterBy: {
               handler() {
                this.$emit('getFilterBy', this.filterBy)
               },
               deep: true
            },
        },
        computed: {
            totalProducts() {
                return this.$page.props.data.total_products
            }
        }
    }
</script>

<style lang="sass" scoped>
#filterGrid
    width: 250px
    min-height: 500px
    border-radius: 20px
    display: flex
    flex-direction: column
    padding: 20px 20px  
    user-select: none
    .filter-items
        display: flex
        flex-direction: column
        .filter-item, .filter-info
            display: block
            padding: 17px 0
            //border-bottom: 1px solid rgba(128, 128, 128, 0.5)
            .key
                display: block
                cursor: pointer
                user-select: none
                align-items: center
                font-size: 16px
                display: flex
                justify-content: space-between
                letter-spacing: 1px
                font-weight: bold
                span
                    width: 85%
                    font-weight: bold
                    &::first-letter
                        text-transform: uppercase
                &:hover
                    text-shadow: 0 0 3px white
                    .mark
                        .mark-branch
                            box-shadow: 0px 0px 3px grey
                .mark
                    position: relative
                    .mark-branch
                        width: 10px
                        height: 2px
                        background-color: grey
                        position: absolute
                        transition: transform 0.5s ease
                        &:nth-of-type(2)
                            right: 10px
                            transform: rotate(-45deg)
                        &:nth-of-type(1)
                            right: 16px
                            transform: rotate(45deg)
            .values-items
                display: flex
                flex-direction: column
                padding: 0px 0
                .value
                    display: flex
                    visibility: hidden
                    padding-left: 1px
                    align-items: center
                    gap: 12px
                    position: relative
                    margin-top: 15px
                    animation: value-anim 0.1s ease forwards
                    &::first-of-type
                        margin-top: 30px
                    &:last-of-type
                        margin-bottom: 15px
                    .custom-checkbox
                        position: absolute
                        width: 20px
                        height: 20px
                        cursor: pointer
                        left: 2px
                        border-radius: 1px
                        pointer-events: none
                        border: 1px solid grey
                        display: flex
                        justify-content: center
                        align-items: center
                        cursor: pointer
                        .custom-checked
                            cursor: pointer
                            pointer-events: none
                            width: 12px
                            height: 12px
                            display: none
                            border-radius: 1px
                    input
                        background-color: black
                        cursor: pointer
                        width: 20px
                        background-color: black
                        height: 20px
                        opacity: 0
                        z-index: 2
                        &:checked + .custom-checkbox
                            border: 1px solid grey
                            .custom-checked
                                //content: "✔"
                                display: block
                                animation: checked-anim 0.2s ease forwards
                        &:not(:checked):not(:hover) + .custom-checkbox
                            .custom-checked
                                animation: unchecked-anim 0.5s ease forwards
                    label
                        display: block
                        cursor: pointer
                        user-select: none
                    &:hover
                        .custom-checkbox
                            .custom-checked
                                width: 5px
                                height: 5px
                                animation: hover-checked 0.2s ease forwards 
                        label
                            text-shadow: 0px 0px 2px white

.filter-info
    font-size: 12px

// .filter-open-enter-active, .filter-open-leave-active
//     transition: max-height 0.4s ease, opacity 0.4s ease

// .filter-open-enter-from, .filter-open-leave-to
//     max-height: 0px
//     opacity: 0
// .filter-open-enter-to, .filter-open-leave-from
//     max-height: 500px
//     opacity: 1

@keyframes value-anim
    from 
        visibility: visible
        opacity: 0
        transform: translateY(-10px)
    to
        opacity: 1
        visibility: visible
        transform: translateY(0px)

@keyframes value-anim-leave 
  from 
    visibility: visible
    opacity: 1
    transform: translateY(0px)
  to 
    opacity: 0
    visibility: hidden
    transform: translateY(-20px)

.show-key-enter-active
    transition: all 0.3s ease
.show-key-leave-active
    transition: all 0.3s ease 
.show-key-enter-from, .show-key-leave-to
    max-height: 0px
    padding: 0px
.show-key-enter-to, .show-key-leave-from
    max-height: 500px
    padding: auto

.values-items.show-key-leave-active 
  animation: value-anim-leave 0.2s ease forwards

@keyframes checked-anim
    0%
        width: 0px
        height: 0px
    100%
        width: 12px
        height: 12px

@keyframes unchecked-anim
    0%
        width: 12px
        height: 12px
    100%
        width: 0px
        height: 0px

@keyframes hover-checked 
    0%
        width: 0px
        height: 0px
    100%
        width: 5px
        height: 5px        

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button 
  -webkit-appearance: none !important
  margin: 0 !important

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



</style>