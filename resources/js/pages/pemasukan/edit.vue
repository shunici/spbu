<template>  
 <b-modal size="xl" id="edit-pemasukan" ref="edit-pemasukan" title="Edit"  :header-bg-variant="'info'">
  <form-pemasukan></form-pemasukan>

       <div v-if="errors.koneksi" class="alert alert-danger" role="alert">
           <strong>{{errors.koneksi}}</strong> 
        </div>
  <template #modal-footer>
        <div class="w-100">         
          <b-button
           :disabled="pesan.sukses"   
            variant="primary"
            size="sm"
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
            @click="$bvModal.hide('edit-pemasukan')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
import formPemasukan from  './form.vue'

import vSelect from 'vue-select';
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formEdit',
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
                uangInput, vSelect, formPemasukan
          },          
          computed : {
                ...mapState(['errors']),
                ...mapState('pemasukan_stores', {                 
                    pemasukan: state => state.pemasukan,
                    pesan : state => state.pesan,
                }),                
                ...mapState('user', {
                    authenticated : state => state.authenticated
                }),             
                ...mapState('kas_stores', {                 
                    kas_sekarang : state => state.kas_sekarang,                  
                }),                         
                ...mapState('kategori_stores', {                 
                    kategori : state => state.kategori_pemasukan,                  
                }),  
          },
          methods : {
                ...mapMutations('pemasukan_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),
                ...mapActions('pemasukan_stores', ['update_pemasukan']),    
                      ...mapActions('kas_stores', ['get_kas']),          
              proses (ev) {
                  ev.preventDefault()
                  this.update_pemasukan().then(() => {
                         this.pesan.sukses = true
                     setTimeout(function () {                      
                        //close modal                   
                        this.$bvModal.hide('edit-pemasukan')
                        this.CLEAR_FORM();
                       this.pesan.sukses = false
                            this.get_kas();    
                    }.bind(this), 1500);

                  } ); //this submit                                                   
              },      
              dobel(){
                alert('sedang diproses....');
              }
          }
      }
</script>

