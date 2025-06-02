<template>

      <nav class="main-header navbar navbar-expand  navbar-dark bg-navy">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>    
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">  
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="fa fa-tachometer"></i>          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">      
              <i  class="fa fa-user-circle mr-1" style="font-size : 22px"> </i>       
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Total Kunjungan Website
                  <span class="float-right text-sm text-danger" ><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">{{aplikasi.kunjungan | currency}} kali</p>                
              </div>
            </div>
            <!-- Message End -->
          </a>   
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>  
<!-- 
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
    </ul>

      <ul class="navbar-nav navbar-bottom shadow">
        <div class="d-flex justify-content-center align-items-center">
    <router-link :to="{ name: 'dashboard' }" class="p-2 text-dark">
        <i class="fa fa-bar-chart"></i>
    </router-link>

    <router-link :to="{ name: 'takmir' }"  class="p-2 text-dark">
        <i class="fa fa-address-book"></i>
    </router-link>


    <router-link :to="{ name: 'data.rekapitulasi' }" class="p-2 text-dark">
        <i class="fa fa-book"></i>
    </router-link>

    <router-link :to="{ name: 'inventaris' }" class="p-2 text-dark">
        <i class="fa fa-window-restore"></i>
    </router-link>

    <router-link :to="{ name: 'data.laporan' }" class="p-2 text-dark">
        <i class="fa fa-columns"></i>
    </router-link>


    <router-link :to="{ name: 'about' }" class="p-2 text-dark">
        <i class="fa fa-sitemap"></i>
    </router-link>

    <router-link :to="{ name: 'bantuan' }" class="p-2 text-dark">
        <i class="fa fa-check-circle"></i>
    </router-link>

    
</div>
                
        
     </ul>

  </nav>



</template>
<script>
  import {mapState } from 'vuex'
  export default {
    name : 'navBar',
    computed : {            
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),
    },
  }
</script>

<style>
  .navbar-bottom {
    display: none;
  position: absolute;
  bottom: -49px;
  left: 0;
  width: 100%;
  background-color: #d2f3f8; /* Warna latar belakang */

  /* display: flex; */
  justify-content: center; /* Pusatkan menu */
  font-size: 27px;
}

.router-link-active {  
   color: white; 
  border-bottom: 5px solid #2562a2 !important;
 
}

 /* destop hilang */
@media (max-width: 768px) {
     .navbar-bottom {
        display: block;
    }
}


/* ini untuk mengubah conten header bekabawah */
@media (max-width: 768px) {
    .content-header {
        padding-top: 76px; /* Nilai untuk mobile */
    }
}




</style>