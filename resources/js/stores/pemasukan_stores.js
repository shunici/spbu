import $axios from '../api.js'
import moment from "moment"


moment.locale('id');
const state = () => ({
    pemasukans: {
        data : [],
        links : {},
        meta : {}
    },
    pemasukan : {
        uraian : '',
        total : '',
        kategori_id : '',
        user_id : '',
        user : '',
        rekapitulasi_id : '',  
        foto : '',
        kas : 'kantor',   
        tgl : moment().format('YYYY-MM-DD HH:mm:ss')                 
    },  
    foto_db : '',
    selected_kategori :  { value: '', label: 'PILIH KATEGORI' },
    page: 1,
    perHalaman : 25,
    id: '',
    urutan : 'desc',     
    fields: [           
        {key: 'no', label : 'No',  visible : true, class : 'text-center'},     
        {key: 'created_at', label : 'Tgl',  visible : true},  
         {key: 'uraian', label : 'Uraian', visible : true},
        {key: 'kategori', label : 'Kategori', visible : true},
        {key: 'total', label : 'Total', visible : true},                    
        {key: 'by', label : 'By', visible : false},         
        {key: 'aksi', label : 'Aksi', visible : false}, 
    ],
    hidden_on : {aktif : true},    
    tahun : moment().format('YYYY'), 
    bulan : moment().format('MM'),    
    pesan : {
        sukses : false
    },

})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.pemasukans = payload;  
           // Gunakan cekRole di dalam mutation
   
    },
    CLEAR_FORM(state) {
        state.pemasukan.uraian = '';
        state.pemasukan.total = 0;  
        state.pemasukan.kas = 'kantor';  

        // state.pemasukan.kategori_id = '';   
        // state.pemasukan.user_id = '';  
        state.pemasukan.rekapitulasi_id = ''; 
        state.pemasukan.foto = ''; 
        state.foto_db = "";
        state.pemasukan.tgl = moment().format('YYYY-MM-DD HH:mm:ss');        
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

        state.pemasukan.uraian =payload.uraian;
        state.pemasukan.total =payload.total;  
        state.pemasukan.kas =payload.kas;  
        state.pemasukan.kategori_id =payload.kategori_id; 
        state.pemasukan.rekapitulasi_id =payload.rekapitulasi_id;
        state.pemasukan.user_id =payload.user_id;     
        state.pemasukan.foto = '/storage/pemasukan/' + payload.foto; 
        state.pemasukan.tgl =payload.tgl;  
    },            
    SHOW_FOTO(state, payload) {
        state.pemasukan.foto =  payload   
    },  
    SIMPAN_FOTO(state, payload) {
        state.foto_db =  payload   
    },
}

const actions = {
    get_pemasukan({ commit, state, dispatch }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/pemasukan`, {                
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
                //memanggil get_rekapitulasi    dan kategori
                
                dispatch('rekapitulasi_stores/get_rekapitulasi', "", { root: true })
                dispatch('kategori_stores/get_kategori', "", { root: true }) 
                dispatch('kas_stores/get_kas', "", { root: true }) 
                commit('ASSIGN_DATA', response.data)              
                resolve(response.data)
            })
        })
    },
    submit_pemasukan({ dispatch, commit, state }) {       
        let form = new FormData();        
        form.append('uraian', state.pemasukan.uraian)
        form.append('total', state.pemasukan.total)
        form.append('kategori_id', state.pemasukan.kategori_id)
        form.append('user_id', state.pemasukan.user_id)
        form.append('rekapitulasi_id', state.pemasukan.rekapitulasi_id)
        form.append('kas', state.pemasukan.kas)
        form.append('foto', state.foto_db)
        form.append('tgl', state.pemasukan.tgl)

        return new Promise((resolve, reject) => {
            $axios.post(`/pemasukan`, form, {
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {     
                dispatch('pengeluaran_stores/get_pengeluaran', "", { root: true })                  
                dispatch('get_pemasukan');                
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
    edit_pemasukan({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/pemasukan/${payload}/edit`)
            .then((response) => {  
                // console.info('edit_pemasukan', response.data)
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    
    show_pemasukan({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/pemasukan/${payload}`)
            .then((response) => {  
                // console.info('edit_pemasukan', response.data)                                
                dispatch('get_pemasukan');                
                dispatch('pengeluaran_stores/get_pengeluaran', "", { root: true })    
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_pemasukan({ commit,  state, dispatch }) {
        let form = new FormData();
        form.append('uraian', state.pemasukan.uraian)
        form.append('total', state.pemasukan.total)
        form.append('kategori_id', state.pemasukan.kategori_id)
        form.append('user_id', state.pemasukan.user_id)
        form.append('rekapitulasi_id', state.pemasukan.rekapitulasi_id)
        form.append('kas', state.pemasukan.kas)
        form.append('foto', state.foto_db)    
        form.append('tgl', state.pemasukan.tgl) 
        return new Promise((resolve, reject) => {
            $axios.post(`/pemasukan/update/${state.id}`, form,{
                headers: {'Content-Type': 'multipart/form-data'}
            })
            .then((response) => {                    
                dispatch('pengeluaran_stores/get_pengeluaran', "", { root: true })                  
                dispatch('get_pemasukan');                         
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
    remove_pemasukan({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/pemasukan/${payload}`)
            .then((response) => {
                dispatch('pengeluaran_stores/get_pengeluaran', "", { root: true })    
                dispatch('get_pemasukan')
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