<template>
    <div id="admin-create-product">
        <form @submit.prevent="submit"
        action="" class="form-create">
            <div class="form-category form-item">
                <label for="category">Категория :</label>
                <select id="category" v-model="form.category_id">
                    <option v-for="category in categories" :key="category_id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
            <div class="form-title form-item">
                <label for="title">Название<span>*</span> :</label>
                <input id="title"type="text" v-model="form.title" >
            </div>

            <div class="form-price form-item">
                <label for="price">Цена<span>*</span> :</label>
                <input id="price" v-model="form.price" type="number">
            </div>

            <div class="form-discount form-item">
                <label for="discount">Цена после скидки :</label>
                <input id="discount"  type="number" v-model="form.discount">
            </div>

            <div class="form-brand form-item">
                <label for="brand">Бренд :</label>
                <input type="text" id="brand" v-model="form.brand">
            </div>

            <div class="form-type form-item">
                <label for="type">Тип :</label>
                <input type="text" id="type" v-model="form.type">
            </div>

            <div class="form-country form-item">
                <label for="country">Страна :</label>
                <input v-model="form.country" type="texe" id="country">
            </div>

            <div class="form-description form-item">
                <label for="description">Комментарий :</label>
                <textarea v-model="form.description" name="description" id="description" maxlength="1000"></textarea>
            </div>

            <div class="form-user form-item">
                <label for="used">Б/у :</label>
                <div class="input-checkbox">
                    <input id="used" type="checkbox" v-model="form.used">
                </div>
            </div>

            <div class="form-images form-item">
                <label  for="images">Images : <span v-if="form.images.length == 0">None</span></label>
                <div class="images">
                    <div v-for="(file, index) in form.images" :key="file.name" class="image">
                        <div @click="deleteImage(index)" class="delete-image">
                            delete
                        </div>

                        <img :src="getUrlfromImage(file)" />
                    </div>
                </div>
                <input type="file" id="images" accept="image/*" @change="eventTargetImage" name="files[]" multiple>
            </div>
            <div class="attributes-in-product">
                <p>attributes in product : <span v-if="Object.keys(form.attributes).length === 0">None</span></p>
                <div class="attributes-list">
                    <div v-for="(value, key) in form.attributes" class="attribute-item">
                        {{ key }} : {{ value }} <div @click="removeAttribute(key)" class="remove-attribute"><span>+</span></div>
                    </div>
                </div>
            </div>
            <div v-if="!createAttribute"
            
            class="form-attributes form-item">
                <select v-model="selectAttribute" name="attributeKey" id="attributeKey">
                    <option v-for="attribute in attributes" :value='attribute'>
                        {{ attribute.key }}
                    </option>
                </select>
                <select v-if="selectAttribute" v-model="selectValue" name="attributeValues" id="attributeValues">
                    <option  v-for="value in selectAttribute.values">
                        {{ value }}
                    </option>
                </select>
                <div class="buttons">
                    <button @click="addAttributeToProduct"  type="button">Add to product</button>
                    <button @click="createAttribute = true">To create a new attribute</button>
                </div>
            </div>

            <div v-if="createAttribute" class="MakeAttribute">
                <div class="inputs">
                    <div class="key">
                        <label for="key">Select or write a new key : </label>
                        <div class="key-select-input">
                            <select v-if="attributes.length > 0 && !createAttributeKey" v-model="new_attribute.key" name="selectKeyForValue" id="">
                            <option v-for="attribute in attributes" :key="attribute.key" :value="attribute.key">
                                {{ attribute.key }}
                            </option>
                        </select>
                        <input v-if="!createAttributeKey" id="key"type="text" v-model="new_attribute.key"> 
                        </div>
                    </div>
                    <div class="value">
                        <label for="value">Value :</label>
                        <input v-model="new_attribute.value" id="value"  type="text">
                    </div>
                </div>
                
                <div class="buttons">
                    <button type="button" @click="createNewAttribute">Create a new attribute</button>

                    <button type="button" @click="createAttribute = false">Return to select</button>
                </div>
            </div>
            <button class="button-save" type="submit"><span v-if="edit">Save changes</span><span v-else>Create product</span></button>
        </form>
        
    </div>
</template>

<script>
import { router } from '@inertiajs/vue3';

    export default {
        data() {
            return {
                edit: false,
                createAttributeKey: false,
                createAttribute: false,
                selectAttribute: null,
                attributeValues : {},
                selectValue: '',
                key_count: 1,
                imagesUrl: [],
                form: {
                    category_id: null,
                    title: null,
                    price: null,
                    discount: null,
                    brand: null,
                    country: null,
                    type: null,
                    description: null,
                    used: false,
                    images: [],
                    attributes: {
                        
                    },
                    
                },
                new_attribute: {
                    key: '',
                    value: '',
                },
            }
        },
        mounted() {
            if (this.$page.url.startsWith('/admin/products/edit')) {

                this.edit = true
                this.form.category_id = this.product.category_id
                this.form.title = this.product.title
                this.form.price = this.product.price
                this.form.discount = this.product.discount
                this.form.brand = this.product.brand
                this.form.country = this.product.country
                this.form.type = this.product.type
                this.form.description = this.product.description
                this.form.used = this.product.used
                this.form.images = this.product.images
                this.form.attributes = this.product.attributes
            }
        },
        computed: {
            getUrlfromImage(file) {
                return (file) => {
                    if (typeof File !== 'undefined' && file instanceof File) {
                        return URL.createObjectURL(file);
                    } else {
                        return file;
                    }
                };
            },
            computed_grid() {
                
            },
        },
        methods: {
            
            submit() {
                console.log(this.form);
                if (!this.edit) {
                    router.post('/admin/products', this.form, {
                        onSuccess: (response) => {
                            console.log('Success:', response);
                        },
                        onError: (errors) => {
                            console.log('Error:', errors);
                        }
                    });
                } else {
                    this.form._method = 'put';
                    router.post(`/admin/products/${this.product.id}`, this.form, {
                        onSuccess: (response) => {
                            console.log('Success:', response);
                        },
                        onError: (errors) => {
                            console.log('Error:', errors);
                        }
                    })
                }
            },
            addAttributeToProduct() {
                this.form.attributes[this.selectAttribute.key] = this.selectValue
                this.new_attribute.value = ''
                this.selectAttribute = null
                this.selectValue = {}
            },
    
            createNewAttribute(){
                const formData = new FormData();
                this.createAttribute = false
                formData.append('key', this.new_attribute.key);
                formData.append('value', this.new_attribute.value)
                router.post('/admin/attributes', this.new_attribute, {
                    onSuccess: (response) => {
                        if (response.status === 200) {
                            this.form.attributes[this.new_attribute.key] = this.new_attribute.value;
                            this.new_attribute.value = ''
                            this.new_attribute.key = ''
                        }
                    },
                    onError: (errors) => {
                        console.log('Error:', errors);
                    }
                });
            },
            eventTargetImage() {
                for (let file of event.target.files) {
                    this.form.images.push(file)
                    console.log(this.form.images);
                }
            },
            deleteImage(index) {
               if (this.edit) {
                this.form.images.splice(index, 1);
               } else {
                this.form.images.splice(index, 1);
               }
            },
            removeAttribute(key) {
                delete this.form.attributes[key]
            },
        },
        props: {
            fillable: Array,
            categories: Array,
            attributes: Object,
            product: Array,
        }
    }
</script>

<style lang="sass" scoped>
#admin-create-product
    width: 100%
    height: auto
    border: 1px solid black
    display: flex
    flex-direction: column
    align-items: center
    padding: 50px 0px
    .form-create
        display: flex
        flex-direction: column
        width: 500px
        gap: 10px
        align-items: center
        .form-item
            width: 100%
            display: grid
            grid-template-columns: 2fr 3fr
            gap: 10px
            label
                text-align: right
                position: relative
                top: 3px
            input, textarea, select
                outline: none
                border-radius: 5px
                height: 30px
                padding: 5px
            .input-checkbox
                input[type='checkbox']
                    cursor: pointer
                    width: 20px
        .form-images
            display: flex
            flex-direction: column
            border: 1px solid grey
            border-radius: 5px
            padding: 20px
            label
                width: 100%
                text-align: left
            .images
                display: flex
                gap: 10px
                border-bottom: 1px solid grey
                padding: 10px
                flex-wrap: wrap
                .image
                    flex-wrap: wrap
                    position: relative
                    &:hover
                        .delete-image
                            display: flex
                    .delete-image
                        position: absolute
                        bottom: 0px
                        width: 100%
                        height: 30%
                        cursor: pointer
                        background-color: darkred
                        text-align: center
                        align-items: center
                        justify-content: center
                        display: none
                    img 
                        width: 100px
                        aspect-ratio: auto
        .attributes-in-product
            display: flex
            border: 1px solid grey
            border-radius: 5px
            padding: 20px
            flex-direction: column
            width: 100%
            .attributes-list
                display: flex
                gap: 5px
                flex-wrap: wrap
                .attribute-item
                    border: 1px solid white
                    padding: 3px
                    position: relative
                    margin-top: 5px
                    flex-wrap: nowrap
                    &:hover
                        .remove-attribute
                            visibility: inherit
                    .remove-attribute
                        position: absolute
                        right: 0px
                        top: 0px
                        background: white
                        height: 100%
                        width: 20px
                        color: black
                        display: flex
                        justify-content: center
                        align-items: center
                        font-weight: bold
                        font-size: 30px
                        visibility: hidden
                        cursor: pointer
                        span
                            transform: rotate(45deg)
                            position: relative
                            top: -1px
                            left: 1px
        .form-attributes
            display: flex
            flex-direction: column
            border: 1px solid grey
            padding: 20px
            .buttons
                display: grid
                gap: 10px
                grid-template-columns: 1fr 1fr
                button
                    height: 30px
                    cursor: pointer
        .MakeAttribute
            width: 100%
            padding: 20px
            border: 1px solid grey
            border-radius: 5px
            display: flex
            flex-direction: column
            gap: 20px
            input
                padding: 5px
            .inputs
                display: grid
                gap: 10px
                .key
                    display: flex
                    gap: 10px
                    flex-direction: column
                    .key-select-input
                        width: 100%
                        gap: 10px
                        display: grid
                        grid-template-columns: 1fr 1fr
                .value
                    display: flex
                    flex-direction: column
                    gap: 10px
                    input
                        width: 100%
            .buttons
                display: grid
                grid-template-columns: 1fr 1fr
                gap: 20px
                button
                    height: 30px
                    cursor: pointer
        .button-save
            margin-top: 5px
            outline: none
            border: none
            height: 30px
            border-radius: 5px
            width: 95%
            cursor: pointer
</style>