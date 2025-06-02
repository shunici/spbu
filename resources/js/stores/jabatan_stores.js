import $axios from '../api.js'

const state = () => ({
    jabatans: {
        data : [],
        links : {},
        meta : {}
    },
    jabatan : {
        gajih_pokok : '',
        nama_jabatan : '',  
        fungsi_tugas : '',    
    },
    pesan : {
        sukses : false
    },
    page: 1,
    id: '',
    userJabatan : []
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.jabatans = payload; 
        
        let user_jabatan = payload.data.map( (o) => { 
            return {
                label : o.nama_jabatan,
                value : o.id,
                gajih_pokok : o.gajih_pokok
            }
        } );
        state.userJabatan = user_jabatan; 
        console.info('jabatan', state.userJabatan)             
    },
    CLEAR_FORM(state) {
        state.jabatan.nama_jabatan = '';
        state.jabatan.gajih_pokok = '';  
        state.jabatan.fungsi_tugas = '';      
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.jabatan.nama_jabatan = payload.nama_jabatan;
        state.jabatan.gajih_pokok = payload.gajih_pokok;      
        state.jabatan.fungsi_tugas = payload.fungsi_tugas;  
    },
}

const actions = {
    get_jabatan({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/jabatan`, {                
                params: {     
                    page : state.page,                 
                    q: search,                                                   
                }
            })
            .then((response) => {
                
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    },
    submit_jabatan({ dispatch, commit, state }) {
       
        return new Promise((resolve, reject) => {
            $axios.post(`/jabatan`, state.jabatan)
            .then((response) => {              
                commit('CLEAR_ERRORS', [] , { root: true })
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }else {
                    commit('SET_ERRORS', {'koneksi' : 'PERIKSA INTERNET ANDA'} , { root: true })          
                }
            })
        })
    },
    edit_jabatan({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/jabatan/${payload}/edit`)
            .then((response) => {        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_jabatan({ commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.put(`/jabatan/${state.id}`, state.jabatan)
            .then((response) => {
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }else {
                    commit('SET_ERRORS', {'koneksi' : 'PERIKSA INTERNET ANDA'} , { root: true })          
                }
            })
        })
    },
    remove_jabatan({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/jabatan/${payload}`)
            .then((response) => {
                dispatch('get_jabatan').then(() => resolve(response.data))
            })
        })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}