<template>
    <div class="card">
        
            <div class="card-header" >    
                <router-link  type="button" class="btn btn-outline-primary float-left" :to="{'path' : '/laporan/add'}" v-if="$role(2)">
                     <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>    Buat Baru
                </router-link>   
                   
           <button  type="button" class="btn btn-outline-primary float-right" @click="hidden">
                     <i class="fa fa-calendar"></i>  
                </button>  
                <button type="button" class="btn btn-outline-primary float-right mr-2" @click="halamanTemplate" v-if="$role(2) || $role(3) || $role(1)">
                        <i class="fa fa-window-restore mr-1" aria-hidden="true"></i>    Template
                    </button>
                                 
            </div> <!-- card-header -->

            <div class="card-header row" :class="{hidden_filter : hidden_on.aktif}">

                <div class="col-md">                                                   
                        
                            <label class="mr-2">Perhalaman</label>
                            <select class="form-control"  v-model="perHalaman">                                 
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>               
                            </select>                           
                        
                </div>
                
                   <div class="col-md">                                                                                                           
                                <label for="jenis_laporan">Jenis Laporan</label>
                                <select class="form-control" v-model="query_jenis_laporan" > 
                                <option value="">SEMUA LAPORAN</option>                                               
                                    <option v-for="(item, index) in jenis_laporan" :key="index + 'laporan'">
                                    {{item}}
                                    </option>                                                                                                                       
                                </select>                           
                             
                </div>
           <div class="col-md">
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
              
    <div class="col-md">   
              <div class="form-group">
                        <label for="year">Pilih Tahun</label>
                        <select class="form-control" v-model="tahun" id="year">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
            </div>
 
            
                
              

         

            
      
            </div> <!-- card-header -->
            <div class="card-body pl-0 pr-0 p-md-3">
                    
                           <button v-if="checkBox.length != 0 && $role(2)" class="btn btn-danger float-right btn-xs mb-2" @click="hapus_checkBox(checkBox.length)">Hapus {{checkBox.length}} Data</button>  


                           <b-table striped hover responsive  bordered :items="laporans.data" small
                           :fields="fields" show-empty  stacked="xs" head-variant="dark" >
                           
                                <template #cell(#)="row">
                                  <input type="checkbox" :value="row.item.id" v-model="checkBox"   :key="row.index + 'check'">
                                </template>      
                                <template #cell(no)="row">
                                    {{ (page*laporans.meta.per_page)-laporans.meta.per_page + row.index +1 }}  
                                </template>                                                                                     
                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button v-if="$role(2)" type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)">
                                            <i class="fas fa-edit"></i>
                                            </button>     
                                            <button type="button" class="btn btn-outline-success btn-xs"  @click="show(row.item.slug)">
                                            <i class="fas fa-eye"></i>
                                            </button>                             
                                        <button v-if="$role(2)" class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 

                                </b-table>

            </div>
            <div class="card-footer row">
                     <div class="col-md-auto mr-auto">                    
                            <b-pagination
                            v-model="page"
                            :total-rows="laporans.meta.total"
                            :per-page="laporans.meta.per_page"
                            first-text="Awal"
                            prev-text="Prev"
                            next-text="Next"
                            last-text="Akhir"
                            aria-controls="laporans"
                            v-if="laporans.data && laporans.data.length > 0"
                            ></b-pagination>        
                    </div>   
                     <div class="col-md-auto ml-auto">                    
                          Menampikan   {{laporans.data.length}}  dari {{laporans.meta.total}} data
                    </div>                                                                            
            </div> <!-- foter -->   
  
   
    </div>
</template>


<script>
import moment from "moment"
moment.locale('id'); 
  import { mapActions, mapState, mapMutations } from 'vuex'
    export default {
        name : 'dataLaporan',
        data (){
            return {              
                 checkBox : [],
                years: [],                  
                  months: moment.months(),
                searching: '',
                 fields: [
                    {key: '#', sortable: true, class : 'text-center'},
                     {key: 'no', sortable: true},
                    {key: 'judul', class : 'text-uppercase', sortable: true},
                    {key: 'jenis_laporan', label : 'JNS LAPORAN', class : 'text-uppercase', sortable: true},                                                        
                    {key: 'aksi', sortable: false}, //TAMBAHKAN CODE INI
                ],
                 allFields: [
                    { key: "#", sortable: true, class: "text-center" },
                    { key: "no", sortable: true },
                    { key: "judul", class: "text-uppercase", sortable: true },
                    { key: "jenis_laporan", label: "JNS LAPORAN", class: "text-uppercase", sortable: true },
                    { key: "aksi", sortable: false }
                ],
               
            }
        },            
         mounted() {
                this.generateYears();
                  this.updateFieldVisibility(); // Panggil saat pertama kali halaman dimuat
                      window.addEventListener("resize", this.updateFieldVisibility); // Update saat layar diubah
            },                  
            beforeDestroy() {
                window.removeEventListener("resize", this.updateFieldVisibility); // Hapus listener saat komponen di-unmount
        },
        created (){
            if(!this.laporans.data.length){                 
                    this.get_laporan();
                    this.get_jenis_laporan();
            }        
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('laporan_stores', {
                laporans: state => state.laporans,
                laporan: state => state.laporan,
                jenis_laporan: state => state.jenis_laporan,
                  hidden_on : state => state.hidden_on, 
              
            }),
            
            page : {
                get(){
                        return this.$store.state.laporan_stores.page
                },
                set(val){
                    this.$store.commit('laporan_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.laporan_stores.perHalaman
                },
                set(val){
                    this.$store.state.laporan_stores.perHalaman = val
                }
            },
            query_jenis_laporan :{
                    get(){
                        return this.$store.state.laporan_stores.query_jenis_laporan
                },
                set(val){
                    this.$store.state.laporan_stores.query_jenis_laporan = val
                }
            },              
            tahun :{
                    get(){
                        return this.$store.state.laporan_stores.tahun
                },
                set(val){
                    this.$store.state.laporan_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.laporan_stores.bulan
                },
                set(val){
                    this.$store.state.laporan_stores.bulan = val
                }
            },
        },
                   
           watch: {
                
                 page(){
                    this.get_laporan()
                },
             
                perHalaman() {            
                    this.get_laporan()
                },
                query_jenis_laporan() {
                      this.get_laporan()
                },                
                tahun (){
                    this.get_laporan() 
                },
                bulan () {
                    this.get_laporan() 
                }
           },
        methods : {
            ...mapMutations('laporan_stores', ['SET_ID_UPDATE']),
              ...mapActions('laporan_stores', ['get_laporan', 'get_jenis_laporan', 'edit_laporan', 'remove_laporan', 'show_laporan']),            
                                 
              hidden () {
                    this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
                }, 
               halamanTemplate() {
                   this.laporan.template = 1;
                    this.$router.push('/laporan/template');
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
                        this.remove_laporan(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_laporan(this.searching)
              
            },
            edit(param){          
               this.edit_laporan(param);   
                    this.$router.push({
                            name : 'edit.laporan',
                            params : {id : param}
                        })       
            },  
            show(param){         
               this.show_laporan(param);   
                    this.$router.push({
                            name : 'show.laporan',
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
                        this.remove_laporan(this.checkBox);
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
                 updateFieldVisibility() {
                        if (window.innerWidth <= 768) {
                            // Mode Mobile: Sembunyikan # dan no
                            this.fields = this.allFields.filter(
                            (field) => !["#", "no"].includes(field.key)
                            );
                        } else {
                            // Mode Desktop: Tampilkan semua
                            this.fields = [...this.allFields];
                        }
                        }
            
            
        }
    }
</script>

