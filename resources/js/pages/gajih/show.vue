<template>  
 <div class="bg-white row d-flex justify-content-center ml-0 mr-0" style="background-color : white">
  
    <div :class="colClass" class="bg-white shadow" ref="tableToCapture" >
                  <img :src="aplikasi.kop" alt="" class="bg-white" style="width : 100%">
                  <h2 class="text-center text-uppercase font-weight-bolder bg-white">Slip Gaji {{waktu()}}</h2>
                  <!-- //hanya tampil pas di destop -->
          <div class="d-none d-md-block">                         
              <table class="atasan table-borderless table-sm bg-white">
                <thead>
                
                  <tr>           
                
                    <td  class="font-weight-bolder align-top" style="width : 10%">Nama</td>  
                    <td style="width : 5%">:</td>                        
                    <td style="width : 35%"> {{user.name}}</td>   
                      <td style="width : 10%" class="font-weight-bolder align-top">Jabatan</td>    
                      <td style="width : 5%">:</td>                                              
                    <td style="width : 35%">   {{user.jabatan.nama_jabatan}}</td>                                            
                  </tr>
                  <tr>                 
                    <td  class="font-weight-bolder align-top">HP</td>      
                    <td class="align-top"> :</td>                                            
                    <td class="align-top"> {{user.hp}}</td> 
                      <td  class="font-weight-bolder align-top">Alamat</td>     
                      <td class="align-top">:</td>                                             
                    <td style="vertical-align: top;"> {{user.alamat}}</td>               
                  </tr>
              

                </thead>


              </table>
              <div class="table-container bg-white">
                    <table class="table table-bordered table-sm mt-3">          
                      <thead class="thead-dark">
                        <tr>           
                          <th class="text-uppercase">PENERIMAAN</th>                         
                              <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td scope="row">Gajih Pokok</td>         
                          <td>{{gajih.gajih_pokok | currency}}</td>          
                        </tr>  

                          <tr  v-for="(item, index) in padded_penerimaan" :key="index"> 
                                  <td>
                                      <input class="form-control" v-if="item.editing" v-model="item.uraian" />
                                      <span v-else>{{ item.uraian}}</span>
                                  </td>                
                                  <td>                   
                                      <uangInput v-if="item.editing" v-model="item.total" ></uangInput>
                                      <span v-else-if="item.total">{{ item.total | currency}}</span>
                                      <span v-else>-</span>
                                  </td>
                        </tr>    
                        
                        <tr class="bg-light">           
                          <th class="text-uppercase">total penerimaan</th>   
                          <th>{{gajih.penerimaan | currency}}</th>                
                        </tr>                
                      </tbody>                                    
                    </table>               
                    <table class="table table-bordered table-sm mt-3">          
                      <thead class="thead-dark">
                        <tr>           
                          <th class="text-uppercase">pengurangan</th>                         
                              <th>Total</th>
                        </tr>
                      </thead>
                      <tbody>        
                        <tr  v-for="(item, index) in padded_pengurangan" :key="index + 'peng'"> 
                                  <td>
                                      <input class="form-control" v-if="item.editing" v-model="item.uraian" />
                                      <span v-else>{{ item.uraian}}</span>
                                  </td>                
                                  <td>
                                    <uangInput v-if="item.editing" v-model="item.total" ></uangInput>
                                    <span v-else-if="item.total">{{ item.total | currency}}</span>
                                    <span v-else>-</span>
                                  </td>                  
                        </tr> 
                          <tr class="bg-light">
                            <th class="text-uppercase">Total pengurangan</th>          
                            <th>{{gajih.pengurangan | currency}}</th>        
                        </tr>               
                      </tbody>                                    
                    </table>         
              </div>
           </div>  <!-- d-none d-md-block hanya tampil pas destop -->

<!-- //hanya tampil pas mobile -->
          <div class="d-block d-md-none">                            
              <div class="row">
                <div class="col-6">
                  <strong>Nama :</strong><br>
                  {{user.name}} <br>
                  <strong>HP :</strong><br>
                  {{user.hp}} <br>             
                </div>      
                <div class="col-6">
                    <strong>Jabatan :</strong><br>
                  {{user.jabatan.nama_jabatan}} <br>         
                    <strong>Email :</strong><br>
                  {{user.email}} <br>         
                </div>
                <div class="col-12">
                  <strong>Alamat :</strong><br>
                  {{user.alamat}}
                </div>
              </div> <!-- row -->                            
                <table class="table table-bordered table-sm mt-3">          
                  <thead class="thead-dark">
                    <tr>           
                      <th class="text-uppercase">PENERIMAAN</th>                         
                          <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td scope="row">Gajih Pokok</td>         
                      <td>{{gajih.gajih_pokok | currency}}</td>          
                    </tr>  

                      <tr  v-for="(item, index) in  gajih.ket_penerimaan" :key="index + 'penersimaan'"> 
                              <td>
                              {{ item.uraian}}
                              </td>                
                              <td>                   
                            
                                {{ item.total | currency}}
                              
                              </td>
                    </tr>    
                    
                    <tr class="bg-light">           
                      <th class="text-uppercase">total penerimaan</th>   
                      <th>{{gajih.penerimaan | currency}}</th>                
                    </tr>    
                    <tr>
                      <th></th>
                    </tr>   
                    <tr>           
                      <th class="text-uppercase bg-dark">PENGURANGAN</th>                         
                          <th class="text-uppercase bg-dark"> Total</th>
                    </tr>
                      <tr  v-for="(item, index) in  gajih.ket_pengurangan" :key="index+'pengurangan'"> 
                              <td>
                              {{ item.uraian}}
                              </td>                
                              <td>                   
                            
                                {{ item.total | currency}}
                              
                              </td>
                    </tr>
                        
                    <tr class="bg-light">           
                      <th class="text-uppercase">total pengurangan</th>   
                      <th>{{gajih.pengurangan | currency}}</th>                
                    </tr>                                       
                  </tbody>                                    
                </table>  
          </div> <!-- d-none d-md-block hanya tampil pas mobile -->



            <div class="font-weight-bolder bg-light p-1 text-uppercase">Total yang diterima petugas : {{gajih.penerimaan - gajih.pengurangan | currency}} </div>
            <p class="bg-light"><i>{{gajih.penerimaan - gajih.pengurangan | terbilang}} rupiah</i></p>
            <div class="text-right mb-4 bg-light ">{{tgl_show(gajih.created_at)}} </div>
            <div class="bg-light " style="width : 100%; background-color : white" v-html="gajih.keterangan"></div>
    </div> <!-- col -->

    <div class="col-12 mt-3 no-print">
         <button class="btn-block btn-success" @click="captureTable"> <i class="fa fa-whatsapp"></i> Kirim Ke WhatsApp {{user.name}}</button>
    </div>
  </div>
</template>

<script>
import  {getMasehiHijriah}  from "../../hijriahmasehi";
import html2canvas from "html2canvas";
import vSelect from 'vue-select';
import moment from "moment"
moment.locale('id');  
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formedit',
          data () {
              return {
                  
                    windowWidth: window.innerWidth, // Lebar layar saat ini
              }
          },
          components : {
            uangInput, vSelect
          },              
         
          computed : {
                ...mapState(['errors']),
                ...mapState('gajih_stores', {    
                      user : state => state.user,             
                    gajih: state => state.gajih, 
                     ket_penerimaan: state => state.gajih.ket_penerimaan,                   
                      ket_pengurangan: state => state.gajih.ket_pengurangan,                   
                    pesan : state => state.pesan,
                    user_list : state => state.user_list,
                }),    
                ...mapState('aplikasi_stores', {   
                  aplikasi : state => state.aplikasi,
                }),
                padded_penerimaan () {
                   const diff = this.ket_pengurangan.length - (this.ket_penerimaan.length+1);
                    const emptyRows = Array.from({ length: diff > 0 ? diff : 0 }, () => ({ uraian: "-", total: "" }));
                    return [...this.ket_penerimaan, ...emptyRows];
                },                
                padded_pengurangan () {
                   const diff = (this.ket_penerimaan.length+1) - this.ket_pengurangan.length;
                    const emptyRows = Array.from({ length: diff > 0 ? diff : 0 }, () => ({ uraian: "-", total: "" }));
                    return [...this.ket_pengurangan, ...emptyRows];
                },                 
                 colClass() {
                return this.windowWidth < 768 ? "col-md-12" : "col-md-7";
              },

          },
          methods : {
              ...mapMutations('gajih_stores', ['CLEAR_FORM']),    
                ...mapActions('gajih_stores', ['update_gajih', 'get_gajih', 'kirim_slip']),            
              proses (ev) {
                
                  ev.preventDefault()
                  this.update_gajih().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_gajih();
                        //close modal
                        this.$bvModal.hide('edit-gajih')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit                                                   
              },
              tgl_show (data){  
                var waktu = getMasehiHijriah(data);                
                 return `${waktu.mHari}, ${waktu.mTanggal} ${waktu.mBulan} ${waktu.mTahun} M / ${waktu.hTanggal} ${waktu.hBulan} ${waktu.hTahun} H`;
              }, 
              waktu (){
                var tgl =  moment();
                 return moment(tgl).format('MMMM YYYY')          
              }, 
               updateWindowWidth() {
                this.windowWidth = window.innerWidth;
              },
              //untuk mengcapture //tabel agar terkirim ke wa
               async captureTable() {
                  const table = this.$refs.tableToCapture;
                    // Tangkap tabel sebagai canvas
                    const canvas = await html2canvas(table);

                    // Konversi canvas menjadi Blob
                    const blob = await new Promise((resolve) => canvas.toBlob(resolve));

                    // Gunakan Clipboard API untuk menyalin ke clipboard
                    if (navigator.clipboard && navigator.clipboard.write) {
                      const data = [new ClipboardItem({ "image/png": blob })];
                      await navigator.clipboard.write(data);
                    
                    } else {
                      alert("Browser Anda tidak mendukung fitur clipboard untuk gambar.");
                    }

                      var text = " *Intensif Takmir " + this.aplikasi.nama + "* \n Periode " + this.waktu()  ;
                      var nomor  = this.user.hp;
                      const encodedMessage = encodeURIComponent(text);
                      // URL untuk membuka WhatsApp
                      const whatsappUrl = `https://wa.me/${nomor}?text=${encodedMessage}`;
                      // Arahkan ke URL WhatsApp
                      window.open(whatsappUrl, "_blank");
              },
                        
                      

          },          
       beforeDestroy() {
            // Hapus event listener saat komponen dihancurkan
            window.removeEventListener("resize", this.updateWindowWidth);
          },
      }
</script>

<style scoped>
.table-container {
  display: flex;
  gap: 20px; /* Jarak antar tabel */
  justify-content: center; /* Memposisikan tabel di tengah */
}

.atasan {
  width: 100%;
  border-collapse: collapse;
}

.atasan th, .atasan td { 
  padding: 0px;
}
    @media print {
        /* .print_lebar_rekapitulasi {           
        } */
        .thead-dark tr th  {
            border : 3px solid black !important;  
             background-color : black !important;
             color : white  !important;
            -webkit-print-color-adjust:exact ;
        }
        
        .tbody tr td {
              border : 3px solid black !important;  
          
        }
        
        .tbody tr th {
              border : 3px solid black !important;  
            background-color : rgb(255, 255, 255) !important;
            -webkit-print-color-adjust:exact ;
        }  
         .table thead tr td,.table tbody tr td{
            border-width: 2px !important;
            border-style: solid !important;
            border-color: black !important;               
            -webkit-print-color-adjust:exact ;
        } 

        .atasan {
          width: 100%  !important;
          border-collapse: collapse;
        }

        .atasan th, .atasan td { 
          padding: 0px  !important;
        }
      
        
  
        
}
</style>

