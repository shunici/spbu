<template>
    <div class="row">

<div class="col-md-6">

            <div class="form-group">
              <label for="nama" class="text-uppercase">Nama Aplikasi</label>
              <input type="text"  id="nama" class="form-control" v-model="aplikasi.nama">              
            </div>
                
            <div class="form-group">
              <label for="telp" class="text-uppercase">Telpon</label>
                <PhoneInput
                      v-model="aplikasi.telp"                  
                      id="telp"
                      placeholder="62813-XXXX-XXXX"
                    />         
            </div>
                
            <div class="form-group">
              <label for="email" class="text-uppercase">email Aplikasi</label>
              <input type="text"  id="email" class="form-control" v-model="aplikasi.email">              
            </div>

            <div class="form-group">
              <label for="alamat" class="text-uppercase">alamat Aplikasi</label>
            <textarea class="form-control" name="" id="" cols="30" rows="10" v-model="aplikasi.alamat" style="height : 150px"></textarea>
            </div> 
             
            <div class="form-group">
              <label for="kab_kota" class="text-uppercase">Kab / Kota</label>
              <input type="text"  id="kab_kota" class="form-control" v-model="aplikasi.kab_kota">              
            </div>
             
              <div class="form-group">
                <label class="text-uppercase" for="">Sosisal media</label>
                <div class="row">
                  <div class="col-md-3 col-sm-6 mt-2">                 
                        <select v-model="selected.class" class="form-control">
                          <option disabled value="">Pilih Sosial Media</option>
                                <option value="fa fa-whatsapp">WhatSapp</option>
                                <option value="fa fa-facebook-official">Facebook</option>
                                <option value="fa-brands fa-instagram">Instagram</option>
                                <option value="fa fa-telegram">Telegram</option>
                                <option value="fa fa-twitter">Twitter</option>   
                                <option value="fa fa-snapchat">SnapChat</option>   
                                <option value="fa fa-youtube">YouTube</option>   

                        </select>
                  </div>
                  <div class="col-md-7 col-sm-6 mt-2">
                    <input type="text" class="form-control" placeholder="tulis link sosial media anda disini" v-model="selected.link">
                  </div>
                  <div class="col-md-2 col-sm-12 mt-2">
                      <button class="btn btn-info btn-sm" @click="add_sosmed">Tambahkan</button>
                  </div>
                </div>
              </div>

              
              <div class="form-group">
                  <div class="row" v-for="(item, index) in sosmed" :key="index + 'sosmed'">
                    <div class="col-12" v-if="item.link">
                        <i :class="item.class" style="font-size:26px"></i> {{item.link}}  <span class="btn btn-danger btn-sm" @click="hapus_sosmed(index)"> <i class="fa fa-trash "></i></span>
                    </div>
                  </div>               
              </div>
              <p class="text-uppercase "><b>Link Daftar</b></p>
              <div class="form-check form-check-inline">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="daftar"  :value="1" v-model="aplikasi.register_rule"> Buka
                </label>
              </div>
              <div class="form-check form-check-inline">
                <label class="form-check-label">
                  <input class="form-check-input" type="radio" name="daftar"  :value="0" v-model="aplikasi.register_rule"> Tutup
                </label>
              </div>
            
</div>

<div class="col-md-6">                         
            <div class="form-group">
              <label for="peta" class="text-uppercase">peta Aplikasi</label>
              <input type="text"  id="peta" class="form-control" v-model="aplikasi.peta">              
            </div>
            
            <div class="form-group">
              <label for="link_peta" class="text-uppercase">link peta </label>
              <input type="text"  id="link_peta" class="form-control" v-model="aplikasi.peta_link">              
            </div>

            <div v-html="aplikasi.peta"></div>
            <a :href="aplikasi.peta_link" target="_blank" rel="noopener noreferrer"> Google Maps</a>
</div>

<div class="col-md-12">
    <label for="des" class="text-uppercase">deskripsi Aplikasi</label>
       <froala  :config="config" v-model="aplikasi.deskripsi"></froala>
</div>
            
         

        



    </div>
</template>

<script>
import PhoneInput from "../../components/PhoneInput.vue";
  import { mapActions, mapState, mapMutations } from 'vuex'
    export default {
         name : 'form-dataAplikasi',
           components: {
           PhoneInput
          },
          data () {
              return {     
                            
              selected : {
                class : '',
                link : ''
              },                                                                    
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
          computed : {
                ...mapState(['errors']),
                ...mapState('aplikasi_stores', {                 
                    aplikasi : state => state.aplikasi,      
                         sosmed : state => state.sosmed,          
                }),
          },
          methods : {
            ...mapMutations('aplikasi_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'TAMBAH_SOSMED']),    
            ...mapActions('aplikasi_stores', ['get_aplikasi', 'update_aplikasi']),                                                            
                add_sosmed(){
                     let a = this.selected.link;
                      if(a != ''){
                            var data = {
                                class : this.selected.class,
                                link : this.selected.link
                            };
                           this.sosmed.push(data)
                            this.selected = {class : '', link : ''};
                        }else{alert('link /isian tidak boleh kosong')}

                },
                hapus_sosmed(param){
                    this.sosmed.splice(param, 1);
                },
        },
        
    
       
          
    }
</script>