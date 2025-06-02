<template>
<div >
  
      <footer class="main-footer no-print">
        <div class="row">
          <div class="col-md-4">
          
             {{aplikasi.nama}} <br>
             Email : {{aplikasi.email}} <br>
            Telp : {{aplikasi.telp}} <br>
             Google Maps : 
                <a :href="aplikasi.peta_link" target="_blank" rel="noopener noreferrer"> Peta Lokasi</a>
          </div>
          <div class="col-md-5">
             <strong>Alamat</strong>  <br>
              {{aplikasi.alamat}} <br>
             
          </div>        
          <div class="col-md-3 mb-4" >
              <strong>Sosial Media</strong> <br>
              <i class="fa fa-whatsapp" style="font-size:15px; cursor : pointer" @click="hubungi"> {{aplikasi.telp}}</i> <br>
              <span class="mr-3" v-for="(item, index) in sosmed" :key="index + 'sosmed'" style="cursor : pointer">
                  <i :class="item.class" style="font-size:15px; "></i> {{item.link}}  <br>
              </span>                    
          </div>
        </div>      
  </footer>

   

      <footer class="main-footer no-print" :style="{'background-color' : '#14171a', 'border-top' : '1px solid #14171a'}">
        <div class="container">          
        Sistem Tata Kelola Rekapitulasi
        </div>
  </footer>




</div>
</template>

<script>
import {mapState, mapActions } from 'vuex';
export default {
  name : 'footerComp',
  data() {
    return {
      
    }
  },
  computed : {
      ...mapState('aplikasi_stores', {
                aplikasi: state => state.aplikasi,   
                sosmed : state => state.sosmed,          
            }),
  },
  methods : { 
      hubungi() {
          var text = "Assalamu'alaikum Wr.Wb"
          var nomor  = this.aplikasi.telp;                   
          // URL untuk membuka WhatsApp
          const whatsappUrl = `https://wa.me/${nomor}?text=${text}`;
          // Arahkan ke URL WhatsApp
          window.open(whatsappUrl, "_blank");
      },  
  }
    
}
</script>

<style>




    
</style>