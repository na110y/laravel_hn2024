export const state = () => ({
    locales: ['vn', 'en'],
    locale: 'vn',
    selectedLocale: '',
    user_name: '',
})

export const mutations = {
    SET_LANG(state, locale) {
      state.selectedLocale = locale
    },
    SET_INFOLOGIN(state, user_name) {
      state.user_name = user_name;
    }
}

export const actions = {
    changeLanguage({ commit }, lang) {
      commit('SET_LANGUAGE', lang)
    }
  
}

export const getters = {
    locales: state => state.locales,
    locale: state => state.locales,
    user_name: state => state.user_name,
  
}