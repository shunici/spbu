import $axios from '../api.js'
import moment from "moment"
moment.locale('id');
const state = () => ({
    kass: {
        data : [],
        links : {},
        meta : {}
    },
    kas : {
        uraian : '',
        input : '',
        kantor : '',
        bank : '',
        jenis_aksi : 'kantor_ke_bank', 
        user_id : '',
        history : '',
        foto : '',  
    },
    kas_sekarang : {
            kantor : 0,
            bank : 0,
    },  
    pesan : {
        sukses : false
    },       
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),
    id: '', 
moveValue: 0, // Nilai yang akan dipindahkan
hidden_on : {
    aktif : true
}

})

const mutations = {
    ASSIGN_DATA(state, payload) {        
        state.kass = payload;
    },
    
    KAS_SEKARANG(state, payload) {
        state.kas_sekarang.kantor = payload.kantor;
        state.kas_sekarang.bank = payload.bank;

        // state.kas.kantor = payload.kantor;
        // state.kas.bank = payload.bank;

    },
    CLEAR_FORM(state) {
       
    },

    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.kas.nama =payload.nama;
        state.kas.jenis =payload.jenis;      
    },
    SET_moveValue(state, payload) {
        
        if (state.kas.jenis_aksi === 'kantor_ke_bank') {                        
            state.kas.kantor =  parseInt(state.kas_sekarang.kantor) - parseInt(payload);
            state.kas.bank =  parseInt(state.kas_sekarang.bank) + parseInt(payload);
          } 
        
          if (state.kas.jenis_aksi === 'bank_ke_kantor') {                                                        
            state.kas.kantor =  parseInt(state.kas_sekarang.kantor) + parseInt(payload);
            state.kas.bank =  parseInt(state.kas_sekarang.bank) - parseInt(payload);
          } 
          state.kas.input = payload;
          state.moveValue = payload;

    },
}

const actions = {
    get_kas({ commit, state, dispatch }) {      
        return new Promise((resolve, reject) => {
            $axios.get(`/kas`, {                
                params: {                   
                    bulan : state.bulan,
                    tahun : state.tahun                                               
                }
            })
            .then((response) => {          
                // console.info('kass', response.data)               
                commit('ASSIGN_DATA', response.data)    
                dispatch('get_kas_sekarang').then(() => resolve(response.data))          
            
            })
        })
    },
    submit_kas({ dispatch, commit, state }) {
       
        return new Promise((resolve, reject) => {
            $axios.post(`/kas`, state.kas)
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
    get_kas_sekarang({ commit }) {      
        return new Promise((resolve, reject) => {
            $axios.get(`/kas-sekarang`)
            .then((response) => {   
                // console.info('kas_sekarang', response.data)     
                commit('KAS_SEKARANG', response.data.data)
                resolve(response.data)
            })
        })
    },
    remove_kas({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/kas/${payload}`)
            .then((response) => {
                dispatch('get_kas_sekarang')
                dispatch('get_kas').then(() => resolve(response.data))
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