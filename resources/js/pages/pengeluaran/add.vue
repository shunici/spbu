<template>  
 <b-modal size="xl" id="add-pengeluaran" title="Buat Data Baru"  :header-bg-variant="'info'">
   
<form-pengeluaran></form-pengeluaran>

       <div v-if="errors.koneksi" class="alert alert-danger" role="alert">
           <strong>{{errors.koneksi}}</strong> 
        </div>
  <template #modal-footer>
        <div class="w-100">         
          <b-button
            variant="primary"
            size="sm"
          :disabled="pesan.sukses" 
            class="float-right"
            @click.prevent="proses"    
            @dblclick="dobel"      
          >
           <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
             {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}}   
          </b-button>
        
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('add-pengeluaran')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>

import formPengeluaran from  './form.vue'

import vSelect from 'vue-select';
import uangInput from '../../components/uang_input.vue';
import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'addPengeluaran',
          components : {
                uangInput, vSelect, formPengeluaran
          },
          data () {
              return {    
                mulai_kamera : true,             
                
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
          computed : {
                ...mapState(['errors']),
                ...mapState('pengeluaran_stores', {                 
                    pengeluaran: state => state.pengeluaran,  
                       pesan : state => state.pesan                
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
          },
          methods : {
              ...mapMutations('pengeluaran_stores', ['CLEAR_FORM','SHOW_FOTO', 'SIMPAN_FOTO']),    
                ...mapActions('pengeluaran_stores', ['submit_pengeluaran', ]),     
                  ...mapActions('kas_stores', ['get_kas']),  
              proses (ev) {

                  ev.preventDefault()
                  var kas_masjid = this.kas_sekarang.masjid;
                   var kas_bank = this.kas_sekarang.bank;
                   var total = this.pengeluaran.total;
                   var jenis_kas = this.pengeluaran.kas;
                   
                    if(jenis_kas == 'masjid') {                
                                  if(kas_masjid > total ) {                  
                                  this.submit()
                                  } else {
                                    alert ('uang kas masjid tidak cukupp');
                                  }
                  } //jenis kas


                  if(jenis_kas == 'bank') {                
                                if(kas_bank > total ) {                  
                                  this.submit()
                                } else {
                                  alert ('uang kas bank tidak cukupp');
                                }
                } //jenis kas              


              },   
              submit (){                
                    this.submit_pengeluaran().then(() => {
                     this.pesan.sukses = true          
                     setTimeout(function () {                    
                        //close modal
                        this.$bvModal.hide('add-pengeluaran')
                        this.CLEAR_FORM();
                      this.pesan.sukses = false
                              this.get_kas();                      
                    }.bind(this), 1700);

                  } ); //this submit  
              },   
              dobel(){
                alert('sedang diproses....');
              }
          }
      }
</script>


<style>
  .fr-active {
    z-index : 5000000 !important;
  }
</style>


