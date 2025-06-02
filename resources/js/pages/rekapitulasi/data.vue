<template>
    <div class="">
        
            <div class="d-flex justify-content-between m-3 no-print">
   
                   <button  type="button" class="btn btn-outline-primary" @click="hidden">
                       <i class=" fa fa-file nr-1"></i>    Filter
                </button>            

                <span>
                <div class="form-check form-check-inline d-none d-lg-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="" id="" value="col-12" v-model="col"> Lebar
                    </label>
                </div>
                 <div class="form-check form-check-inline d-none d-lg-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="" id="" value="col-8" v-model="col"> Sedang
                    </label>
                </div>
                 <div class="form-check form-check-inline d-none d-lg-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="" id="" value="col-4" v-model="col"> Kecil
                    </label>
                </div>
                </span> 

            </div> <!-- flexx -->
            <hr>
            <div class="card-header row" :class="{hidden_filter : hidden_on.aktif}">

                <div class="col-md">                                                   
                          <div class="form-group">
                            <label class="mr-2">Perhalaman</label>
                            <select class="form-control"  v-model="perHalaman">   
                                 <option value="1">1</option>                              
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>               
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
         
                <div class="col-md">                    
                          <div class="form-group">
                            <label class="mr-2">Urutan</label>
                            <select class="form-control"  v-model="urutan" >
                                   <option value="asc">Terlama</option>
                                <option value="desc">Terbaru</option>                        
                            </select>                          
                        </div>
                </div>

            <div class="col-md">
                <label class="mr-2">Pencarian</label>
            <div class="input-group input-group">
                    <input type="text" class="form-control"  v-model="searching"  v-on:keyup.enter="cari">
                        <span class="input-group-append">
                    <button type="button" class="btn btn-primary btn-flat" @click="cari">PROSES</button>
                    </span>
            </div>
            </div>

            <div class="col-12 text-center mt-2">
                                                                
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
       

<span v-for="(rekap, index) in rekapitulasis.data" :key="index" class=" row d-flex justify-content-center mt-3 ">   
       <div :class="col" class="bg-white shadow">
        <div class="d-flex justify-content-between p-2 ">              
                <div class="btn-group float-left" v-if="isAuth">
                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">    
                    </button>
                        <div class="dropdown-menu" role="menu" style="position: absolute; transform: translate3d(67px, -165px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="top-start">
                        <button class="dropdown-item"  @click="show(rekap.id)">Lihat</button>
                        <button class="dropdown-item"  @click="copyTableToClipboard(index)">Salin</button>
                    </div>
                </div>
                <p class="text-right float-right">No Rekap {{rekap.id}}</p>
        </div>
    <!-- <div class="col-12 text-center">
    <img src="/img/kop-masjid.jpg" alt="" style="width : 100%">
    </div> -->

                  
        <div  :ref="'table' + index"  > <!-- salinojutsu -->
                        <span class="text-center">
                        <h4 class=" text-uppercase"><b>rekapitulasi data</b> <br>
                      {{aplikasi.nama}}
                        </h4>
                        <p>    Periode  {{periode(rekap.tgl_mulai, rekap.tgl_akhir)}}    </p>
           
                            </span>                                                                                                                      
                             <h4 class="text-uppercase text-center"><b>Data Pengeluaran</b></h4>         
                 <!-- pengeluaran -->
               <b-table  hover responsive  bordered :items="rekap.pengeluaran" small
                          :fields="visibleFields" show-empty  stacked="xs" foot-clone >                                   
                                <template #cell(no)="row">                                   
                                    <div @click="row.toggleDetails">
                                            {{ row.index +1}}  
                                    </div>
                                </template>  

                                <template #cell(uraian)="row">
                                    <div v-html="row.item.uraian">
                                      
                                    </div>
                                </template>
                                <template #cell(kategori)="row">
                                    <div @click="row.toggleDetails">
                                        {{row.item.kategori.nama}}
                                    </div>
                                </template>
                                 <template #cell(total)="row">
                                    <div @click="row.toggleDetails">
                                        {{row.item.total | currency}}
                                    </div>
                                </template>


                                  <template #cell(created_at)="row">
                                    <div>
                                        {{tgl_show(row.item.created_at)}}
                                    </div>
                                </template>

                                <template #cell(by)="row">
                                    <div>
                                        {{row.item.user.name}}
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
                            <img class="img-fluid" :src="'/storage/pengeluaran/'+row.item.foto" alt="Photo">
                                <hr>
                                <strong><i class="fa fa-briefcase mr-1"></i> Kategori</strong>
                                <p class="text-muted">{{row.item.kategori.nama}}</p>
                            
                                <hr>
                               <strong><i class="fas fa-book mr-1"></i> Uraian</strong>
                                <p class="text-muted" v-html="row.item.uraian">
                              
                                </p>
                                <hr>
                                <strong><i class="fa fa-fax mr-1"></i> Total</strong>
                                <p style="font-size:1.5rem" class="font-weight-bold">
                                Rp  {{row.item.total | currency}} 
                                </p> 
                                <p><i> {{row.item.total ? row.item.total : 0 | terbilang}} rupiah</i></p>
                                
                                <hr>
                                <strong><i class="fa fa-user mr-1"></i> Dibuat oleh</strong>
                                <p class="text-muted"> {{row.item.user.name}} | {{row.item.user.role.name}} </p>
                            <hr>
                                <strong><i class="fa fa-calendar mr-1"></i> Tanggal dibuat</strong>
                                <p class="text-muted">
                                {{tgl_detil(row.item.created_at)}}
                                </p>
                    </div>
                </template>
                                
                                     <!-- tabel footer -->
                            <template v-slot:foot(no)><div class="d-none"></div></template> 
                            <template v-slot:foot(created_at)><div class="d-none"></div></template> 
                            <template v-slot:foot(uraian)><div class="d-none"></div></template> 
                            <template v-slot:foot(kategori)>Total</template> 
                            <template v-slot:foot(total)>
                                Rp {{rekap.pengeluaran_rek | currency}}
                           </template> 
                            <template v-slot:foot(by)><div class="d-none"></div></template>                               
                                
                </b-table>
         <h4 class="text-uppercase text-center"><b>Data pemasukan</b></h4>     
            <!-- pemasukan -->
               <b-table  hover responsive  bordered :items="rekap.pemasukan" small
                          :fields="visibleFields" show-empty  stacked="xs" foot-clone>                                   
                                <template #cell(no)="row">                                   
                                    <div @click="row.toggleDetails">
                                            {{ row.index +1}}  
                                    </div>
                                </template>  

                                <template #cell(uraian)="row">
                                    <div v-html="row.item.uraian" v-if="row.item.uraian">                                      
                                    </div>
                                    <span v-else class="text-center">-</span>
                                </template>
                                <template #cell(kategori)="row">
                                    <div @click="row.toggleDetails">
                                        {{row.item.kategori.nama}}
                                    </div>
                                </template>
                                 <template #cell(total)="row">
                                    <div @click="row.toggleDetails">
                                        {{row.item.total | currency}}
                                    </div>
                                </template>


                                  <template #cell(created_at)="row">
                                    <div>
                                        {{tgl_show(row.item.created_at)}}
                                    </div>
                                </template>

                                <template #cell(by)="row">
                                    <div>
                                        {{row.item.user.name}}
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
                            <img class="img-fluid" :src="'/storage/pemasukan/'+row.item.foto" alt="Photo">
                                <hr>
                                <strong><i class="fa fa-briefcase mr-1"></i> Kategori</strong>
                                <p class="text-muted">{{row.item.kategori.nama}}</p>
                            
                                <hr>
                               <strong><i class="fas fa-book mr-1"></i> Uraian</strong>
                                <p class="text-muted" v-html="row.item.uraian" >
                                   
                                </p>
                                <hr>
                                <strong><i class="fa fa-fax mr-1"></i> Total</strong>
                                <p style="font-size:1.5rem" class="font-weight-bold">
                                Rp  {{row.item.total | currency}} 
                                </p> 
                                <p><i> {{row.item.total ? row.item.total : 0 | terbilang}} rupiah</i></p>
                                
                                <hr>
                                <strong><i class="fa fa-user mr-1"></i> Dibuat oleh</strong>
                                <p class="text-muted"> {{row.item.user.name}} | {{row.item.user.role.name}} </p>
                            <hr>
                                <strong><i class="fa fa-calendar mr-1"></i> Tanggal dibuat</strong>
                                <p class="text-muted">
                                {{tgl_detil(row.item.created_at)}}
                                </p>
                    </div>
                </template>
                                
                                     <!-- tabel footer -->
                            <template v-slot:foot(no)><div class="d-none"></div></template> 
                            <template v-slot:foot(created_at)><div class="d-none"></div></template> 
                            <template v-slot:foot(uraian)><div class="d-none"></div></template> 
                            <template v-slot:foot(kategori)>Total</template> 
                            <template v-slot:foot(total)>
                                Rp {{rekap.pemasukan_rek | currency}}
                           </template> 
                            <template v-slot:foot(by)><div class="d-none"></div></template> 
                            
                   
 
                            

                             
                </b-table>

         
   <div class="col-12">    <h4 class="text-center  text-uppercase "><b>total keseluruhan</b></h4>  </div>
                <table class="table">                                 
                    <tbody>
                        <tr>
                         <td scope="row" >  Kas Saldo Awal</td>
                            <td class="font-weight-bolder text-right"  v-b-tooltip.hover.left :title="terbilang_konver(rekap.saldo_awal)">  <b>Rp {{rekap.saldo_awal | currency}}</b></td>                           
                        </tr>
                        <tr>
                            <td scope="row">Total Pemasukan</td>
                            <td class="font-weight-bolder text-right"  v-b-tooltip.hover.left :title="terbilang_konver(rekap.pemasukan_rek)" ><b>Rp {{rekap.pemasukan_rek | currency}}</b></td>                           
                        </tr>
                        <tr>
                            <td scope="row">Total Pengeluaran</td>
                            <td class="font-weight-bolder text-right"  v-b-tooltip.hover.left :title="terbilang_konver(rekap.pengeluaran_rek)"><b>Rp {{rekap.pengeluaran_rek | currency}}</b></td>                           
                        </tr>
                        <tr>
                            <td scope="row">   Kas Saldo Akhir</td>
                            <td class="font-weight-bolder text-right"  v-b-tooltip.hover.left :title="terbilang_konver(rekap.saldo_akhir)"> <b>Rp {{rekap.saldo_akhir | currency}}</b></td>                           
                        </tr>
                    </tbody>
                </table>

 </div> <!-- salinojutsu -->  
                     
    </div> <!-- col -->

</span>


    

            <div class="card-footer row">
                     <div class="col-md-auto mr-auto">                    
                            <b-pagination
                            v-model="page"
                            :total-rows="rekapitulasis.meta.total"
                            :per-page="rekapitulasis.meta.per_page"
                            first-text="Awal"
                            prev-text="Prev"
                            next-text="Next"
                            last-text="Akhir"
                            aria-controls="rekapitulasis"
                            v-if="rekapitulasis.data && rekapitulasis.data.length > 0"
                            ></b-pagination>        
                    </div>   
                     <div class="col-md-auto ml-auto">                    
                          Menampikan   {{rekapitulasis.data.length}}  dari {{rekapitulasis.meta.total}} data
                    </div>                                                                            
            </div> <!-- foter -->                   
       
    </div>
</template>


<script>
import { terbilang } from '../../terbilang';

import moment from "moment"
moment.locale('id');  
  import { mapActions, mapState, mapGetters } from 'vuex'

    export default {
           name : 'rekapitulasiData',
        data (){
            return {     
                  col : 'col-12',         
                checkBox : [],
                searching: '',                                
                years: [],                  
                months: moment.months()
                
               
            }
        },
        components : {
            
        },
        created (){
            if(!this.rekapitulasis.data.length){                 
                    this.get_rekapitulasi();
            }       
              this.get_rekapitulasi(); 
        },              
         mounted() {
                this.generateYears();
                  this.updateFieldVisibility();
                window.addEventListener("resize", this.updateFieldVisibility); 
            },                    
            beforeDestroy() {
                window.removeEventListener("resize", this.updateFieldVisibility);
        },
        computed : {
            ...mapState(['errors']),
        ...mapGetters(['isAuth']),
            ...mapState('rekapitulasi_stores', {
                rekapitulasis: state => state.rekapitulasis,
                rekapitulasi: state => state.rekapitulasi,
                 fields: state => state.fields,
                       hidden_on : state => state.hidden_on,                 
            }),       
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),
            
       visibleFields() {
        return this.fields.filter(field => field.visible)
      },
            
            page : {
                get(){
                        return this.$store.state.rekapitulasi_stores.page
                },
                set(val){
                    this.$store.commit('rekapitulasi_stores/SET_PAGE', val)
                }
            },
            perHalaman :{
                    get(){
                        return this.$store.state.rekapitulasi_stores.perHalaman
                },
                set(val){
                    this.$store.state.rekapitulasi_stores.perHalaman = val
                }
            },
             urutan : {
                get(){
                        return this.$store.state.rekapitulasi_stores.urutan
                },
                set(val){
                    this.$store.commit('rekapitulasi_stores/SET_URUTAN', val)
                }
            },           
            tahun :{
                    get(){
                        return this.$store.state.rekapitulasi_stores.tahun
                },
                set(val){
                    this.$store.state.rekapitulasi_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.rekapitulasi_stores.bulan
                },
                set(val){
                    this.$store.state.rekapitulasi_stores.bulan = val
                }
            },
        },
                   
           watch: {    
               urutan(){
                this.get_rekapitulasi(this.searching)
               },         
                 page(){
                this.get_rekapitulasi(this.searching)
                },
             
                perHalaman() {            
                this.get_rekapitulasi(this.searching)
                },
                bulan(){
                     this.get_rekapitulasi(this.searching)
                },
                tahun (){
                     this.get_rekapitulasi(this.searching)
                }
           },
        methods : {
              ...mapActions('rekapitulasi_stores', ['get_rekapitulasi', 'show_rekapitulasi']),                         
            hidden () {
                this.hidden_on.aktif ? this.hidden_on.aktif = false : this.hidden_on.aktif = true
            },  
              terbilang_konver(data){
                    let angka = data ? data : 0;
                    return terbilang(angka) + ' rupiah';
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
                        this.remove_rekapitulasi(param);
                    }
                })
           
            }, 
            cari (){                         
            this.get_rekapitulasi(this.searching)
              
            },
            show(param){
               this.show_rekapitulasi(param)        
                this.$router.push({
                  name : 'show.rekapitulasi',
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
                        this.remove_rekapitulasi(this.checkBox);
                        this.checkBox = [];
                    }
                })
                  
            },
            periode(data1, data2){
                return moment(data1).format('DD MMMM') + "  -  " + moment(data2).format('DD MMMM YYYY')
            },
            aktif_aksi (rekap){
                let today =  moment();
                 const startDate = moment(rekap.tgl_mulai);
                  const endDate = moment(rekap.tgl_akhir);                  
                return today.isBetween(startDate, endDate, null, '[]');
            },
            tgl_show (data){
            return moment(data).format('dddd, DD MMMM')          
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
            },
            
                copyTableToClipboard(index) {
                // Mengambil HTML dari tabel menggunakan ref
                const tableHtml = this.$refs['table' + index][0].outerHTML;

                // Membuat elemen <textarea> sementara untuk menyalin HTML ke clipboard
                const textArea = document.createElement('textarea');
                textArea.value = tableHtml;

                // Menyembunyikan <textarea> dari tampilan
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);

                // Menyalin teks ke clipboard
                textArea.select();
                document.execCommand('copy');

                // Menghapus <textarea> sementara
                document.body.removeChild(textArea);

                alert('Table ' + (index + 1) + ' copied to clipboard!');
                },

                
                 updateFieldVisibility() {
                            if (window.innerWidth <= 768) {
                                // Mode Mobile
                                this.fields.forEach((field) => {
                                if (["no", "created_at", "uraian"].includes(field.key)) {
                                    field.visible = false;
                                }
                                });
                            } else {
                                // Mode Desktop
                                this.fields.forEach((field) => {
                                if (["no", "created_at", "uraian"].includes(field.key)) {
                                    field.visible = true;
                                }
                                });
                            }
                 } 

           
            
            
        }
    }
</script>

