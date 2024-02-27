export const state = () => ({
    locales: ['vn', 'en'],
    locale: 'vn',
    selectedLocale: '',
})

export const mutations = {
    SET_LANG(state, locale) {
      state.selectedLocale = locale
    },
}

export const actions = {
    changeLanguage({ commit }, lang) {
      commit('SET_LANGUAGE', lang)
    }
  
}

export const getters = {
    locales: state => state.locales,
    locale: state => state.locales,
  
}