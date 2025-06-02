import $axios from '../api.js'

const state = () => ({
    barangs: {
        data : [],
        links : {},
        meta : {}
    },
    barang : {
        nama : '',
        satuan : '',
        kuantitas : '',
        nominal : '',
        keterangan : ''
    },
    barang_list :[], //vue select
    pesan : {
        sukses : false
    },
    page: 1,
    perHalaman : 50,
    id: '',
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.barangs = payload;
        
        let barang = payload.data.map( (o) => { 
            return {
                label : o.nama,
                value : o.id,
                satuan : o.satuan,
                kuantitas : o.kuantitas,
                nominal : o.nominal
            }
        } );
        state.barang_list = barang;



    },
    CLEAR_FORM(state) {
        state.barang.nama = '';   
        state.barang.satuan = '';  
        state.barang.kuantitas = '';  
        state.barang.nominal = '';  
        state.barang.keterangan = '';  
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.barang.nama =payload.nama;
        state.barang.satuan =payload.satuan;      
        state.barang.kuantitas =payload.kuantitas;   
        state.barang.nominal =payload.nominal;   
        state.barang.keterangan =payload.keterangan;   
    },
}

const actions = {
    get_barang({ commit, state }, payload) {     
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/barang`, {                
                params: {     
                    page : state.page, 
                    per_page: state.perHalaman,
                    q: search,                                                   
                }
            })
            .then((response) => {
                // console.info('tag', response.data)
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    },
    submit_barang({ dispatch, commit, state }) {
       
        return new Promise((resolve, reject) => {
            $axios.post(`/barang`, state.barang)
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
    edit_barang({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/barang/${payload}/edit`)
            .then((response) => {        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_barang({ commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.put(`/barang/${state.id}`, state.barang)
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
    remove_barang({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/barang/${payload}`)
            .then((response) => {
                dispatch('get_barang').then(() => resolve(response.data))
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