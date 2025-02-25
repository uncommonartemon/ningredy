<template>
    <div v-if="products" class="products">
        <div class="filters">
            <div class="search">
                <input type="search" placeholder="search by id / title / category / brand / type / attributes" v-model="search" @input="search_input()">
            </div>     
            <div class="sorting">
                <label for="">sort by : </label>
                <select v-model="select_sort" name="sort" id="sort">
                    <option v-for="key of product_keys" :value="key">{{key}}</option>
                </select>
            </div>   
            <div class="filters_checkbox">
                <div class="drop-down-block" @mouseleave="attribute_key_checked_hidden = true">
                    <button @mouseover="attribute_key_checked_hidden = false" >filter by attribute.key: </button>
                    <div v-if="!attribute_key_checked_hidden" class="down-drop-items">
                        <div class="drop-down-item" v-for="(attribute, index) in attributes" >
                            <div class="drop-down-key">
                                <input type="checkbox" :id="'attribute_checked_' + index" v-model="attribute_checked_key" :value="attribute.key"> 
                                <label :for="'attribute_checked_' + index">{{ attribute.key }} </label>
                            </div>
                            <div class="drop-down-value" v-for="(value, idx) in attribute.values" :key="idx">
                                <input type="checkbox" 
                                    :id="'attribute_value_' + attribute.key + '_' + idx"
                                    v-model="attribute_checked_value" 
                                    @change="updateCheckedValues(attribute.key, value)"
                                    :value="value">
                                <label :for="'attribute_value_' + attribute.key + '_' + idx">{{ value }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="cell-titles">
            <div class="create-new-product">
                <a href="/admin/products/create">+</a>
            </div>
            <div class="cell sortByClick" @click="sortByClick('id')" :style="select_sort.slice(0, -1) == 'id' ? active_title : {}">
                id <span v-if="select_sort == 'id+'">+</span> <span v-if="select_sort == 'id-'">-</span>
            </div>
            <div class="cell sortByClick" @click="sortByClick('title')" :style="select_sort.slice(0, -1) == 'title' ? active_title : {}">
                title <span v-if="select_sort == 'title+'">+</span> <span v-if="select_sort == 'title-'">-</span>
            </div>
            <div class="cell sortByClick" @click="sortByClick('category')" :style="select_sort.slice(0, -1) == 'category' ? active_title : {}">
                category <span v-if="select_sort == 'category+'">+</span> <span v-if="select_sort == 'category-'">-</span>
            </div>
            <div class="cell sortByClick" @click="sortByClick('price')" :style="select_sort.slice(0, -1) == 'price' ? active_title : {}">
                price <span v-if="select_sort == 'price+'">+</span> <span v-if="select_sort == 'price-'">-</span>
            </div>
            <div class="cell sortByClick" @click="sortByClick('brand')" :style="select_sort.slice(0, -1) == 'brand' ? active_title : {}">
                brand <span v-if="select_sort == 'brand+'">+</span> <span v-if="select_sort == 'brand-'">-</span>
            </div>
            <div class="cell">
                type <span v-if="select_sort == 'type+'">+</span> <span v-if="select_sort == 'type-'">-</span>
            </div>
            <div class="cell sortByClick" @click="sortByClick('used')" :style="select_sort.slice(0, -1) == 'used' ? active_title : {}">
                used? <span v-if="select_sort == 'used+'">+</span> <span v-if="select_sort == 'used-'">-</span>
            </div>
            <div class="cell" :style="select_sort.slice(0, -1) == 'created_at' || select_sort.slice(0, -1) == 'updated_at'  ? active_title : {}">
                <p style="white-space: wrap" class="create-update">
                    <span class='sortByClick'><span @click="sortByClick('created_at')">created</span><span v-if="select_sort == 'created_at+'">+</span> <span style="white-space: nowrap" v-if="select_sort == 'created_at-'">-</span></span>
                    <span class='sortByClick'><span @click="sortByClick('updated_at')">/updated</span><span v-if="select_sort == 'updated_at+'">+</span> <span style="white-space: nowrap" v-if="select_sort == 'updated_at-'">-</span></span>
                </p>
            </div>
            <div class="cell attributes sortByClick" @click="sortByClick('attributes')" :style="select_sort.slice(0, -1) == 'attributes' ? active_title : {}">
                attributes <span v-if="select_sort == 'attributes+'">+</span> <span v-if="select_sort == 'attributes-'">-</span>
            </div>
            <div class="cell"></div>
        </div>
        <div v-for="product in filteredProducts" class="product">
            <div
            class="image cell">
                <img :src="product.images && product.images.length ? product.images[0] : 'path/to/default/image.jpg'" alt="">
            </div>
            <div class="cell" :style="product.matches == 'id' ? borderG : {}">
                {{ product.id }}
            </div>
            <div class="cell" :style="product.matches == 'title' ? borderG : {}" >
                {{ product.title }}
            </div>
            <div class="cell" :style="product.matches == 'category' ? borderG : {}">
                {{ product.category.name }}
            </div>
            <div class="cell cell-price">
                <span class="price" :style="product.discount ? {textDecoration : 'line-through'} : true">{{ product.price }}</span>
                <span class="discount">{{ product.discount }}</span>
            </div>
            <div class="cell" :style="product.matches == 'brand' ? borderG : {}">
                {{ product.brand }}
            </div>
            <div class="cell" :style="{}">
                {{ product.type }}
            </div>
            <div class="cell">
                <span v-if="product.used">Yes</span>
                <span v-else>No</span>
            </div>
            <div class="cell data">
                <div class="created">{{ dataComputed(product.created_at) }} </div>
                <div class="updated">{{ dataComputed(product.updated_at) }}</div>
            </div>
            <div class="cell attributes" :style="product.matches == 'attributes' ? {borderBottom : '1px dotted lightgreen', borderTop: '1px dotted lightgreen'} : {}">
                <div v-for="(value, key) in product.attributes" :key="key" :value="value" class="attribut">
                    <p>{{ key }} : {{ value }}, </p>
                </div>
            </div>
            <div class="cell buttons">
                <Link class="link" :href="`/admin/products/edit/${product.id}`"  :style="{background : styles.light}">edit</Link>
                <button @click="deleteProduct(product.title, product.id)" :style="{background : styles.light}">delete</button>
            </div>
        </div>
    </div>
</template>

<script>
import { router, Link } from '@inertiajs/vue3';
    export default {
        data() {
            return {
                select_sort: 'id+',
                search: null,
                attribute_checked_key: [],
                attribute_checked_value: [],
                checked_key: [],
                checked_value: [],
                attribute_key_checked_hidden: true,
                filter: {
                    id: true,
                    price: null,
                    used: null,
                },
                borderG: {
                    borderBottom : '2px dotted lightgreen'
                },
                active_title: {
                    backgroundColor : this.$page.props.styles.light,
                    color: this.$page.props.styles.dark1  
                },
            }
        },
        props: {
          products: Array,  
          attributes: Array,
    
        },
        mounted() {

        },
        computed: {
            product_keys() {
                if (this.products.length > 0) {
                    let keys = []
                    for (let key of Object.keys(this.products[0])) {
                        if (['title','brand','category','id','price','discount','used','attributes','updated_at','created_at'].includes(key)) {
                            keys.push(key + '+')
                            keys.push(key + '-')
                        }
                    }
                    return keys
                } else return []
            },
            
            styles() {
                return this.$page.props.styles
            },
            search_attributes() {
                
            },  
            filteredProducts() {
                let sortedProducts = [...this.products];
                const searchTerm = this.search ? this.search.toLowerCase() : '';

                sortedProducts = sortedProducts.map(product => ({
                    ...product,
                    matches: '' 
                }));

                if(this.select_sort) {
                    const select = this.select_sort.slice(0, -1);
                    const order = this.select_sort.slice(-1);
                    if (select === 'title') {
                        if (select === 'title' || select === 'brand' || 'category') {
                            sortedProducts.sort((a, b) => {
                                return order === '-' 
                                    ? a.title.localeCompare(b.title) 
                                    : b.title.localeCompare(a.title);
                            });
                        }
                    } else if (select === 'id') {
                        sortedProducts.sort((a, b) => {
                            return order === '+' 
                                ? a.id - b.id 
                                : b.id - a.id; 
                        });
                    }else if (select === 'price' || select === 'discount') {
                        sortedProducts.sort((a, b) => {
                            const priceA = a.discount ? a.discount : a.price; 
                            const priceB = b.discount ? b.discount : b.price; 
                            return order === '+' ? priceA - priceB : priceB - priceA;
                        })
                    } else if (select === 'used') {
                        sortedProducts.sort((a,b) => {
                            return order === '+' ? (a.used === b.used ? 0 : a.used ? -1 : 1) : (a.used === b.used ? 0 : a.used ? 1 : -1);
                        })
                    } else if (select === 'attributes') { 
                        sortedProducts.sort((a,b ) => {
                            return order === '-' ? Object.keys(a.attributes).length - Object.keys(b.attributes).length : Object.keys(b.attributes).length - Object.keys(a.attributes).length;
                        })
                    } else if (select === 'updated_at' || select === 'created_at') {
                        sortedProducts.sort((a,b) => {
                            // return order === '+' ? 
                            console.log(Date(a[select]));
                            return order === '+' 
                                ? new Date(a[select]) - new Date(b[select]) 
                                : new Date(b[select]) - new Date(a[select]);
                        })
                    }
                }

                if (this.attribute_checked_key.length) {
                    sortedProducts = sortedProducts.filter(product => 
                        this.attribute_checked_key.some(key => product.attributes.hasOwnProperty(key))
                    );
                }

                if (this.attribute_checked_value.length) {
                    sortedProducts = sortedProducts.filter(product =>
                        this.attribute_checked_value.every(value =>
                            this.checked_key.some(key =>
                                product.attributes[key] && product.attributes[key].includes(value)
                            )
                        )
                    );
                }

                if (this.search) {
                    const searchTerm = this.search.toLowerCase();
                    sortedProducts = sortedProducts.filter(product => {
                        const idMatch = String(product.id).includes(searchTerm);
                        const titleMatch = product.title.toLowerCase().includes(searchTerm);
                        const categoryMatch = product.category.name.toLowerCase().includes(searchTerm);
                        const brandMatch = product.brand.toLowerCase().includes(searchTerm);
                        const typeMatch = product.type.toLowerCase().includes(searchTerm);
                        const attributesMatch = Object.entries(product.attributes).some(([key, value]) => 
                            key.toLowerCase().includes(searchTerm) || String(value).toLowerCase().includes(searchTerm)
                        );

                        if (idMatch) {
                            product.matches = `id`;
                        } else if (titleMatch) {
                            product.matches = `title`;
                        } else if (categoryMatch) {
                          product.matches = 'category'  
                        } else if (brandMatch) {
                          product.matches = 'brand'  
                        } else if (typeMatch) {
                            product.matches = `type`;
                        } else if (attributesMatch) {
                            product.matches = `attributes`;
                        }

                        return idMatch || titleMatch || categoryMatch || brandMatch || attributesMatch || typeMatch;
                    });
                }
                
                return sortedProducts;
            },
            

            
            
        }, 
        methods: {
            updateCheckedValues(attributeKey, value) {
                // Добавление key и value в checked_key и checked_value
                if (!this.checked_key.includes(attributeKey)) {
                this.checked_key.push(attributeKey);
            }

            // Добавление value в checked_value, если его нет
            if (!this.checked_value.includes(value)) {
                this.checked_value.push(value);
            }
            },
            attributes_array() {

            },
            sortByClick(option) {
                if (option + '+' != this.select_sort && option + '-' != this.select_sort) {
                    this.select_sort = option + '+'
                } else if (option + '+' == this.select_sort) {
                    this.select_sort = option + '-'
                } else if (option + '-' == this.select_sort) {
                    this.select_sort = option + '+'
                }
               
            },
  
            deleteProduct(title,id) {
                confirm(`Delete product '${title}' ?`) ?  router.delete(`/admin/products/${id}`) : false
            },
            dataComputed(data) {
                return new Date(data).toLocaleString(); 
            },
            
        },
    }
</script>

<style lang="sass" scoped>
    .products
        display: flex
        flex-direction: column
        gap: 10px
        padding: 20px
        padding-top: 30px
        .product, .cell-titles
            padding: 5px
            display: flex
            display: grid
            grid-template-columns: repeat(12, 1fr)
            border-radius: 10px
        .cell-titles
            margin-bottom: 10px
            gap: 15px
            .cell
                display: flex
                justify-content: center
                padding: 5px 10px
                border-radius: 20px
                border: 1px solid white
                text-align: center
                vertical-align: center
                word-wrap: break-word
                align-items: center
                &:last-of-type
                    visibility: hidden
                &:first-of-type
                    visibility: hidden
            .attributes
                grid-column: span 2
        .product
            height: 120px
            border: 1px solid white
            .data
                display: flex
                flex-direction: column
                justify-content: space-between
                height: 100%
                .created
                    border-bottom: 1px solid white
                .created, .updated
                    padding: 5px
                    display: flex
                    justify-content: center
                    text-align: center
                    align-items: center
            .image
                display: flex
                height: 100%
                img
                    max-width: 100px
                    height: 100%
                    max-height: 110px
                    object-fit: contain
                    
            .cell
                display: flex
                justify-content: center
                align-items: center
                text-align: center
                &:not(:last-child)
                    border-right: 1px solid white
            .cell-price
                display: flex !important
                gap: 10px
                flex-direction: column !important
            .attributes
                grid-column: span 2
                text-align: start
                padding: 10px
                align-items: start
                justify-content: start
                overflow-y: auto 
                display: flex
                flex-direction: column
                p
                    word-break: keep-all
                    white-space: nowrap
            .buttons
                display: flex
                flex-direction: column
                gap: 10px
                button, .link
                    font-size: 15px
                    color: black
                    display: flex
                    justify-content: center
                    align-items: center
                    text-align: center
                    height: 30px
                    width: 90%
                    border-radius: 10px
                    border: 1px solid black
                    cursor: pointer
                    &:nth-of-type(1)
                        background-color: white
                        
.highlight
    background-color: yellow

.filters
    display: flex
    justify-content: center
    gap: 20px
    .search
        input
            width: 500px
            border-radius: 10px
            padding: 5px 10px
            border-style: none
            height: 25px
    .sorting
        height: 25px
        select
            height: 25px
            cursor: pointer
            option
                height: 25px
                cursor: pointer !important


.drop-down-block
    display: flex
    flex-direction: column
    position: relative
    user-select: none
    button
        width: 200px
        cursor: pointer
        height: 25px
    .down-drop-items
        position: absolute
        background-color: white
        border: 1px solid black
        overflow: scroll
        max-height: 300px
        max-width: 500px
        display: flex
        flex-direction: column
        gap: 5px
        padding: 10px 5px
        top: 25px
        .drop-down-item
            padding: 5px
            gap: 10px
            padding: 5px
            display: flex
            color: black
            max-width: 500px
            width: 300px
            display: flex
            flex-direction: column
            .drop-down-value
                padding-left: 10px
            input
                width: 20px !important
                height: 20px !important
                cursor: pointer
                flex-shrink: 0
            label
                cursor: pointer
                position: relative
                left: 5px
                top: -4px
                
.sortByClick
    cursor: pointer
    user-select: none
    display: inline-flex

.create-new-product
    display: flex !important
    display: flex
    justify-content: center
    align-items: center
    a
        display: flex
        width: 41px
        height: 41px
        border: 1px solid white
        justify-content: center
        align-items: center
        text-align: center
        border-radius: 50%
        transition: all 0.3s ease
        font-weight: bold
        &:hover
            background-color: white
            color: black
        
</style>