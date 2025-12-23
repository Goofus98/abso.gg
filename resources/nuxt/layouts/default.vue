<template>
  <v-app>
    <navbar />
    <v-main>
      <Nuxt />
    </v-main>
  </v-app>
</template>

<script>
  import Vuetify from 'vuetify'


  export default {
      head() {
      return {
        link: [
          {
            rel: 'stylesheet',
            href: 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap'
          }
        ]
      }
    },
    mounted () {
      this.$vuetify.theme.dark = true
    }

  }
</script>

<style>
html, body {
  overflow: auto !important 
}

main {
  background-image: url('/images/bg.svg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}
</style>
