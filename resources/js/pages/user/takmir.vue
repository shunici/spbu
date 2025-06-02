<template>
    <div class="row ">       
        <div class="col-md-3 col-6 PriceTableAll mt-3" v-for="(row, index) in users" :key="'user' +index">
            <!-- Profile Image -->
            <div class="card card-primary takmir_bingkai">
                  <img class="card-img-top takmir_foto " :src="'/storage/user/' + row.foto" alt="profil-takmir" v-b-tooltip.hover.bottom :title="row.jabatan.fungsi_tugas">
              <div class="card-body text-center ">                               
                <h6 class="font-weight-bold">{{row.name}}</h6>

                <p class="text-muted ">  {{row.jabatan.nama_jabatan}}</p>

                <button v-if="$role(2) || $role(3) || $role(1)" class="btn btn-success rounded-pill text-center btn-xs " @click="hubungi(row.hp)">
                  <i class="fa fa-whatsapp"></i>  Hubungi</button>
              </div>
          
            </div>
        
</div> <!-- //col -->   
Total : {{users.length}} Petugas                 
    </div>
</template>


<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
    export default {
        data (){
            return {              
              
                searching: '',
                 fields: [             
                     {key: 'no', sortable: true},
                    {key: 'name', sortable: true},
                    {key: 'email', sortable: true},
                    {key: 'jabatan', sortable: true},   
                    {key: 'gajih_pokok', label : 'Gaji Pokok', sortable: true},      
                    {key: 'status', label : 'Status', sortable: true},                             
                    {key: 'aksi', label : 'Setting', sortable: false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        created (){
            if(!this.users.length){                 
                    this.get_user();
            }    
            
            if(!this.jabatans.data.length){                 
                    this.get_jabatan();
            }      
        },
        
        computed : {
            ...mapState(['errors']),
            ...mapState('user_stores', {
                users: state => state.user_aktif,            
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
            }
            
            
        }
    }
</script>

<style>
    .takmir_foto {
        /* width: 300px; */
        height: auto;
         object-fit: cover;
    }
    .PriceTableAll{
        display: flex;

    }
    .takmir_bingkai {
        width: 100%;    
    }
</style>

