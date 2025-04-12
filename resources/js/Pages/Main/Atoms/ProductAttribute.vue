<template>
    <div> 
        {{ attribute_key }} : {{ value }}
    </div>
</template>

<script>
    export default {
        props: {
            attribute_key: String,
            value: String,
        }
    }
</script>

<style lang="sass" scoped>

</style>