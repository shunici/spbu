<template>
    <div class="card">
        
            <div class="card-header no-print">
                 <button v-if="$role(2)"  type="button" class="btn btn-outline-primary float-left" v-b-modal.add-gajih @click="add">
                       <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>     Buat Data Baru
                </button>
                 <button  type="button" class="btn btn-outline-primary float-right  no-print mr-2" @click="print">
                        <i class="fa fa-print"></i>   PRINT
                </button>      
                               
           <button  type="button" class="btn btn-outline-primary float-right mr-2" @click="hidden">
                     <i class="fa fa-calendar"></i>  
                </button>           
            </div> <!-- card-header -->
            <div class="card-header row no-print" :class="{hidden_filter : hidden_on.aktif}">                                              
                    
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
            
                <div class="col-12 mt-3">
                                                                       
                    <div class="form-group">
                            <div class="form-check d-inline-block mr-3" v-for="field in fields" :key="field.key" >
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    v-model="field.visible"
                                    :disabled="visibleFields.length == 1 && field.visible"
                                    :id="'checkbox-' + field.key"
                                    v-if="field.label !== 'Aksi' || $role(2) || $role(3)"
                                >
                                <label class="form-check-label" :for="'checkbox-' + field.key"  v-if="field.label !== 'Aksi' || $role(2) || $role(3) ">
                                    {{ field.label }}
                                </label>
                            </div>
                    </div> <!-- //formcehbox -->
           
           
                </div>                     
            </div> <!-- card-header -->
            
            <div class="card-body pl-0 pr-0 p-md-3"  ref="tableToCapture" >                                                 

              <div  class="text-center kop" style="display : none">
                    <img :src="aplikasi.kop" alt="" style="width : 100%" class="text-center"> 
                    <h3 class="text-uppercase">Data Gajih       {{bulan_tahun}} </h3>
            </div>
                           <b-table  hover responsive  bordered :items="gajihs.data" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" foot-clone>

                              <template #cell(nama)="row">                                  
                                  <div @click="row.toggleDetails">
                                        {{row.item.user.name}}   
                                  </div>                                                                     
                                </template>
                                   <template #cell(jabatan)="row">  
                                        <div @click="row.toggleDetails">                                
                                        {{row.item.user.jabatan.nama_jabatan}}    
                                        </div>                              
                                </template>

                              <template #cell(penerimaan)="row">    
                                   <div @click="row.toggleDetails">                              
                                        {{row.item.penerimaan | currency}}     
                                    </div>                             
                                </template>
                                <template #cell(pengurangan)="row">                                  
                                        {{row.item.pengurangan | currency}}                                  
                                </template>
                                <template #cell(total)="row">  
                                     <div @click="row.toggleDetails">                                
                                        {{row.item.penerimaan - row.item.pengurangan | currency}}                                  
                                        </div>
                                </template>
                              <template #cell(#)="row">                                  
                                       <span class="badge" :class="row.item.sdh_terima ? 'badge-warning' : 'badge-primary' ">{{row.item.sdh_terima ? 'S' : 'B'}}</span>                                                        
                                </template>      
                                <template #cell(no)="row">
                                    {{row.index +1 }}  
                                </template>                                                                                     
                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button v-if="$role(2)" type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-gajih>
                                            <i class="fas fa-edit"></i>
                                            </button>     
                                                                                                                                             
                                        <button type="button" class="btn btn-outline-info btn-xs"  @click="show(row.item.id)">
                                            <i class="fas fa-eye"></i>
                                            </button>                             
                                        <button v-if="$role(2)" class="btn btn-outline-danger btn-xs"  @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 

                                
                            <!-- //detail -->
                <template #cell(detail)="row">
                    <b-button size="sm" variant="grey" @click="row.toggleDetails">
                    {{ row.detailsShowing ? 'Tutup' : 'Lihat'}}
                    </b-button>            
                </template>
                <template #row-details="row">
                                                       
                            <table class="table table-bordered table-sm bg-white">                                
                                        <thead>
                                        <tr class="bg-light">           
                                            <th colspan="2" class="text-uppercase">PENERIMAAN</th>                                                             
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Gaji Pokok</td>
                                                <td>   {{row.item.gajih_pokok | currency}} </td>
                                            </tr>                                            
                                                <tr  v-for="(atam, index) in json_parse(row.item.ket_penerimaan)" :key="index + 'item_penerimaan'"> 
                                                        <td>{{ atam.uraian}}</td>                
                                                        <td>{{ atam.total | currency }} </td>                                                      
                                                </tr> 
                                                <tr>
                                                    <th class="text-right">Total Rp</th>
                                                    <th>{{row.item.penerimaan | currency}}</th>
                                                </tr>
                                                 <tr class="bg-light">           
                                                        <th colspan="2" class="text-uppercase">pengurangan</th>                                                             
                                                    </tr>
                                                 <tr  v-for="(atam, index) in json_parse(row.item.ket_pengurangan)" :key="index + 'item_pengurangan'"> 
                                                        <td>{{ atam.uraian}}</td>                
                                                        <td>{{ atam.total | currency }} </td>                                                      
                                                </tr> 
                                                <tr>
                                                    <th class="text-right">Total Rp</th>
                                                    <th>{{row.item.pengurangan | currency}}</th>
                                                </tr>
                                                <tr class="bg-secondary"> 
                                                    <th class="text-right">Total yang diterima  Rp</th>
                                                    <th>{{row.item.penerimaan - row.item.pengurangan | currency}} </th>
                                                </tr>


                                        </tbody>
                            </table>
                   
                </template>

                                <!-- foter -->
                                
                                <template v-slot:foot(no)>    
                                    <div class="d-none"></div>
                                </template> 

                                <template v-slot:foot(nama)>    
                                    <div class="d-none"></div>
                                </template>   
                                <template v-slot:foot(jabatan)>    
                                <div class="d-none"></div>
                                </template>  
                                    <template v-slot:foot(penerimaan)>    
                                        <div class="d-none"></div>
                                </template>   
                                <template v-slot:foot(pengurangan)>    
                                         Total
                                </template>   
                                    <template v-slot:foot(total)>    
                                      {{totalJumlah | currency}}
                                </template>   
                                
                                <template v-slot:foot(#)>    
                                       <div class="d-none"></div>
                                </template>  
                                 <template v-slot:foot(aksi)>    
                                       <div class="d-none"></div>
                                </template>  


                                </b-table>
                                <p class="float-right"> <i> {{totalJumlah | terbilang}} rupiah</i></p>
                                   <p class="float-left"> <i> {{sisa_user_belum_didata(total_user.length, gajihs.data.length)}} </i></p>
                                
            </div> <!-- cardbody -->
            <div class="card-footer">
                 <span class="badge badge-primary">B</span>  belum diterima  &nbsp;&nbsp;&nbsp; 
                 <span class="badge badge-warning">S</span>  sudah diterima        
                 <p class="float-right no-print"><i>Input Pengeluaran ketika semua data lengkap</i> </p>       
                 
  
         <button class="btn-block btn-success" @click="captureTable"> <i class="fa fa-whatsapp"></i>Salin Data</button>
                                 
                
            </div>         
            <form-add></form-add>
            <form-edit></form-edit>
       
    </div>
</template>


<script>
import html2canvas from "html2canvas";
  import { mapActions, mapState, mapMutations } from 'vuex'
  import moment from "moment"
moment.locale('id');  
import formAdd from './add.vue';
import formEdit from './edit.vue';
    export default {
        data (){
            return {              
                 
                searching: '',                               
                  // Set default selected year to the current year
                   years: [],                  
                  months: moment.months(), // Get month names from moment.js
                
               
            }
        },
        components : {
            formAdd, formEdit
        },
         mounted() {
                this.generateYears();

                this.updateFieldVisibility();
                window.addEventListener("resize", this.updateFieldVisibility);             
               
            },            
            beforeDestroy() {
                window.removeEventListener("resize", this.updateFieldVisibility);
        },
        created (){
            if(!this.gajihs.data.length){                 
                    this.get_gajih();
            }   
             this.data_user();     
        },        
        
        computed : {
            ...mapState(['errors']),
            ...mapState('gajih_stores', {
                gajihs: state => state.gajihs,
                gajih: state => state.gajih,
                 total_user : state => state.total_user,
                fields: state => state.fields,
                hidden_on : state => state.hidden_on,     
            }),                    
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),                
            visibleFields() {
                return this.fields.filter(field => field.visible)
            },   
             totalJumlah() {
                var penerimaan =  this.gajihs.data.reduce((total, item) => total + item.penerimaan, 0);
                var pengurangan = this.gajihs.data.reduce((total, item) => total + item.pengurangan, 0);
            return  parseFloat(penerimaan) - parseFloat(pengurangan);
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
            bulan_tahun(){ //menampilkan waktu pada gajih takmir
                var bulan =  this.bulan;
                var tahun =  this.tahun;
                 var waktu = moment({ year: tahun, month: bulan - 1}); 
                 return  waktu.format("MMMM YYYY"); // Output: "Oktober 2024"
            }
           
        },
                   
           watch: {
                
                 bulan(){
                    this.get_gajih()
                },             
                tahun() {            
                    this.get_gajih()
                },
           },
        methods : {
             ...mapMutations('gajih_stores', ['CLEAR_FORM']),  
              ...mapActions('gajih_stores', ['get_gajih', 'edit_gajih', 'remove_gajih', 'data_user']),            
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
                        this.remove_gajih(param);
                    }
                })
           
            }, 
            add(){
                
                this.CLEAR_FORM()
            },
            edit(param){
               this.edit_gajih(param)            
            },              
            show(param){
               this.edit_gajih(param)
                this.$router.push({
                            name : 'show.gajih',
                            params : {id : param}
                    })   

            },            
            hapus_checkBox(param) {
               
                 this.$swal({
                    title:  param + '  Item Data Akan Dihapus',
                    text: "Tindakan ini akan menghapus secara permanent!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Lanjutkan!'
                }).then((result) => {
                    if (result.value) {
                        this.remove_gajih(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
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
                },
                
            sisa_user_belum_didata(user, gajih){
                var data = user-gajih;
                if(data != 0) {
                    return  data + " user belum mempunyai tabel gajih";
                }
                 return "Tabel penggajihan lengkap";
            },
            json_parse(param){
                return JSON.parse(param)
            },
            
            print(){                
            document.querySelector('.kop').style.display = '';
                setTimeout(function () {
                     window.print(); 
                document.querySelector('.kop').style.display = 'none';
                  
                 }.bind(this), 1000);
            },
            
               async captureTable() {
                     document.querySelector('.kop').style.display = '';
                  const table = this.$refs.tableToCapture;
                    // Tangkap tabel sebagai canvas
                    const canvas = await html2canvas(table);

                    // Konversi canvas menjadi Blob
                    const blob = await new Promise((resolve) => canvas.toBlob(resolve));

                    // Gunakan Clipboard API untuk menyalin ke clipboard
                    if (navigator.clipboard && navigator.clipboard.write) {
                      const data = [new ClipboardItem({ "image/png": blob })];
                      await navigator.clipboard.write(data);
                    
                    } else {
                      alert("Browser Anda tidak mendukung fitur clipboard untuk gambar.");
                    }
                        setTimeout(function () {
                          
                        document.querySelector('.kop').style.display = 'none';
                        
                        }.bind(this), 900);
                  
              },              
              hidden () {
                    this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
                },
                
                 updateFieldVisibility() {
                            if (window.innerWidth <= 768) {
                                // Mode Mobile
                                this.fields.forEach((field) => {
                                if (["jabatan", "penerimaan", "pengurangan", "#"].includes(field.key)) {
                                    field.visible = false;
                                }
                                });
                            } else {
                                // Mode Desktop
                                this.fields.forEach((field) => {
                                if (["jabatan", "penerimaan", "pengurangan", "#"].includes(field.key)) {
                                    field.visible = true;
                                }
                                });
                            }
                 }
            
            
        }
    }
</script>

