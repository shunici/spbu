<template>
    <div class="card">
        
            <div class="card-header" v-if="$role(2)">
                 <button  type="button" class="btn btn-outline-primary float-left" v-b-modal.add-barang>
                          <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>    Buat Data Baru
                </button>            
            </div> <!-- card-header -->
            <div class="card-header row">

                <div class="col-md-auto mr-auto">                                                   
                          <div class="form-inline">
                            <label class="mr-2">Perhalaman</label>
                            <select class="form-control"  v-model="perHalaman">                                 
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>               
                            </select>                           
                        </div>        
                </div>
                
                <div class="col-md-auto ml-auto">           
               <div class="form-inline">
                    <label class="mr-2">Pencarian</label>
                    <input type="text" class="form-control" v-model="searching"  v-on:keyup.enter="cari">                    
                        <div class="input-group-append">
                        <button type="submit" class="btn btn-default" @click="cari">
                        <i class="fas fa-search"></i>
                        </button>
                        </div>
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
                    
                             


                           <b-table striped hover responsive  bordered :items="barangs.data" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" >
                           
    
                                <template #cell(no)="row">
                                    {{ (page*barangs.meta.per_page)-barangs.meta.per_page + row.index +1 }}  
                                </template>        
                                
                                <template #cell(kuantitas)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.kuantitas | currency}}
                                    </div>
                                </template>
                                <template #cell(nominal)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.nominal | currency}}
                                    </div>
                                </template>

                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-barang>
                                            <i class="fas fa-edit"></i>
                                            </button>                                
                                        <button v-if="$role(1)" class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 

                                </b-table>



            </div>
            <div class="card-footer row">
                     <div class="col-md-auto mr-auto">                    
                            <b-pagination
                            v-model="page"
                            :total-rows="barangs.meta.total"
                            :per-page="barangs.meta.per_page"
                            first-text="Awal"
                            prev-text="Prev"
                            next-text="Next"
                            last-text="Akhir"
                            aria-controls="barangs"
                            v-if="barangs.data && barangs.data.length > 0"
                            ></b-pagination>        
                    </div>   
                     <div class="col-md-auto ml-auto">                    
                          Menampikan   {{barangs.data.length}}  dari {{barangs.meta.total}} data
                    </div>                                                                            
            </div> <!-- foter -->            
            <form-add></form-add>
            <form-edit></form-edit>
    </div>
</template>


<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
import formAdd from './add.vue';
import formEdit from './edit.vue';
    export default {
        data (){
            return {                        
                searching: '',
                 fields: [                    
                     {key: 'no', sortable: true, label : 'No', visible : true},
                    {key: 'nama',  sortable: true, label : 'Nama', visible : true},
                    {key: 'kuantitas',  sortable: true, label : 'Kuantitas', visible : true},  
                     {key: 'satuan',  sortable: true, label : 'Satuan', visible : true},                                                      
                      {key: 'nominal',  sortable: true, label : 'Setara', visible : true},
                    {key: 'keterangan',  sortable: true, label : 'Keterangan', visible : true},
                    {key: 'aksi', sortable: false, label : 'Aksi', visible : false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        components : {
            formAdd, formEdit
        },
        created (){
            if(!this.barangs.data.length){                 
                    this.get_barang();
            }        
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('barang_stores', {
                barangs: state => state.barangs,
                barang: state => state.barang,
            }),                  
            visibleFields() {
                return this.fields.filter(field => field.visible)
            },  
            
            page : {
                get(){
                        return this.$store.state.barang_stores.page
                },
                set(val){
                    this.$store.commit('barang_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.barang_stores.perHalaman
                },
                set(val){
                    this.$store.state.barang_stores.perHalaman = val
                }
            },
        },
                   
           watch: {
                
                 page(){
                    this.get_barang()
                },
             
                perHalaman() {            
                    this.get_barang()
                },
           },
        methods : {
              ...mapActions('barang_stores', ['get_barang', 'edit_barang', 'remove_barang']),            
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
                        this.remove_barang(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_barang(this.searching)
              
            },
            edit(param){
               this.edit_barang(param)            
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
                        this.remove_barang(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },
            
            
        }
    }
</script>

