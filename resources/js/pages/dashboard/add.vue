<template>  
 <b-modal id="add-kas" size="xl" title="Buat Data Baru">
  
        <div v-if="pesan.sukses" class="alert alert-primary" role="alert">
           <strong>Data Berhasil Dibuat</strong> 
        </div>
<h4 class="text-uppercase">SALDO</h4>
<table class="table table-bordered table-sm">
  <thead class="thead-dark">   
    <tr>
      <th class="text-uppercase">kas Masjid</th>
      <th class="text-uppercase">kas Bank</th>    
    </tr>
  </thead>
  <tbody>
    <tr>   
      <td>{{kas_sekarang.masjid | currency}}</td>
      <td>{{kas_sekarang.bank | currency}}</td>
    </tr>
  </tbody>
</table>

  
<label for="uraian" class="text-uppercase">uraian</label>            
 <froala :tag="'textarea'" :config="config" v-model="kas.uraian"></froala>

   <p class="text-danger" v-if="errors.uraian"><i>{{ errors.uraian[0] }}</i></p> <br>

      
         <label for="kas" class="text-uppercase">perpindahan kas</label> <br>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="kas.jenis_aksi" type="radio" name="kas" id="kas" value="masjid_ke_bank" @click="handleClick" > dari masjid ke bank
           </label>
         </div>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="kas.jenis_aksi"  type="radio" name="kas" id="kas" value="bank_ke_masjid" @click="handleClick" > dari bank ke masjid
           </label>
         </div>   

          <p class="text-danger" v-if="errors.kas"><i>Jenis Transaksi Wajid Diconteng Salah Satu</i></p>






<div class="form-group mt-2">
  <label for="input" class="text-uppercase">Nominal Perpindahan</label>         
   <uangInput v-model="moveValue" placeholder="input bayar disini" style="font-size:1.5rem" class="font-weight-bold"></uangInput>
                    {{kas.input ? kas.input : 0  | terbilang}}<br>
    <p class="text-danger" v-if="errors.input"><i>{{ errors.total[0] }}</i></p>
</div>
<h4 class="text-uppercase">Saldo Setelah Dipindah</h4>
<table class="table table-bordered table-sm" ref="history">
  <thead class="thead-dark">   
    <tr>
      <th class="text-uppercase">kas Masjid</th>
      <th class="text-uppercase">kas Bank</th>    
    </tr>
  </thead>
  <tbody>
    <tr>   
      <td>{{kas.masjid | currency}}</td>
      <td>{{kas.bank | currency}}</td>
    </tr>
  </tbody>
</table>




  


  <template #modal-footer>
        <div class="w-100">         
          <b-button
            variant="primary"
            size="sm"
            class="float-right"
            @click="proses"          
          >
            Proses
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('add-kas')"
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
          
          components : {
                uangInput
          },
          data () {
              return {
                tes : '',
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
          computed : {
                ...mapState(['errors']),
                ...mapState('kas_stores', {                 
                    kas: state => state.kas,
                       kas_sekarang: state => state.kas_sekarang,
                         pesan : state => state.pesan,                 
                }),
                
          
             moveValue : {
                get(){
                        return this.$store.state.kas_stores.moveValue
                },
                set(val){
                    this.$store.commit('kas_stores/SET_moveValue', val)
                }
            },
          },
          methods : {
              ...mapMutations('kas_stores', ['CLEAR_FORM']),    
                ...mapActions('kas_stores', ['submit_kas', 'get_kas']),    
                      
              proses (ev) {
                  ev.preventDefault()
                  this.copyTableToClipboard();
                  this.submit_kas().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_kas();
                        //close modal
                        this.$bvModal.hide('add-kas')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1500);

                  } ); //this submit                                                   
              },
               handleClick(event) {
                    // Ambil value yang dipilih
                    const selectedValue = event.target.value;
                    this.kas.jenis_aksi = selectedValue
                    this.moveValue = this.moveValue               
                  },
                copyTableToClipboard() {                  
                 this.kas.history = this.$refs.history.outerHTML;             
                }
          }
      }
</script>

