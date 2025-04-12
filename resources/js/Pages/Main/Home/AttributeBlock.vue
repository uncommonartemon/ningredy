<template>
    <div id="attributes-alt">
        <div class="attributes-search">
            <input
            v-model="search"
            :style="{
                backgroundColor: styles.dark_2,
                border: `1px solid ${styles.dark_4}`,
                color: styles.white
            }"
            :placeholder="`search tags`"
            type="text">
        </div>
        <transition-group name="attribute-group">
            <div ref="attribute"
            v-for="(attribute, index) in getAttribute" :key="index" class="attribute"
            @mouseenter="hover_attribute = index" @mouseleave="hover_attribute = null"
            :style="{
            backgroundColor: activeAttribute(index, attribute)
            }">
                {{ Object.keys(attribute)[0] }} : {{ attribute[Object.keys(attribute)[0]] }}
            </div>
        </transition-group>
    </div>
</template>

<script>
import gsap from 'gsap'
import ScrollTrigger from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

    export default {
        data() {
            return {
                hover_attribute: null,
                search: '',
            }
        },
        computed: {
            getAttribute() {
                if (this.attributes) {
                    return this.attributes.reduce((acc, attr) => {
                        attr.values.forEach(value=> acc.push({[attr.key] : value}))
                        return acc
                    },[]).filter(item => {
                        const key = Object.keys(item)[0]
                        return item[key].toLowerCase().includes(this.search.toLowerCase())
                    })
                }
            },
            // activeAttribute() {

            // }
        },
        methods: {
            activeAttribute(index, attribute) {
                const attr = Object.values(attribute)[0]
                if (this.product_hover_attributes) {
                    const isHovered = this.product_hover_attributes.some(item=>Object.values(item)[0] === attr)
                    if (isHovered) {
                        return this.styles.dark_4
                    }
                }
                if (this.hover_attribute === index) {
                    return this.styles.dark_4
                } else if (this.hover_attribute != index) {
                    return this.styles.dark_2 
                }
            },
            initBlockAnim() {
                const attribute = this.$refs.attribute
                attribute.forEach((el)=> {
                    gsap.fromTo(el, {
                        opacity: 0,
                        y: 50
                    }, {
                        opacity: 1,
                        duration: 0.2,
                        y: 0,
                        scrollTrigger: {
                            trigger: el,
                            toggleActions: 'play reverse restart reverse'
                        }
                    } )
                })
            }
        },
        mounted() {
            if (this.attributes.length) {
                this.initBlockAnim()
            }
        },
        props: {
            styles: Object,
            attributes: Array,
            product_hover_attributes: Array,
        },
    
    }
</script>

<style lang="sass" scoped>
#attributes-alt
    display: flex
    flex-wrap: wrap
    gap: 5px
    max-height: 500px
    transition: max-height 0.2s ease
    .attributes-search
        width: 100%
        display: flex
        justify-content: start
        margin: 20px 0 10px
        input
            width: 100%
            max-width: 400px
            height: 30px
            border-radius: 5px
            padding: 10px 10px
            font-size: 15px
    .attribute
        display: flex
        flex-wrap: wrap
        justify-content: center
        padding: 5px
        flex-grow: 1
        cursor: pointer
        border-radius: 5px


.attribute-group-enter-active, .attribute-group-leave-active
    position: relative
    transition: all 0.5s ease

.attribute-group-enter-to, .attribute-group-leave-from
    opacity: 0
    
.attribute-group-move
    transition: transform 0.5s ease

</style>