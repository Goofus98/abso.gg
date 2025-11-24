<template>
  <div>
    <navbar />
    <Nuxt />
  </div>
</template>

<script lang="ts">
import {Vue, Component} from "vue-property-decorator";
/*import NavBar from '../components/navbar.vue'

@Component({
    components: {
        NavBar
    }
})*/

@Component
export default class IndexClass extends Vue {
}
</script>
