<template>  
 <div>
   
      <VoiceProcessor @processed="handleProcessed" class="float-right" />
<label for="uraian" class="text-uppercase">uraian</label>            

    <froala :tag="'text-area'" :config="config" v-model="pengeluaran.uraian"></froala>

   <p class="text-danger" v-if="errors.uraian"><i>{{ errors.uraian[0] }}</i></p> <br>


        <div class="form-group">
          <label for="kategori" class="text-uppercase">kategori</label>
          <v-select v-model="selected_kategori" :options="kategori"></v-select>
        <p class="text-danger" v-if="errors.kategori_id"><i>Kategori harus diisi</i></p>
        </div>
                   
      <div class="form-group">
   
        <label for="total" class="text-uppercase">total</label>
           <uangInput v-model="pengeluaran.total" placeholder="masukkan total" style="font-size:1.5rem" class="font-weight-bold"></uangInput>
            {{pengeluaran.total ? pengeluaran.total : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.total"><i>{{ errors.total[0] }}</i></p>
      </div>
      
                
      <label for="#" class="text-uppercase">Dana keluar menggunakan kas</label> <br> 
        <div class="form-check form-check-inline">
          <label class="form-check-label text-uppercase">
            <input class="form-check-input " type="radio" name="kas" id="" value="kantor" v-model="pengeluaran.kas"> Kas Kantor
          </label>
        </div>
        
        <div class="form-check form-check-inline">
          <label class="form-check-label text-uppercase">
            <input class="form-check-input " type="radio" name="kas" id="" value="bank" v-model="pengeluaran.kas">  Kas Bank
          </label>
        </div> 

        

<table class="table table-bordered table-sm mt-3">
  <thead class="thead-dark">   
    <tr>
      <th class="text-uppercase">kas Kantor</th>
      <th class="text-uppercase">kas Bank</th>    
    </tr>
  </thead>
  <tbody>
    <tr>   
      <td>{{kas_sekarang.kantor | currency}}</td>
      <td>{{kas_sekarang.bank | currency}}</td>
    </tr>
  </tbody>
</table>

<div class="row">


<div class="col-md-6">
<div class="text-center">
      <!-- Menampilkan feed kamera -->
      <video v-if="!pengeluaran.foto"  ref="video" autoplay playsinline></video>
      <!-- Canvas untuk menangkap gambar -->
      <canvas ref="canvas" style="display: none;"></canvas>
      <!-- hasil tangkapan -->
      <div class="form-group">
          <img v-if="pengeluaran.foto" class="foto_upload mt-3" :src="pengeluaran.foto" alt="">
      </div>
</div>        
<div class="form-group">                  
                    <div class="input-group">
                      <div class="custom-file" @change="uploadImage($event, 'foto')">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Upload File</label>
                      </div>
                      <div class="input-group-append">
                        <span class="btn btn-default" @click="mulai_kamera.foto ? startCamera('foto', 'video') : captureImage('foto', 'video', 'canvas')" > <i class="fa fa-camera mr-1"></i>{{mulai_kamera.foto ? 'Mulai Kamera' : 'Tangkap Gambar'}}</span>
                      </div>
                      <div class="input-group-append" v-if="pengeluaran.jumlah_gambar === 0">
                        <span class="btn btn-success" @click="tambah_foto()"> <i class="fa fa-plus-square mr-1" aria-hidden="true"> </i> </span>
                      </div>
                    </div>
          </div>
</div> <!-- col -->


<div class="col-md-6" v-if="pengeluaran.jumlah_gambar > 0" >
<div class="text-center">
      <!-- Menampilkan feed kamera -->
      <video v-if="!pengeluaran.foto1"  ref="video1" autoplay playsinline></video>
      <!-- Canvas untuk menangkap gambar -->
      <canvas ref="canvas1" style="display: none;"></canvas>
      <!-- hasil tangkapan -->
      <div class="form-group">
          <img v-if="pengeluaran.foto1" class="foto_upload mt-3" :src="pengeluaran.foto1" alt="">
      </div>
</div>        
<div class="form-group">                  
                    <div class="input-group">
                      <div class="custom-file" @change="uploadImage($event, 'foto1')">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Upload File</label>
                      </div>
                      <div class="input-group-append">
                        <span class="btn btn-default" @click="mulai_kamera.foto1 ? startCamera('foto1', 'video1') : captureImage('foto1', 'video1', 'canvas1')" > <i class="fa fa-camera mr-1"></i>{{mulai_kamera.foto1 ? 'Mulai Kamera' : 'Tangkap Gambar'}}</span>
                      </div>
                      <div class="input-group-append" v-if="pengeluaran.jumlah_gambar === 1">
                        <span class="btn btn-success" @click="tambah_foto()"> <i class="fa fa-plus-square mr-1" aria-hidden="true"> </i> </span>
                      </div>
                      <div class="input-group-append" v-if="pengeluaran.jumlah_gambar === 1">
                        <span class="btn btn-danger" @click="hapus_foto()"><i class="fas fa-trash mr-1"></i></span>
                      </div>

                    </div>
          </div>
</div> <!-- col -->



<div class="col-md-6" v-if="pengeluaran.jumlah_gambar > 1" >
<div class="text-center">
      <!-- Menampilkan feed kamera -->
      <video v-if="!pengeluaran.foto2"  ref="video2" autoplay playsinline></video>
      <!-- Canvas untuk menangkap gambar -->
      <canvas ref="canvas2" style="display: none;"></canvas>
      <!-- hasil tangkapan -->
      <div class="form-group">
          <img v-if="pengeluaran.foto2" class="foto_upload mt-3" :src="pengeluaran.foto2" alt="">
      </div>
</div>        
<div class="form-group">                  
                    <div class="input-group">
                      <div class="custom-file" @change="uploadImage($event, 'foto2')">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Upload File</label>
                      </div>
                      <div class="input-group-append">
                        <span class="btn btn-default" @click="mulai_kamera.foto2 ? startCamera('foto2', 'video2') : captureImage('foto2', 'video2', 'canvas2')" > <i class="fa fa-camera mr-1"></i>{{mulai_kamera.foto2 ? 'Mulai Kamera' : 'Tangkap Gambar'}}</span>
                      </div>
                      <div class="input-group-append" v-if="pengeluaran.jumlah_gambar === 2">
                        <span class="btn btn-success" @click="tambah_foto()"> <i class="fa fa-plus-square mr-1" aria-hidden="true"> </i> </span>
                      </div>
                      <div class="input-group-append" v-if="pengeluaran.jumlah_gambar === 2">
                        <span class="btn btn-danger" @click="hapus_foto()"><i class="fas fa-trash mr-1"></i></span>
                      </div>

                    </div>
          </div>
</div> <!-- col -->



<div class="col-md-6" v-if="pengeluaran.jumlah_gambar > 2" >
<div class="text-center">
      <!-- Menampilkan feed kamera -->
      <video v-if="!pengeluaran.foto3"  ref="video3" autoplay playsinline></video>
      <!-- Canvas untuk menangkap gambar -->
      <canvas ref="canvas3" style="display: none;"></canvas>
      <!-- hasil tangkapan -->
      <div class="form-group">
          <img v-if="pengeluaran.foto3" class="foto_upload mt-3" :src="pengeluaran.foto3" alt="">
      </div>
</div>        
<div class="form-group">                  
                    <div class="input-group">
                      <div class="custom-file" @change="uploadImage($event, 'foto3')">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Upload File</label>
                      </div>
                      <div class="input-group-append" >
                        <span class="btn btn-default" @click="mulai_kamera.foto3 ? startCamera('foto3', 'video3') : captureImage('foto3', 'video3', 'canvas3')" > <i class="fa fa-camera mr-1"></i>{{mulai_kamera.foto3 ? 'Mulai Kamera' : 'Tangkap Gambar'}}</span>
                      </div>
                      <div class="input-group-append" v-if="pengeluaran.jumlah_gambar === 3">
                        <span class="btn btn-danger" @click="hapus_foto()"><i class="fas fa-trash mr-1"></i></span>
                      </div>

                    </div>
          </div>
</div> <!-- col -->

</div> <!-- row -->
        
    <label for="tanggal" class="text-uppercase">tanggal transaksi</label>                   
<b-form-datepicker id="tanggal"   v-model="pengeluaran.tgl"  :locale="'id'"  class="mb-2"></b-form-datepicker>          
      
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
import VoiceProcessor from './../../components/keluarMasuk.vue'; // Import komponen
import vSelect from 'vue-select';
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formEdit',
          data () {
              return {    
                  isEditorReady: false, // Untuk mencegah inisialisasi sebelum siap
              mulai_kamera : {
                foto : true,
                foto1 : true,
                foto2 : true,
                foto3 : true,
              } ,     
                                                                                                
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
                    imageInsertButtons: ['imageUpload'],                       
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

                        'image.removeError': function (error) {                        
                          alert('Terjadi error saat menghapus gambar: ' + error.message);
                        },
                    }   
                },     
              }
          },
          components : {
                uangInput, vSelect, VoiceProcessor
          },        
          computed : {
                ...mapState(['errors']),
                ...mapState('pengeluaran_stores', {                 
                    pengeluaran: state => state.pengeluaran,
                    pesan : state => state.pesan,
                    url : state => state.url.hapus,
                      foto_db : state => state.foto_db  
                }),                
                ...mapState('user', {
                    authenticated : state => state.authenticated
                }),                             
                ...mapState('kas_stores', {                 
                    kas_sekarang : state => state.kas_sekarang,                  
                }),                                      
                ...mapState('kategori_stores', {                 
                    kategori : state => state.kategori_pengeluaran,                  
                }),                        
                selected_kategori  :{
                        get(){
                            return this.$store.state.pengeluaran_stores.selected_kategori 
                    },
                    set(val){
                        this.pengeluaran.kategori_id = val.value;
                        this.pengeluaran.user_id = this.authenticated.id;   
                        this.$store.state.pengeluaran_stores.selected_kategori = val
                    }
                },
          },
          methods : {
                ...mapMutations('pengeluaran_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),
                ...mapActions('pengeluaran_stores', ['update_pengeluaran']),  
                tambah_foto() {                                   
                      this.pengeluaran.jumlah_gambar =    this.pengeluaran.jumlah_gambar +1;                 
                },
                 hapus_foto() {
                        this.pengeluaran.jumlah_gambar =    this.pengeluaran.jumlah_gambar -1;                 
                     
                },
                  
                      handleProcessed(param) {                        
                          this.pengeluaran.total = param.total;
                          var labelDicari = param.kategori;
                          let hasil =  this.kategori.find(item => item.label.toLowerCase() === labelDicari.toLowerCase());
                          this.selected_kategori.label = hasil.label;
                          this.selected_kategori.value = hasil.value;
                          this.pengeluaran.kategori_id = hasil.value;
                            this.pengeluaran.user_id = this.authenticated.id;                        
                      },          
              uploadImage(event, lokasi) {  
                  let poto = event.target.files[0];   //file yang dikirim ke db               
                  let tampil_foto =  {
                    a : URL.createObjectURL(poto),
                    b : lokasi  
                  };
                  var simpan = {
                    a : poto,
                    b : lokasi
                  };

                this.SHOW_FOTO(tampil_foto) //ini kirim ke vuex utk citra
                 this.SIMPAN_FOTO(simpan) //ini simpan db
              
              }, 
                captureImage(foto, a, b) {
                  const video = this.$refs[a];
                  const canvas = this.$refs[b];
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
                            let simpan = {
                              a : file,
                              b : foto
                            };        
                    this.SIMPAN_FOTO(simpan) //ini simpan db  

                    
                 let show = {
                   a : URL.createObjectURL(blob),
                   b : foto
                 };
                    this.SHOW_FOTO(show) //ini kirim ke vuex utk citra
                                       
                  }, "image/jpeg");
                     this.mulai_kamera[foto] = true;                             
                },
               async startCamera(foto, a) {                
           
                this.pengeluaran[foto] = '';
                this.foto_db[foto] = '';
                    try {
                      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                      this.$refs[a].srcObject = stream;
                         this.mulai_kamera[foto] = false;
                    } catch (error) {
                      console.error("Gagal mengakses kamera:", error);
                      alert("Kamera tidak dapat diakses. Pastikan izin diberikan.");
                    }
                  }, 
          }
      }
</script>

