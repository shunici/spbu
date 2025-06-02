<template>
    <div class="row mt-4">
      <div class="col-md-12">
        <h3 class="text-uppercase">Pemberitahuan</h3>
        <p class="text-uppercase">tampilkan</p>
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input" v-model="aplikasi.alert_tampil" type="radio" name="tampil" id="" :value="1"> ya
          </label>
        </div>
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input" v-model="aplikasi.alert_tampil"  type="radio" name="tampil" id="" :value="0"> tidak
          </label>
        </div>
      <div class="form-group">
        <label for="judul" class="text-uppercase">Judul</label>
        <input type="text" class="form-control" name="" id="judul"  placeholder="judul pengumuman" v-model="aplikasi.alert_judul">
      </div>
     
      <div class="form-group">
        <label for="isi" class="text-uppercase">Isian</label>
        <input type="text" class="form-control" name="" id="isi"  placeholder="isi pengumuman" v-model="aplikasi.alert_isi">
      </div>

       <p class="font-weight-bold text-uppercase">Warna</p>
      <div class="form-check form-check-inline">
          <label class="form-check-label mr-3" v-for="option in options" :key="option.value">
          <input class="form-check-input "   type="radio" :value="option.value" v-model="aplikasi.alert_warna"/>
          {{ option.label }}
        </label>
      </div>    

<blockquote :class="aplikasi.alert_warna" class="shadow" v-if="aplikasi.alert_judul">
  <h5 id="tip">{{aplikasi.alert_judul}}</h5>
  <p>{{aplikasi.alert_isi}}</p>
</blockquote>


 </div>
     <div class="col-md-6 mt-4">
           <label for="logo" class="text-uppercase">Logo</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="logo" @change="uploadImage($event, 'logo')">
                  <label class="custom-file-label" for="logo">Pilih Logo</label>
              </div>
        </div>
          <img class="foto_upload mb-3 mt-3" :src="aplikasi.logo" alt="" width="100">
   </div>

     <div class="col-md-6">     
          <label for="kop" class="text-uppercase">Kop Aplikasi</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="kop" @change="uploadImage($event, 'kop')">
                  <label class="custom-file-label" for="kop">Pilih Foto</label>
              </div>
        </div>
          <img class="foto_upload mb-3 mt-3" :src="aplikasi.kop" alt="" width="100">
      </div>


 

        <div class="col-md-3">
           <label for="foto1" class="text-uppercase">Backround</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto1" @change="uploadImage($event, 'foto1')">
                  <label class="custom-file-label" for="foto1">Pilih Foto</label>
              </div>
        </div>
          <img class="foto_upload mb-3 mt-3" :src="aplikasi.foto1" alt="" width="100">
        </div>
        
        <div class="col-md-3">
           <label for="foto2" class="text-uppercase">Slide 1</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto2" @change="uploadImage($event, 'foto2')">
                  <label class="custom-file-label" for="foto2">Pilih Foto</label>
              </div>
        </div>
          <img class="foto_upload mb-3 mt-3" :src="aplikasi.foto2" alt="" width="100">
        </div>

        <div class="col-md-3">
           <label for="foto3" class="text-uppercase">Slide 2</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto3" @change="uploadImage($event, 'foto3')">
                  <label class="custom-file-label" for="foto3">Pilih Foto</label>
              </div>
        </div>
          <img class="foto_upload mb-3 mt-3" :src="aplikasi.foto3" alt="" width="100">
        </div>

        <div class="col-md-3">
           <label for="foto4" class="text-uppercase">Slide 3</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="foto4" @change="uploadImage($event, 'foto4')">
                  <label class="custom-file-label" for="foto4">Pilih Foto</label>
              </div>
        </div>
          <img class="foto_upload mb-3 mt-3" :src="aplikasi.foto4" alt="" width="100">
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


            <button type="submit" class="btn btn-success float-right" @click.prevent="simpan"  :disabled="pesan"  >
                 <b-spinner small type="grow" v-if="pesan"></b-spinner>
             {{pesan ? "Sedang di proses tunggu sebentar..." : "Simpan Perubahan"}}   
            </button>





     
    </div>
</template>
<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
    export default {
         name : 'form-aplikasi',
          data () {
              return {
                pesan : false,
                 options: [
                    { label: 'Toska', value: 'quote-info' },
                    { label: 'Kuning', value: 'quote-warning' },
                    { label: 'Merah', value: 'quote-danger' },
                    { label: 'Biru', value: 'quote-primary' },
                    { label: 'Hijau', value: 'quote-success' },
                      { label: 'Hitam', value: 'quote-dark' },
                  ],
              }
          },          
          computed : {
                ...mapState(['errors']),
                ...mapState('aplikasi_stores', {                 
                    aplikasi : state => state.aplikasi,
                 
                }),
          },
          methods : {
            ...mapMutations('aplikasi_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),    
            ...mapActions('aplikasi_stores', ['get_aplikasi', 'update_aplikasi']), 
             
        simpan(){                                                                            
          this.update_aplikasi();
          this.pesan = true
           setTimeout(function () {
                  this.pesan = false
                }.bind(this), 1900)
        },                                   
          uploadImage(event, lokasi) {  
                  let poto = {
                      data : event.target.files[0],   //file yang dikirim ke db    
                      lokasi : lokasi           
                  } 
                  let tampil_foto =   {
                    data : URL.createObjectURL(event.target.files[0]),
                    lokasi : lokasi
                  }          
                this.SHOW_FOTO(tampil_foto) //ini kirim ke vuex utk citra
                 this.SIMPAN_FOTO(poto) //ini simpan db
              
          },  
        },
       
          
    }
</script>