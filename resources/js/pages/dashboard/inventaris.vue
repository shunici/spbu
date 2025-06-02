<template>

        <div class="card">
          <div class="card-header">
              <h3>Inventaris </h3>
          </div>
          <div class="card-body row">

       
          <div class="col-12">
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="ion ion-easel"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Inventaris</span>
                <span class="info-box-number">{{inventaris.total | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div> 

          </div>
          <!-- ./col -->
          <div class="col-12">
            
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="ion ion-checkmark-circled"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Kondisi Baik</span>
                <span class="info-box-number">{{inventaris.kondisi_baik | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>

          </div>
          <!-- ./col -->
          <div class="col-12">
               
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="ion ion-close-circled"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Kondisi Rusak</span>
                <span class="info-box-number" >{{inventaris.kondisi_rusak | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>

          </div>
          <!-- ./col -->
          <div class="col-12">
            
            <!-- small box --> 
            <div class="info-box">
              <span class="info-box-icon bg-danger" v-b-tooltip.hover.left :title="terbilang_konver(nilai_aset)"><i class="ion ion-clipboard"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Nilai Aset Inventaris</span>
                <span class="info-box-number" v-b-tooltip.hover.bottom :title="terbilang_konver(nilai_aset)">{{nilai_aset | currency}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            
          </div>
          <!-- ./col -->
        

         </div> <!-- card-body -->
         <div class="card-footer">
           <p class="float-left"></p>
           <router-link class="float-right" :to="{name : 'inventaris'}">Detill</router-link>
         </div> <!-- footer -->

        </div><!--  card -->
                   
         

</template>


<script>
  import { mapState } from 'vuex'
import { terbilang } from '../../terbilang';
import moment from "moment"
moment.locale('id');  
    export default {
        data (){
            return {                                       
                     
            }
        },    
        computed : {
          ...mapState('inventaris_stores', {
                  inventaris : state => state.jumlah,  
                  nilai_aset : state => state.nilai_aset,       
          })
        },
        methods : {
            terbilang_konver(data){
                    let angka = data ? data : 0;
                    return terbilang(angka) + ' rupiah';
              },
             
        }                
       
    }



</script>

