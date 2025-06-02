<template>
    <div class="card">    

        
<div class="card-header">
 <div class="card-title">Data User</div>
        <div class="card-tools">
            <div class="input-group input-group-sm">
                 <input type="text" name="table_search" class="form-control float-right" placeholder="Cari Nama User" v-model="searching">
                <div class="input-group-append">
                <button type="submit" class="btn btn-default" @click="cari">
                <i class="fas fa-search"></i>
                </button>
                </div>
            </div>
        </div>
</div>          

        <div class="card-body table-responsive p-0">

                
                           <b-table striped hover responsive  bordered :items="users.data" :fields="fields" show-empty  stacked="xs">
                                <template #cell(no)="row">
                                    {{ (page*users.meta.per_page)-users.meta.per_page + row.index +1 }}  
                                </template>

                                 <template #cell(role)="row">
                                    <div :key="row.index + 'role'">
                                            {{row.item.role.name}}
                                            
                                    </div>
                                </template> 
                                

                                 <template #cell(status)="row">
                                    <div :key="row.index + 'st' " v-if="row.item.role_id > 0">
                                <span class="badge badge-success">Aktif</span>
                                    </div>
                                </template> 

                               
                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                        
                                        <button class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 

                                </b-table>
<div class="row">


                    <div class="col-md-9">
                            <b-pagination
                            v-model="page"
                            :total-rows="users.meta.total"
                            :per-page="users.meta.per_page"
                            aria-controls="users"
                            v-if="users.data && users.data.length > 0"
                            ></b-pagination>
                    </div>

                    <div class="col-md-3">
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
                    
</div>
        </div>
                   
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
                    {key: 'role', sortable: true},
                    {key: 'status', sortable: true}, 
                    {key: 'aksi', sortable: false}, //TAMBAHKAN CODE INI
                ],
               
            }
        },
        created () {
            this.getUserLists()
            this.getRoles()
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('user', {
                users: state => state.users,
                roles: state => state.roles,
                permissions: state => state.permissions,
                role_permission: state => state.role_permission
            }),
            
            page : {
                get(){
                        return this.$store.state.user.page
                },
                set(val){
                    this.$store.commit('user/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.user.perHalaman
                },
                set(val){
                    this.$store.state.user.perHalaman = val
                }
            },
        },
                   
           watch: {
                 page(){
                    this.getUserLists()
                },
             
                perHalaman() {            
                    this.getUserLists()
                }
           },
        methods : {
              ...mapActions('user', ['getUserLists', 'getRoles', 'remove_user' ]),            
            hapus(param){              
                //ketika dihapus, data guru yang beralasi juga hapus
                 this.$swal({
                    title: 'User yang sudah di Hapus tidak akan kembali',
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
            this.getUserLists(this.searching)
              
            }
        }
    }
</script>