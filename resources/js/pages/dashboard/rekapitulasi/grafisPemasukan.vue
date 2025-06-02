<template>

  <div class="card">
      <div class="card-header">
        <div class="card-title"><h3>Kategori Pemasukan</h3></div>       
           <button  type="button" class="btn btn-outline-primary float-right btn-sm " @click="hidden">
                     <i class=" fa fa-calendar"></i>  
                </button>       
      </div>

      <div class="card-header row" :class="{hidden_filter : hidden_on.aktif}">      
            <div class="col-md">
                 <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select class="form-control" v-model="tahun" id="year" @change="queryTahun()">
                        <option v-for="year in years" :key="year" :value="year" >{{ year }}</option>
                        </select>
                    </div>
            </div>
            <div class="col-md">
                 <div class="form-group">
                    <label for="month">Pilih Bulan</label>
                    <select class="form-control" id="month" v-model="bulan" @change="queryBulan($event)">                    
                     <option v-for="(month, index) in months" :key="index" :value="formatMonth(index + 1)" >
                        {{ month }}
                    </option>
                    </select>
                </div>
            </div>
      </div>
      <div class="card-body">
           <bar-chart :chart-data="datacollection" :options="chartOptions"></bar-chart>
      </div>
      <div class="card-footer">
              <router-link class="float-right" :to="{name : 'data.rekapitulasi'}">Detill Pemasukan</router-link>
      </div>
  </div>
    
 
</template>

<script>
import moment from "moment"
moment.locale('id');  
import { mapActions, mapState } from 'vuex'
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
import axios from 'axios';

// Registrasi elemen Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  components: {
    BarChart: Bar, // Komponen bar chart
  },
  data() {
    return {
        years: [],                  
    months: moment.months(), // Get month names from moment.js
    tahun : moment().format('YYYY'),
    bulan :moment().format('MM'),

      datacollection: {
        labels: [], // Kategori pemasukan
        datasets: [], // Data pemasukan
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    };
  },
  computed : {
 ...mapState('pemasukan_stores', {   
           hidden_on : state => state.hidden_on,      
 })
  },
  mounted() {
        this.generateYears();
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {                    

        const response = await axios.get(`/api/rekapitulasi-kategori`, 
        {          
                params : {
                    tahun : this.tahun, 
                    bulan : this.bulan,                                  
                }
        }) ; // Endpoint API Anda
        const pemasukanData = response.data.data;        
        // Menyiapkan data untuk chart
        const labels = Object.keys(pemasukanData.pemasukan); // Nama kategori
        const data = Object.values(pemasukanData.pemasukan); // Total per kategori
        this.datacollection.labels = labels;
        this.datacollection.datasets = [
          {
            label: 'Pemasukan ' + moment(this.bulan).format('MMMM'),
            backgroundColor: '#9CCC65',
            borderColor: '#1E88E5',
            data: data,
          },
        ];
      } catch (error) {
        console.error('Gagal mengambil data pemasukan:', error);
      }
    },    
    queryBulan : function(e){    //untuk tampilan             
      var name = e.target.options[e.target.options.selectedIndex].text;                  
      this.fetchData();
  },   
  queryTahun (){            
    this.fetchData()
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
              
              hidden () {
                    this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
                },


  },
};
</script>

<style scoped>

</style>
