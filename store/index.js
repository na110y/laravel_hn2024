export const state = () => ({
    locales: ['vn', 'en'],
    locale: 'vn',
    selectedLocale: '',
    role: null,
})

export const mutations = {
    SET_LANG(state, locale) {
      state.selectedLocale = locale
    },
    SET_ROLE(state, role) {
      state.role = role
    }
}

export const actions = {
    changeLanguage({ commit }, lang) {
      commit('SET_LANGUAGE', lang)
    },
    async setUserRole({ commit }, role) {
      commit('SET_ROLE', role)
    }
  
}

export const getters = {
    locales: state => state.locales,
    locale: state => state.locales,
  
}