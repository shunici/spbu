<template>

        <div class="container mb-3">        
              <h3>Data Kas   </h3>
         
          <div class="row">           
          <div class="col-lg-4 col-12">
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-info" v-b-tooltip.hover.left :title="terbilang_konver(kas.masjid)"><i class="ion ion-briefcase"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Kas Tunai</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(kas.masjid)">Rp {{kas.masjid | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>       
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-12">            
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-success" v-b-tooltip.hover.left :title="terbilang_konver(kas.bank)"><i class="fa fa-university"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Kas Bank</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(kas.bank)">Rp {{kas.bank | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>  
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-12">         
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-danger" v-b-tooltip.hover.left :title="terbilang_konver(kas.bank + kas.masjid)"><i class="ion ion-clipboard"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Seluruh</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(kas.bank + kas.masjid)">Rp {{kas.bank + kas.masjid | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>          
          </div>
          <!-- ./col -->

         </div> <!-- row -->
         <div class="card-footer">           
           <router-link class="float-right" :to="{name : 'kas'}">Lihat Detil Data Kas</router-link>
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
                
            }
        },  
        computed : {
          ...mapState('kas_stores', {
            kas: state => state.kas_sekarang,
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

