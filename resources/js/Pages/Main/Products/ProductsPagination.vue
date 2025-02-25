<template>
    <div class="pagination">
        <button :style="{backgroundColor : styles.dark_2, borderColor : styles.contrast_1}"
            class="button-mark"
            v-if="products.prev_page_url || true"
            @click="loadPage(products.prev_page_url)"
        >
            <div class="mark">
                <div :style="{backgroundColor : styles.contrast_2, borderColor : styles.contrast_2}" class="mark-branch"></div>
                <div :style="{backgroundColor : styles.contrast_2}" class="mark-branch"></div>
            </div>
        </button>
        <button :style="{backgroundColor : styles.dark_2 , color: styles.contrast_2, borderColor : styles.contrast_2}"
            v-if="currentPage > 3"
            @click="loadPage(products.first_page_url)"
        >
            1
        </button>
        <span :style="{backgroundColor : styles.dark_2, color: styles.contrast_2, borderColor : styles.sturq2}" v-if="currentPage > 4">...</span>
        <button :style="{backgroundColor : styles.dark_2 , color: styles.contrast_2, borderColor : styles.contrast_2}"
            v-for="page in pageRangeLeft"
            :key="page"
            @click="loadPage(getPageUrl(page))"
        >
            {{ page }}
        </button>

        <button :style="{backgroundColor : styles.dark_2, color: styles.contrast_1, borderColor : styles.contrast_2, borderWidth : '1px', borderStyle: 'solid'}"
            :class="['active']"
            @click="loadPage(getPageUrl(currentPage))"
        >
            {{ currentPage }}
        </button>
        <button :style="{backgroundColor : styles.dark_2, color : styles.contrast_2, borderColor : styles.contrast_2}"
            v-for="page in pageRangeRight"
            :key="page"
            @click="loadPage(getPageUrl(page))"
        >
            {{ page  }}
        </button>
        <span :style="{backgroundColor : styles.dark_2, color: styles.contrast_2, borderColor : styles.contrast_2}" v-if="currentPage < totalPages - 3">...</span>
        <button :style="{backgroundColor : styles.dark_2, color: styles.contrast_2, borderColor : styles.contrast_2}"
            v-if="currentPage < totalPages - 2"
            @click="loadPage(products.last_page_url)"
        >
            {{ totalPages }}
        </button>
        <button class="button-mark"
            :style="{backgroundColor : styles.dark_2, borderColor : styles.contrast_2}"
            v-if="products.next_page_url || true"
            @click="loadPage(products.next_page_url)"
        >
            <div class="mark2">
                <div :style="{backgroundColor : styles.contrast_2}" class="mark-branch"></div>
                <div :style="{backgroundColor : styles.contrast_2}" class="mark-branch"></div>
            </div>
        </button>
    </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

export default {
    data() {
        return {
            styles: this.$page.props.styles
        };
    },
    props: {
        products: Object,
        filterBy: Object,
    },
    computed: {
        currentPage() {
            return this.products.current_page;
        },
        totalPages() {
            return this.products.last_page;
        },
        pageRangeLeft() {
            let pages = [];
            for (let i = this.currentPage - 2; i < this.currentPage; i++) {
                if (i > 0) pages.push(i);
            }
            return pages;
        },
        pageRangeRight() {
            let pages = [];
            for (let i = this.currentPage + 1; i <= this.currentPage + 2 && i <= this.totalPages; i++) {
                pages.push(i);
            }
            return pages;
        },
    },
    methods: {
        loadPage(url) {

            router.visit(url, {
                method: 'get',
                onSuccess() {},
                preserveState: true,
                preserveScroll: true,
                data: this.filterBy
            });
        },
        getPageUrl(page) {
            return `${this.products.path}?page=${page}`;
        },
    },
};
</script>

<style lang="sass" scoped>
.pagination
    display: flex
    gap: 5px
button, span
    padding: 5px
    border-radius: 7px
    border: 0px
    width: 32px
    height: 32px
    display: flex
    justify-content: center
    text-align: center
    align-items: center
    cursor: pointer
    &:hover
        border-width: 1px
        border-style: solid
.button-mark          
    .mark, .mark2
        position: relative
        cursor: pointer
        transform: rotate(90deg)
        .mark-branch
            width: 9px
            height: 2px
            position: absolute
            top: -1px
            right: 0px
            transition: transform 0.5s ease
            &:nth-of-type(2)
                right: -2px
                transform: rotate(45deg)
            &:nth-of-type(1)
                right: -8px
                transform: rotate(-45deg) 
    .mark2 
        .mark-branch
            top: -1px
            &:nth-of-type(2)
                right: -2px
                transform: rotate(-45deg)
            &:nth-of-type(1)
                right: -8px
                transform: rotate(45deg) 
</style>