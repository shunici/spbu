<template>
    <div class="card">
        
            <div class="card-header">    
                <router-link  type="button" class="btn btn-outline-primary float-left" :to="{'name' : 'add.laporan'}" v-if="$role(2)">
                     <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>    Buat Data Baru
                </router-link>  
                
                <button type="button" class="btn btn-outline-primary float-right" @click="halamanData" v-if="$role(2) || $role(3) || $role(1)">
                        <i class="fa fa-window-restore mr-1" aria-hidden="true"></i>    Data Laporan
                    </button>   
                
            </div> <!-- card-header -->
            <div class="card-header row">

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
                                <label for="jenis_laporan" class="text-uppercase">jenis laporan</label>
                                <select class="form-control" v-model="query_jenis_laporan" > 
                                <option value="">SEMUA LAPORAN</option>                                               
                                    <option v-for="(item, index) in jenis_laporan" :key="index">
                                    {{item}}
                                    </option>                                                                                                                       
                                </select>                           
                             
                </div>

                <div class="col-md-12">

                </div>
                
              

         

            
      
            </div> <!-- card-header -->
            <div class="card-body">
                    
                             <button v-if="checkBox.length != 0 && $role(2)" class="btn btn-danger float-right btn-xs mb-2" @click="hapus_checkBox(checkBox.length)">Hapus {{checkBox.length}} Data</button>  


                           <b-table striped hover responsive  bordered :items="template.data" small
                           :fields="fields" show-empty  stacked="xs" head-variant="dark" >
                           
                                <template #cell(#)="row">
                                  <input type="checkbox" :value="row.item.id" v-model="checkBox"   :key="row.index + 'check'">
                                </template>      
                                <template #cell(no)="row">
                                    {{ row.index +1 }}  
                                </template>                                                                                      
                                <template #cell(aksi)="row">
                                    <div :key="row.index">   
                                                                                                                                         
                                        <button v-if="$role(2)" type="button" class="btn btn-outline-info btn-xs"  @click="pakai(row.item.id)">
                                            <i class="fa fa-check-circle"></i>
                                            </button>                                                                                                   
                                        <button v-if="$role(2)" type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)">
                                            <i class="fas fa-edit"></i>
                                            </button>     
                                            <button type="button" class="btn btn-outline-primary btn-xs"  @click="show(row.item.slug)">
                                            <i class="fas fa-eye"></i>
                                            </button>                             
                                        <button v-if="$role(2)" class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 

                                </b-table>

            </div>         
   
    </div>
</template>


<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
    export default {
        name : 'dataLaporan',
        data (){
            return {              
                 checkBox : [],
                searching: '',
                 fields: [
                    {key: '#', sortable: true, class : 'text-center'},
                     {key: 'no', sortable: true},
                    {key: 'judul', class : 'text-uppercase', sortable: true},
                    {key: 'jenis_laporan', class : 'text-uppercase', sortable: true},                                                        
                    {key: 'aksi', sortable: false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        components : {
        
        },
        created (){
            if(!this.template.data.length){                 
                    this.get_template();
                    this.get_jenis_laporan();
            }        
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('laporan_stores', {
                template: state => state.template,
                laporan: state => state.laporan,
                jenis_laporan: state => state.jenis_laporan,
              
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
        },
                   
           watch: {
                
                 page(){
                    this.get_template()
                },
             
                perHalaman() {            
                    this.get_template()
                },
                query_jenis_laporan() {
                      this.get_template()
                }
           },
        methods : {
            ...mapMutations('laporan_stores', ['SET_ID_UPDATE']),
              ...mapActions('laporan_stores', ['get_template', 'get_jenis_laporan', 'edit_laporan', 'remove_laporan', 'show_laporan']),                              
               halamanData() {
                   this.laporan.template = 0;
                    this.$router.push('/laporan/data');
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
            this.get_template(this.searching)
              
            },
            edit(param){
                this.SET_ID_UPDATE(param);
               this.edit_laporan(param);   
                    this.$router.push({
                            name : 'edit.laporan',
                            params : {id : param}
                        })       
            }, 
            pakai(param){
                   this.laporan.template = 0; 
                this.SET_ID_UPDATE(param);
               this.edit_laporan(param);   
                    this.$router.push({
                            name : 'add.laporan',                      
                        })       
            }, 
                    
            show(param){
                this.SET_ID_UPDATE(param);
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
            
            
        }
    }
</script>

