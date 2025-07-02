import $axios from '../api.js'
import moment from "moment"
moment.locale('id');
const state = () => ({
    gajihs: {
        data : [],
        links : {},
        meta : {}
    },
    intensive : [],
    user : {
        name : '',
        jabatan : {
            nama_jabatan : ''
        }
    },
    gajih : {
        user_id : '',
        penerimaan : 0,
        ket_penerimaan : [],
        pengurangan : 0,   
        ket_pengurangan :[],
        sdh_terima : 0,
        keterangan : '',
        gajih_pokok : 0, //keperluan v-select aja
        tgl : moment().format('YYYY-MM-DD HH:mm:ss'), 
    },  
    hidden_on : {aktif : true},  
    user_selected :  { value: '', label: 'PILIH USER', gajih_pokok : 0}, //vselect
    pesan : {
        sukses : false
    },         
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),
    page: 1, 
    id: '',
    user_list : [], //user yang belum insert di tabel gajih
    total_user : [],
    fields: [             
        {key: 'no', label : 'No', visible : true, class : 'text-center'},
       {key: 'nama', label : 'Nama', visible : true},
        {key: 'jabatan', label : 'Jabatan', visible : true},
       {key: 'penerimaan', label : 'Penerimaan', visible : true},  
        {key: 'pengurangan', label : 'Pengurangan', visible : true}, 
         {key: 'total', label : 'Total', visible : true}, 
         {key: '#', label : '#', visible : true},                                                       
       {key: 'aksi', label : 'Aksi', visible : false}, //TAMBAHKAN CODE INI
   ], 
})

const mutations = {
    ASSIGN_DATA(state, payload) {
       state.gajihs = payload            
    },
    ASSIGN_INTENSIVE(state, payload) {
       state.intensive = payload            
    },
    
    ASSIGN_USER(state, payload) {  
        console.info('user', payload)    
        var user =  payload.map( (o) => { 
            var jabatan = o.jabatan;
            var gajih_pokok = 0;
            if(jabatan != null) {
                jabatan = o.jabatan.nama_jabatan;            
                gajih_pokok = o.jabatan.gajih_pokok;
            } else {
                jabatan = 'belum ada jabatan';

            }

            return {
                label : o.name + " - " + jabatan,
                value : o.id,
                foto : o.foto,
                gajih_pokok : gajih_pokok                
            }
        } );         
        state.user_list = user;      
    },
    ASSIGN_TOTAL_USER(state, payload) { //total user aktif
        state.total_user = payload;   
    },    

    CLEAR_FORM(state) {   
        state.gajih.penerimaan = 0      
        state.gajih.pengurangan = 0     
        state.gajih.sdh_terima = 0  
        state.gajih.gajih_pokok = 0

        state.user_selected.value = "";
        state.user_selected.label = "PILIH USER";
        state.user_selected.gajih_pokok = 0;
        

    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.user = payload.user;  
        state.user_selected.label = payload.user.name;  
        state.user_selected.value = payload.user.id;        
        state.gajih.user_id = payload.user_id;  
        state.gajih.penerimaan = payload.penerimaan;  
        state.gajih.ket_penerimaan = JSON.parse(payload.ket_penerimaan); 
        state.gajih.pengurangan = payload.pengurangan;  
        state.gajih.ket_pengurangan = JSON.parse(payload.ket_pengurangan);
        state.gajih.sdh_terima = payload.sdh_terima;  
        state.gajih.keterangan = payload.keterangan;    
        state.gajih.gajih_pokok = payload.gajih_pokok;  
        state.gajih.created_at = payload.created_at; 
        state.gajih.tgl = payload.tgl; 
        
    },
}

const actions = {
    get_intensive({ commit, state }, payload) {               
        return new Promise((resolve, reject) => {            
            $axios.get(`/gajih-intensive`, {                
                params: {                   
                    bulan : state.bulan,
                    tahun : state.tahun,
                    id :    payload                                          
                }
            })
            .then((response) => {   
                console.info('intensive', response.data)             
                commit('ASSIGN_INTENSIVE', response.data.data)              
                resolve(response.data)
            })
        })
    },
    get_gajih({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/gajih`, {                
                params: {                   
                    bulan : state.bulan,
                    tahun : state.tahun                                               
                }
            })
            .then((response) => {
        console.info('gajih', response.data.data)
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    },
    submit_gajih({ dispatch, commit, state }) {
       
        return new Promise((resolve, reject) => {
            $axios.post(`/gajih`, state.gajih)
            .then((response) => {       
                dispatch('data_user')
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
    data_user({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/data-user/${payload}`)
            .then((response) => {                       
                commit('ASSIGN_USER', response.data.data)
                commit('ASSIGN_TOTAL_USER', response.data.total_user)
                resolve(response.data)
            })
        })
    },
    edit_gajih({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/gajih/${payload}/edit`)
            .then((response) => {        
                console.info('gajih', response.data.data)            
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_gajih({ commit, state }) {      
        return new Promise((resolve, reject) => {
            $axios.put(`/gajih/${state.id}`, state.gajih)
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
    remove_gajih({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/gajih/${payload}`)
            .then((response) => {
                dispatch('data_user')
                dispatch('get_gajih').then(() => resolve(response.data))
            })
        })
    },
    kirim_slip({commit}, payload){

        return new Promise((resolve, reject) => {
           $axios.post(`/kirim-slip`, payload, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
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
        
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}