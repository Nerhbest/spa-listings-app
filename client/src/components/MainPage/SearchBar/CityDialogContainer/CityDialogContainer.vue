<template>
    <v-layout>
        <v-dialog :value="needShowCityContainer"
                  fullscreen
        >
            <v-card  class="category-container">
                <v-card-title class="category-container__title">
                    <v-btn icon dark @click="toggleCityContainer">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <h3>Выберите Город</h3>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3
                                v-for="city in cities"
                                :key="city.id"
                                class="text-xs-center mb-3"
                                @click.stop="handleActiveCity(city)"
                        >
                            <h4>{{ city.name }}</h4>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
   export default {
    name: 'city-dialog-container',
    props: ['needShowCityContainer', 'cities'],
    methods: {
        handleActiveCity(city)
        {
            this.$emit('handle-active-city', city);
            this.toggleCityContainer();
        },
        toggleCityContainer()
        {
            this.$emit('toggle-city-container');
        }
    }

   }
</script>

<style lang="stylus">

</style>
