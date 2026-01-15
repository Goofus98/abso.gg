<template>
  <svg
    class="avatar-svg"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    :style="svgStyle"
    viewBox="0 0 152 152"
  >
    <g>
      <!-- <rect width="152" height="152" fill="#1b2838" rx="8" ry="8" /> -->

      <!-- profile image -->
      <image
        x="14"
        y="14"
        width="125"
        height="125"
        :href="avatarHref"
        :xlink:href="avatarHref"
        preserveAspectRatio="xMidYMid slice"
        loading="lazy"
      />

      <!-- frame -->
      <image
        x="0"
        y="0"
        width="152"
        height="152"
        :href="frameHref"
        :xlink:href="frameHref"
        preserveAspectRatio="none"
        loading="lazy"
      />
    </g>
  </svg>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  avatarHref: {
    type: String,
    required: true
  },
  frameHref: {
    type: String,
    required: false,
    default: null
  },
  size: {
    type: [Number, String],
    default: 48
  }
})

const svgStyle = computed(() => ({
  width: `${props.size}px`,
  height: `${props.size}px`
}))
</script>

<style scoped>
.avatar-svg {
  display: inline-block;
}
</style>