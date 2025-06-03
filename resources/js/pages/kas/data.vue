<template>
    <div class="card">
        
            <div class="card-header">
                 <button  v-if="$role(2)" type="button" class="btn btn-outline-primary float-left" v-b-modal.add-kas>
                       <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>     Buat Data Baru
                </button>     
                  <button  type="button" class="btn btn-outline-primary float-right" @click="hidden">
                       <i class=" fa fa-file nr-1"></i>    Filter
                </button>           
            </div>                                    
             <!-- card-header -->
            <div class="card-header row" :class="{hidden_filter : hidden_on.aktif}">                
   
               
            <div class="col-md">
                 <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select class="form-control" v-model="tahun" id="year">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
            </div>
            <div class="col-md">
                 <div class="form-group">
                    <label for="month">Pilih Bulan</label>
                    <select class="form-control" id="month" v-model="bulan">
                        <option value="0">Semua Bulan</option>
                     <option v-for="(month, index) in months" :key="index" :value="formatMonth(index + 1)">
                        {{ month }}
                    </option>
                    </select>
                </div>
            </div>    
                               
            </div> <!-- card-header -->
            <div class="card-body pl-0 pr-0 p-md-3">                                                 
                            <h4 class="text-center text-uppercase ">Data Perpindahan Saldo Kas </h4>
                            <p class="text-center text-uppercase "> {{waktu_kop(bulan)}} - {{tahun}}</p>
                            <h5 class="text-center font-weight-bold text-uppercase ">{{aplikasi.nama}}</h5>
                           <b-table  hover responsive="sm"  bordered :items="kass.data"
                           :fields="fields" show-empty small  >
                           
                                                                                   
                                <template #thead-top>
                                        <b-tr>      
                                        <b-th colspan="3"></b-th>                                  
                                        <b-th colspan="2" class="text-center">Data Perpindahan</b-th>
                                        <b-th colspan="2" class="text-center">Penyimpanan Kas</b-th>                                   
                                        </b-tr>
                                </template>

     
                                <template #cell(no)="row">
                                    {{row.index +1 }}  
                                </template> 

                                  <template #cell(created_at)="row">
                                    <div  @click="row.toggleDetails">
                                        {{tgl_show(row.item.created_at)}}
                                    </div>
                                </template>


                                <template #cell(sumber_kas)="row">
                                    <div v-if="row.item.jenis_aksi"  @click="row.toggleDetails">
                                        {{row.item.jenis_aksi == "kantor_ke_bank" ? 'Kantor' : 'Bank'}}
                                    </div>
                                </template> 
                                  <template #cell(tujuan_kas)="row">
                                    <div v-if="row.item.jenis_aksi"  @click="row.toggleDetails">
                                       {{row.item.jenis_aksi == "kantor_ke_bank" ? 'Bank' : 'Kantor'}}
                                    </div>
                                </template> 


                                
                                 <template #cell(jumlah)="row">  
                                     <div  @click="row.toggleDetails">
                                        {{row.item.input | currency}}   
                                    </div>                              
                                                                                                        
                                </template>
                                 <template #cell(kantor)="row">  
                                     <div @click="row.toggleDetails">
                                        {{row.item.kantor | currency}}      
                                        <span class="badge badge-dark float-right" v-if="row.item.perubahanM">R</span> 
                                    </div>                              
                                                                                                       
                                </template>
                                
                                 <template #cell(bank)="row">                                
                                 <div @click="row.toggleDetails">
                                   {{row.item.bank | currency}}   
                                       <span class="badge badge-dark float-right" v-if="row.item.perubahanB">R</span> 
                                  </div>                                                                     
                                </template>
                                   
                                 <template #cell(total)="row">                                
                                 <div @click="row.toggleDetails">
                                   {{row.item.bank + row.item.kantor | currency}}   
                                     
                                  </div>                                                                     
                                </template>


     <!-- //detail -->
                <template #cell(detail)="row">
                    <b-button size="sm" variant="grey" @click="row.toggleDetails">
                    {{ row.detailsShowing ? 'Tutup' : 'Lihat'}}
                    </b-button>            
                </template>

                <template #row-details="row">
                    <div :key="'detail' + row.index" class="card-body bg-white" >                                       
                            <img class="img-fluid" :src="'/storage/kas/'+row.item.foto" alt="Photo">
                                <hr>                                                                                     
                               <strong><i class="fas fa-book mr-1"></i> Uraian</strong>
                                <p class="text-muted" v-html="row.item.uraian">
                              
                                </p>
                                
                                <hr>
                                <strong><i class="fa fa-fax mr-1"></i> Kas Kantor</strong>
                                <p style="font-size:1.5rem" class="font-weight-bold">
                                Rp  {{row.item.kantor | currency}} 
                                </p> 
                                <p><i> {{row.item.kantor ? row.item.kantor : 0 | terbilang}} rupiah</i></p>
                                
                                <hr>
                                <strong><i class="fa fa-fax mr-1"></i> Kas Bank</strong>
                                <p style="font-size:1.5rem" class="font-weight-bold">
                                Rp  {{row.item.bank | currency}} 
                                </p> 
                                <p><i> {{row.item.bank ? row.item.bank : 0 | terbilang}} rupiah</i></p>

                                <hr>
                                <strong><i class="fa fa-user mr-1"></i> Dibuat oleh</strong>
                                <p class="text-muted"> {{row.item.user.name}} | {{row.item.user.jabatan.nama_jabatan}} </p>
                            <hr>
                                <strong><i class="fa fa-calendar mr-1"></i> Tanggal dibuat</strong>
                                <p class="text-muted">
                                {{tgl_detil(row.item.created_at)}}
                                </p>
                                
                                <hr>                                                                                     
                               <strong><i class="fas fa-book mr-1"></i> Data Awal</strong>
                                <div class="text-muted" v-html="row.item.history"></div>
                            <span v-if="$role(2)">                           
                              <button v-if="row.index === kass.data.length - 1" class="btn btn-outline-danger float-right" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i>Hapus </button>
                            </span>
                    </div>
                </template>



                                 

                                </b-table>

            <span class="badge badge-dark">R</span> <i>menunjukan adanya perubahan inputan keluar masuk data terbaru</i>

            </div>
            
                   
            <form-add></form-add>          
    </div>
</template>


<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
import formAdd from './add.vue';

import moment from "moment"
moment.locale('id');  
    export default {
        data (){
            return {              
                 checkBox : [],
                searching: '',
                 fields: [ 
                    {key: 'no'},
                    {key: 'created_at', label : 'Tgl' },                    
                    {key: 'jumlah', label : 'Jumlah' },  
                    {key: 'sumber_kas', label : 'Sumber Kas' },  
                    {key: 'tujuan_kas',  label : 'Tujuan Kas'},        
                    {key: 'kantor', label : 'Kantor' },    
                    {key: 'bank', label : 'Bank' },  
                     {key: 'total', label : 'Total' }                                                                                                   
                ],     

                  // Set default selected year to the current year
                   years: [],                  
                  months: moment.months() // Get month names from moment.js
            }
        },
         mounted() {
                this.generateYears();
            },
        components : {
            formAdd
        },
        created (){
            if(!this.kass.data.length){                 
                    this.get_kas();
            }    
            
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('kas_stores', {
                kass: state => state.kass,
                kas: state => state.kas,
                hidden_on : state => state.hidden_on,                                    
            }),       
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),
            
           
            tahun :{
                    get(){
                        return this.$store.state.kas_stores.tahun
                },
                set(val){
                    this.$store.state.kas_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.kas_stores.bulan
                },
                set(val){
                    this.$store.state.kas_stores.bulan = val
                }
            },
        },
                   
           watch: {
                tahun (){
                     this.get_kas()
                },
                bulan () {
                     this.get_kas()
                }
              
           },
        methods : {
              ...mapActions('kas_stores', ['get_kas',  'remove_kas']),            
            hapus(param){              
                //ketika dihapus, data guru yang beralasi juga hapus
                 this.$swal({
                    title: 'Hapus Data',
                    text: "Tindakan ini akan menghapus secara permanent!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Lanjutkan!'
                }).then((result) => {
                    if (result.value) {
                        this.remove_kas(param);
                    }
                })
           
            }, 
            
            tgl_show (data){
            return moment(data).format('DD-MM-YYYY')          
            }, 
                     
            tgl_detil (data){
                  return moment(data).format('LLLL')           
            },  
              waktu_kop(data){
                  if(data != 0) {
                    return  moment(data).format('MMMM')
                  }
                  return "Januari-Desember" ;
            }, 
                                    
            hidden () {
                this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
            }, 
             generateYears() {
                const currentYear = moment().year();                
                const startYear = 2024; // Atur tahun awal sesuai kebutuhan
                 for (let year = startYear; year <= currentYear + 1; year++) {
                        this.years.push(year);
                    }
            },
             formatMonth(month) {
                return month < 10 ? `0${month}` : `${month}`; // Format month to '01', '02', etc.
                }
            
            
        }
    }



</script>

