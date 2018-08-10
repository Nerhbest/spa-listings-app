<template>
    <v-layout>
        <v-dialog :value="needShowCategoryContainer"
                  fullscreen
        >
            <v-card  class="category-container">
                <v-card-title class="category-container__title">
                        <v-btn icon dark @click="toggleCategoryContainer">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <h3 >Выберите категорию</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3
                                v-for="(category,index) in categories"
                                :key="index"
                                class="text-xs-center mb-3"
                                @click.stop="handleActiveCategory(category)"
                        >
                            <h4>{{ category.name }}</h4>
                            <v-flex xs12
                                    v-for="(subCategory) in category.children"
                                    :key="subCategory.id"
                                    @click.stop="handleActiveCategory(subCategory)"
                            >
                                {{ subCategory.name }}
                            </v-flex>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
   export default {
        name: 'category-dialog-container',
        props: ['categories',
                'needShowCategoryContainer'],
        methods: {
            toggleCategoryContainer()
            {
                this.$emit("toggle-category-container");
            },
            handleActiveCategory(category)
            {
                this.$emit('handle-active-category', category);
                this.toggleCategoryContainer()
            }
        }
   }
</script>

<style lang="stylus" scoped>
</style>

