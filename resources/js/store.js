import Vue from 'vue'
import Vuex from 'vuex'

import auth from './stores/auth.js'
import user from './stores/user.js'
import user_stores from './stores/user_stores.js'

import kategori_stores from './stores/kategori_stores.js'
import barang_stores from './stores/barang_stores.js'
import pengeluaran_stores from './stores/pengeluaran_stores.js'
import pemasukan_stores from './stores/pemasukan_stores.js'
import rekapitulasi_stores from './stores/rekapitulasi_stores.js'
import kas_stores from './stores/kas_stores.js'
import inventaris_stores from './stores/inventaris_stores.js'
import jabatan_stores from './stores/jabatan_stores.js'
import gajih_stores from './stores/gajih_stores.js'
import laporan_stores from './stores/laporan_stores.js'
import aplikasi_stores from './stores/aplikasi_stores.js'




Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth,  user,  kategori_stores, barang_stores, pemasukan_stores, pengeluaran_stores,
        rekapitulasi_stores, kas_stores, inventaris_stores, jabatan_stores,
        gajih_stores, user_stores, laporan_stores, aplikasi_stores
    },
    state: {
        token: localStorage.getItem('token'),
        errors: [],
    },
    getters: {
        isAuth: state => {
            return state.token != "null" && state.token != null            
        }
    },
    mutations: {
        SET_TOKEN(state, payload) {
            state.token = payload
        },
        SET_ERRORS(state, payload) {
            state.errors = payload
        },
        CLEAR_ERRORS(state) {
            state.errors = []
        }
    }
})

export default store