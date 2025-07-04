import $axios from '../api.js'
import moment from "moment"
moment.locale('id');
const state = () => ({
    pengeluarans: [],
    total : 0,
    pengeluaran : {
        uraian : '',
        total : '',
        kategori_id : '',
        user_id : '',
        user : '',
        rekapitulasi_id : '',  
        foto : '',  
        kas : 'kantor',  
        tgl : moment().format('YYYY-MM-DD HH:mm:ss'),                      
    },  
    
 //pengeluaran tabel
 data_pengeluaran: [],
 kategori_header: [],
 kategori_total : [],      
//tutu pengeluaran tabel
    foto_db : '',
    selected_kategori :  { value: '', label: 'PILIH KATEGORI' },  
    id: '',
    urutan : 'desc',  
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),
    fields: [           
        {key: 'no', label : 'No',  visible : true, class : 'text-center' },     
        {key: 'created_at', label : 'Tgl',  visible : true, },  
         {key: 'uraian', label : 'Uraian', visible : true},
        {key: 'kategori', label : 'Kategori', visible : true, },
        {key: 'total', label : 'Total', visible : true, },                    
        {key: 'by', label : 'By', visible : false},         
        {key: 'aksi', label : 'Aksi', visible : false}, 
    ],
    hidden_on : {aktif : false},   
    kategori  : '',  
    pesan : {
        sukses : false
    },

})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.pengeluarans = payload.data;  
        state.total = payload.total;  
    },
    
    ASSIGN_DATA_TABEL(state, payload) {
        state.data_pengeluaran = payload.data;  
        state.kategori_header= payload.kategori_header; 
        state.total = payload.total;  
        
        const kategori_totals = {};
             payload.data.forEach(row => {
            payload.kategori_header.forEach(kategori => {
            if (!kategori_totals[kategori]) kategori_totals[kategori] = 0;
            kategori_totals[kategori] += row[kategori] || 0;
            });
        });
        state.kategori_total = kategori_totals;
    },
    CLEAR_FORM(state) {
        state.pengeluaran.uraian = '';
        state.pengeluaran.total = 0;  
        state.pengeluaran.kas = 'kantor';  
        // state.pengeluaran.kategori_id = '';   
        // state.pengeluaran.user_id = '';  
        state.pengeluaran.rekapitulasi_id = ''; 
        state.pengeluaran.foto = '';   
        state.foto_db = "";
        state.pengeluaran.tgl = moment().format('YYYY-MM-DD HH:mm:ss');        
             
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
    ASSIGN_FORM(state, payload) {
        state.selected_kategori.label =payload.kategori.nama;
        state.selected_kategori.value =payload.kategori.id;

        state.pengeluaran.uraian =payload.uraian;
        state.pengeluaran.total =payload.total;  
        state.pengeluaran.kas =payload.kas;  
        state.pengeluaran.kategori_id =payload.kategori_id; 
        state.pengeluaran.rekapitulasi_id =payload.rekapitulasi_id;
        state.pengeluaran.user_id =payload.user_id;     
        state.pengeluaran.foto = '/storage/pengeluaran/' + payload.foto; 
        state.pengeluaran.tgl =payload.tgl;  
    },            
    SHOW_FOTO(state, payload) {
        state.pengeluaran.foto =  payload   
    },  
    SIMPAN_FOTO(state, payload) {
        state.foto_db =  payload   
    },
}

const actions = {
    get_pengeluaran({ commit, state, dispatch }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/pengeluaran`, {                
                params: {     
                    kategori : state.kategori,                                                                                                                
                    bulan : state.bulan,
                    tahun : state.tahun 

                }
            })
            .then((response) => {
                console.info('edit_pengluaran', response.data)
                dispatch('rekapitulasi_stores/get_rekapitulasi', "", { root: true })                
                dispatch('kategori_stores/get_kategori', "", { root: true })     
                dispatch('kas_stores/get_kas', "", { root: true }) 
                dispatch('get_pengeluaran_tabel')   
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    },
    get_pengeluaran_tabel({ commit, state, dispatch }) {   
        return new Promise((resolve, reject) => {
            $axios.get(`/pengeluaran-tabel`, {                
                params: {                                                                                                                                
                    bulan : state.bulan,
                    tahun : state.tahun 
                }
            })
            .then((response) => {             
            console.info('pengeluaran tabel', response.data)           
                commit('ASSIGN_DATA_TABEL', response.data)              
                resolve(response.data)
            })
        })
    },
    submit_pengeluaran({ dispatch, commit, state }) {       
        let form = new FormData();        
        form.append('uraian', state.pengeluaran.uraian)
        form.append('total', state.pengeluaran.total)
        form.append('kategori_id', state.pengeluaran.kategori_id)
        form.append('user_id', state.pengeluaran.user_id)
        form.append('rekapitulasi_id', state.pengeluaran.rekapitulasi_id)      
        form.append('kas', state.pengeluaran.kas)
        form.append('foto', state.foto_db)
        form.append('tgl', state.pengeluaran.tgl)
        console.info('pengeluaran', form)
        return new Promise((resolve, reject) => {
            $axios.post(`/pengeluaran`, form, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {                  
                dispatch('get_pengeluaran'); 
                dispatch('pemasukan_stores/get_pemasukan', "", { root: true })  
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
    edit_pengeluaran({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/pengeluaran/${payload}/edit`)
            .then((response) => {  
                // console.info('edit_pengeluaran', response.data)
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    
    show_pengeluaran({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/pengeluaran/${payload}`)
            .then((response) => {  
                // console.info('edit_pengeluaran', response.data)
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_pengeluaran({ commit, state, dispatch }) {
        let form = new FormData();
        form.append('uraian', state.pengeluaran.uraian)
        form.append('total', state.pengeluaran.total)
        form.append('kategori_id', state.pengeluaran.kategori_id)
        form.append('user_id', state.pengeluaran.user_id)
        form.append('rekapitulasi_id', state.pengeluaran.rekapitulasi_id)
        form.append('kas', state.pengeluaran.kas)
        form.append('foto', state.foto_db)   
        form.append('tgl', state.pengeluaran.tgl)  
        return new Promise((resolve, reject) => {
            $axios.post(`/pengeluaran/update/${state.id}`, form,{
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => { 
                dispatch('pemasukan_stores/get_pemasukan', "", { root: true })                    
                dispatch('get_pengeluaran')                               
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
    remove_pengeluaran({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/pengeluaran/${payload}`)
            .then((response) => {
                dispatch('pemasukan_stores/get_pemasukan', "", { root: true })  
                dispatch('get_pengeluaran')                
                .then(() => resolve(response.data))
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