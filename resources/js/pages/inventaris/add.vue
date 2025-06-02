<template>  
 <b-modal size="xl" id="add-inventaris" title="Buat Inventaris Baru"  :header-bg-variant="'info'">
  

        <div class="form-group">
            <label for="nama" class="text-uppercase">Nama inventaris</label>
            <input  v-on:keyup.enter="proses" type="text" id="nama" class="form-control" placeholder="Nama" v-model="inventaris.nama">
        <p class="text-danger" v-if="errors.nama"><i>{{ errors.nama[0] }}</i></p>
        </div>  
        <div class="form-group">          
            <label for="kategori" class="text-uppercase">kategori</label>
            <select class="form-control" v-model="inventaris.kategori" >                                                
                <option v-for="(item, index) in kategori" :key="index">
                  {{item}}
                </option>
                                                                                                                       
            </select>  
        </div>
        <div class="row">     
          <div class="col-8">              
                <div class="form-group">
                    <label for="total" class="text-uppercase">total </label>
                    <input  v-on:keyup.enter="proses" type="text" id="total" class="form-control" placeholder="total" v-model="inventaris.total">
                <p class="text-danger" v-if="errors.total"><i>{{ errors.total[0] }}</i></p>
                </div>  
          </div>   
          <div class="col-4">              
              <div class="form-group">
                            <label for="satuan" class="text-uppercase">satuan</label>
                            <select v-if="!lainnya" class="form-control"   @change="onChange($event)" >                                 
                                <option selected value="pcs">pcs</option>
                                <option value="rangkap">rangkap</option>
                                <option value="buah">buah</option>    
                                <option value="lainnya">lainnya</option>                                                                                                            
                            </select>   
                          <input v-if="lainnya" type="text" class="form-control" v-model="inventaris.satuan"  @dblclick="input_close" > 

              </div>  
          </div>
        </div>

      <div class="form-group">
        <label for="total" class="text-uppercase">Harga Per item</label>
           <uangInput v-model="inventaris.harga" placeholder="Harga per items" style="font-size:1.5rem" class="font-weight-bold"></uangInput>
                    {{inventaris.harga ? inventaris.harga : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.harga"><i>{{ errors.harga[0] }}</i></p>
      </div>

      <label for="uraian" class="text-uppercase">uraian</label>            
      <froala :tag="'textarea'" :config="config" v-model="inventaris.uraian"></froala>
      
      
      <!-- Menampilkan feed kamera -->
      <video v-if="!inventaris.foto"  ref="video" autoplay playsinline></video>
      <!-- Canvas untuk menangkap gambar -->
      <canvas ref="canvas" style="display: none;"></canvas>
      <!-- hasil tangkapan -->
      <div class="form-group">
          <img class="foto_upload mt-3" :src="inventaris.foto" alt="">
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
        
            
 
 <div v-if="Object.keys(errors).length">
      <ul>
        <li v-for="(messages, field) in errors" :key="field">
          <span v-for="(message, index) in messages" :key="index">
            {{ message }}
          </span>
        </li>
      </ul>
    </div>
                   
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
            @dblclick="dobel"   
            :disabled="pesan.sukses"          
          >                       
            <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
             {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}}   
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('add-inventaris')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {
              mulai_kamera : true,
                lainnya : false,
                 config: {
                    toolbarButtons: {
                       'moreText': {
                            'buttons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', 'textColor', 'backgroundColor', 'inlineClass', 'inlineStyle', 'clearFormatting']
                          },
                           'moreParagraph': {
                              'buttons': ['alignLeft', 'alignCenter', 'formatOLSimple', 'alignRight', 'alignJustify', 'formatOL', 'formatUL', 'paragraphFormat', 'paragraphStyle', 'lineHeight', 'outdent', 'indent', 'quote']
                            },
                              'moreRich': {
                              'buttons': ['insertLink',  'insertTable', 'emoticons', 'fontAwesome', 'specialCharacters', 'embedly', 'insertFile', 'insertHR']
                            },
                            
                              'moreMisc': {
                              'buttons': ['undo', 'redo', 'fullscreen', 'print', 'getPDF', 'spellChecker', 'selectAll', 'html', 'help']
                            },
                    },
                    events: {
                      'froalaEditor.initialized': function () {
                        console.log('initialized')
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
                ...mapState('inventaris_stores', {                 
                    inventaris: state => state.inventaris,
                    pesan : state => state.pesan,
                    kategori : state => state.kategori
                }),
          },
          methods : {
              ...mapMutations('inventaris_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),    
                ...mapActions('inventaris_stores', ['submit_inventaris', 'get_inventaris']),            
              proses (ev) {
                  ev.preventDefault()
                  this.submit_inventaris().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_inventaris();
                        //close modal
                        this.$bvModal.hide('add-inventaris')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit                                                   
              },                             
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
                            const file = new File([blob], "inventaris.jpeg", {
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
              dobel(){
                alert('sedang diproses....');
              },
              onChange: function(e){
                var value = e.target.value;               
                if(value == 'lainnya') {
                  this.lainnya = true;
                  this.inventaris.satuan = value
                } else {
                  this.inventaris.satuan = value
                }

            }, 
            input_close () {
               this.lainnya = false
            }
          }
      }
</script>

