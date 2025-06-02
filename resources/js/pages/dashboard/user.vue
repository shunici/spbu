<template>

        <div class="card">
          <div class="card-header">
              <h3>Pengurus Masjid   </h3>
          </div>
          <div class="card-body row">

       
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-info"  v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.pemasukan_rek)">
              <div class="inner">
                <h3><sup style="font-size: 20px">Rp </sup>{{rekapitulasi.pemasukan_rek | currency}}</h3>

                <p>Total Input Pemasukan {{rekapitulasi.pemasukan.length}}x</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-success" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.pengeluaran_rek)">
              <div class="inner">
  <h3><sup style="font-size: 20px">Rp </sup>{{rekapitulasi.pengeluaran_rek | currency}}</h3>

                <p>Total Input Pengeluaran {{rekapitulasi.pengeluaran.length}}x</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-warning" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.saldo_awal)">
              <div class="inner">
              <h3><sup style="font-size: 20px">Rp </sup>{{rekapitulasi.saldo_awal | currency}}</h3>

                <p>Saldo Pekan Lalu</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-12">
            <!-- small box -->
            <div class="small-box bg-danger" v-b-tooltip.hover.left :title="terbilang_konver(rekapitulasi.saldo_akhir)">
              <div class="inner">
             <h3><sup style="font-size: 20px">Rp </sup>{{rekapitulasi.saldo_akhir | currency}}</h3>

                <p>Saldo Akhir</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->

         </div> <!-- card-body -->
         <div class="card-footer">
           <p class="float-left"> Periode {{periode(rekapitulasi.tgl_mulai, rekapitulasi.tgl_akhir)}}</p>
           <router-link class="float-right" :to="{name : 'data.rekapitulasi'}">Detill</router-link>
         </div> <!-- footer -->

        </div><!--  card -->
                   
         

</template>


<script>

import { terbilang } from '../../terbilang';
import moment from "moment"
moment.locale('id');  
    export default {
        data (){
            return {                                       
                       rekapitulasi : this.$store.state.rekapitulasi_stores.rekapitulasi_sekarang,
            }
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

