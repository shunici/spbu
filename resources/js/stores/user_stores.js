import $axios from '../api.js'

const state = () => ({
    users: { //user seluruh
        data : [],
        links : {},
        meta : {}
    },
    user_aktif : [], // vue select
    user_all : [], //vue select
    user : {
        name : '',
        email : '',
        password : '',
        alamat : '',
        hp : '',
        foto : '',
        jabatan_id : '',
        status : 1,
        nomor_urut : 0

    },
    foto_db : '',

    pesan : {
        sukses : false
    },
    page: 1,
    perHalaman : 100,
    id: '',  
    selected_jabatan:  { value: '', label: 'PILIH KATEGORI', gajih_pokok : ''},
    hidden_on : {aktif : true},  
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.users = payload;   

         //untuk v-select user hanya yang aktif
        let activeUsers = payload.data
            .filter(user => user.status === 1) // Hanya user dengan status aktif
            .map(user => ({
                ...user,               
                label: user.name,
                value: user.id,
                foto : user.foto,
                jabat : user.jabatan.nama_jabatan
            }));
           state.user_aktif = activeUsers;

           let userAll = payload.data
            .filter(user => user.role_id != 1) // Hanya user dengan status aktif
            .map(user => ({
                ...user,
                label: user.name,
                value: user.id,
                foto : user.foto,
                // jabatan : user.jabatan.nama_jabatan
            }));
           state.user_all = userAll;


    },    
    ASSIGN_FORM(state, payload) {
        state.user.name =payload.name;   
        state.user.email =payload.email;   
        state.user.status =payload.status; 
        state.user.nomor_urut =payload.nomor_urut; 
        state.user.foto = '/storage/user/' + payload.foto;  
         
        if(payload.jabatan_id) {            
        state.user.jabatan_id =payload.jabatan.id; 
        //vue select
        state.selected_jabatan.value =payload.jabatan.id;  
        state.selected_jabatan.label =payload.jabatan.nama_jabatan; 
        state.selected_jabatan.gajih_pokok =payload.jabatan.gajih_pokok;
        }  else {
            state.selected_jabatan.value = '';  
            state.selected_jabatan.label = 'PILIH JABATAN'; 
            state.selected_jabatan.gajih_pokok =0;
        }



    },
    CLEAR_FORM(state) {
        state.user.name = '';          
    },

    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    },                      
    SHOW_FOTO(state, payload) {
        state.user.foto =  payload   
    },  
    SIMPAN_FOTO(state, payload) {
        state.foto_db =  payload   
    },
}

const actions = {
    get_user({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/user`, {                
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
    submit_user({ dispatch, commit, state }) {
        let form = new FormData();                
        form.append('name', state.user.name)
        form.append('email', state.user.email)
        form.append('password', state.user.password)
        form.append('alamat', state.user.alamat)
        form.append('hp', state.user.hp)
        form.append('jabatan_id', state.user.jabatan_id)
        form.append('status', state.user.status)
        form.append('nomor_urut', state.user.nomor_urut)
        form.append('role_id', 0)
        form.append('email_verified_at', '')
        form.append('foto', state.foto_db)

        return new Promise((resolve, reject) => {
            $axios.post(`/user/store`, form)
            .then((response) => {              
                commit('CLEAR_ERRORS', [] , { root: true })
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    edit_user({ commit }, payload) {
        commit('SET_ID_UPDATE', payload);
        return new Promise((resolve, reject) => {
            $axios.get(`/user/${payload}/edit`)
            .then((response) => {                        
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    update_user({ commit, state }) {
        let form = new FormData();                
        form.append('name', state.user.name)
        form.append('alamat', state.user.alamat)
        form.append('hp', state.user.hp)
        form.append('jabatan_id', state.user.jabatan_id)
        form.append('status', state.user.status)
        form.append('nomor_urut', state.user.nomor_urut)        
        form.append('foto', state.foto_db)

        return new Promise((resolve, reject) => {
            $axios.post(`/user/update/${state.id}`, form)
            .then((response) => {
                resolve(response.data)
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    remove_user({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/user/${payload}`)
            .then((response) => {
                dispatch('get_user').then(() => resolve(response.data))
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