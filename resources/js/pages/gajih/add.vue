<template>  
 <b-modal size="xl" id="add-gajih" title="Buat Data Baru"  :header-bg-variant="'info'">
  

    
        <v-select :options="user_list" v-model="user_selected">
        <template slot="option" slot-scope="option">      
          <div class="user-panel d-flex">
              <div class="image">
                <img :src="'/storage/user/'+option.foto" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                <div class="d-block">{{option.label}}</div>
                  <span class="description">{{option.jabatan}}</span>
              </div>
            </div>
        </template>
      </v-select>
  <p class="text-danger" v-if="errors.user_id"><i>pemateri harus diisi</i></p>
       
      <div class="form-group">
        <label for="penerimaan" class="text-uppercase">penerimaan</label>
           <uangInput v-model="gajih.penerimaan" placeholder="input gaji" style="font-size:1.5rem" class="font-weight-bold"></uangInput>
                    {{gajih.penerimaan ? gajih.penerimaan : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.penerimaan"><i>{{ errors.penerimaan[0] }}</i></p>
      </div>
            
      <!-- <div class="form-group">
        <label for="pengurangan" class="text-uppercase">pengurangan</label>
           <uangInput v-model="gajih.pengurangan" placeholder="input gaji" style="font-size:1.5rem" class="font-weight-bold"></uangInput>
                    {{gajih.pengurangan ? gajih.pengurangan : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.pengurangan"><i>{{ errors.pengurangan[0] }}</i></p>
      </div> -->
 
 <p>Diterima</p>
 <div class="form-check form-check-inline">
   <label class="form-check-label">
     <input class="form-check-input" type="radio" name="sdh_terima" id="" value="1" v-model="gajih.sdh_terima"> Sudah
   </label>
 </div>
 <div class="form-check form-check-inline">
   <label class="form-check-label">
     <input class="form-check-input" type="radio" name="sdh_terima" id="" s value="0" v-model="gajih.sdh_terima"> Belum
   </label>
 </div>
        


      <table class="table table-bordered table-sm">
        <thead>
          <tr class="bg-light">           
            <th colspan="2" class="text-uppercase">PENERIMAAN</th>     
            <th>Aksi</th>             
          </tr>
        </thead>
        <tbody>
          <tr>
            <td scope="row">Gajih Pokok</td>         
            <td>{{gajih.gajih_pokok | currency}}</td>
            <td>
                     <button class="btn btn-outline-primary btn-xs" v-if="ket_penerimaan.length === 0" @click="tambah_penerimaan">Buat Data</button>
            </td>
          </tr>  

            <tr  v-for="(item, index) in ket_penerimaan" :key="index"> 
                    <td>
                        <input class="form-control" v-if="item.editing" v-model="item.uraian" />
                        <span v-else>{{ item.uraian}}</span>
                    </td>                
                    <td>                   
                         <uangInput v-if="item.editing" v-model="item.total" ></uangInput>
                        <span v-else>{{ item.total | currency }}</span>
                    </td>
                    <td>
                        <button class="btn btn-outline-success btn-xs" @click="jumlahkan_penerimaan(item.editing = !item.editing)">
                            {{ item.editing ? 'Simpan' : '' }}  <i v-if="!item.editing" class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-xs" @click="hapus_penerimaan(index)"><i class="fas fa-trash"></i> </button>                         
                          <button class="btn btn-outline-primary btn-xs" v-if="index === ket_penerimaan.length - 1" @click="tambah_penerimaan">+</button>
                    </td>
           </tr>                    
        </tbody>
         <thead>
          <tr class="bg-light">           
            <th class="text-uppercase">total penerimaan</th>   
            <th>{{gajih.penerimaan | currency}}</th>  
            <th></th>             
          </tr>
          <tr>
            <th colspan="3"></th>
          </tr>
        </thead>
        <tfoot>
          <tr class="bg-light">
              <th class="text-uppercase" colspan="2">pengurangan</th>            
              <th>     <button class="btn btn-outline-primary btn-xs" v-if="ket_pengurangan.length === 0" @click="tambah_pengurangan">Buat Data</button></th>
          </tr>
          <tr  v-for="(item, index) in ket_pengurangan" :key="index"> 
                    <td>
                        <input class="form-control" v-if="item.editing" v-model="item.uraian" />
                        <span v-else>{{ item.uraian}}</span>
                    </td>                
                    <td>
                       <uangInput v-if="item.editing" v-model="item.total" ></uangInput>
                        <span v-else>{{ item.total | currency}}</span>
                    </td>
                    <td>
                        <button class="btn btn-outline-success btn-xs" @click="jumlahkan_pengurangan(item.editing = !item.editing)">
                            {{ item.editing ? 'Simpan' : '' }}  <i v-if="!item.editing" class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-xs" @click="hapus_pengurangan(index)"><i class="fas fa-trash"></i> </button>                         
                          <button class="btn btn-outline-primary btn-xs" v-if="index === ket_pengurangan.length - 1" @click="tambah_pengurangan">+</button>
                    </td>
           </tr> 
            <tr class="bg-light">
              <th class="text-uppercase">Total pengurangan</th>          
              <th>{{gajih.pengurangan | currency}}</th>
              <th></th>
           </tr>
           
            <tr>
              <th class="text-uppercase"></th>          
              <th></th>
              <th></th>
           </tr>
            <tr class="bg-light">
              <th class="text-uppercase">Total yang diterima petugas</th>          
              <th>{{gajih.penerimaan - gajih.pengurangan | currency}}</th>
              <th></th>
           </tr>
          
        </tfoot>
        
      </table>         
 
        <froala :tag="'textarea'" :config="config" v-model="gajih.keterangan"></froala>
      
      <div v-if="errors.koneksi" class="alert alert-danger" role="alert">
           <strong>{{errors.koneksi}}</strong> 
        </div>
  

  <template #modal-footer>
        <div class="w-100">         
          <b-button
            variant="primary"
            size="sm"
            class="float-right"
            @click="proses"   
            :disabled="pesan.sukses"       
          >
           <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
             {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}}           
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('add-gajih')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
import vSelect from 'vue-select';
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {
                  user_selected: this.$store.state.gajih_stores.user_selected,
                   ket_penerimaan: [
                    { uraian: 'transportasi', total: 5000, editing: false },
                    { uraian: 'lembur', total: 2000, editing: false },
                ],
                ket_pengurangan: [
                    { uraian: 'libur', total: 5000, editing: false },
                    { uraian: 'kasbon', total: 2000, editing: false },
                ],                                                                  
              config: {
                  language: 'id',
                  imageUpload: false,
                  imageUploadRemoteUrls: true,  
                  requestWithCORS: false,
                  imageManagerLoadURL: this.$store.state.laporan_stores.config.imageManagerLoadURL,  
                    imageManagerLoadMethod: "GET",
                    imageManagerDeleteURL : false,
                  imageManagerToggleTags: true,    
                    imageManagerScrollOffset: 10,
                    imageManagerPageSize: 10,   
                      imageManagerPreloader: '/images/loader.gif',
                      imageMove: true,     
                      toolbarButtons: [
                        'fontFamily', '|',  
                        'paragraphFormat', '|', 
                        'outdent', 'indent', '|',
                        'textColor', 'backgroundColor', '|',   
                         'fontSize', '|',
                        'align', '|',  
                        'lineHeight', '|',  
                        'formatOL', 'formatUL', '|',  // ➕ Tambahkan Ordered & Unordered List
                        'bold', 'italic', 'underline',  
                        'undo', 'redo', '|',  
                        'insertTable', '|',
                        'insertImage', '|',
                        'insertLink', '|',                     
                        'html'                  
                    ],    
                    imageInsertButtons: ['imageBack', '|', 'imageManager'],                       
                      toolbarButtonsXS: [
                      'bold', '|',        
                      'textColor', 'backgroundColor', '|',   
                      'formatOL', 'formatUL', '|',
                      'insertTable' // Hanya tombol penting di mobile
                    ],
                    tableStyles: {
                    borderTable: 'border tabel',
                    class2: 'Class 2',
                    class3: 'Class 3'
                  },
                   fontFamily: this.$store.state.laporan_stores.config.fontFamily, 
                   fontSize: ['8', '10', '12', '14', '18', '24', '30', '36'],
                 events: {                                    
                      'froalaEditor.initialized': function () {
                        console.info('initialized')
                      }
                    }   
                },   
                                 
              }
          },
          components : {
            uangInput, vSelect
          },
          watch :{
            user_selected(){
                  this.gajih.user_id = this.user_selected.value; 
                  this.gajih.gajih_pokok = this.user_selected.gajih_pokok; 
                  this.jumlahkan_penerimaan();
                  this.jumlahkan_pengurangan();
                  
            },

          },
         
          computed : {
                ...mapState(['errors']),
                ...mapState('gajih_stores', {                 
                    gajih: state => state.gajih,                  
                    pesan : state => state.pesan,
                    user_list : state => state.user_list,
                }), 
          },
          methods : {
              ...mapMutations('gajih_stores', ['CLEAR_FORM']),    
                ...mapActions('gajih_stores', ['submit_gajih', 'get_gajih']),            
              proses (ev) {

                  this.gajih.ket_penerimaan = JSON.stringify(this.ket_penerimaan);
                  this.gajih.ket_pengurangan = JSON.stringify(this.ket_pengurangan);
                  ev.preventDefault()
                  this.submit_gajih().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_gajih();
                        //close modal
                        this.$bvModal.hide('add-gajih')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit                                                   
              },
              
            hapus_penerimaan(index) {
                          this.ket_penerimaan.splice(index, 1);
                            this.jumlahkan_penerimaan()
              },
            tambah_penerimaan() {
                  this.ket_penerimaan.push({
                          uraian: '',
                          total: parseFloat(0),
                          editing: false,
                      });                    
              },
                 
            hapus_pengurangan(index) {
                          this.ket_pengurangan.splice(index, 1);
                            this.jumlahkan_pengurangan()
              },
            tambah_pengurangan() {
                  this.ket_pengurangan.push({
                          uraian: '',
                          total: parseFloat(0),
                          editing: false,
                      });
              },
              jumlahkan_penerimaan (hanya_kosong = '') {                
                  var tes = hanya_kosong;
                //menjumlahkan bagian total pada ket penerimaan
                  const totalSum = this.ket_penerimaan.reduce((sum, item) => parseFloat(sum) + parseFloat(item.total), 0);
                  this.gajih.penerimaan = parseFloat(totalSum) + parseFloat(this.gajih.gajih_pokok)
              },
              jumlahkan_pengurangan (hanya_kosong = '') {                
                  var tes = hanya_kosong;
                //menjumlahkan bagian total pada ket pengurangan
                  const totalSum = this.ket_pengurangan.reduce((sum, item) => parseFloat(sum) + parseFloat(item.total), 0);
                  this.gajih.pengurangan = parseFloat(totalSum) 
              },

          }
      }
</script>

