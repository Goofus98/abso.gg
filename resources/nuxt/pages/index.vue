<template>
    <h1>{{ thing }}</h1>

</template>

<script lang="ts">
import {Vue, Component} from "vue-property-decorator";

@Component
export default class IndexClass extends Vue {
    thing = 69;
}
</script>

<style></style>