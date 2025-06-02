<template>
  <div class="card">
      <div class="card-header">
        <div class="card-title"><h3>Pergerakan Saldo</h3></div>       
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
                    <option value="0">Semua Bulan</option>
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
              <router-link class="float-right" :to="{name : 'data.rekapitulasi'}">Detill Saldo</router-link>
      </div>
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
    tahun : moment().format('YYYY'),
    bulan :moment().format('MM'),

      datacollection: {
        labels: [],
        datasets: [
          {
            label: 'Data Saldo',
            backgroundColor: '#f39c12',
            borderColor: '#1E88E5',
            data: [],
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
       pengeluaran : state => state.rek_grafis_pengeluaran,
      pemasukan : state => state.rek_grafis_pemasukan,
         saldo : state => state.rek_grafis_saldo,
           hidden_on : state => state.hidden_on,
      
 })
  },
mounted() {
    this.generateYears();
     
    setTimeout(() => {    
    this.proses();
    }, 4000);

},
  methods : {
       ...mapActions('rekapitulasi_stores', ['get_rek_grafis_saldo']),   
        proses(){
          
            var payload = {
                tahun : this.tahun,
                bulan : this.bulan,   //01,02,03 dll          
            }
               this.get_rek_grafis_saldo(payload);                            
            setTimeout(function () {
                this.datacollection.datasets[0].data = this.saldo;    
            }.bind(this), 800);
        },     
             queryBulan : function(e){    //untuk tampilan             
                var name = e.target.options[e.target.options.selectedIndex].text;               
                if(name == 'Semua Bulan') {
                    this.datacollection.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sept', 'Okt', 'Nov', 'Des'];
                } else {
                  this.datacollection.labels = [name];
                }
                this.proses();
            },   
            queryTahun (){            
              this.proses()
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
  created () {
    this.datacollection.labels = [moment().format('MMMM')];
 
  }
};
</script>

<style scoped>
/* div {
  height: 400px;
  width: 600px;
} */
</style>
