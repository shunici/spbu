<template>
  <div class="card">
        <div class="card-header">
              <h3>Kategori Inventaris  </h3>
          
             
          </div>
      <div class="card-body">
            <pie-chart :chart-data="datacollection" :options="chartOptions"></pie-chart>
      </div>
    <div class="card-footer">
      <router-link class="float-right" :to="{name : 'inventaris'}">Lihat Detill</router-link>
    </div>
  </div>
</template>

<script>
import { Pie } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  CategoryScale,
} from 'chart.js';

// Registrasi komponen chart yang diperlukan
ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);
  import {mapState } from 'vuex'
export default {
  components: {
    PieChart: Pie, // Komponen Pie Chart
  },
  data() {
    return {
      datacollection: {
        labels: [] ,
        datasets: [
          {
            label: 'Colors Distribution',
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            data: [2,3,4,5],
          },
        ],
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },
  mounted () {         
            
    setTimeout(() => {    
    this.proses();
    }, 5000);
  },
  computed : {
    ...mapState('inventaris_stores', {
      kategori : state => state.kategori,
      sum_kategori_inventaris : state => state.sum_kategori_inventaris
 }),      
  },
    methods : {
        proses() {                               
        
            let array = this.sum_kategori_inventaris;            
            this.datacollection.labels =   Object.keys(array); // untuk stringnya, cth alat operasional

             // Fungsi untuk menghasilkan warna random
            function generateRandomColor() {
                let letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            let data_nilai = Object.values(array)  // cth 20 adahal value dari alat operasional
            // Membuat array baru berisi warna random sesuai jumlah elemen di array
            let warna = data_nilai.map(() => generateRandomColor());
              this.datacollection.datasets[0].backgroundColor = warna; 

            let angka_nilai = data_nilai.map(Number); // data nilai sebelumnya angka berupa string, kita akan ubah jadi nomor
              this.datacollection.datasets[0].data = angka_nilai; //utk nilainya                        
            
        }
    }
  
  
};
</script>

<style scoped>

</style>
