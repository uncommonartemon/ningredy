<template>
    <div ref='searchVue'
    :style="{
        backgroundColor: styles.dark_2,
        color: styles.white,
    }"
     id="HeaderSearch">
        <div  v-if="searchItemOpen" class="header-search-items">
            <TransitionGroup appear name="search-item" >
                <HeaderSearchItem
                v-for="(product, index) in getProducts"
                :key="index"
                :product="product"
                ></HeaderSearchItem>
            </TransitionGroup>
        </div>
    </div>
</template>

<script>
import gsap from 'gsap';
import HeaderSearchItem from './HeaderSearchItem.vue'
    export default {
        data() {
            return {
                styles: this.$page.props.styles,
                searchItemOpen: false,
                tl: null,
            }
        },
        components: {
            HeaderSearchItem
        },
        props: {
            windowTop: Number,
            search: String,
        },
        methods: {
            animateContraction() {
                let pos = this.$parent.$refs.searchInput.getBoundingClientRect()
                const tl = gsap.timeline();
                tl.to(this.$refs.seachVue,{
                    top: pos.top,
                    left: pos.left,
                    width: pos.width,
                    height: pos.height,
                    borderRadius: '20px',
                    duration: 0.5,
                    ease: 'power2.out'
                })
            },
            animateExpansion() {
                let pos = this.$parent.$refs.searchInput.getBoundingClientRect()
                const tl = gsap.timeline();
                console.log(pos);
                if (this.$refs.searchVue) {
                    tl.set(this.$refs.searchVue, {
                        position: 'fixed',
                        borderRadius: '20px',
                        top: pos.top,
                        left: pos.left,
                        width: pos.width,
                        height: pos.height,
                        border: `0px solid ${this.styles.dark_4}`
                    }),
                    tl.to(this.$refs.searchVue, {
                        duration: 0.2,
                        scaleX: 2,
                        ease: 'power2.out'
                    });
                    tl.to(this.$refs.searchVue, {
                        duration: 0.3,
                        scaleY: 2,
                        ease: 'power2.inOut',
                        padding: 0
                    });
                    tl.to(this.$refs.searchVue, {
                        duration: 0,
                        scale: 1,
                        width: window.innerWidth > 1400 ? '1410px': '100vw',
                        maxWidth: '1405px',
                        left: window.innerWidth > 1400 ? (window.innerWidth - 1400 - 5) / 2  : 0,
                        opacity: 0.98,
                        maxHeight: 500,
                        top: this.$parent.$el.getBoundingClientRect().height,
                        ease: 'power2.inOut',
                        padding: '30px 50px',
                        backgroundColor: this.hexToRgba(this.styles.dark_2, 0.7),
                        borderRadius: '0px 0px 20px 20px',
                    })
                    tl.to(this.$refs.searchVue, { 
                        maxHeight: 'auto',
                        height: 260,
                        duration: 0.2,
                    });
                    tl.eventCallback("onComplete", () => {
                        setTimeout(() => {
                            this.searchItemOpen = true;
                            tl.to(this.$refs.searchVue, { 
                                height: 'auto',
                                duration: 0,
                                border: `1px solid ${this.styles.dark_4}`
                            });
                        }, 500)
                    });
                }
            },
            hexToRgba(hex, alpha) {
                // Преобразование HEX в RGB
                let r = parseInt(hex.slice(1, 3), 16);
                let g = parseInt(hex.slice(3, 5), 16);
                let b = parseInt(hex.slice(5, 7), 16);
                
                // Возвращаем строку в формате rgba
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            }
        },
        mounted() {
            this.animateExpansion();
            window.addEventListener('click', this.handleClickOutside);
        },
        beforeDestroy() {
            window.removeEventListener('click', this.handleClickOutside);
            this.searchItemOpen = false
        },
        computed: {
            getProducts() {
                return this.$page.props.searchProducts 
            },
            getWidth() {
                return (window.innerWidth - 1400) / 2
            }
        },

    }
</script>

<style lang="sass" scoped>
#HeaderSearch 
    border-top: 0px !important
    position: absolute
    display: flex
    transition: all 0.3s ease
    &:hover
        opacity: 1 !important
    .header-search-items
        width: 100%
        display: flex
        justify-content: start
        gap: 15px
        width: 100%
        row-gap: 20px
        flex-wrap: wrap

.search-item-move
    transition: all 0.3s ease
.search-item-move-active
    position: absolute
.search-item-enter-active
    transition: all 0.3s ease 
.search-item-leave-active
    transition: all 0.3s ease
.search-item-enter-from, .search-item-leave-to
    opacity: 0
    transform: translateX(-20px)
.search-item-enter-to, .search-item-leave-from
    opacity: 1
    transform: translateX(0px)
    position: relative

</style>