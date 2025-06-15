<template>
    <div class="card">
        
            <div class="card-header" v-if="$role(2)">
                 <button  type="button" class="btn btn-outline-primary float-left" v-b-modal.add-realisasi>
                          <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>    Buat Data Baru
                </button>            
            </div> <!-- card-header -->
            <div class="card-header row">

                     
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
                                    v-if="field.label !== 'Aksi' || $role(2) || $role(1)"
                                >
                                <label class="form-check-label" :for="'checkbox-' + field.key"     v-if="field.label !== 'Aksi' || $role(2) || $role(1)">
                                    {{ field.label }}
                                </label>
                            </div>
                    </div> <!-- //formcehbox -->
           
                </div>
         

            
      
            </div> <!-- card-header -->
            <div class="card-body pl-0 pr-0 p-md-3">
                    
                             


                           <b-table striped hover responsive  bordered :items="realisasis.data" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" foot-clone >
                           
    
                                <template #cell(no)="row">
                                 <div  @click="row.toggleDetails"> {{  row.index +1 }}  </div>  
                                </template>        
                                
                            <template #cell(tgl)="row">
                               <div @click="row.toggleDetails">
                                    {{tgl_show(row.item.tgl)}}
                               </div>                                                             
                        </template>
                        
                                <template #cell(uraian)="row">
                                   <div v-html="row.item.uraian" @click="row.toggleDetails"></div>
                                </template>    
                                
                                <template #cell(toko)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.toko}}
                                    </div>
                                </template>
 
                                <template #cell(nominal)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.nominal | currency}}
                                    </div>
                                </template>
                                
                                <template #cell(total)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.total | currency}}
                                    </div>
                                </template>

                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-realisasi>
                                            <i class="fas fa-edit"></i>
                                            </button>                                
                                        <button v-if="$role(1)" class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
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
                            <img class="img-fluid" :src="'/storage/realisasi/'+row.item.foto" alt="Photo">
                                <hr>
                               <strong><i class="fa fa-briefcase mr-1"></i> Uraian</strong>
                                <div class="text-muted" v-html="row.item.uraian">                              
                                </div>
                                <hr>    
                                
                               <strong><i class="fas fa-book mr-1"></i> Nama Toko</strong>
                                <div class="text-muted">   {{row.item.toko}}                           
                                </div>
                                <hr>  
                               <strong><i class="fa fa-fax mr-1"></i> Detil Jumlah</strong>
                                <p class="text-muted">      {{row.item.nominal | currency}}     x    {{row.item.qty}}      {{row.item.satuan}}          
                                </p>
                                <hr>      
                               <strong><i class="fas fa-book mr-1"></i> Total Seluruh</strong>
                                <p class="text-muted">      {{row.item.total | currency}}                        
                                </p>
                                <hr>     
                               <strong><i class="fa fa-window-restore"></i> Keterangan</strong>
                                <div class="text-muted">   {{row.item.Keterangan}}                           
                                </div>
                                <hr>                
                                <strong><i class="fa fa-calendar mr-1"></i> Tanggal dibuat</strong>
                                <p class="text-muted">
                                {{tgl_detil(row.item.created_at)}}
                                </p>
                    </div>
                </template>

                                
                            <!-- tabel footer -->
                            <template v-slot:foot(no)><div class="d-none"></div></template> 
                            <template v-slot:foot(tgl)><div class="d-none"></div></template> 
                            <template v-slot:foot(uraian)><div class="d-none"></div></template> 
                            <template v-slot:foot(toko)><div class="d-none"></div></template> 
                            <template v-slot:foot(qty)><div class="d-none"></div></template> 
                            <template v-slot:foot(satuan)><div class="d-none"></div></template> 
                            <template v-slot:foot(nominal)>Grand Total</template> 
                            <template v-slot:foot(total)>
                               {{total_seluruh | currency}}
                           </template> 
                   
                            <template v-slot:foot(aksi)><div class="d-none"></div></template> 



                                </b-table>



            </div>        
            <form-add></form-add>
            <form-edit></form-edit>
    </div>
</template>


<script>
import moment from "moment"
  import { mapActions, mapState, mapMutations } from 'vuex'
import formAdd from './add.vue';
import formEdit from './edit.vue';
    export default {
        data (){
            return {                        
                searching: '',
                 fields: [                    
                     {key: 'no',  label : 'No', visible : true},
                    {key: 'tgl',   label : 'Tgl', visible : true},
                    {key: 'uraian',   label : 'Uraian', visible : true},
                    {key: 'toko',   label : 'Toko', visible : true},
                         {key: 'nominal',   label : 'Hrg Satuan', visible : true},
                    {key: 'qty',   label : 'Qty', visible : true},  
                     {key: 'satuan',   label : 'Satuan', visible : false},                                                      
                 
                    {key: 'total',   label : 'Total', visible : true},                    
                    {key: 'aksi', sortable: false, label : 'Aksi', visible : false}, //TAMBAHKAN CODE INI
                ],
                  years: [],    
            months: moment.months() // Get month names from moment.js
               
            }
        },
        components : {
            formAdd, formEdit
        },
        created (){
            if(!this.realisasis.data.length){                 
                    this.get_realisasi();
            }        
        },      
           watch: {
                tahun (){
                     this.get_realisasi()
                },
                bulan () {
                     this.get_realisasi()
                }
              
           },
        computed : {
            ...mapState(['errors']),
            ...mapState('realisasi_stores', {
                realisasis: state => state.realisasis,
                realisasi: state => state.realisasi,
            }),                  
            visibleFields() {
                return this.fields.filter(field => field.visible)
            },              
                     
            tahun :{
                    get(){
                        return this.$store.state.realisasi_stores.tahun
                },
                set(val){
                    this.$store.state.realisasi_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.realisasi_stores.bulan
                },
                set(val){
                    this.$store.state.realisasi_stores.bulan = val
                }
            },   
            total_seluruh() {
            return this.realisasis.data.reduce((total, item) => total + item.total, 0);
            }, 
        },
                                     
         mounted() {
                this.generateYears();
                  this.updateFieldVisibility();
                window.addEventListener("resize", this.updateFieldVisibility); 
            },                    
            beforeDestroy() {
                window.removeEventListener("resize", this.updateFieldVisibility);
        },
        methods : {
              ...mapActions('realisasi_stores', ['get_realisasi', 'edit_realisasi', 'remove_realisasi']),            
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
                        this.remove_realisasi(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_realisasi(this.searching)
              
            },
            edit(param){
               this.edit_realisasi(param)            
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
                        this.remove_realisasi(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },            
            tgl_show (data){
           return moment(data).format('DD-MM')          
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
                } ,
            
                 updateFieldVisibility() {
                            if (window.innerWidth <= 768) {
                                // Mode Mobile
                                this.fields.forEach((field) => {
                                if (["no", "tgl", 'uraian', 'qty', 'satuan', 'nominal',].includes(field.key)) {
                                    field.visible = false;
                                }
                                });
                            } else {
                                // Mode Desktop
                                this.fields.forEach((field) => {
                                if (["no", "tgl", 'uraian', 'qty',  'nominal',].includes(field.key)) {
                                    field.visible = true;
                                }
                                });
                            }
                 }
            
            
        }
    }
</script>

