import $axios from '../api.js'
import moment from "moment"
const state = () => ({
    realisasis: {
        data : [],
        links : {},
        meta : {}
    },
    realisasi : {
        uraian : '',
        toko : '',
        qty : 1,
        satuan : '',        
        nominal : 0, //harga satuan
        total : '',
        keterangan : '',
        foto : '',
        tgl : moment().format('YYYY-MM-DD HH:mm:ss')  
    }, 
    pesan : {
        sukses : false
    },
    foto_db : '',
    page: 1,
    perHalaman : 50,
    id: '',
    hidden_on : {aktif : true},             
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.realisasis = payload;     
    },
    CLEAR_FORM(state) {
        state.realisasi.nama = '';   
        state.realisasi.satuan = '';  
        state.realisasi.kuantitas = '';  
        state.realisasi.nominal = '';  
        state.realisasi.keterangan = '';  
        state.realisasi.tgl = moment().format('YYYY-MM-DD HH:mm:ss');
        state.foto_db =  ''  
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.realisasi.uraian =payload.uraian;
        state.realisasi.toko =payload.toko; 
        state.realisasi.qty =payload.qty; 
        state.realisasi.satuan =payload.satuan;              
        state.realisasi.nominal =payload.nominal; 
        state.realisasi.total =payload.total;   
        state.realisasi.keterangan =payload.keterangan;   
        state.realisasi.foto = '/storage/realisasi/' + payload.foto; 
        state.realisasi.tgl =payload.tgl;  
    },             
    SHOW_FOTO(state, payload) {
        state.realisasi.foto =  payload   
    },  
    SIMPAN_FOTO(state, payload) {
        state.foto_db =  payload   
    },
}

const actions = {
    get_realisasi({ commit, state }, payload) {     
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/realisasi`, {                
                params: {     
                    bulan : state.bulan,
                    tahun : state.tahun                                                    
                }
            })
            .then((response) => {
                console.info('realisasi', response.data)
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    },
    submit_realisasi({ dispatch, commit, state }) {   
        let form = new FormData();        
        form.append('uraian', state.realisasi.uraian)
        form.append('toko', state.realisasi.toko)
        form.append('qty', state.realisasi.qty)
        form.append('satuan', state.realisasi.satuan)
        form.append('nominal', state.realisasi.nominal)
        form.append('total', state.realisasi.total)
        form.append('keterangan', state.realisasi.keterangan)
        form.append('foto', state.foto_db)
        form.append('tgl', state.realisasi.tgl)

        return new Promise((resolve, reject) => {
            $axios.post(`/realisasi`, form, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {                                   
                dispatch('get_realisasi');                
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
    edit_realisasi({ dispatch, commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/realisasi/${payload}/edit`)
            .then((response) => {        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_realisasi({ commit, state, dispatch}) {
        
        let form = new FormData();        
        form.append('uraian', state.realisasi.uraian)
        form.append('toko', state.realisasi.toko)
        form.append('qty', state.realisasi.qty)
        form.append('satuan', state.realisasi.satuan)
        form.append('nominal', state.realisasi.nominal)
        form.append('total', state.realisasi.total)
        form.append('keterangan', state.realisasi.keterangan)
        form.append('foto', state.foto_db)
        form.append('tgl', state.realisasi.tgl)
        
        return new Promise((resolve, reject) => {
            $axios.post(`/realisasi/update/${state.id}`, form,{
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {
                dispatch('get_realisasi'); 
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
    remove_realisasi({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/realisasi/${payload}`)
            .then((response) => {
                dispatch('get_realisasi').then(() => resolve(response.data))
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