<template>
    <div class="header">
        <mHeader />
        <nuxt-child />
    </div>
  </template>
  <script>
  import mHeader from './mHeader.vue'
  
  export default {
    name: 'Default',
    components: { mHeader },
  }
  </script>