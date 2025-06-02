<template>
<div class="sidebar-mini layout-boxed layout-navbar-fixed"
 :style="{     
      'background-image' : `url(${aplikasi.foto1})`,
        'background-repeat': 'repeat-y',
          'background-size': '100%'
    }" >
    
    <div class="wrapper" >
          <nav-bar></nav-bar>  
     
          <side-bar></side-bar> 
            <div class="content-wrapper">                         
                        <breadCrumb></breadCrumb>  
                  <div class="content">               
                    <div class="container-fluid">                         
                        <router-view></router-view>
                      </div>
                  </div>        
          </div>
        <footer-comp></footer-comp>
    </div>

 


</div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import navBar from './components/navbar'
import footerComp from './components/footer'
import sideBar from './components/sidebar'
import breadCrumb from './components/breadcrumb'


export default {
        computed: {
            ...mapState(['token']),
            ...mapGetters(['isAuth']),
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),
        },
    components : {       
         breadCrumb, footerComp, navBar, sideBar
    },
    name : 'app',
    methods : {        
          ...mapActions('rekapitulasi_stores', ['get_rekapitulasi_sekarang' ]), 
          ...mapActions('pengeluaran_stores', ['get_pengeluaran']),
          ...mapActions('pemasukan_stores', ['get_pemasukan']),
          ...mapActions('inventaris_stores', ['total_inventaris', 'get_inventaris' ]), 
          ...mapActions('aplikasi_stores', ['get_aplikasi']), 
          ...mapActions('kas_stores', ['get_kas']), 
          ...mapActions('kategori_stores', ['get_kategori']), 
          ...mapActions('laporan_stores', ['get_laporan', 'get_jenis_laporan']),        
  
    },
    created (){     
          this.get_aplikasi();   
        this.get_rekapitulasi_sekarang();
        this.get_inventaris();
       this.total_inventaris();       
        this.get_kas();
        this.get_kategori();
           this.get_laporan();
        this.get_jenis_laporan();                 
              this.get_pemasukan()
                   this.get_pengeluaran()
    },
     mounted() {
  }


}
</script>

<style>
    .hidden_filter {
        display: none;
    }

    .small-box h3 {
        white-space :normal !important;
    }
    /* //menghilangkan border class tabel froala */
    table.borderTable td {        
          border-width: 2px !important;
            border-style: solid !important;
            border-color: black !important;               
            -webkit-print-color-adjust:exact ;
    }

     table.borderTable td span {
        padding: 6px !important;
         
    }
    /* untuk modal gambar froala /kop kop */
    .fr-overlay {
        position: sticky !important;
    }
    /* tombol delete gambar froala kop */
    .fr-delete-img {
        display: none !important;
    }   
     .fr-active {
    z-index : 5000000 !important;
  }

    /* saat print  */
    @media print {
  .shadow {
    box-shadow: none !important;
  }
    .card,
    .card-body,
    .row {
    margin: 0 !important;
    padding: 0 !important;   
    }
}

 
    @media print {
      
        .thead-dark tr th  {
            border : 3px solid black !important;  
             background-color : black !important;
             color : white  !important;
            -webkit-print-color-adjust:exact ;
        }
        
        .tbody tr td {
              border : 3px solid black !important;  
          
        }
        
        .tbody tr th {
              border : 3px solid black !important;  
            background-color : rgb(255, 255, 255) !important;
            -webkit-print-color-adjust:exact ;
        }  
         .table thead tr td,.table tbody tr td{
            border-width: 2px !important;
            border-style: solid !important;
            border-color: black !important;               
            -webkit-print-color-adjust:exact ;
        } 
        #header {
            border-bottom: 0px;
        }
        
        .wrapper {
            background-color : rgb(255, 255, 255) !important;
        }
        .content-wrapper {
            background-color : rgb(255, 255, 255) !important;
        }
        
        .content {
            background-color : rgb(255, 255, 255) !important;
        }
         .card-body {
            background-color : rgb(255, 255, 255) !important;
        }
         .card {
            background-color : rgb(255, 255, 255) !important;
        }
        
         .container-fluid {
            background-color : rgb(255, 255, 255) !important;
        }
        
}

video, 
.foto_upload {
  width: 100%;
  max-width: 360px;
  height: 270px;
  border: 2px solid #ddd;
  object-fit: cover;
}

/* //untuk froala memecah baris */
.fr-dii {
    float: left;
   margin: 5px 5px 5px 0px;
}
/* froala tengah */
 .fr-dib {
float: none;
  display: block;
  margin: 5px auto;
 }



</style>


