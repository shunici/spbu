<template>
  <div class="card">
      <div class="card-header">
        <div class="card-title"><h3>Pengeluaran</h3></div>   
       <button  type="button" class="btn btn-outline-primary float-right" @click="proses">
                   Proses
                </button> 
      </div>
      <div class="card-header row">
      
            <div class="col-md">
                 <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select class="form-control" v-model="tahunA" id="year">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
            </div>
            <div class="col-md">
                 <div class="form-group">
                    <label for="month">Pilih Bulan</label>
                    <select class="form-control" id="month" v-model="bulanA">
                    <option value="0">Semua Bulan</option>
                     <option v-for="(month, index) in months" :key="index" :value="formatMonth(index + 1)">
                        {{ month }}
                    </option>
                    </select>
                </div>
            </div>
            <div class="col-md align-self-center">               
               <h6 class="text-center font-weight-bold mt-3 ">Dibandingkan Dengan</h6>
            </div>
            
            <div class="col-md">
                 <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select class="form-control" v-model="tahunB" id="year">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
            </div>
            <div class="col-md">
                 <div class="form-group">
                    <label for="month">Pilih Bulan</label>
                    <select class="form-control" id="month" v-model="bulanB">
                    <option value="0">Semua Bulan</option>
                     <option v-for="(month, index) in months" :key="index" :value="formatMonth(index + 1)">
                        {{ month }}
                    </option>
                    </select>
                </div>
            </div>
      </div>

    <bar-chart :chart-data="datacollection" :options="chartOptions"></bar-chart>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex'
import moment from "moment"
moment.locale('id');  
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js';

// Registrasi komponen chart yang diperlukan
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  components: {
    BarChart: Bar, // Komponen Bar Chart
  },
  data() {
    return {
                          // Set default selected year to the current year
    years: [],                  
    months: moment.months(), // Get month names from moment.js
    tahunA : 2024,
    bulanA :'',
    tahunB : 2024,
    bulanB : '',

      datacollection: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [
          {
            label: 'Sales 2023',
            backgroundColor: '#42A5F5',
            borderColor: '#1E88E5',
            data: [40, 55, 75],
          },
          {
            label: 'Sales 2022',
            backgroundColor: '#9CCC65',
            borderColor: '#7CB342',
            data: [50, 65, 85, 95, 66],
          },
        ],
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },  
  computed : {
 ...mapState('rekapitulasi_stores', {
      rekapitulasi_grafis: state => state.rekapitulasi_grafis,
 })
  },
mounted() {
    this.generateYears();
},
  methods : {
       ...mapActions('rekapitulasi_stores', ['get_rekapitulasi_grafis']),   
        proses(){
          
            var payload = {
                tahunA : this.tahunA,
                bulanA : this.bulanA,
                tahunB : this.tahunB,
                bulanB : this.bulanB,
            }
               this.get_rekapitulasi_grafis(payload);
           //data A         
            this.datacollection.labels = ['senin', 'rabu', 'kamis', 'jumat', 'sabtu'];
             this.datacollection.datasets[0].data = [20,30,40,20,25];
        this.datacollection.datasets[1].data = [15,28,31,50,45];

        },

                
        generateYears() {
        const currentYear = moment().year();                
        const startYear = 2022; // Atur tahun awal sesuai kebutuhan
            for (let year = startYear; year <= currentYear + 1; year++) {
                this.years.push(year);
            }
    },
        formatMonth(month) {
        return month < 10 ? `0${month}` : `${month}`; // Format month to '01', '02', etc.
        },
  }
};
</script>

<style scoped>
/* div {
  height: 400px;
  width: 600px;
} */
</style>
