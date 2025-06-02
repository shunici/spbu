<template>

<div>
   
     <data-kas v-if="$role(2) || $role(3) || $role(1)"></data-kas>   
  
    <div class="row">
        <div class="col-12">
                <data-comp></data-comp>  
        </div>
        <div class="col-md-6 pl-0 pr-0 p-md-3">
   <grafis-rekap></grafis-rekap>
        </div>
        <div class="col-md-6 pl-0 pr-0 p-md-3">
            <grafis-saldo></grafis-saldo>
        </div>
        <div class="col-12 pl-0 pr-0 p-md-3">
            <grafis-pemasukan></grafis-pemasukan>
        </div>
        <div class="col-12 pl-0 pr-0 p-md-3">
            <grafis-pengeluaran></grafis-pengeluaran>
        </div>
        <div class="col-md-6 pl-0 pr-0 p-md-3">
            <inventaris-data></inventaris-data>
        </div>       
        <div class="col-md-6 pl-0 pr-0 p-md-3">
            <pie-inventaris></pie-inventaris>
        </div>  

    </div>


 
             
  
    
</div>
      

</template>
<script>

import dataKas from './kas.vue';
import dataComp from './data.vue';

import grafisRekap from './rekapitulasi/grafisRekap.vue';
import grafisSaldo from './rekapitulasi/grafisSaldo.vue';
import grafisPemasukan from './rekapitulasi/grafisPemasukan.vue';
import grafisPengeluaran from './rekapitulasi/grafisPengeluaran.vue';


import inventarisData from './inventaris.vue';
import pieInventaris from './pie_inventaris.vue';




        export default {
            name : 'index-dashboar',
      data() {
          return {
            
          }
      },
      components : {
          dataComp,  dataKas, grafisRekap, grafisSaldo, grafisPemasukan, grafisPengeluaran, inventarisData,
          pieInventaris
      }
    }
</script>
