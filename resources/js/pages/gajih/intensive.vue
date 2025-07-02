<template>

<div class="row d-flex justify-content-center ml-0 mr-0  mt-4">
    <div class="col-6">   
              <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select class="form-control" v-model="tahun" id="year">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
            </div>
            <div class="col-6">
                 <div class="form-group">
                    <label for="month">Pilih Bulan</label>
                    <select class="form-control" id="month" v-model="bulan">
                        <option value="0">Semua Bulan</option>
                     <option v-for="(month, index) in months" :key="index + 'bulan'" :value="formatMonth(index + 1)">
                        {{ month }}
                    </option>
                    </select>
                </div>
            </div>   
            
    <div class="card shadow p-2" v-for="(gajih, index) in intensive" :key="index+'sdf'"  :class="colClass">  
              <!-- {{authenticated}}    -->
<img :src="aplikasi.kop" alt="" class="" style="width : 100%">

<hr>
<br>


<h3 class="text-center text-uppercase font-weight-bolder">Slip Gaji {{waktu(gajih.tgl)}}</h3>
   <hr>                   
      <div class="row">
        <div class="col-6">
          <strong>Nama :</strong><br>
          {{gajih.user.name}} <br>
          <strong>HP :</strong><br>
          {{gajih.user.hp}} <br>             
        </div>      
        <div class="col-6">
            <strong>Jabatan :</strong><br>
          {{gajih.user.jabatan.nama_jabatan}} <br>         
            <strong>Email :</strong><br>
          {{gajih.user.email}} <br>         
        </div>
        <div class="col-12">
           <strong>Alamat :</strong><br>
          {{gajih.user.alamat}}
        </div>
      </div>      
      <table class="table table-bordered table-sm mt-3">          
        <thead class="thead-dark">
          <tr>           
            <th class="text-uppercase">PENERIMAAN</th>                         
                <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td scope="row">Gajih Pokok</td>         
            <td>{{gajih.gajih_pokok | currency}}</td>          
          </tr>  

            <tr  v-for="(item, index) in  parse(gajih.ket_penerimaan)" :key="index + 'penerimaan'"> 
                    <td>
                    {{ item.uraian}}
                    </td>                
                    <td>                   
                   
                      {{ item.total | currency}}
                     
                    </td>
           </tr>    
           
          <tr class="bg-light">           
            <th class="text-uppercase">total penerimaan</th>   
            <th>{{gajih.penerimaan | currency}}</th>                
          </tr>    
          <tr>
            <th></th>
          </tr>   
           <tr>           
            <th class="text-uppercase bg-dark">PENGURANGAN</th>                         
                <th class="text-uppercase bg-dark"> Total</th>
          </tr>
            <tr  v-for="(item, index) in  parse(gajih.ket_pengurangan)" :key="index+'pengurangan'"> 
                    <td>
                    {{ item.uraian}}
                    </td>                
                    <td>                   
                   
                      {{ item.total | currency}}
                     
                    </td>
           </tr>
              
          <tr class="bg-light">           
            <th class="text-uppercase">total pengurangan</th>   
            <th>{{gajih.pengurangan | currency}}</th>                
          </tr>    
          <tr>
            <th colspan="2">Total yang diterima takmir : {{gajih.penerimaan - gajih.pengurangan | currency}}</th>
          </tr>
          <tr>
            <td colspan="2"><i>{{gajih.penerimaan - gajih.pengurangan | terbilang}} rupiah</i></td>
          </tr>
       

        </tbody>                                    
      </table>   

        {{tgl_show(gajih.created_at)}} <br><br> <hr>
    <div style="width : 100%" v-html="gajih.keterangan"></div>
         
    </div> 
    
</div>
      

</template>
<script>
import moment from "moment"
moment.locale('id');  
import uangInput from '../../components/uang_input.vue';
import { mapActions, mapState, mapMutations } from 'vuex'
        export default {
            name : 'index-intensive',
      data() {
          return {
             windowWidth: window.innerWidth, // Lebar layar saat ini
               years: [],                  
                  months: moment.months() 
          }
      },
      components : {
          uangInput
      },
      mounted  () {   
           this.generateYears();                  
              setTimeout(() => {
               this.get_intensive(this.authenticated.id);
              }, 600);             
                         
        
      },
      computed : {          
            ...mapState('gajih_stores', {
                intensive: state => state.intensive,            
            }),         
            ...mapState('user', {
                authenticated: state => state.authenticated,            
            }),     
                ...mapState('aplikasi_stores', {   
                  aplikasi : state => state.aplikasi,
                }),  
                 colClass() {
                return this.windowWidth < 768 ? "col-md-12" : "col-md-7";
              },              
           
            tahun :{
                    get(){
                        return this.$store.state.gajih_stores.tahun
                },
                set(val){
                    this.$store.state.gajih_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.gajih_stores.bulan
                },
                set(val){
                    this.$store.state.gajih_stores.bulan = val
                }
            },
      },
         watch: {
                tahun (){
                  this.get_intensive(this.authenticated.id);
                },
                bulan () {
                  this.get_intensive(this.authenticated.id);
                }
              
           },
      methods : {
          ...mapActions('gajih_stores', ['get_intensive']),
          parse(payload) {
              return JSON.parse(payload)
          },    
              tgl_show (data){
                var tgl = data? data : moment();
                 return moment(tgl).format('dddd, DD MMMM YYYY')          
              },       
              waktu (tgl){             
                 return moment(tgl).format('MMMM YYYY')          
              }, 
               updateWindowWidth() {
                this.windowWidth = window.innerWidth;
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
                }
      },
       beforeDestroy() {
            // Hapus event listener saat komponen dihancurkan
            window.removeEventListener("resize", this.updateWindowWidth);
          },
    }
</script>
