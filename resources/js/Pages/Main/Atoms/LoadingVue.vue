<template>
    <div ref="loading"
    id="loading">
        <div :style="{backgroundColor: styles.contrast_1}" ref="strip1" class="strip"></div>
        <div :style="{backgroundColor: styles.contrast_2}" ref="strip2" class="strip"></div>
    </div>
</template>

<script>
import gsap from 'gsap';
    export default {
        data() {
            return {
                styles : this.$page.props.styles,
            }
        },
        methods: {
            loading() {
                const tl = gsap.timeline();
                const el1 = this.$refs.strip1
                const el2 = this.$refs.strip2
                const strips = [el1, el2]
                tl.set(strips, {
                    height: '100vh',
                    left: 0,
                    width: this.inner /15,
                    top: 0
                })
                tl.to(strips, {
                    left: this.inner / 2,
                    duration: 0.5,
                    width: this.inner / 7,
                    stagger: 0.1
                })
                tl.to(strips, {
                    left: this.inner,
                    duration: 0.5,
                    width : this.inner / 15,
                    stagger: 0.1
                })
            }
        },
        mounted() {
            this.loading()
            console.log(this.inner);
        },
        computed: {
            inner() {
                return window.innerWidth
            }
        }
    }
</script>

<style lang="sass" scoped>
    #loading
        overflow: hidden
        position: absolute
        height: 100%
        width: 100%
        display: flex
        .strip
            height: 100%
            position: absolute
</style>