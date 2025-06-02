import $axios from '../api.js'
import moment from "moment"
moment.locale('id'); 
const state = () => ({
    laporans: {
        data : [],
        links : {},
        meta : {}
    },
    laporan : {
        judul : '',
        jenis_laporan : '',
        template : 0,
        uraian : '',   
    },
    laporan_show : '',
    pesan : {
        sukses : false
    },
    hidden_on : {aktif : true},   
    page: 1,
    perHalaman : 25,
    id: '',
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),
    jenis_laporan : [],
    query_jenis_laporan : '',
    template : {
        data : [],
        links : {},
        meta : {}
    },
    config: { 
        language: 'id',
       imageUpload: false,
       imageUploadRemoteUrls: true,  
       requestWithCORS: false,
       imageManagerLoadURL: '/api/load_images', // Endpoint untuk memuat daftar gambar
       imageManagerLoadMethod: "GET",
       imageManagerDeleteURL : false,
       imageManagerToggleTags: true,  
       imageManagerScrollOffset: 10,
       imageManagerPageSize: 10,   
       imageManagerPreloader: '/images/loader.gif',
       imageMove: true,    
       documentReady: true,         
       toolbarButtons: [
        'fontFamily', '|',  
        'paragraphFormat', '|', 
        'outdent', 'indent', '|',
        'textColor', 'backgroundColor', '|', 
        'fontSize', '|',
        'align', '|',  
        'lineHeight', '|',  
        'formatOL', 'formatUL', '|',  // ➕ Tambahkan Ordered & Unordered List
        'bold', 'italic', 'underline',  
        'undo', 'redo', '|',  
        'insertTable', '|',
        'insertImage', '|',
        'insertLink', '|',
        'print', '|',
        'html'                  
    ],    
      imageInsertButtons: ['imageBack', '|', 'imageManager'],       
        toolbarButtonsXS: [
         'bold', '|',        
         'textColor', 'backgroundColor', '|',   
         'formatOL', 'formatUL', '|',
         'insertTable' // Hanya tombol penting di mobile
       ],
         tableStyles: {
           borderTable: 'Buat Garis',
           class3: 'Hilangkan Garis',
        //    class2: 'class 2',         
         },  
         imageDefaultWidth: "100%",
         imageResize: true,
         imageStyles: {
            fullKan: 'Full kan',                  
          },
         fontFamilySelection: true,
         fontFamilyDefaultSelection: 'Arial',  
         fontSizeSelection: true,       
         fontSizeDefaultSelection: '19',
          fontFamily: {}, 
          fontSizeUnit : 'pt',
          fontSize: ['8', '10', '12', '14', '15', '16', '18', '19', '22', '24', '30', '36'],
          events: {                                    
           'froalaEditor.initialized': function () {
             console.info('initialized')
           }
         }   

    } 
    
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.laporans = payload;   
    },    
    ASSIGN_DATA_TEMPLATE(state, payload) {
        state.template = payload;   
    },
    CLEAR_FORM(state) {
           state.laporan_show = ""
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    
    ASSIGN_FORM(state, payload) {
        state.laporan.judul =payload.judul;
        state.laporan.jenis_laporan =payload.jenis_laporan;
        state.laporan.template =payload.template;
        state.laporan.uraian =payload.uraian;        
    },
    ASSIGN_SHOW(state, payload) {
        state.laporan_show = payload
    },

    JENIS_LAPORAN(state, payload) {
        state.jenis_laporan = payload
    },
    QUERI_JENIS_LAP (state, payload) {   
        state.query_jenis_laporan = payload
    },
    FONT_FROALA(state, payload) {
        state.config.fontFamily = payload
    }
}

const actions = {
    get_laporan({ commit, state, dispatch }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/laporan`, {                
                params: {     
                    page : state.page, 
                    per_page: state.perHalaman,
                    jenis_laporan : state.query_jenis_laporan,
                    bulan : state.bulan,
                    tahun : state.tahun,                                              
                }
            })
            .then((response) => {
                // console.info('tag', response.data)
                dispatch('get_template')
                commit('ASSIGN_DATA', response.data)             
                resolve(response.data)
            })
        })
    },
    get_template({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/laporan-template`, {                
                params: {     
                    page : state.page,                     
                    jenis_laporan : state.query_jenis_laporan,                                                   
                }
            })
            .then((response) => {
                // console.info('tag', response.data)
                commit('ASSIGN_DATA_TEMPLATE', response.data)             
                resolve(response.data)
            })
        })
    },
    submit_laporan({ dispatch, commit, state }) {
       
        return new Promise((resolve, reject) => {
            $axios.post(`/laporan`, state.laporan)
            .then((response) => {                   
                commit('CLEAR_ERRORS', [] , { root: true })
                dispatch('get_laporan')
                dispatch('get_template')
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
    edit_laporan({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/laporan/${payload}/edit`)
            .then((response) => {        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },    
    show_laporan({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/laporan/${payload}/show`)
            .then((response) => {       
                commit('ASSIGN_SHOW', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_laporan({ commit, dispatch, state }) {
        return new Promise((resolve, reject) => {
            $axios.put(`/laporan/${state.id}`, state.laporan)
            .then((response) => {
                dispatch('get_laporan')
                dispatch('get_template')
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
    remove_laporan({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/laporan/${payload}`)
            .then((response) => {
                dispatch('get_laporan').then(() => resolve(response.data))
            })
        })
    },
    get_jenis_laporan({ commit }) {
        return new Promise((resolve, reject) => {
            $axios.get(`/jenis-laporan`)
            .then( (response) => {      
                    // console.info('enis laporan', response.data)                                        
                commit('FONT_FROALA', response.data.font)
                commit('JENIS_LAPORAN', response.data.laporan)
                resolve(response.data)
            } )
           
        })
    },

}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}