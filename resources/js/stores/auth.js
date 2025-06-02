import $axios from '../api.js'

const state = () => ({
 
})

const mutations = {
    
}

const actions = {
    submit({ commit }, payload) {      
        localStorage.setItem('token', null)
        commit('SET_TOKEN', null, { root: true })
        return new Promise((resolve, reject) => {
            $axios.post('/login', payload)
            .then((response) => {
                console.info('tag', response)
                if (response.data.status == 'success') {
                    localStorage.setItem('token', response.data.token)
                    commit('SET_TOKEN', response.data.token, { root: true })
                } else {
                    commit('SET_ERRORS', { invalid: 'Email/Password Salah' }, { root: true })
                }
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    logout_user() {            
        return new Promise((resolve, reject) => {
            $axios.post('/logout')     
            .then((response) => {
                console.info('tess', response.data)
                resolve(response.data)
            })
        })
    },    
    register({ commit }, payload) {             
        return new Promise((resolve, reject) => {            
            $axios.post('/register', payload)
            .then((response) => {                        
                if (response.data.status == 'success') {             
                    localStorage.setItem('token', response.data.token)
                    commit('SET_TOKEN', response.data.token, { root: true })
                } else {
                    commit('SET_ERRORS',  { root: true })
                }
            
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}