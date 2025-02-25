<template>
    <div
    :style="{
        width: styles.width,
        height: styles.height,
        borderRadius: styles.borderRadius,
        backgroundColor: styles.dark1,
        backgroundImage: `url(${form.image})`,
    }"
    id="CategoryCard">
        <div class="category-card-top">
            <div class="category-cart-top-left">
                
            </div>
           <div
           :style="{
            backgroundColor : styles.dark2,
           }"
           @click="!edit ? editCategoryCard() : edit = !edit"
           class="cube edit">
                <span v-if="edit">Edit</span>
                <span 
                v-if="!edit">Save</span>
           </div>
           <div
            :style="{
                backgroundColor : styles.dark2,
            }" 
            @click="categoryDelete"
            class="cube delete">
                Del
           </div>
        </div>
        <div class="category-card-bot">
            <form 
            v-if="!edit"
            @submit.prevent="editCategoryCard"
            class="form-edit-card">
            <input :style="{
                  backgroundColor: styles.dark2,
                  border: '1px solid ' + styles.dark2
                  }" 
                  type="text" v-model="form.name" placeholder="Название категории" required />
            <input
                :style="{
                  backgroundColor: styles.dark2,
                  border: '1px solid ' + styles.dark2
                  }" 
                  @change="handleFileUpload"
                type="file" accept="image/*"/>
            </form>
            <p v-if="edit"
            :style="{
                backgroundColor: styles.dark2,
                borderRadius: styles.borderRadius
            }"
            >{{ form.name }}</p>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { router, useForm } from '@inertiajs/vue3';
    export default {
        data() {
            return {
                edit: true,
                new_image: null,
                form: useForm({
                    _method: 'put',
                    name: this.category.name,
                    image: this.category.image_url,
                    image_path: this.category.image,
                    image_file : null,
                })
            }
        },
        props: {
            category: {
                type: Object
            },
        },
        mounted() {
         
        },
        computed: {
            styles() {
                return this.$page.props.styles
            }
        },
        methods: {
            editCategoryCard() {
                if (this.new_image != this.category.image_url && this.new_image != null) {
                    this.form.image = this.new_image;
                }
                router.post('categories/' + this.category.id, this.form, {
                    onSuccess: (response) => {
                        //console.log(response);
                        this.edit = true;
                    },
                    onError: (errors) => {
                        console.error('Error', errors);
                    }
                });
            },
            categoryDelete() {
                router.delete(`categories/${this.category.id}`, this.category)
            },
            handleFileUpload(event) {
                this.form.image_file = event.target.files[0];
                if (this.form.image_file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                    this.new_image = e.target.result;
                };
                    reader.readAsDataURL(this.form.image_file);
                }
            },
        },
    }
</script>

<style lang="sass" scoped>
    #CategoryCard
        padding: 25px
        overflow: hidden
        background-size: cover
        background-repeat: no-repeat
        display: flex
        flex-direction: column
        justify-content: space-between
        .category-card-top
            display: flex
            justify-content: end
            gap: 7px
            .cube
                width: 50px
                height: 50px
                border-radius: 15px
                display: flex
                justify-content: center
                align-items: center
                text-align: center
                cursor: pointer
                font-size: 12px
        .category-card-bot
            display: flex
            p
                width: 100%
                padding: 15px
                display: flex
                justify-content: center
                align-items: center
                text-align: center
            form
                width: 100%
                display: flex
                flex-direction: column
                gap: 10px
                input
                    padding: 0px 20px
                    border-radius: 15px
                    width: 100%
                    height: 50px
                    display: flex
                    justify-content: center
                    align-items: center
                    color: white
                input[type='file']
                    padding: 14px 0 0 15px
                    display: flex
                    cursor: pointer
                    &::after
                        display: flex
                        justify-content: center
                        align-items: center
                        text-align: center
                        width: 116px
                        height: 30px
                        border-radius: 10px
                        content: 'Upload Image'
                        color: #2E2E2E
                        display: flex
                        background: white
                        position: relative
                        bottom: 26px
                        left: -1px
                        font-weight: bold
                        cursor: pointer
            
</style>