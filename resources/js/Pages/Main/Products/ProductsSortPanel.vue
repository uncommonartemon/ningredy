<template>
    <div id="ProductsSortPanel">
        <div class="sort-panel-content">
            <div v-if="false" class="bread-crumbs">
                {{ $page.url}}
            </div>
            <div class="sort-panel">
                <div class="sort-panel-left">
                    <Link href="/" :style="{backgroundColor : styles.dark_2}"
                    @click=""
                     class="backup-button">
                        <div @mouseover="" class="button-mark">
                            <div class="mark">
                                <div  class="mark-branch"></div>
                                <div  class="mark-branch"></div>
                            </div>
                        </div>
                    </Link>
                    <p class="category-name">
                        <span v-for="span in category.name_en">{{ span }}</span>
                    </p>
                </div>
                <div class="sort-panel-right">
                    <div @mouseleave="show = false" class="sort-select">
                        <div 
                        :class="{'anim-border' : show}"
                        :style="show ? {backgroundColor : styles.dark_2, zIndex : 2} : {backgroundColor : styles.dark_2} "
                        @click="show = !show" 
                        @mouseover="show = true"
                        class="sort-selected">
                            <span>{{ sortByValue }}</span>
                            <div class="mark">
                                <div :class="{'branch-anim1' : show, 'branch-reverse1' : !show}" class="mark-branch"></div>
                                <div :class="{'branch-anim2' : show, 'branch-reverse2' : !show}" class="mark-branch"></div>
                            </div>
                        </div>
                        <transition name="sort-items">
                            <div
                            :style="show ? {backgroundColor : styles.dark_2, zIndex : 2} : {backgroundColor : styles.dark_2}"
                            v-if="show"
                            class="sort-items">
                                <div
                                v-for="(value, key, index) in sortList"
                                :style="{animationDelay : `${index * 0.06}s`}"
                                :class="{'sort-item-enter' : show }"
                                @click="sortByKey = key; sortByValue = value"
                                class="sort-item">
                                    {{ value }}
                                </div>
                            </div>
                        </transition>
                    </div>
                    <div class="grid-view">
                        <div class="columns-or-rows">
                            <div @click="gridEvent('columns')"
                            :style="gridType == 'columns' ? {backgroundColor : styles.dark_4} : {backgroundColor : styles.dark_2}"
                            class="columns-type">
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                                <div :style=" gridType == 'columns' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="columns-type-item"></div>
                            </div>
                            <div @click="gridEvent('rows')"
                            :style="gridType == 'rows' ? {backgroundColor : styles.dark_4} : {backgroundColor : styles.dark_2}"
                            class="rows-type">
                                <div :style=" gridType == 'rows' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="rows-type-item"></div>
                                <div :style=" gridType == 'rows' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="rows-type-item"></div>
                                <div :style=" gridType == 'rows' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="rows-type-item"></div>
                                <div :style=" gridType == 'rows' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="rows-type-item"></div>
                                <div :style=" gridType == 'rows' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="rows-type-item"></div>
                                <div :style=" gridType == 'rows' ? {backgroundColor : styles.contrast_2} : {backgroundColor : styles.white, opacity : '0.9'}" class="rows-type-item"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                styles: this.$page.props.styles,
                show: false,
                sortList: {
                    'price+' : 'Price: Low to High',
                    'price-' : 'Price: High to Low',
                    'created' : 'Newest Arrivals',
                    'discount' : 'Best discount',
                    'title+' : 'Name Ascending',
                    'title-' : 'Name Descending',
                },
                sortByKey: 'created', 
                sortByValue: 'Newest Arrivals',
                gridColumns: 'auto-fill',
                gridType: 'columns',
            }
        },
        props: {
            category: Object,
        },
        methods: {
            backHistory() {
                window.history.back()
            },
            gridEvent(value) {
                this.gridType = value
                this.$emit('update-grid', value)
            },
        }, 
        watch: {
            sortByKey: {
                handler() {
                    this.$emit('update-sort', this.sortByKey)
                },
                
            }
        }
    }
</script>

<style lang="sass" scoped>
#ProductsSortPanel
    margin: 20px 0
    display: flex
    width: 100%
    .sort-panel-content
        width: 100%
        display: flex
        flex-direction: column
        .bread-crumbs
            display: flex
        .sort-panel
            width: 100%
            display: flex
            justify-content: space-between
            align-items: center
            .sort-panel-left
                display: flex
                gap: 10px
                align-items: center
                .backup-button
                    outline: none
                    cursor: pointer
                    border: 0px
                    width: 38px
                    height: 38px
                    border-radius: 50%
                    display: flex
                    justify-content: center
                    align-items: center
                    .button-mark
                        .mark
                            position: relative
                            cursor: pointer
                            transform: rotate(90deg)
                            .mark-branch
                                width: 10px
                                height: 2px
                                background-color: grey
                                position: absolute
                                top: 0px
                                right: 0px
                                transition: transform 0.5s ease
                                &:nth-of-type(2)
                                    top: 0px
                                    right: -3px
                                    transform: rotate(45deg)
                                &:nth-of-type(1)
                                    top: 0px
                                    right: -9px
                                    transform: rotate(-45deg) 
                p
                    display: flex
                    font-size: 19px
                    display: block
                    font-weight: bold
            .sort-panel-right
                display: flex
                gap: 20px
                .sort-select
                    display: flex
                    flex-direction: column
                    position: relative
                    &:hover
                        .sort-selected
                            border-radius: 20px 20px 0px 0px
                    .sort-selected
                        padding: 10px 20px
                        padding-right: 40px
                        width: 250px
                        border-radius: 20px 20px 20px 20px
                        transition: border-radius 0.5s ease
                        .mark
                            position: relative
                            cursor: pointer
                            .mark-branch
                                width: 10px
                                height: 2px
                                background-color: grey
                                position: absolute
                                top: -10px
                                transition: transform 0.5s ease
                                &:nth-of-type(2)
                                    right: -25px
                                    transform: rotate(-45deg)
                                &:nth-of-type(1)
                                    right: -19px
                                    transform: rotate(45deg)
                .grid-view
                    display: flex
                    .columns-or-rows
                        width: 100px
                        display: flex
                        justify-content: space-between
                        .columns-type, .rows-type
                            width: 50%
                            padding: 10px
                            display: flex
                            justify-content: center
                            align-items: center
                            cursor: pointer
                        .columns-type
                            border-radius: 20px 0px 0px 20px
                            display: grid
                            justify-content: center
                            align-items: center
                            grid-template-columns: repeat(3, auto)
                            column-gap: 4px
                            .columns-type-item
                                width: 4px
                                height: 4px
                                position: relative
                                left: 2px
                        .rows-type
                            border-radius: 0px 20px 20px 0px
                            display: grid
                            justify-content: center
                            align-items: center
                            grid-template-columns: 4px 12px 
                            column-gap: 3px
                            row-gap: 2px 
                            .rows-type-item
                                position: relative
                                right: 3px
                                height: 4px
                                        
                .sort-items
                    position: absolute
                    top: 40px
                    display: flex
                    flex-direction: column
                    gap: 10px
                    border-radius: 0px 0px 20px 20px
                    animation: sort-items 0.5s ease forwards
                    .sort-item
                        width: 250px
                        padding: 0px 20px
                        cursor: pointer
                        visibility: hidden
                        &:hover
                            text-shadow: 0px 0px 3px white , 0px 0px 3px white
                        &:first-of-type
                            padding-top: 15px
                        &:last-of-type
                            padding-bottom: 20px

.sort-item-enter
    animation: sort-item 0.1s ease forwards
.sort-item-leave
    animation: sort-item 0.1s ease reverse forwards
// .anim-border
//     animation: anim-border 0.6s ease forwards

@keyframes anim-border
    0%
        border-radius: 20px 20px 20px 20px
    100%
        border-radius: 20px 20px 0px 0px

@keyframes sort-items
    0%
        max-height: 0px
        border-radius: 20px 20px 20px 20px
    100%
        max-height: 500px
        border-radius: 0px 0px 20px 20px

@keyframes sort-item
    0%
        visibility: visible
        opacity: 0
    100%
        opacity: 1
        visibility: visible

.sort-items-leave-active
    transition: all 0.2s ease
.sort-items-leave-from 
    //height: 300px
    transform: translateY(0px)
    border-radius: 0px 0px 20px 20px
    opacity: 1
.sort-items-leave-to
    //height: 0px
    transform: translateY(0px)
    border-radius: 20px 20px 20px 20px
    opacity: 0

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