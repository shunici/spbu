<template>
    <div class="card">
            <div class="card-header" v-if="$role(2)">
                 <button   type="button" class="btn btn-outline-primary float-left" v-b-modal.add-user>
                          <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>    Buat Data Baru
                </button>    
                        
           <button  type="button" class="btn btn-outline-primary float-right btn-sm" @click="hidden">
                     <i class="fa fa-calendar"></i>  
                </button>           
            </div> <!-- card-header -->
            <div class="card-header row" :class="{hidden_filter : hidden_on.aktif}">

                <div class="col-md-auto ">                                                   
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
                
                <div class="col-md-auto">           
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
                                                                       
                <b-form-group>
                <b-form-checkbox
                :disabled="visibleFields.length == 1 && field.visible"
                v-for="field in fields" 
                v-model="field.visible" 
                :key="field.key"           
                name="flavour-4a"
                inline
                class="text-center">
                            {{ field.label }}
                        </b-form-checkbox>
                </b-form-group> 
           
                </div>

            
      
            </div> <!-- card-header -->
            <div class="card-body pl-0 pr-0 p-md-3">                                                 
                           <b-table striped hover responsive  bordered :items="user_all" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" > 

                                <template #cell(name)="row">
                                    <div  @click="row.toggleDetails">
                                        {{row.item.name}}
                                    </div>                              
                                </template>       
                                
                                <template #cell(email)="row">
                                    <div  @click="row.toggleDetails">
                                        {{row.item.email}}
                                    </div>                              
                                </template>                                                                  
                                <template #cell(jabatan_id)="row">
                                  <div v-if="row.item.jabatan"  @click="row.toggleDetails">
                                      {{row.item.jabatan.nama_jabatan}}
                                  </div>
                                  <div v-else>
                                      Menunggu Konfirmasi
                                  </div>
                                </template> 
                                 
                                <template #cell(gajih_pokok)="row">
                                  <div v-if="row.item.jabatan"  @click="row.toggleDetails">
                                      {{row.item.jabatan.gajih_pokok | currency}}
                                  </div>
                                  <div v-else>
                                      Menunggu Konfirmasi
                                  </div>
                                </template>    
                                <template #cell(no)="row">
                                    <div @click="row.toggleDetails">
                                        {{ row.item.nomor_urut}}  
                                    </div>
                                </template>    
                                
                                <template #cell(status)="row">                                   
                                <span class="badge" :class="row.item.status == 1 ? 'badge-success' : 'badge-danger' " > {{row.item.status == 1 ? 'Aktif' : 'Non Aktif'}}</span>
                                </template>                                                                                   
                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-user>
                                            <i class="fa fa-users"></i> 
                                            </button>                                
                                     
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
                                                <img class="img-fluid" :src="'/storage/user/'+row.item.foto" alt="Photo">
                                                    <hr>
                                                    <strong><i class="fa fa-user mr-1"></i> Nama </strong>
                                                    <p class="text-muted">{{row.item.name}}</p>                            
                                                    <hr>
                                                <strong><i class="fas fa-book mr-1"></i> Alamat</strong>
                                                    <p class="text-muted" v-html="row.item.alamat">                              
                                                    </p>
                                                    <hr>
                                                    <strong><i class="fa fa-fax mr-1"></i> Tugas</strong>
                                                    <p v-if="row.item.jabatan" class="text-muted"> {{row.item.jabatan.fungsi_tugas}} </p>       
                                                    <p v-else class="text-muted">Menunggu Konfirmasi</p>                         
                                                    <hr>   
                                                <strong><i class="fa fa-mobile  mr-1"></i> Kontak</strong>
                                                    <p  class="text-muted"> {{row.item.hp}} </p>                                                                               
                                                    <hr>    
                                                    
                                                    <button v-if="$role(2) || $role(3) || $role(1)" class="btn btn-success rounded-pill pull-right" @click="hubungi(row.item.hp)">
                                                    <i class="fa fa-whatsapp"></i>  Hubungi</button>
                                        </div>
                                    </template>

                                </b-table>



            </div>
            <div class="card-footer row">
                     <div class="col-md-auto mr-auto">                    
                            <b-pagination
                            v-model="page"
                            :total-rows="users.meta.total"
                            :per-page="users.meta.per_page"
                            first-text="Awal"
                            prev-text="Prev"
                            next-text="Next"
                            last-text="Akhir"
                            aria-controls="users"
                            v-if="users.data && users.data.length > 0"
                            ></b-pagination>        
                    </div>   
                     <div class="col-md-auto ml-auto">                    
                          Menampikan   {{users.data.length}}  dari {{users.meta.total}} data
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
                     {key: 'no', label : 'No', visible : true, class : 'text-center' },
                    {key: 'name', label : 'Nama', visible : true,},
                    {key: 'email', label : 'Email', visible : true},                                                          
                    {key: 'jabatan_id', label : 'Jabatan', visible : true,},   
                    {key: 'gajih_pokok', label : 'Gaji Pokok', label : 'Gaji Pokok', visible : false,},      
                    {key: 'status', label : 'Status', visible : true,},                             
                    {key: 'aksi', label : 'Setting', sortable: false, visible : false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        components : {
            formAdd, formEdit
        },
        created (){
            if(!this.users.data.length){                 
                    this.get_user();
            }    
            
            if(!this.jabatans.data.length){                 
                    this.get_jabatan();
            }      
        },
        
        computed : {
            ...mapState(['errors']),
            ...mapState('user_stores', {
                users: state => state.users,
                 user_all : state => state.user_all,
                user: state => state.user,
                 hidden_on : state => state.hidden_on, 
            }),
            
            ...mapState('jabatan_stores', {
                jabatans: state => state.jabatans,            
            }),
            
            page : {
                get(){
                        return this.$store.state.user_stores.page
                },
                set(val){
                    this.$store.commit('user_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.user_stores.perHalaman
                },
                set(val){
                    this.$store.state.user_stores.perHalaman = val
                }
            },                 
            visibleFields() {
                return this.fields.filter(field => field.visible)
                
            },  
        },
                   
           watch: {
                
                 page(){
                    this.get_user()
                },
             
                perHalaman() {            
                    this.get_user()
                },
           },
        methods : {
              ...mapActions('user_stores', ['get_user', 'edit_user', 'remove_user']),            
              ...mapActions('jabatan_stores', ['get_jabatan']),
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
                        this.remove_user(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_user(this.searching)
              
            },
            edit(param){
               this.edit_user(param)            
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
                        this.remove_user(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },                 
            hubungi(param) {
                var text = "Assalamu'alaikum Wr.Wb"
                    var nomor  = param;                   
                    // URL untuk membuka WhatsApp
                    const whatsappUrl = `https://wa.me/${nomor}?text=${text}`;
                    // Arahkan ke URL WhatsApp
                    window.open(whatsappUrl, "_blank");
            },            
              hidden () {
                    this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
                },
                 updateFieldVisibility() {
                            if (window.innerWidth <= 768) {
                                // Mode Mobile
                                this.fields.forEach((field) => {
                                if (["email", "gajih_pokok"].includes(field.key)) {
                                    field.visible = false;
                                }
                                });
                            } else {
                                // Mode Desktop
                                this.fields.forEach((field) => {
                                if (["email", "gajih_pokok"].includes(field.key)) {
                                    field.visible = true;
                                }
                                });
                            }
                 }

            
            
        },
         mounted() {
                this.updateFieldVisibility();
                window.addEventListener("resize", this.updateFieldVisibility);
            },
            beforeDestroy() {
                window.removeEventListener("resize", this.updateFieldVisibility);
        },
    }
</script>

