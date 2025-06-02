//IMPORT SECTION
import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import Home from './pages/home.vue'
import About from './pages/about.vue'
import Bantuan from './pages/bantuan.vue'
import profile from './pages/profile/index.vue'
import Login from './pages/login.vue'
import Register from './pages/register.vue'
import reset from './pages/resetPassword.vue'
import store from './store.js'


import kategori from './pages/kategori/index.vue'
import barang from './pages/barang/index.vue'
import inventaris from './pages/inventaris/index.vue'
import pengeluaran from './pages/pengeluaran/index.vue'
import pemasukan from './pages/pemasukan/index.vue'
import kas from './pages/kas/index.vue'
import jabatan from './pages/jabatan/index.vue'
import aplikasi from './pages/aplikasi/index.vue'



import gajih from './pages/gajih/index.vue'
import show_gajih from './pages/gajih/show.vue'
import intensive from './pages/gajih/intensive.vue'

import user from './pages/user/index.vue'
import takmir from './pages/user/takmir.vue'

import index_rekapitulasi from './pages/rekapitulasi/index.vue'
import data_rekapitulasi from './pages/rekapitulasi/data.vue'
import show_rekapitulasi from './pages/rekapitulasi/show.vue'
//laporan
import index_laporan from './pages/laporan/index.vue'
import data_laporan from './pages/laporan/data.vue'
import template_laporan from './pages/laporan/template.vue'
import edit_laporan from './pages/laporan/edit.vue'
import add_laporan from './pages/laporan/add.vue'
import show_laporan from './pages/laporan/show.vue'

import dashboard from './pages/dashboard/index.vue'

// hak akses
import Setting from './pages/setting/Index.vue'
import role from './pages/setting/roles/role.vue'
import SetPermission from './pages/setting/roles/SetPermission.vue'






//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    // linkActiveClass: "active",
        
    routes: [   
            
        {
            path: '/aplikasi',
            name: 'aplikasi',
            meta: {title : 'Data Aplikasi' },
            component: aplikasi
        },         
        {
            path: '/laporan',
            name: 'laporan',                      
            component: index_laporan,
            children : [
                {
                    path : 'data',
                    name : 'data.laporan',
                    component : data_laporan,
                    meta: { title: 'Data Laporan' }
                },  
                {
                    path : 'template',
                    name : 'template.laporan',
                    component : template_laporan,
                    meta: { title: 'Template Laporan' }
                }, 
                {
                    path : 'add',
                    name : 'add.laporan',
                    component : add_laporan,
                    meta: {requiresAuth: true, title: 'Buat Laporan' }
                },              
                {
                    path: 'show/:id',
                    name: 'show.laporan',
                    component: show_laporan,
                    meta: { title: 'Data Laporan' }
                },             
                {
                    path: 'edit/:id',
                    name: 'edit.laporan',
                    component: edit_laporan,
                    meta: { requiresAuth: true, title: 'Laporan Edit' }
                },
            ]
        },
           
        {
            path: '/user',
            name: 'user',
            meta: { requiresAuth: true,  title : 'Data Pegawai' },
            component: user
        },             
        {
            path: '/takmir',
            name: 'takmir',
            meta: {  title : 'Pegawai' },
            component: takmir
        },   
        {
            path: '/gajih',
            name: 'gajih',
            meta: { requiresAuth: true,  title : 'Data Gaji' },
            component: gajih
        },              
        {
            path: '/gajih-show/:id',
            name: 'show.gajih',
            meta: { requiresAuth: true,  title : 'Data Gaji' },
            component: show_gajih
        },   
        {
            path: '/intensive',
            name: 'intensive',
            meta: { requiresAuth: true,  title : 'Data Intensive' },
            component: intensive
        }, 
        {
            path: '/jabatan',
            name: 'jabatan',
            meta: { requiresAuth: true,  title : 'Data Jabatan' },
            component: jabatan
        },       
        {
            path: '/dashboard',
            name: 'dashboard',
            meta: { title : 'Data Dashboard' },
            component: dashboard
        },     
        {
            path: '/inventaris',
            name: 'inventaris',
            meta: {title : 'Data Inventaris' },
            component: inventaris
        },      
        {
            path: '/rekapitulasi',
            name: 'rekapitulasi',        
            component: index_rekapitulasi,
            children : [
                {
                    path : '/data',
                    name : 'data.rekapitulasi',
                    component : data_rekapitulasi,
                    meta: { title: 'Data Rekapitulasi' }
                },              
                {
                    path: 'show/:id',
                    name: 'show.rekapitulasi',
                    component: show_rekapitulasi,
                    meta: {requiresAuth: true, title: 'Data Rekapitulasi' }
                },
            ]
        },      
        {
            path: '/kas',
            name: 'kas',
            meta: { title : 'Data Kas' },
            component: kas
        },      
        {
            path: '/pemasukan',
            name: 'pemasukan',
            meta: { requiresAuth: true,  title : 'Data Pemasukan' },
            component: pemasukan
        },        
        {
            path: '/pengeluaran',
            name: 'pengeluaran',
            meta: { requiresAuth: true,  title : 'Data Pengeluaran' },
            component: pengeluaran
        },    
        {
            path: '/kategori',
            name: 'kategori',
            meta: {title : 'Data Kategori' },
            component: kategori
        },            
        {
            path: '/barang',
            name: 'barang',
            meta: {title : 'Settingan Zakat' },
            component: barang
        },   
        {
            path: '',
            name: 'home',
            component: Home,
            meta: {title : 'Home' }
        },
        {
            path: '/about',
            name: 'about',
            component: About,
            meta: {title : 'Tentang Kami' }
        },        
        {
            path: '/bantuan',
            name: 'bantuan',
            component: Bantuan,
            meta: {title : 'FAQ & Bantuan' }
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },    
        {
            path: '/register',
            name: 'register',
            component: Register
        },          
        {
            path: '/reset',
            name: 'reset',
            component: reset
        },
        { // hak akses
            path: '/setting',
            component: Setting,
            meta: { requiresAuth: true },
            children: [
                {
                    path : 'role',
                    name : 'role',
                    component : role,
                    meta: { title: 'Role Akses' }
                },
                {
                    path: 'role-permission',
                    name: 'role.permissions',
                    component: SetPermission,
                    meta: { title: 'Izin Akses' }
                },
            ]
        },
        {
            path : '/profil',
            name : 'profile',
            component : profile,
            meta : {requiresAuth : true, title : 'Profile'}
        }
        
      
    ],
    scrollBehavior(to, from, savedPosition) {
        return { x: 0, y: 0 }; // Selalu scroll ke atas
      },
});

//Navigation Guards
router.beforeEach((to, from, next) => {
    store.commit('CLEAR_ERRORS')
   
    if (to.matched.some(record => record.meta.requiresAuth)) {        
        let auth = store.getters.isAuth      
        if (!auth) {            
            next({ name: 'home' })
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router