<template>
    <div :style="{ 
        '--loader-bg': styles.contrast_1,
        borderColor: `${styles.contrast_1} #0000` 
    }" class="loader">

    </div>
</template>

<script>
    export default {
        computed: {
        },
        mounted() {
            // lottie.loadAnimation({
            //     container: this.$refs.loading,
            //     renderer: 'svg',
            //     loop: true,
            //     autoplay: true,
            //     path: `/storage/${this.settings.loading_dark}`
            // })
        },
        props: {
            settings: Object,
            styles: Object,
        }
    }
</script>

<style scoped>
/* HTML: <div class="loader"></div> */
.loader {
  width: 50px;
  aspect-ratio: 1;
  border-radius: 50%;
  border: 8px solid;
  border-color: #000 #0000;
  animation: l1 1s infinite;
}
@keyframes l1 {to{transform: rotate(.5turn)}}
</style>