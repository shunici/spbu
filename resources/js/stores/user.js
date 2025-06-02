import $axios from '../api.js'

const state = () => ({
    users: [],
    roles: [],
    permissions: [],
    role_permission: [],
    authenticated: [],
    foto_db : '',  
    page: 1,
    perHalaman : 10,
    
})

const mutations = {
    
    SET_PAGE(state, payload) {
        state.page = payload
    },
    ASSIGN_USER(state, payload) {
        state.users = payload
    },
    ASSIGN_ROLES(state, payload) {
        state.roles = payload
    },
    ASSIGN_PERMISSION(state, payload) {
        state.permissions = payload
    },
    ASSIGN_ROLE_PERMISSION(state, payload) {
        state.role_permission = payload
    },
    CLEAR_ROLE_PERMISSION(state, payload) {
        state.role_permission = []
    },
    ASSIGN_USER_AUTH(state, payload) {
        state.authenticated = payload
        state.authenticated.foto = '/storage/user/' + payload.foto
    },            
    SHOW_FOTO(state, payload) {       
        state.authenticated.foto =  payload   
    },  
    SIMPAN_FOTO(state, payload) {    
        state.foto_db =  payload   
    },
}

const actions = {
    getUserLists({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/user-lists`, {
                params: {     
                    page : state.page, 
                    per_page: state.perHalaman,
                    q: search,                                                                   }
            })
            .then((response) => {                     
                commit('ASSIGN_USER', response.data)
                resolve(response.data)
            })
        })
    },
    setRoleUser({commit}, payload) {
        return new Promise((resolve, reject) => {
            commit('CLEAR_ERRORS', '', {root: true})
            $axios.post(`/set-role-user`, payload)
            .then((response) => {             
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    getRoles({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/roles`)
            .then((response) => {
                commit('ASSIGN_ROLES', response.data.data)
                resolve(response.data)
            })
        })
    },
    getAllPermission({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/permissions`)
            .then((response) => {
                commit('ASSIGN_PERMISSION', response.data.data)
                resolve(response.data)
            })
        })
    },
    getRolePermission({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit('CLEAR_ERRORS', '', {root: true})
            $axios.post(`/role-permission`, {role_id: payload})
            .then((response) => {
                commit('ASSIGN_ROLE_PERMISSION', response.data.data)
                resolve(response.data)
            })
        })
    },
    setRolePermission({ commit }, payload) {
        return new Promise((resolve, reject) => {
            commit('CLEAR_ERRORS', '', {root: true})
            $axios.post(`/set-role-permission`, payload)
            .then((response) => {
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    getUserLogin({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`user-authenticated`)
            .then((response) => {
                                // console.info('user-authenticate', response.data.data)
                commit('ASSIGN_USER_AUTH', response.data.data)
                resolve(response.data)
            })
        })
    },   
    update_user({ commit, state, dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.post(`/update-user/${state.authenticated.id}`, payload, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {
                // console.info('data', response.data)
                $(document).Toasts('create', {title: 'Berhasil diproses..', body: 'Data telah sukses'}); 
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },        
    remove_user({dispatch}, payload) {
        return new Promise((resolve, reject) => {
            $axios.post(`/update-delete/${payload}`)
            .then((response) => {
                dispatch('getUserLists').then(() => resolve(response.data))
            })
        })
    },
      
    ubah_password({ commit, state, dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.post(`/change-password`, payload)
            .then((response) => {  
                $(document).Toasts('create', {title: 'Berhasil diproses..', body: 'Data telah sukses'}); 
                resolve(response.data)
            })
            .catch((error) => {
                console.info('ini error', error.response)
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



