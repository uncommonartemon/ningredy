<template lang="">
    <div id='admin-header'>
        <div class="logo">
            .logo
        </div>
        <ul class="admin-nav">
            <Link :href="'/admin'" class="admin-nav-item dashboard">Панель</Link>
            <Link :href="'/admin/products'" class="admin-nav-item">Товары</Link>
            <Link :href="'/admin/categories'" class="admin-nav-item categories">Категории</Link>
            <Link :href="'/admin/orders'" class="admin-nav-item">Заказы</Link>
            <Link :href="'/admin/settings'" class='admin-settings'>Настройки</Link>
        </ul>
        <div class="user">
            .user
        </div>
    </div>
</template>
<script>
import { Link } from '@inertiajs/vue3'
export default {
    components: {
        Link,
    },
    data() {
        return {
            adminNav: {
                
            },
        }
    },
    mounted() {
       
    },
}
</script>
<style lang="sass">

#admin-header
    height: 100px
    width: 100%
    border-bottom: 1px solid white
    display: flex
    align-items: center
    justify-content: space-around
    .user, .logo , .admin-nav
        height: 60px
        display: flex
        align-items: center
        padding: 0px 30px
        background-color: #292929
        border-radius: 40px
    .admin-nav
        width: auto
        background-color: #292929
        gap: 50px
        padding: 0px 40px
        border-radius: 40px
        li
            list-style-type: none
        a
            height: 100%
            display: flex
            align-items: center
</style>