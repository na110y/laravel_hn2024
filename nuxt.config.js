export default {
  head: {
    title: 'Nuxt2024',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  css: [
    'bootstrap/dist/css/bootstrap.css',
    'bootstrap-vue/dist/bootstrap-vue.css'
  ],

  plugins: [
    '~/plugins/api/userApi.js',
    '~/plugins/i18n.js'
  ],

  components: true,

  buildModules: [
    '@nuxtjs/fontawesome'
  ],
  styleResources: {
    scss: [
      '~assets/scss/_reset.scss',
      '~assets/scss/_variables.scss',
      '~assets/scss/main.scss'
    ]
  },

  modules: [
    'bootstrap-vue/nuxt',
    '@nuxtjs/axios',
    '@nuxtjs/pwa',
    '@nuxtjs/auth-next',
  ],

  axios: {
    baseURL: '/',
  },

  pwa: {
    manifest: {
      lang: 'en'
    }
  },

  build: {
  },

}
