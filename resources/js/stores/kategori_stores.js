import $axios from '../api.js'

const state = () => ({
    kategoris: {
        data : [],
        links : {},
        meta : {}
    },
    kategori : {
        nama : '',
        jenis : '',   
    },
    kategori_pengeluaran :[], //vue select
    kategori_pemasukan :[], //vue select
    pesan : {
        sukses : false
    },
    id: '',
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.kategoris = payload;
        
//untuk inisiasi vue select pengeluaran
        var pengeluaran = payload.data.filter(function(peng) {
            return peng.jenis.toLowerCase() === 'pengeluaran';
          });

        let arr_peng = pengeluaran.map( (o) => { 
            return {
                label : o.nama,
                value : o.id
            }
        } );
        state.kategori_pengeluaran = arr_peng;

        //untuk inisiasi vue select pemasukan
        var pemasukan = payload.data.filter(function(pem) {
            return pem.jenis.toLowerCase() === 'pemasukan';
          });

          let arr_pem = pemasukan.map( (o) => { 

            return {
                label : o.nama,
                value : o.id
            }

        } );
        state.kategori_pemasukan = arr_pem;
        console.info('pemasukan', arr_pem)


    },
    CLEAR_FORM(state) {
        state.kategori.nama = '';
        state.kategori.jenis = '';      
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.kategori.nama =payload.nama;
        state.kategori.jenis =payload.jenis;      
    },
}

const actions = {
    get_kategori({ commit, state }, payload) {     
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/kategori`, {                
                params: {                      
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
    submit_kategori({ dispatch, commit, state }) {
       
        return new Promise((resolve, reject) => {
            $axios.post(`/kategori`, state.kategori)
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
    edit_kategori({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/kategori/${payload}/edit`)
            .then((response) => {        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_kategori({ commit, state }) {
        return new Promise((resolve, reject) => {
            $axios.put(`/kategori/${state.id}`, state.kategori)
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
    remove_kategori({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/kategori/${payload}`)
            .then((response) => {
                dispatch('get_kategori').then(() => resolve(response.data))
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