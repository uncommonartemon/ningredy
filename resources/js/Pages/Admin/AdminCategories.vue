<script>
import { Inertia } from '@inertiajs/inertia';
import AdminHeader from './Components/AdminHeader.vue';
import AdminCategoryCard from './Components/AdminCategoryCard.vue';
import { router } from '@inertiajs/vue3';

export default {
  components: {
      AdminHeader,
      AdminCategoryCard,
  },
  props: {
      categories: Array
  },
  data() {
      return {
          name: '',
          image: null,
          white: '#d9e9cf',
          imageUrl: null,
      }
  },
  methods: {
      handleFileUpload(event) {
          this.image = event.target.files[0];
          if (this.image) {
            const reader = new FileReader();
            reader.onload = (e) => {
              this.imageUrl = e.target.result;
            };
            reader.readAsDataURL(this.image);
          }
      },
      submitForm() {
          const formData = new FormData();
          formData.append('name', this.name);
          if (this.image) {
              formData.append('image', this.image);
          }
          
          router.post('/admin/categories', formData);
      }
  }, 
  computed: {
    styles() {
      return this.$page.props.styles
    }
  },
  mounted() {

  }
}
</script>

<template>
  <div id="AdminCategories">
      <div 
      :style="{
        display: 'grid',
        gridTemplateColumns: `repeat(auto-fill, minmax(${styles.width}, 1fr))`
      }"
      class="admin-categories-grid">
        <form
          :style="{
            backgroundColor: white,
            color: 'black',
            backgroundImage: imageUrl ? `url(${imageUrl})` : 'none',
            backgroundSize: 'cover',
            width: styles.width,
            height: styles.height,
            borderRadius: styles.borderRadius,
            backgroundColor: styles.dark1,
            borderColor: styles.light,
          }"
          class="add-new-category" 
          @submit.prevent="submitForm">
              <div class="new-category-inputs">
                <input :style="{
                  backgroundColor: styles.dark2,
                  border: '1px solid ' + styles.dark2
                  }" 
                  type="text" v-model="name" placeholder="Название категории" required />
                <input
                :style="{
                  backgroundColor: styles.dark2,
                  border: '1px solid ' + styles.dark2
                  }" 
                type="file" accept="image/*" @change="handleFileUpload" />
                
              </div>
              <button 
              :style="{
                backgroundColor : styles.dark2,
                color: styles.light
              }"
              type="submit">Создать категорию</button>
        </form> 
        <transition-group
          name="category-card"
        >
          <AdminCategoryCard
            v-for="category in categories" :key="category.id"
            :category="category" 
          >  
          </AdminCategoryCard>
        </transition-group> 
  
      </div>
  </div>
</template>


<style lang="sass">
  #AdminCategories
    display: flex
    padding: 50px 
    height: 100%
    flex-wrap: wrap
    .admin-categories-grid
      display: grid
      width: 100%
      flex-wrap: wrap
      row-gap: 25px
      justify-content: center
      place-items: center
      .add-new-category
          background: black
          display: flex
          flex-direction: column
          justify-content: space-between
          padding: 30px

          .new-category-inputs
              display: flex
              flex-direction: column
              gap: 20px
              input
                  padding: 0px 20px
                  border-radius: 15px
                  height: 50px
                  display: flex
                  justify-content: center
                  align-items: center
                  color: white
                  &::placeholder
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
          button
              height: 50px
              border-radius: 20px
              cursor: pointer
              border: 1px solid #2E2E2E
              font-size: 13px
              font-weight: 600
              transition: color 0.2s ease, background-color 0.2s ease, font-weight 0.3s ease, border-color 0.2s ease
              &:hover
                color: white
                background-color: #2E2E2E
                border-color: white

.slide-enter-active, .slide-leave-active
  transition: all 0.5s ease

.slide-enter-from, .slide-leave-to
  transform: translateX(100%)
</style>