<template>
    <div class="">
        
            <div class="d-flex justify-content-between m-3 no-print">
                 <button  type="button" class="btn btn-outline-primary" v-b-modal.add-pemasukan>
                     <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>Buat Baru
                </button>    
                 <router-link :to="{name: 'pemasukan_tabel'}" class="btn btn-outline-primary">
                   <i class="nav-icon fa fa-clone mr-1"></i>  Lihat Tabel Pemasukan
                 </router-link>

                <span>
                <div class="form-check form-check-inline d-none d-lg-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="" id="" value="col-12" v-model="col"> Lebar
                    </label>
                </div>
                 <div class="form-check form-check-inline d-none d-lg-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="" id="" value="col-8" v-model="col"> Sedang
                    </label>
                </div>
                 <div class="form-check form-check-inline d-none d-lg-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="" id="" value="col-4" v-model="col"> Kecil
                    </label>
                </div>
                </span> 

                 <button  type="button" class="btn btn-outline-primary " @click="hidden">
                     <i class="fa fa-calendar mr-1"></i>    Filter
                </button>            
            </div> <!-- dflex -->
            <hr>
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
           

            <div class="col-md">                
                    <div class="form-group">
                    <label for="kategori" class="text-uppercase">kategori </label>
                    <v-select v-model="selected_kategori" :options="kategori"></v-select>
                    <p class="text-danger" v-if="errors.kategori_id"><i>Kategori harus diisi</i></p>
                    </div>
            </div>
            
            <div class="col-md">                                  
                    <label for="kategori" class="text-uppercase" style="opacity : 0">i</label> <br>
                   <button class="btn-success btn-block btn-sm" @click.prevent="semua_kategori">Semua Kategori</button>
            </div>

            <div class="col-12 text-center mt-2">
                                                                
                   <div class="form-group">
                            <div class="form-check d-inline-block mr-3" v-for="field in fields" :key="field.key" >
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    v-model="field.visible"
                                    :disabled="visibleFields.length == 1 && field.visible"
                                    :id="'checkbox-' + field.key"
                                    v-if="field.label !== 'Aksi' || $role(2)"
                                >
                                <label class="form-check-label" :for="'checkbox-' + field.key" v-if="field.label !== 'Aksi' || $role(2)">
                                    {{ field.label }}
                                </label>
                            </div>
                    </div> <!-- //formcehbox -->
           

            </div>              
            </div> <!-- card-header -->      
       
<span  class="row d-flex justify-content-center ml-0 mr-0  mt-4">    
    
            <div :class="col" class="bg-white shadow">
                <h4 class="mt-2 mb-0 text-center text-uppercase font-weight-bolder">Data pemasukan <br> {{aplikasi.nama}}</h4>                                                                                                 
<p class="text-center">  Periode  {{bulan_saja}} Tahun {{tahun}}</p>
                      <hr>
                      
               <b-table striped hover responsive  bordered :items="pemasukans" small
                          :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" foot-clone >     
                             
                                <template #cell(no)="row" >
                                    <div @click="row.toggleDetails">  {{ row.index +1}}  </div>                                  
                                </template>  

                                <template #cell(uraian)="row">
                                    <div v-html="row.item.uraian" @click="row.toggleDetails">
                                      
                                    </div>
                                </template>
                                <template #cell(kategori)="row">
                                    <div @click="row.toggleDetails">
                                        {{row.item.kategori.nama}}
                                    </div>
                                </template>
                                 <template #cell(total)="row">
                                    <div @click="row.toggleDetails">
                                        {{row.item.total | currency}} 
                                        <span class="badge float-right no-print" :class="row.item.kas =='kantor' ? 'badge-primary' : 'badge-warning' " > {{row.item.kas == 'kantor' ? 'K' : 'B'}}</span>
                                    </div> 
                                </template>

                                  <template #cell(created_at)="row">
                                    <div  @click="row.toggleDetails"> 
                                        {{tgl_show(row.item.tgl)}}
                                    </div>
                                </template>

                                <template #cell(aksi)="row">
                                    <div :key="row.index"  class="btn-group btn-group-xs">   
                                                                                                                                        
                                        <button type="button" class="btn btn-outline-success btn-xs mr-1"  @click="edit(row.item.id)" v-b-modal.edit-pemasukan>
                                            <i class="fas fa-edit"></i>
                                            </button>                                
                                        <button class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 
                                <template #cell(by)="row">
                                    <div>
                                        {{row.item.user.name}}
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
                            <img class="img-fluid" :src="'/storage/pemasukan/'+row.item.foto" alt="Photo">
                                <hr>
                                <strong><i class="fa fa-briefcase mr-1"></i> Kategori</strong>
                                <p class="text-muted">{{row.item.kategori.nama}}</p>
                            
                                <hr>
                               <strong><i class="fas fa-book mr-1"></i> Uraian</strong>
                                <p class="text-muted" v-html="row.item.uraian">
                              
                                </p>
                                <hr>
                                <strong><i class="fa fa-fax mr-1"></i> Total</strong>
                                <p style="font-size:1.5rem" class="font-weight-bold">
                                Rp  {{row.item.total | currency}} 
                                </p> 
                                <p><i> {{row.item.total ? row.item.total : 0 | terbilang}} rupiah</i></p>
                                
                                <hr>
                                <strong><i class="fa fa-user mr-1"></i> Dibuat oleh</strong>
                                 <p class="text-muted"> {{row.item.user.name}} | {{row.item.user.jabatan.nama_jabatan}} </p>
                            <hr>
                                <strong><i class="fa fa-calendar mr-1"></i> Tanggal dibuat</strong>
                                <p class="text-muted">
                                {{tgl_detil(row.item.created_at)}}
                                </p>
                    </div>
                </template>
                                
                <!-- tabel footer -->
                            <template v-slot:foot(no)><div class="d-none"></div></template> 
                            <template v-slot:foot(created_at)><div class="d-none"></div></template> 
                            <template v-slot:foot(uraian)><div class="d-none"></div></template> 
                            <template v-slot:foot(kategori)>Total</template> 
                            <template v-slot:foot(total)>
                                Rp {{total | currency}}
                           </template> 
                            <template v-slot:foot(by)><div class="d-none"></div></template> 
                            <template v-slot:foot(aksi)><div class="d-none"></div></template> 
  






                      
                            </b-table>
                        
                        
                    </div> <!-- col -->     

</span>        
            <form-add></form-add>
            <form-edit></form-edit>
         
       
    </div>
</template>


<script>
import vSelect from 'vue-select';
import moment from "moment"
moment.locale('id');  
  import { mapActions, mapState, mapMutations } from 'vuex'
import formAdd from './add.vue';
import formEdit from './edit.vue';

    export default {
           name : 'pemasukanData',
        data (){
            return {   
                   col : 'col-12',           
                checkBox : [],
                searching: '',
                years: [],                  
                months: moment.months() // Get month names from moment.js
               
            }
        },
        components : {
            formAdd, formEdit, vSelect
        },
        created (){
            if(!this.pemasukans.length){                 
                    this.get_pemasukan();
            }       
         
        },               
         mounted() {
                this.generateYears();
                  this.updateFieldVisibility();
                window.addEventListener("resize", this.updateFieldVisibility); 
            },                    
            beforeDestroy() {
                window.removeEventListener("resize", this.updateFieldVisibility);
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('pemasukan_stores', {
                pemasukans: state => state.pemasukans,
                total: state => state.total,
                 fields: state => state.fields,
                hidden_on : state => state.hidden_on,
            }), 
        ...mapState('user', {           
             auth : state => state.authenticated,               
        }),                       
        ...mapState('kategori_stores', {                 
            kategori : state => state.kategori_pemasukan,                  
        }),         
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),           
       visibleFields() {
        return this.fields.filter(field => field.visible)
      },    
                                                                
            selected_kategori : {
                    get(){
                            return this.$store.state.pemasukan_stores.selected_kategori
                },
                set(val){
                    this.$store.state.pemasukan_stores.kategori = val.value
                    this.$store.state.pemasukan_stores.selected_kategori = val
                }
            },
            
            tahun :{
                    get(){
                        return this.$store.state.pemasukan_stores.tahun
                },
                set(val){
                    this.$store.state.pemasukan_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.pemasukan_stores.bulan
                },
                set(val){
                    this.$store.state.pemasukan_stores.bulan = val
                }
            },
             bulan_saja () {              
                 var bln = this.bulan;
                 if (bln == 0) {
                     return 'semua bulan'
                 } else {
                      return moment(bln).format('MMMM') 
                 }                
            }
        },
                   
           watch: {   
               selected_kategori(){
                    this.get_pemasukan(this.searching)
               },            
                bulan(){
                     this.get_pemasukan(this.searching)
                },
                tahun (){
                     this.get_pemasukan(this.searching)
                }
           },
        methods : {
              ...mapActions('pemasukan_stores', ['get_pemasukan', 'edit_pemasukan', 'remove_pemasukan']),                         
                hidden () {
                this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
            },    
            semua_kategori(){
                this.selected_kategori.value = "";
                this.selected_kategori.label = "SEMUA KATEGORI";
            this.$store.state.pemasukan_stores.kategori = "";
             this.get_pemasukan(this.searching);
            },
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
                        this.remove_pemasukan(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_pemasukan(this.searching)
              
            },
            edit(param){
               this.edit_pemasukan(param)            
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
                        this.remove_pemasukan(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },
            periode(data1, data2){
                return moment(data1).format('DD MMMM') + "  -  " + moment(data2).format('DD MMMM YYYY')
            },
            aktif_aksi (rekap){
                let today =  moment();
                 const startDate = moment(rekap.tgl_mulai);
                  const endDate = moment(rekap.tgl_akhir);                  
                return today.isBetween(startDate, endDate, null, '[]');
            },
           
            tgl_show (data){
            return moment(data).format('DD')          
            },               
            tgl_detil (data){
                  return moment(data).format('LLLL')           
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
                 updateFieldVisibility() {
                            if (window.innerWidth <= 768) {
                                // Mode Mobile
                                this.fields.forEach((field) => {
                                if (["aksi", 'no' ].includes(field.key)) {
                                    field.visible = false;
                                }
                                });
                            } else {
                                // Mode Desktop
                                this.fields.forEach((field) => {
                                if (["no", "created_at", ].includes(field.key)) {
                                    field.visible = true;
                                }
                                });
                            }
                 }                            
            
            
        }
    }
</script>

