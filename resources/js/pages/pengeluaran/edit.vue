<template>  
 <b-modal id="edit-pengeluaran" ref="edit-pengeluaran" title="Edit"  :header-bg-variant="'info'">
  
  
<form-pengeluaran></form-pengeluaran>

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
            @click="$bvModal.hide('edit-pengeluaran')"
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
import formPengeluaran from  './form.vue'
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formEdit',
          data () {
              return {                                       
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
                uangInput, vSelect, formPengeluaran
          },        
          computed : {
                ...mapState(['errors']),
                ...mapState('pengeluaran_stores', {                 
                    pengeluaran: state => state.pengeluaran,
                    pesan : state => state.pesan,
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
                ...mapMutations('pengeluaran_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),
                ...mapActions('pengeluaran_stores', ['update_pengeluaran']),                            
              proses (ev) {
                  ev.preventDefault()
                  this.update_pengeluaran().then(() => {
                         this.pesan.sukses = true   
                     setTimeout(function () {                  
                        //close modal                   
                        this.$bvModal.hide('edit-pengeluaran')
                        this.CLEAR_FORM();
                      this.pesan.sukses = false                            
                    }.bind(this), 1700);

                  } ); //this submit                                                   
              },  
              dobel(){
                alert('sedang diproses....');
              }
          }
      }
</script>

