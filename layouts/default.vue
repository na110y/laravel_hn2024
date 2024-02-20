<template>
    <div class="header">
        <mHeader />
        <nuxt-child />
        <mFooter />

    </div>
  </template>
  <script>
  import mHeader from './mHeader.vue'
  import mFooter from './mFooter.vue'
  
  export default {
    name: 'Default',
    components: { mHeader, mFooter }
  }
  </script>