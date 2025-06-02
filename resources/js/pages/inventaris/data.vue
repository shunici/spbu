<template>
    <div class="card">
        
            <div class="card-header no-print" v-if="$role(2)">
                 <button  type="button" class="btn btn-outline-primary float-left" v-b-modal.add-inventaris>
                      <i class="fa fa-plus-square mr-1" aria-hidden="true"></i>      Buat Data Baru
                </button>      
                
                   <button  type="button" class="btn btn-outline-primary float-right" @click="hidden">
                       <i class=" fa fa-file nr-1"></i>    Filter
                </button>       
            </div> <!-- card-header -->
            <div class="card-header no-print row" :class="{hidden_filter : hidden_on.aktif}" >

                <div class="col-md-6">                                                   
                          <div class="form-inline">
                            <label class="mr-2">Perhalaman</label>
                            <select class="form-control"  v-model="perHalaman">    
                                 <option value="">Semua Data</option>                             
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>     
                                <option value="200">200</option>     
                                  <option value="500">500</option>         
                            </select>                           
                        </div>        
                </div>
                
                <div class="col-md-6">           
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
                <div class="col-12 mt-3" v-if="$role(2) || $role(1) || $role(3)">
                                                                       
                    <div class="form-group">
                            <div class="form-check d-inline-block mr-3" v-for="field in fields" :key="field.key" >
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    v-model="field.visible"
                                    :disabled="visibleFields.length == 1 && field.visible"
                                    :id="'checkbox-' + field.key"
                                    v-if="field.key !== 'aksi' || $role(2)"
                                >
                                <label class="form-check-label" :for="'checkbox-' + field.key"     v-if="field.key !== 'aksi' || $role(2)">
                                    {{ field.label }}
                                </label>
                            </div>
                    </div> <!-- //formcehbox -->
           
                </div>
         

            
          
            </div> <!-- card-header -->
                                                
<div class="card-body pl-0 pr-0 p-md-3">
    
                           <b-table striped hover responsive  bordered :items="inventariss.data" small
                           :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" foot-clone>                                             

                               
                                <template #cell(no)="row">
                                    {{ (page*inventariss.meta.per_page)-inventariss.meta.per_page + row.index +1 }}  
                                </template>         

                                <template #cell(nama)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.nama}}
                                    </div>
                                </template>
                                <template #cell(kategori)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.kategori}}
                                    </div>
                                </template>
                                
                                <template #cell(total)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.total}}
                                    </div>
                                </template>
                                
                                <template #cell(kondisi_baik)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.kondisi_baik}}
                                    </div>
                                </template>   
                                <template #cell(kondisi_rusak)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.kondisi_rusak}}
                                    </div>
                                </template>
                                <template #cell(nilai_aset)="row">
                                    <div @click="row.toggleDetails">   
                                        {{row.item.harga * row.item.total | currency}}
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
                            <img class="img-fluid" :src="'/storage/inventaris/'+row.item.foto" alt="Photo">
                                <hr>
                                <strong><i class="fa fa-briefcase mr-1"></i> Nama </strong>
                                <p class="text-muted">{{row.item.nama}}</p>                            
                                <hr>
                               <strong><i class="fas fa-book mr-1"></i> Keterangan</strong>
                                <p class="text-muted" v-html="row.item.uraian">                              
                                </p>
                                <hr>
                                <strong><i class="fa fa-fax mr-1"></i> Total</strong>
                                <p class="text-muted">{{row.item.total}} {{row.item.satuan}} </p>                                
                                <hr>                              
                                <strong><i class="fa fa-calendar mr-1"></i> Tanggal dibuat</strong>
                                <p class="text-muted">
                                {{tgl_detil(row.item.created_at)}}
                                </p>
                    </div>
                </template>


                                <template #cell(aksi)="row">
                                    <div :key="row.index">                                                                                                   
                                        <button type="button" class="btn btn-outline-success btn-xs"  @click="edit(row.item.id)" v-b-modal.edit-inventaris>
                                            <i class="fas fa-edit"></i>
                                            </button>         
                                                   <button class="btn btn-outline-warning btn-xs" @click="edit(row.item.id)" v-b-modal.kondisi-inventaris><i class="fa fa-cubes"></i> </button>                       
                                        <button class="btn btn-outline-danger btn-xs" @click="hapus(row.item.id)" ><i class="fas fa-trash"></i> </button>
                                    </div>
                                </template> 
                                <!-- //footer -->
                                 <!-- <template v-slot:custom-foot>    
                                 <tr>
                                        <th :colspan="visibleFields.length - 3"  class="text-right">
                                         
                                        </th>
                                        <th>{{jumlah.total}}</th>
                                        <th>{{jumlah.kondisi_baik}}</th>
                                        <th>{{jumlah.kondisi_rusak}}</th>   
                                                                                                                                                                                                                                                                   
                                    </tr>                                   
                            </template>   -->


       <template v-slot:foot(no)>    
           <div class="d-none"></div>
      </template> 

       <template v-slot:foot(nama)>    
           <div class="d-none"></div>
      </template>   
        <template v-slot:foot(kategori)>    
     Total
      </template>  
        <template v-slot:foot(total)>    
            {{totalJumlah}}
      </template>   
        <template v-slot:foot(kondisi_baik)>    
             {{totalKondisiBaik}}
      </template>   
        <template v-slot:foot(kondisi_rusak)>    
            {{totalKondisiRusak}} 
      </template>   
        <template v-slot:foot(nilai_aset)>    
           {{total_nilai_aset | currency}}
      </template> 




                                </b-table>
</div> <!-- body card -->

       
            <div class="card-footer row no-print">
                     <div class="col-md-auto mr-auto">                    
                            <b-pagination
                            v-model="page"
                            :total-rows="inventariss.meta.total"
                            :per-page="inventariss.meta.per_page"
                            first-text="Awal"
                            prev-text="Prev"
                            next-text="Next"
                            last-text="Akhir"
                            aria-controls="inventariss"
                            v-if="inventariss.data && inventariss.data.length > 0"
                            ></b-pagination>        
                    </div>   
                     <div class="col-md-auto ml-auto">                    
                          Menampikan   {{inventariss.data.length}}  dari {{inventariss.meta.total}} data
                    </div>                                                                            
            </div> <!-- foter -->            
            <form-add></form-add>
            <form-edit></form-edit>
             <form-kondisi></form-kondisi>
           
    </div>
</template>


<script>
import moment from "moment"
moment.locale('id');  
  import { mapActions, mapState, mapMutations } from 'vuex'
import formAdd from './add.vue';
import formEdit from './edit.vue';
import formKondisi from './kondisi.vue';
    export default {
        data (){
            return {              
              
                searching: '',                               
            }
        },
        components : {
            formAdd, formEdit, formKondisi
        },
        created (){
            if(!this.inventariss.data.length){                 
                    this.get_inventaris();
            }        
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('inventaris_stores', {
                inventariss: state => state.inventariss,
                inventaris: state => state.inventaris,
                jumlah : state => state.jumlah,
                 fields : state => state.fields,
                   hidden_on : state => state.hidden_on,                 
            }),                    
            visibleFields() {
                return this.fields.filter(field => field.visible)
            },            
            page : {
                get(){
                        return this.$store.state.inventaris_stores.page
                },
                set(val){
                    this.$store.commit('inventaris_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.inventaris_stores.perHalaman
                },
                set(val){
                    this.$store.state.inventaris_stores.perHalaman = val
                }
            },
            totalJumlah() {
            return this.inventariss.data.reduce((total, item) => total + item.total, 0);
            },            
            totalKondisiBaik() {
            return this.inventariss.data.reduce((total, item) => total + item.kondisi_baik, 0);
            },            
            totalKondisiRusak() {
            return this.inventariss.data.reduce((total, item) => total + item.kondisi_rusak, 0);
            },
            total_nilai_aset(){
                 return this.inventariss.data.reduce((acc, item) => {
                    return acc + item.harga * item.total;
                }, 0);
            }

        },
                   
           watch: {
                
                 page(){
                    this.get_inventaris()
                },
             
                perHalaman() {            
                    this.get_inventaris()
                },
           },
        methods : {
              ...mapActions('inventaris_stores', ['get_inventaris', 'edit_inventaris', 'remove_inventaris']),            
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
                        this.remove_inventaris(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_inventaris(this.searching)
              
            },
            edit(param){
               this.edit_inventaris(param)            
            },                             
            tgl_detil (data){
                  return moment(data).format('LLLL')           
            },                                   
            hidden () {
                this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
            },              
                 updateFieldVisibility() {
                            if (window.innerWidth <= 768) {
                                // Mode Mobile
                                this.fields.forEach((field) => {
                                if (["kategori", "nilai_aset"].includes(field.key)) {
                                    field.visible = false;
                                }
                                });
                            } else {
                                // Mode Desktop
                                this.fields.forEach((field) => {
                                if (["kategori", "nilai_aset"].includes(field.key)) {
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
