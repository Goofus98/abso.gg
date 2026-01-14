<template>
  <v-app>
    <navbar />
    <v-main>
      <Nuxt/>
    </v-main>
  </v-app>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import { getModule } from "vuex-module-decorators";
import GModServersModule from "../store/gmodServers";
import CommunityStatsModule from "../store/communityStats";

@Component({
  //head() {
    //return {
      //link: [
      //  {
       //   rel: 'stylesheet',
        //  href: 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap'
       //}
      //]
    //}
 // },
  //async fetch(this: DefaultLayout) {

      //console.log("updating the thuing");
      //const gmodServerModule = getModule(GModServersModule, this.$store);
      //await gmodServerModule.initalize();

  //},
  //fetchOnServer: false,
})
export default class DefaultLayout extends Vue {
  async created(){
    const gmodServerModule = getModule(GModServersModule, this.$store);
    await gmodServerModule.initalize();

    const CommunStatsModule = getModule(CommunityStatsModule, this.$store);
    await CommunStatsModule.initalize();
  }
  mounted() {
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
