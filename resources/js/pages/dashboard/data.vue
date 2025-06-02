<template>

        <div class="container mb-3">
              <h3>Rekapitulasi   </h3>  
            <p>  Periode {{periode(rekapitulasi.tgl_mulai, rekapitulasi.tgl_akhir)}}</p>
          <div class="row">

                 
          <div class="col-lg-3 col-12">
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-info" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.pemasukan_rek)"><i class="ion ion-arrow-graph-up-right"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Pemasukan ({{rekapitulasi.pemasukan.length}}x)</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(rekapitulasi.pemasukan_rek)">Rp {{rekapitulasi.pemasukan_rek | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>       
          </div>
          <!-- ./col -->

                  
          <div class="col-lg-3 col-12">
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-success" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.pengeluaran_rek)"><i class="ion ion-arrow-graph-down-right"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Pengeluaran ({{rekapitulasi.pengeluaran.length}}x)</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(rekapitulasi.pengeluaran_rek)">Rp {{rekapitulasi.pengeluaran_rek | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>       
          </div>

           
          <div class="col-lg-3 col-12">
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-warning" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.saldo_awal)"><i class="ion ion-soup-can-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Saldo Pekan Lalu</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(rekapitulasi.saldo_awal)">Rp {{rekapitulasi.saldo_awal | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>       
          </div>

             
          <div class="col-lg-3 col-12">
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-danger" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.saldo_akhir)"><i class="ion ion-soup-can"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Saldo Akhir</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(rekapitulasi.saldo_akhir)">Rp {{rekapitulasi.saldo_akhir | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>       
          </div>

          <!-- ./col -->

         </div> <!-- card-body -->
         <div class="card-footer">         
           <router-link class="float-right" :to="{name : 'data.rekapitulasi'}">Lihat Detill Data Rekapitulasi</router-link>
         </div> <!-- footer -->

        </div><!--  card -->
                   
         

</template>


<script>
   import {  mapState } from 'vuex'
import { terbilang } from '../../terbilang';
import moment from "moment"

moment.locale('id');  
    export default {
        data (){
            return {                                       
                      // rekapitulasi : this.$store.state.rekapitulasi_stores.rekapitulasi_sekarang,
            }
        },   
        computed : {
                 ...mapState('rekapitulasi_stores', {    
                 rekapitulasi : state => state.rekapitulasi_sekarang,
                }),    
        },
        methods : {
          
              terbilang_konver(data){
                    let angka = data ? data : 0;
                    return terbilang(angka) + ' rupiah';
              },
              
            periode(data1, data2){
                return moment(data1).format('DD MMMM') + "  -  " + moment(data2).format('DD MMMM YYYY')
            },
        }                
       
    }



</script>

