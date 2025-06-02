<template>
    
    <div class="card bg-white">
       
            <div class="d-flex justify-content-between m-3 no-print">
        <button class="btn btn-outline-primary  no-print mr-2" @click="$router.go(-1)"> <i class="fa fa-code"></i> Kembali  </button>
                  
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
                    <button  type="button" class="btn btn-outline-primary  no-print" @click="print">
                        <i class="fa fa-print"></i>   PRINT
                </button>  
          

            </div> <!-- card-header -->
          <hr>
            <div class="card-body">      
    <b-form-group class="no-print">
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
<span  class="row d-flex justify-content-center ">    
  
    <div :class="col" class="text-center kop" style="display : none">
    <img :src="aplikasi.kop" alt="" style="width : 100%">
    </div>

<div :class="col">                  
                      
                        <h4 class=" text-uppercase text-center" ><b>rekapitulasi data</b></h4>
                        <p class="text-center">    Periode  {{periode(rekapitulasis.tgl_mulai, rekapitulasis.tgl_akhir)}}    </p>
           
                                                                                                                                      
                             <h4 class="text-uppercase text-center"><b>Data Pengeluaran</b></h4>         

                 <!-- pengeluaran -->
               <b-table  hover responsive  bordered :items="rekapitulasis.pengeluaran"  small
                          :fields="visibleFields" show-empty  stacked="xs" foot-clone>                                   
                                <template #cell(no)="row">                                   
                                    <div @click="row.toggleDetails" class="text-center">
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
                            <img class="img-fluid" :src="'/storage/rekapitulasi/'+row.item.foto" alt="Photo">
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
                            <template v-slot:foot(kategori)>Total Pengeluaran</template> 
                            <template v-slot:foot(total)>
                                Rp {{rekapitulasis.pengeluaran_rek | currency}}
                           </template> 
                            <template v-slot:foot(by)><div class="d-none"></div></template> 


                         
                            

                             
                </b-table>

        

                 
                            <h4 class="text-uppercase text-center"><b>Data Pemasukan</b></h4>                                                     
            
            
                 <!-- pemasukan -->
               <b-table  hover responsive  bordered :items="rekapitulasis.pemasukan" small
                          :fields="visibleFields" show-empty  stacked="xs" head-variant="dark" foot-clone>                                   
                                <template #cell(no)="row">                                   
                                    <div @click="row.toggleDetails" class="text-center">
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
                            <img class="img-fluid" :src="'/storage/rekapitulasi/'+row.item.foto" alt="Photo">
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
                            <template v-slot:foot(kategori)>Total Pemasukan</template> 
                            <template v-slot:foot(total)>
                                Rp {{rekapitulasis.pemasukan_rek | currency}}
                           </template> 
                            <template v-slot:foot(by)><div class="d-none"></div></template> 
  
                                              
                </b-table>
   <h4 class="text-center  text-uppercase "><b>total keseluruhan</b></h4> 
                <table class="table table-sm" style="width : 100%; ">                                 
                    <tbody>
                        <tr>
                         <td scope="row" >  Kas Saldo Awal  <br>
                           <b><i> {{rekapitulasis.saldo_awal | terbilang}} rupiah</i></b>
                         </td>
                            <td class="font-weight-bolder text-right">  <b>Rp {{rekapitulasis.saldo_awal | currency}}</b></td>                           
                        </tr>
                        <tr>
                            <td scope="row">Total Pemasukan <br>
                               <b><i> {{rekapitulasis.pemasukan_rek | terbilang}} rupiah</i></b>
                            </td>
                            <td class="font-weight-bolder text-right"><b>Rp {{rekapitulasis.pemasukan_rek | currency}}</b></td>                           
                        </tr>
                        <tr>
                            <td scope="row">Total Pengeluaran <br>
                               <b><i> {{rekapitulasis.pengeluaran_rek | terbilang}} rupiah</i></b>
                            </td>
                            <td class="font-weight-bolder text-right"><b>Rp {{rekapitulasis.pengeluaran_rek | currency}}</b></td>                           
                        </tr>
                        <tr>
                            <td scope="row">   Kas Saldo Akhir <br>
                               <b><i> {{rekapitulasis.saldo_akhir | terbilang}} rupiah</i></b>
                            </td>
                            <td class="font-weight-bolder text-right"> <b>Rp {{rekapitulasis.saldo_akhir | currency}}</b></td>                           
                        </tr>
                    </tbody>
                </table>

    </div> <!-- col -->
                     
     

</span>


            </div> <!-- card body-->
                
       
    </div>
</template>



<script>
import { terbilang } from '../../terbilang';

import moment from "moment"
moment.locale('id');  
  import { mapActions, mapState, mapMutations } from 'vuex'

    export default {
           name : 'rekapitulasishow',
        data (){
            return {              
                checkBox : [],
                searching: '',
                col : 'col-12'
                
               
            }
        },
        components : {
            
        },
        computed : {
            ...mapState(['errors']),
            ...mapState('rekapitulasi_stores', {
                rekapitulasis: state => state.rekapitulasi_show,              
                 fields: state => state.fields,                                        
            }),       
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),            
       visibleFields() {
        return this.fields.filter(field => field.visible)
      },
         
     
        },
                   
          
        methods : {
              ...mapActions('rekapitulasi_stores', ['get_rekapitulasi', 'show_rekapitulasi']),                         
             
              terbilang_konver(data){
                    let angka = data ? data : 0;
                    return terbilang(angka) + ' rupiah';
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
            return moment(data).format('dddd, DD-MMMM')          
            },               
            tgl_detil (data){
                  return moment(data).format('LLLL')           
            }, 
            print(){                
            document.querySelector('.kop').style.display = '';
                setTimeout(function () {
                     window.print(); 
                document.querySelector('.kop').style.display = 'none';
                  
                 }.bind(this), 1000);
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
    }

           
            
            
        }
    }
</script>

<style>
   
</style>