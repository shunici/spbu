<template>  
 <div>


     <froala :tag="'text-area'" :config="config" v-model="realisasi.uraian"></froala>
      <p class="text-danger" v-if="errors.uraian"><i>{{ errors.uraian[0] }}</i></p> <br>         

        <div class="form-group">
            <label for="toko" class="text-uppercase">nama toko</label>
            <input  v-on:keyup.enter="proses" type="text" id="toko" class="form-control" placeholder="nama toko" v-model="realisasi.toko">
        <p class="text-danger" v-if="errors.toko"><i>{{ errors.toko[0] }}</i></p>
        </div>   
  
  <div class="row">
        <div class="col-6">
            <label for="qty" class="text-uppercase">Jumlah / Qty</label>            
                <uangInput v-model="qty" placeholder="Nominal"></uangInput>
        <p class="text-danger" v-if="errors.qty"><i>{{ errors.qty[0] }}</i></p>
        </div>   
    
        <div class="col-6">
            <label for="satuan" class="text-uppercase">{{kustom ? 'Satuan Kustom' : 'Satuan'}}</label>
              <select v-model="realisasi.satuan" class="form-control" id="satuan" v-if="!kustom">
                  <option disabled value="">-- Pilih Satuan --</option>
                  <option
                    v-for="satuan in satuanOptions"
                    :key="satuan.value"
                    :value="satuan.value"
                  >
                    {{ satuan.label }}
                  </option>               
                </select>
         <input
        v-if="kustom"
        type="text"
        class="form-control"
        id="customSatuan"
        v-model="realisasi.satuan"
        placeholder="Contoh: pack, liter, dus, dll"
      />
                <button class="btn  btn-xs" :class="kustom ? 'btn-outline-info' : 'btn-outline-success'" @click="kustom = !kustom">Kustom Satuan</button>

        </div>

</div> <!-- row -->

      <div class="form-group mt-3">
        <label for="nominal" class="text-uppercase">Harga Satuan</label>
           <uangInput v-model="hargasatuan" placeholder="Nominal"></uangInput>
                    {{realisasi.nominal ? realisasi.nominal : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.nominal"><i>{{ errors.nominal[0] }}</i></p>
      </div>                         

      <div class="form-group mt-3">
        <label for="total" class="text-uppercase">Total</label>           
                    <h3 class="font-weight-bold">{{realisasi.total | currency}}</h3>
                    {{realisasi.total ? realisasi.total : 0  | terbilang}}<br>
              
      </div>  


<div class="text-center">
      <!-- Menampilkan feed kamera -->
      <video v-if="!realisasi.foto"  ref="video" autoplay playsinline></video>
      <!-- Canvas untuk menangkap gambar -->
      <canvas ref="canvas" style="display: none;"></canvas>
      <!-- hasil tangkapan -->
      <div class="form-group">
          <img v-if="realisasi.foto" class="foto_upload mt-3" :src="realisasi.foto" alt="">
      </div>
</div>
<button @click="mulai_kamera ? startCamera() : captureImage()"  type="button" class="btn btn-primary btn-block text-uppercase"><i class="fa fa-camera"></i> {{mulai_kamera ? 'Mulai Kamera' : 'Tangkap Gambar'}} </button>
        <div class="form-group mt-4">
            <label for="foto_utama" class="text-uppercase">ATAU upload file</label>              
            <div class="input-group">
                  <div class="custom-file">
                      <input type="file" class="custom-file-input" id="exampleInputFile" @change="uploadImage($event)">
                      <label class="custom-file-label" for="exampleInputFile">Pilih Foto</label>
                  </div>
            </div>
        </div>
        

        
    <label for="tanggal" class="text-uppercase">tanggal transaksi</label>                   
<b-form-datepicker id="tanggal"   v-model="realisasi.tgl"  :locale="'id'"  class="mb-2"></b-form-datepicker>          
     
<textarea name="" id="" cols="30" rows="10" class="form-control" v-model="realisasi.keterangan"></textarea>
 <div v-if="Object.keys(errors).length">
      <ul>
        <li v-for="(messages, field) in errors" :key="field">
          <span v-for="(message, index) in messages" :key="index">
            {{ message }}
          </span>
        </li>
      </ul>
    </div>

       </div>


</template>

<script>
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formrealisasi',
          data () {
              return {
                mulai_kamera : true,   
                kustom : false,
                satuanOptions: [
                   { value: 'Buah', label: 'Buah' },
                  { value: 'pcs', label: 'Pieces' },
                  { value: 'kg', label: 'Kilogram' },
                  { value: 'psg', label: 'Pasang' },
                  { value: 'Batang', label: 'Batang' },
                  { value: 'roll', label: 'Roll' },
                  { value: 'Meter', label: 'Meter' },
                  { value: 'Kotak', label: 'Kotak' },
                { value: 'Lembar', label: 'Lembar' },
                { value: 'm³', label: 'Meter Kubik' },
                  { value: 'm²', label: 'Meter Persegi' },
                  { value: 'Liter', label: 'Liter' },
                    { value: 'pack', label: 'Pack' },
                    { value: 'Dus', label: 'Dus' },
                      { value: 'Ikatan', label: 'Ikatan' },
                      { value: 'Ekor', label: 'Ekor' },
                        { value: 'Keranjang', label: 'Keranjang' },
                ],                                                                                                             
              config: {
                  language: 'id',
                  imageUpload: false,
                  imageUploadRemoteUrls: true,  
                  requestWithCORS: false,
                   imageManagerLoadURL: '/api/load_images', // Endpoint untuk memuat daftar gambar
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
                        borderTable: 'Buat Garis',
                        class3: 'Hilangkan Garis',                 
                  },                  
                   fontSizeDefaultSelection: '16',
                    fontSizeUnit : 'px',
                   fontFamily: this.$store.state.laporan_stores.config.fontFamily, 
                   fontSize: ['8', '10', '12', '14', '16', '18', '24', '30', '36'],
                 events: {                                    
                      'froalaEditor.initialized': function () {
                        console.info('initialized')
                      }
                    }   
                }, 
                
              }
              

          },
     
          components : {
                uangInput
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('realisasi_stores', {                 
                    realisasi: state => state.realisasi,
                    pesan : state => state.pesan
                }),                
                hargasatuan :{
                        get(){
                            return this.$store.state.realisasi_stores.realisasi.nominal
                    },
                    set(val){
                        this.$store.state.realisasi_stores.realisasi.nominal = val;
                         this.$store.state.realisasi_stores.realisasi.total=  val*this.realisasi.qty
                          
                    }
                },                                
                qty :{
                        get(){
                            return this.$store.state.realisasi_stores.realisasi.qty
                    },
                    set(val){
                      this.$store.state.realisasi_stores.realisasi.qty = val;
                      this.$store.state.realisasi_stores.realisasi.total=  val*this.realisasi.nominal
                          
                    }
                },
                

          },
          methods : {
              ...mapMutations('realisasi_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),    
                ...mapActions('realisasi_stores', ['submit_realisasi', 'get_realisasi']),                                 
              uploadImage(event) {  
                  let poto = event.target.files[0];   //file yang dikirim ke db               
                  let tampil_foto =   URL.createObjectURL(poto)            
                this.SHOW_FOTO(tampil_foto) //ini kirim ke vuex utk citra
                 this.SIMPAN_FOTO(poto) //ini simpan db
              
              }, 
                captureImage() {
                  const video = this.$refs.video;
                  const canvas = this.$refs.canvas;
                  const context = canvas.getContext("2d");

                  // Set ukuran canvas sama dengan video
                  canvas.width = video.videoWidth;
                  canvas.height = video.videoHeight;

                  // Gambar frame video ke canvas
                  context.drawImage(video, 0, 0, canvas.width, canvas.height);

                  // Konversi canvas menjadi Blob
                  canvas.toBlob((blob) => {     
                    //ubah blob jadi sebuah file foto               
                            const file = new File([blob], "pengeluaran.jpeg", {
                                type: "image/jpeg",
                                lastModified: Date.now(),
                              });  
                              console.info('foto kamera', '')                 
                    this.SIMPAN_FOTO(file) //ini simpan db  
                    this.SHOW_FOTO(URL.createObjectURL(blob)) //ini kirim ke vuex utk citra
                                       
                  }, "image/jpeg");
                     this.mulai_kamera = true;                             
                },
               async startCamera() {
                  this.SHOW_FOTO('') //ini kirim ke vuex utk citra
                 this.SIMPAN_FOTO('') //ini simpan db
                    try {
                      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                      this.$refs.video.srcObject = stream;
                         this.mulai_kamera = false;
                    } catch (error) {
                      console.error("Gagal mengakses kamera:", error);
                      alert("Kamera tidak dapat diakses. Pastikan izin diberikan.");
                    }
                  },    
             
          }
      }
</script>

