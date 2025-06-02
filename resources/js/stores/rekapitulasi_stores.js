import $axios from '../api.js'
import moment from "moment"
moment.locale('id');
const state = () => ({
    rekapitulasis: {
        data : [],
        links : {},
        meta : {}
    },
    rekapitulasi_show : [],
    rekapitulasi : {
        uraian : '',
        total : '',
        kategori_id : '',
        user_id : '',
        user : '',
        rekapitulasi_id : '',  
        foto : '',                               
    },  
    rekapitulasi_sekarang : {
        keterangan : '',
        saldo_awal : '',
        pemasukan_rek : '',
        pemasukan : [],
        pengeluaran_rek : '',
        pengeluaran : [],
        saldo_akhir : '',
        tgl_awal : '',
        tgl_akhir : '',
    },
    foto_db : '',
    selected_kategori :  { value: '', label: 'PILIH KATEGORI' },
    page: 1,
    perHalaman : 10,
    id: '',
    urutan : 'desc',  
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),   
    fields: [           
        {key: 'no', label : 'No',  class : 'text-center', visible : true, thStyle : {color : 'white !important', backgroundColor : 'black !important'}},    
        {key: 'created_at', label : 'Tgl', visible : false, thStyle : {color : 'white !important', backgroundColor : 'black !important'}},    
         {key: 'uraian', label : 'Uraian', visible : false, thStyle : {color : 'white !important', backgroundColor : 'black !important'} },
        {key: 'kategori', label : 'Kategori', visible : true, thStyle : {color : 'white !important', backgroundColor : 'black !important'}},
        {key: 'total', label : 'Total', visible : true, thStyle : {color : 'white !important', backgroundColor : 'black !important'}},                  
        {key: 'by', label : 'By', visible : false, thStyle : {color : 'white !important', backgroundColor : 'black !important'}}, 
    
        // {key: 'Detail', label : 'detail', sortable: true, visible : true},         
    ],
    hidden_on : {aktif : true},


    rek_grafis_pengeluaran : [],
    rek_grafis_pemasukan : [],
    rek_grafis_saldo : [],   
  
    
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.rekapitulasis = payload;  
    },
    
    ASSIGN_DATA_SHOW(state, payload) {
        state.rekapitulasi_show = payload;  
    },
    REKAPITULASI_SEKARANG(state, payload) {
            state.rekapitulasi_sekarang = payload;   
    },

    REKAPITULASI_GRAFIS(state, payload) {
        state.rek_grafis_pengeluaran = payload.pengeluaran;  
        state.rek_grafis_pemasukan = payload.pemasukan;  
    },
    REK_GRAFIS_SALDO(state, payload) {
        state.rek_grafis_saldo= payload;        
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    SET_URUTAN(state, payload) {
        state.urutan = payload
    },   
}

const actions = {
    get_rekapitulasi({ commit, state, dispatch }, payload) {      
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/rekapitulasi`, {                
                params: {     
                    page : state.page, 
                    per_page: state.perHalaman,
                    q: search,                                                   
                    urutan : state.urutan,                                                              
                    bulan : state.bulan,
                    tahun : state.tahun 

                }
            })
            .then((response) => {   
                // console.info('rekapitulasi ', response.data)             
                dispatch('get_rekapitulasi_sekarang')                  
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    }, 
    
    get_rekapitulasi_sekarang({ commit }) {
        
        return new Promise((resolve, reject) => {
            $axios.get(`/rekapitulasi-sekarang`)
            .then((response) => {   
                // console.info('rekapitulasi sekarang', response.data)     
                commit('REKAPITULASI_SEKARANG', response.data.data)
                resolve(response.data)
            })
        })
    },
    show_rekapitulasi({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/rekapitulasi/${payload}`)
            .then((response) => {  
               
                commit('ASSIGN_DATA_SHOW', response.data.data)
                resolve(response.data)
            })
        })
    },
    get_rekapitulasi_grafis({commit, dispatch}, payload)  { //untuk chart js 
        let tahun = payload.tahun ;
        let bulan = payload.bulan; 
        return new Promise((resolve, reject) => {
            $axios.get(`/rekapitulasi-grafis`, {
                params : {
                    tahun : tahun, 
                    bulan : bulan,                                  
                }
            })
            .then((response) => {                     
            // console.info('rekap grafis', response.data.data)              
                commit('REKAPITULASI_GRAFIS', response.data.data)
                resolve(response.data)
            })
        })
    },
    get_rek_grafis_saldo({commit}, payload)  { //untuk chart js 
        let tahun = payload.tahun ;
        let bulan = payload.bulan; 
        return new Promise((resolve, reject) => {
            $axios.get(`/rekapitulasi-grafis-saldo`, {
                params : {
                    tahun : tahun, 
                    bulan : bulan,                                  
                }
            })
            .then((response) => {     
                // console.info('chart js grafisa saldo', response.data)              
                commit('REK_GRAFIS_SALDO', response.data.data)
                resolve(response.data)
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