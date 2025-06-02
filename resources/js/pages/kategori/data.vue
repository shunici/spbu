<template>
    <div class="card">
        
            <div class="card-header" v-if="$role(2)">
                 <button  type="button" class="btn btn-outline-primary float-left" v-b-modal.add-kategori>
                          <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>    Buat Data Baru
                </button>            
            </div> <!-- card-header -->
            <div class="card-header row">

            
                
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
                    
                             


                           <b-table striped hover responsive  bordered :items="kategoris.data" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" >
                           
    
                                <template #cell(no)="row">
                                    {{  row.index +1 }}  
                                </template>                                                                                     
                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-kategori>
                                            <i class="fas fa-edit"></i>
                                            </button>                                
                                        <button v-if="$role(1)" class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 

                                </b-table>



            </div>         
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
                    {key: 'jenis', class : 'text-capitalize', sortable: true, label : 'Jenis', visible : true},                                                        
                    {key: 'aksi', sortable: false, label : 'Aksi', visible : false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        components : {
            formAdd, formEdit
        },
        created (){
            if(!this.kategoris.data.length){                 
                    this.get_kategori();
            }        
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('kategori_stores', {
                kategoris: state => state.kategoris,
                kategori: state => state.kategori,
            }),                  
            visibleFields() {
                return this.fields.filter(field => field.visible)
            },  
            
            page : {
                get(){
                        return this.$store.state.kategori_stores.page
                },
                set(val){
                    this.$store.commit('kategori_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.kategori_stores.perHalaman
                },
                set(val){
                    this.$store.state.kategori_stores.perHalaman = val
                }
            },
        },
            
        methods : {
              ...mapActions('kategori_stores', ['get_kategori', 'edit_kategori', 'remove_kategori']),            
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
                        this.remove_kategori(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_kategori(this.searching)
              
            },
            edit(param){
               this.edit_kategori(param)            
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
                        this.remove_kategori(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },
            
            
        }
    }
</script>

