<template>
    <div class="">
        
            <div class="d-flex justify-content-between m-3 no-print">
                                 
                 <button  type="button" class="btn btn-outline-primary " @click="$router.go(-1)">
                     <i class="fa fa-calendar mr-1"></i>    kembali
                </button>            
            </div> <!-- dflex -->
            <hr>
            <div class="card-header row no-print">
   
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
                   
            </div> <!-- card-header -->      
       

                <h4 class="mt-2 mb-0 text-center text-uppercase font-weight-bolder">Data pengeluaran <br> {{aplikasi.nama}}</h4>                                                                                                 
<p class="text-center">  Periode  {{bulan_saja}} Tahun {{tahun}}</p>
                      <hr>
    <div class="table-responsive" style="max-height: 700px; overflow-y: auto;">      
  <table class="table table-bordered table-striped mb-0 table-sm">
          <thead class="bg-dark sticky-header">
            <tr>
                <th></th>
                <th></th>
              <th  :colspan="kategori_header.length" class="text-center">
                Kategori
              </th>
            </tr>
            <tr>                
              <th >No</th>
              <th>Keterangan</th>
              <th v-for="(kategori, index) in kategori_header" :key="index + 'a'">
                {{ kategori }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in data_pengeluaran" :key="index+'n'">
              <td>{{ row.no }}</td>
              <td class="uraian-cell" :id="'popover-target-' + index" >
                     <div v-html="truncateHtml(row.uraian, 40)"></div>     
                         
                        <b-popover :target="'popover-target-'  + index" triggers="hover" placement="right">   
                          <div v-html="row.uraian"></div>
                            <img class="img-fluid" v-b-modal.pengeluaran-mdl :src="'/storage/pengeluaran/'+row.foto" alt="Photo" width="100" @click="modal_gambar(row.foto)">
                            <p>Transaksi dibuat {{tgl_detil(row.tgl)}}</p>
                                 
                        </b-popover>                   
              </td>
              <td v-for="(kategori, idx) in kategori_header" :key="idx">
                {{row[kategori] ? formatCurrency(row[kategori])  : '-' }}
              </td>
            </tr>
          </tbody>
            <tfoot class="table-light">         
            <tr>
              <th colspan="2" class="border">Total per Kategori</th>
              <th v-for="(kategori, index) in kategori_header" :key="index + 'dd'" class="center border">
                {{ kategori_total[kategori] | currency}}
              </th>
            </tr>      
            <tr class="border">
              <th colspan="2" class="border bg-info">Total Seluruh</th>            
              <td  :colspan="kategori_header.length" class="text-center border bg-info" v-b-popover.hover.top="total_terbilang(total)" >
                  <h4 class="mb-0 mt-0"> <b>{{total | currency}}</b></h4>
              </td>
            </tr>  
          </tfoot>
     </table>       
     
                                      <b-modal id="pengeluaran-mdl" size="xl"   hide-footer>                                        
                                     <img class="img-fluid" v-b-modal.pengeluaran-mdl :src="'/storage/pengeluaran/'+foto" alt="Photo">
                                      </b-modal>                                        
         </div>
    </div>
</template>


<script>
import { terbilang } from '../../terbilang';
import moment from "moment"
moment.locale('id');  
  import { mapActions, mapState, mapMutations } from 'vuex'


    export default {
           name : 'pengeluaranData',
        data (){
            return {     
              maximize : false,          
                years: [],                  
                months: moment.months(), // Get month names from moment.js
                foto : ''
            
               
            }
        },
        components : {
           
        },
        mounted (){
              this.generateYears();                                                                 
        },               
      
        computed : {
            ...mapState(['errors']),
            ...mapState('pengeluaran_stores', {
                data_pengeluaran: state => state.data_pengeluaran,   
                kategori_header: state => state.kategori_header,   
                kategori_total: state => state.kategori_total,    
                total: state => state.total,                                            
            }), 
        ...mapState('user', {           
             auth : state => state.authenticated,               
        }),                       
        ...mapState('kategori_stores', {                 
            kategori : state => state.kategori_pengeluaran,                  
        }),         
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),           
                                                     
            
            
            tahun :{
                    get(){
                        return this.$store.state.pengeluaran_stores.tahun
                },
                set(val){
                    this.$store.state.pengeluaran_stores.tahun = val
                }
            },
            bulan :{
                    get(){
                        return this.$store.state.pengeluaran_stores.bulan
                },
                set(val){
                    this.$store.state.pengeluaran_stores.bulan = val
                }
            },
             bulan_saja () {              
                 var bln = this.bulan;
                 if (bln == 0) {
                     return 'semua bulan'
                 } else {
                      return moment(bln).format('MMMM') 
                 }                
            }
        },
                   
           watch: {   
                       
                bulan(){
                     this.get_pengeluaran_tabel()
                     this.hitung_kategori_total()
                },
                tahun (){
                     this.get_pengeluaran_tabel()
                     this.hitung_kategori_total()
                }
           },
        methods : {
              ...mapActions('pengeluaran_stores', ['get_pengeluaran_tabel']),  
              modal_gambar(sumber){
                  return this.foto = sumber;
              },
              stripHtml(html) {
                const div = document.createElement("div");
                div.innerHTML = html;
                return div.textContent || div.innerText || "";
              },      
               // Memotong teks (bukan HTML tag-nya)
                  truncateHtml(html, length) {
                  // Buat elemen untuk parsing HTML
                        const div = document.createElement("div");
                        div.innerHTML = html;

                        // Ambil isi teks saja untuk dipotong
                        const text = div.textContent || div.innerText || "";

                        // Potong teks (jaga agar HTML tidak rusak)
                        // const truncatedText = text.length > length ? text.substring(0, length) + "..." : text;

                        return text;
                  }, 
            formatCurrency(value) {
                var angka = value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");
                return angka;
              },
              total_terbilang(data){
                return terbilang(data);
              },              
            tgl_detil (data){
                return moment(data).format('dddd, DD MMMM ')           
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
            
            
        }
    }
</script>

<style scoped>
/* Sticky header support */
.sticky-header th {
  position: sticky;
  top: 0;  
  z-index: 2;
  background-color: black;
}


.uraian-cell {
 max-width: 150px;      /* Batasi lebar kolom */
  white-space: nowrap;   /* Jangan pindah ke baris baru */
  overflow: hidden;      /* Sembunyikan kelebihan teks */
  text-overflow: ellipsis; /* Tambahkan "..." */
}
</style>