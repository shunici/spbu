<template>
    <div class="card">
       
            <div class="card-header">
                 <button v-if="$role(2)"  type="button" class="btn btn-outline-primary float-left" v-b-modal.add-jabatan>
                     <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>        Buat Data Baru
                </button>            
            </div> <!-- card-header -->
            <div class="card-header">                                                  
               <div class="form-inline">
                    <label class="mr-2">Pencarian</label>
                    <input type="text" class="form-control" v-model="searching"  v-on:keyup.enter="cari">                    
                        <div class="input-group-append">
                        <button type="submit" class="btn btn-default" @click="cari">
                        <i class="fas fa-search"></i>
                        </button>
                        </div>
               </div>
      

                <div class="col-md-12 mt-3">
                                                                        
                    <div class="form-group">
                            <div class="form-check d-inline-block mr-3" v-for="field in fields" :key="field.key + 'jabatan'" >
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    v-model="field.visible"
                                    :disabled="visibleFields.length == 1 && field.visible"
                                    :id="'checkbox-' + field.key"
                                    v-if="field.label !== 'Aksi' || $role(2)"
                                >
                                <label class="form-check-label" :for="'checkbox-' + field.key"     v-if="field.label !== 'Aksi' || $role(2)">
                                    {{ field.label }}
                                </label>
                            </div>
                    </div> <!-- //formcehbox -->
                </div>
         

            
      
            </div> <!-- card-header -->
            <div class="card-body pl-0 pr-0 p-md-3">
                    
                          


                           <b-table striped hover responsive  bordered :items="jabatans.data" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" >
                           

                              <template #cell(gajih_pokok)="row">                                  
                                        {{row.item.gajih_pokok | currency}}                                  
                                </template>   
                                <template #cell(no)="row">
                                    {{row.index +1 }}  
                                </template>                                                                                     
                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-jabatan>
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
                     {key: 'no', label : 'No', visible : true},
                    {key: 'nama_jabatan', label : 'Nama Jabatan', visible : true},
                    {key: 'gajih_pokok', label : 'Gaji Pokok', visible : true},                                                        
                    {key: 'aksi',  label : 'Aksi', visible : false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        components : {
            formAdd, formEdit
        },
        created (){
            if(!this.jabatans.data.length){                 
                    this.get_jabatan();
            }        
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('jabatan_stores', {
                jabatans: state => state.jabatans,
                jabatan: state => state.jabatan,
            }),                              
            visibleFields() {
                return this.fields.filter(field => field.visible)
            },  
            
            page : {
                get(){
                        return this.$store.state.jabatan_stores.page
                },
                set(val){
                    this.$store.commit('jabatan_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.jabatan_stores.perHalaman
                },
                set(val){
                    this.$store.state.jabatan_stores.perHalaman = val
                }
            },
        },
                   
           watch: {
                
                 page(){
                    this.get_jabatan()
                },
             
                perHalaman() {            
                    this.get_jabatan()
                },
           },
        methods : {
              ...mapActions('jabatan_stores', ['get_jabatan', 'edit_jabatan', 'remove_jabatan']),            
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
                        this.remove_jabatan(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_jabatan(this.searching)
              
            },
            edit(param){
               this.edit_jabatan(param)            
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
                        this.remove_jabatan(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },
            
            
        }
    }
</script>

