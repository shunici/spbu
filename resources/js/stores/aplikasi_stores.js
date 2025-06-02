import $axios from '../api.js'

const state = () => ({   
    aplikasi : {       
            nama : '',
            telp : '',   
            foto1 : '',
            foto2 : '',
            foto3 : '',
            foto4 : '',
            logo : '',
            kop : '',
            alamat : '',
            kab_kota : '',
            peta_link : '',
            peta : '',
            tentang : '',
            email : '',
            sosmed : [],
            register_rule : false,  
            alert_tampil : false,
            alert_judul : '',
            alert_isi : '',
            alert_warna : '',                                         
    },       
    id : 1,
    foto1 : '',
    foto2 : '',
    foto3 : '',
    foto4 : '',
    logo : '',
    kop : '',
    sosmed : []
})

const mutations = {    
    ASSIGN_FORM(state, payload) {          
        state.id = payload.id;                 
        state.aplikasi = {                 
            nama : payload.nama,   
            telp : payload.telp,
            foto1 :`/storage/aplikasi/`+ payload.foto1,
            foto2 :`/storage/aplikasi/`+ payload.foto2,
            foto3 :`/storage/aplikasi/`+ payload.foto3,
            foto4 :`/storage/aplikasi/`+ payload.foto4,
            kop :`/storage/aplikasi/`+ payload.kop,
            logo :`/storage/aplikasi/`+ payload.logo,
            alamat : payload.alamat,
            kab_kota : payload.kab_kota,
            peta_link : payload.peta_link,
            peta : payload.peta,
            tentang : payload.tentang,
            email : payload.email,
            register_rule : payload.register_rule,
            alert_tampil : payload.alert_tampil,
            alert_judul : payload.alert_judul,
            alert_isi : payload.alert_isi,
            alert_warna : payload.alert_warna,
            kunjungan : payload.kunjungan,
            bantuan : payload.bantuan,
           
        }
        state.sosmed = payload.sosmed ? JSON.parse(payload.sosmed) : '';
    },       
    SHOW_FOTO(state, payload) {
        state.aplikasi[payload.lokasi]=  payload.data   
    },  
    SIMPAN_FOTO(state, payload) {
        state[payload.lokasi] =  payload.data   
    },  
    // TAMBAH_SOSMED(state, payload) {
    //     state.sosmed.push({
    //         class : payload.class,
    //         link : payload.link
    //     })
    // }
}

const actions = {
    get_aplikasi({ commit, state }) {      
        return new Promise((resolve, reject) => {
            $axios.get(`/aplikasi`)
            .then((response) => {    
                console.info('aplikasi', response.data.data)             
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
   
    update_aplikasi({ state, dispatch, commit }) {  
           
        let form = new FormData();                        
        form.append('nama', state.aplikasi.nama)
        form.append('telp', state.aplikasi.telp)
        form.append('alamat', state.aplikasi.alamat)
        form.append('kab_kota', state.aplikasi.kab_kota)
        form.append('peta_link', state.aplikasi.peta_link)
        form.append('peta', state.aplikasi.peta)
        form.append('tentang', state.aplikasi.tentang)
        form.append('email', state.aplikasi.email)
        form.append('sosmed', JSON.stringify(state.sosmed))  
        form.append('register_rule', state.aplikasi.register_rule)  
        form.append('foto1', state.foto1)  
        form.append('foto2', state.foto2)  
        form.append('foto3', state.foto3)  
        form.append('foto4', state.foto4)  
        form.append('logo', state.logo)  
        form.append('kop', state.kop)  
        form.append('alert_tampil', state.aplikasi.alert_tampil)  
        form.append('alert_judul', state.aplikasi.alert_judul)  
        form.append('alert_isi', state.aplikasi.alert_isi)  
        form.append('alert_warna', state.aplikasi.alert_warna)  
        return new Promise((resolve, reject) => {
            $axios.post(`/aplikasi/update/${state.id}`, form, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {   
                console.info('Success:', response.data);
                dispatch('get_aplikasi');                                    
                resolve(response.data);
            })
            .catch((error) => {
                console.error('Error:', error.response ? error.response.data : error.message);
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }else {
                    commit('SET_ERRORS', {'koneksi' : 'PERIKSA INTERNET ANDA'} , { root: true })          
                }
            })
        });
        
    },   
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}
