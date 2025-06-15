<template>
     <aside class="main-sidebar bg-navy sidebar-dark-info elevation-4">
  

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img :src="aplikasi.logo" class="img-circle elevation-2"  alt="User Image">
        </div>
        <div class="info">       
          <span class="d-block  font-weight-light text-white" style="white-space: normal">{{aplikasi.nama}} </span>
        </div>
      </div>
      <div class="user-panel mt-3 pb-3 mb-3 d-flex" v-if="isAuth">
        <div class="image">
          <img :src="authenticated.foto ? authenticated.foto : 'img/user2-160x160.jpg'" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">       
          <router-link class="d-block" :to="{name : 'profile'}">{{authenticated.name}}</router-link>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
       
          <li class="nav-item" @click="ganti_menu('dashboard')">
            <router-link :to="{name : 'dashboard'}" class="nav-link"  :class="{active : posisi == 'dashboard' }">
              <i class="nav-icon fa fa-bar-chart"></i>
              <p>Dashboard</p>
            </router-link>
          </li> 

          <li class="nav-item"  @click="ganti_menu('kas')" v-if="$role(2) || $role(3) || $role(1)">
            <router-link :to="{name : 'kas'}" class="nav-link" :class="{active : posisi == 'kas'}">
              <i class="nav-icon fa fa-clone"></i>
              <p>Kas</p>
            </router-link>
          </li> 
          <li class="nav-item" @click="ganti_menu('rekap')">
            <router-link :to="{name : 'data.rekapitulasi'}" class="nav-link"  :class="{active : posisi == 'rekap'}">
              <i class="nav-icon fa fa-book"></i>
              <p>Rekapitulasi</p>
            </router-link>
          </li> 
          <li class="nav-item" @click="ganti_menu('pengeluaran')" v-if="$role(2) || $role(3) || $role(1) && isAuth">
            <router-link :to="{name : 'pengeluaran'}" class="nav-link"  :class="{active : posisi == 'pengeluaran'}">
              <i class="nav-icon fa fa-arrow-circle-down"></i>
              <p>Pengeluaran</p>
            </router-link>  
          </li> 
          
          <li class="nav-item" @click="ganti_menu('pemasukan')" v-if="$role(2) || $role(3) || $role(1) && isAuth" >
            <router-link :to="{name : 'pemasukan'}" class="nav-link" :class="{active : posisi == 'pemasukan'}">
              <i class="nav-icon fa fa-arrow-circle-up"></i>
              <p>Pemasukan</p>
            </router-link>
          </li>     
          
          <li class="nav-item" @click="ganti_menu('realisasi')" v-if="$role(2) || $role(3) || $role(1) && isAuth" >
            <router-link :to="{name : 'realisasi'}" class="nav-link" :class="{active : posisi == 'realisasi'}">
              <i class="nav-icon fa fa-arrow-circle-up"></i>
              <p>Realisasi</p>
            </router-link>
          </li>     
        
          <li class="nav-item"  @click="ganti_menu('user')" v-if="$role(2) || $role(3) || $role(1)">
            <router-link :to="{name : 'user'}" class="nav-link" :class="{active : posisi == 'user'}">
            <i class="nav-icon fa fa-address-book"></i>
              <p>Data Pegawai</p>
            </router-link>
          </li> 
          <li class="nav-item"  @click="ganti_menu('takmir')">
            <router-link :to="{name : 'takmir'}" class="nav-link" :class="{active : posisi == 'takmir'}">
            <i class="nav-icon fa fa-user-circle"></i>
              <p>Pegawai</p>
            </router-link>
          </li> 
           
          <li class="nav-item"  @click="ganti_menu('gajih')" v-if="$role(2) || $role(3) || $role(1) && isAuth">
            <router-link :to="{name : 'gajih'}" class="nav-link" :class="{active : posisi == 'gajih'}">
              <i class="nav-icon fa fa-list-alt"></i>
              <p>Gajih</p>
            </router-link>
          </li>          
          <li class="nav-item"  @click="ganti_menu('intensive')" v-if="isAuth && !$role(1)">
            <router-link :to="{name : 'intensive'}" class="nav-link" :class="{active : posisi == 'intensive'}">
              <i class="nav-icon fa fa-address-card"></i>
              <p>Insentif</p>
            </router-link>
          </li>  
          <li class="nav-item"  @click="ganti_menu('inven')">
            <router-link :to="{name : 'inventaris'}" class="nav-link" :class="{active : posisi == 'inven'}">
              <i class="nav-icon fa fa-window-restore"></i>
              <p>Inventaris</p>
            </router-link>
          </li>                
          <li class="nav-item menu-close" @click="ganti_menu('pengaturan')" v-if="$role(2) || $role(3) || $role(1) && isAuth">
            <a href="#" class="nav-link"  :class="{active : posisi == 'pengaturan'}">
              <i class="nav-icon fa fa-wrench"></i>
              <p>
                Pengaturan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item" v-if="$role(1) || $role(2) && isAuth">
                <router-link :to="{name : 'aplikasi'}"  class="nav-link">                              
                  <i class="fa fa-user-plus nav-icon"></i>
                  <p>Profile</p>
              </router-link>
              </li>
          <li class="nav-item"  @click="ganti_menu('jabatan')"  v-if="$role(2) || $role(3) || $role(1)">
            <router-link :to="{name : 'jabatan'}" class="nav-link" :class="{active : posisi == 'jabatan'}">
              <i class="nav-icon fa fa-sitemap"></i>
              <p>Jabatan</p>
            </router-link>
          </li>  
          <li class="nav-item" v-if="$role(2) || $role(3) || $role(1)"  @click="ganti_menu('kategori')">
            <router-link :to="{name : 'kategori'}" class="nav-link" :class="{active : posisi == 'kategori'}">
              <i class="nav-icon fa fa-window-restore"></i>
              <p>Kategori</p>
            </router-link>
          </li>  
              <li class="nav-item" v-if="$role(1)">
                <router-link :to="{name : 'role'}"  class="nav-link">                              
                  <i class="fa fa-users nav-icon"></i>
                  <p>Role Akses</p>
              </router-link>
              </li>
              <li class="nav-item" v-if="$role(1)">
                <router-link :to="{name : 'role.permissions'}"  class="nav-link">                              
                  <i class="fa fa-user-plus nav-icon"></i>
                  <p>Izin Akses</p>
              </router-link>
              </li>
            </ul>
          </li>
            

          <li class="nav-item menu-close" @click="ganti_menu('laporan')">
            <a href="#" class="nav-link"  :class="{active : posisi == 'laporan' }">
              <i class="nav-icon fa fa-file"></i>
              <p>
                Laporan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview"  >   
              <li class="nav-item">                          
                <router-link :to="{path : '/laporan/data'}"  class="nav-link" >                              
                  <i class="far fa-circle nav-icon"></i>
                  <p >Laporan²</p>
              </router-link>                    
              </li>                      
            </ul>

          </li>
          
          <li class="nav-item"  @click="ganti_menu('about')">
            <router-link :to="{name : 'about'}" class="nav-link" :class="{active : posisi == 'about'}">
              <i class="nav-icon fa fa-sitemap"></i>
              <p>Tentang Kami</p>
            </router-link>
          </li>
                  
          <li class="nav-item"  @click="ganti_menu('bantuan')">
            <router-link :to="{name : 'bantuan'}" class="nav-link" :class="{active : posisi == 'bantuan'}">
              <i class="nav-icon fa fa-check-circle"></i>
              <p>Bantuan</p>
            </router-link>
          </li>
              <li class="nav-item" @click="logout">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p> {{isAuth ? "Logout" : "Login"}}</p>
   
                </a>
              </li>              

        </ul>
      </nav>
     
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

</template>


<script>
import { mapState, mapGetters, mapActions, mapMutations } from 'vuex'

export default {
    data() {
      return {
        posisi : '', //link aktif
      }
    },
    computed: {
        ...mapState('user', {
            authenticated: state => state.authenticated
        }),
        ...mapGetters(['isAuth']),
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),   
    },
    methods: {  
        ...mapActions('user', ['getUserLogin']),
        ...mapActions('auth', ['logout_user']),            
        logout() {
          this.logout_user();
            return new Promise((resolve, reject) => {
                localStorage.removeItem('token')
                resolve()
            }).then(() => {
                this.$store.state.token = localStorage.getItem('token')
                this.$router.push('/login')
            })
        },        
        ganti_menu (param){     
           this.posisi = param
        }
    },
    created (){
       this.getUserLogin()
    }
}
</script>
