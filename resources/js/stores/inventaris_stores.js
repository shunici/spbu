import $axios from '../api.js'

const state = () => ({
    inventariss: {
        data : [],
        links : {},
        meta : {}
    },   
    inventaris : {
        nama : '',
        total : 0,
        satuan : '',
        kondisi_baik : 0,
        kondisi_rusak : 0,
        harga : 0,
        kategori : '',
        keterangan : '',
        foto : '',        
    },
    pesan : {
        sukses : false
    },
    page: 1,
    perHalaman : '',
    id: '',
    foto_db : '',
    kategori : [], //kategori controller
    jumlah : {},
    sum_kategori_inventaris : [],
    nilai_aset : 0,
    fields: [               
        {key: 'no',  visible : true, label : 'No', class : 'text-center'},
       {key: 'nama',  label : 'Nama', visible : true},
       {key: 'kategori',  label : 'Kategori', visible : true},
       {key: 'total',  label : 'Total', visible : true},
       {key: 'kondisi_baik',  label : 'Baik', visible : true},                                                        
       {key: 'kondisi_rusak',  label : 'Rusak', visible : true,},  
          {key: 'nilai_aset',  label : 'Nilai Aset', visible : true,},                                                                                                                                                                                                         
       {key: 'aksi', sortable: false, label : 'Aksi',}, //TAMBAHKAN CODE INI
   ],
   hidden_on : {aktif : true},
   
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.inventariss = payload;      
    },
    CLEAR_FORM(state) {
          state.inventaris.foto = "";
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },
    ASSIGN_TOTAL_INVENTARIS(state, payload) {
        state.jumlah.total = payload.total;
        state.jumlah.kondisi_baik = payload.kondisi_baik;
        state.jumlah.kondisi_rusak = payload.kondisi_rusak;
        state.kategori = payload.kategori;
        state.sum_kategori_inventaris = payload.sum_kategori_inventaris;
        state.nilai_aset = payload.nilai_aset;
    },
    
    ASSIGN_FORM(state, payload) {
        state.inventaris.nama =payload.nama;   
        state.inventaris.total =payload.total;   
        state.inventaris.satuan =payload.satuan; 
        state.inventaris.kondisi_baik =payload.kondisi_baik;   
        state.inventaris.kondisi_rusak =payload.kondisi_rusak;   
        state.inventaris.harga =payload.harga;   
        state.inventaris.kategori =payload.kategori; 
        state.inventaris.keterangan =payload.keterangan;     
        state.inventaris.foto = '/storage/inventaris/' + payload.foto;     
    },
              
    SHOW_FOTO(state, payload) {
        state.inventaris.foto =  payload   
    },  
    SIMPAN_FOTO(state, payload) {
        state.foto_db =  payload   
    },
}

const actions = {
    get_inventaris({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/inventaris`, {                
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
    submit_inventaris({ dispatch, commit, state }) {           
        let form = new FormData();   
        form.append('nama', state.inventaris.nama)     
        form.append('total', state.inventaris.total)
        form.append('satuan', state.inventaris.satuan)
        form.append('kondisi_baik', state.inventaris.total)
        form.append('kondisi_rusak', state.inventaris.kondisi_rusak)
        form.append('harga', state.inventaris.harga)
        form.append('kategori', state.inventaris.kategori)
        form.append('keterangan', state.inventaris.keterangan)
        form.append('foto', state.foto_db)

        return new Promise((resolve, reject) => {
           $axios.post(`/inventaris`, form, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {              
                commit('CLEAR_ERRORS', [] , { root: true })
                dispatch('total_inventaris').then(() => resolve(response.data))
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
    edit_inventaris({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/inventaris/${payload}/edit`)
            .then((response) => {        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_inventaris({ commit, state }) {
        let form = new FormData();   
        form.append('nama', state.inventaris.nama)     
        form.append('total', state.inventaris.total)
        form.append('satuan', state.inventaris.satuan)
        form.append('kondisi_baik', state.inventaris.total)
        form.append('kondisi_rusak', state.inventaris.kondisi_rusak)
        form.append('harga', state.inventaris.harga)
        form.append('kategori', state.inventaris.kategori)
        form.append('keterangan', state.inventaris.keterangan)
        form.append('foto', state.foto_db)
        
        return new Promise((resolve, reject) => {
            $axios.post(`/inventaris/update/${state.id}`, form, {
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
    },
    inventaris_kondisi({dispatch,state}) {
 
        return new Promise((resolve, reject) => {           
            $axios.post(`/inventaris-kondisi/${state.id}`, state.inventaris)
            .then((response) => {                      
                dispatch('total_inventaris').then(() => resolve(response.data))
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
    remove_inventaris({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/inventaris/${payload}`)
            .then((response) => {
                dispatch('get_inventaris').then(() => resolve(response.data))
            })
        })
    },
    total_inventaris({commit}) {        
        return new Promise((resolve, reject) => {
            $axios.get(`/inventaris-total`)
            .then((response) => {       
                // console.info('inventaris', response.data.data) 
                commit('ASSIGN_TOTAL_INVENTARIS', response.data.data)
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