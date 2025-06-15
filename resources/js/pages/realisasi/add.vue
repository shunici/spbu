<template>  
 <b-modal size="xl" id="add-realisasi" title="realisasi Baru">  
   


        
<form-realisasi></form-realisasi>
     
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
            @click="$bvModal.hide('add-realisasi')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
import formRealisasi from './form.vue'
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {

              }
          },
          components : {
            formRealisasi
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('realisasi_stores', {                 
                    realisasi: state => state.realisasi,
                    pesan : state => state.pesan
                }),
          },
          methods : {
              ...mapMutations('realisasi_stores', ['CLEAR_FORM']),    
                ...mapActions('realisasi_stores', ['submit_realisasi', 'get_realisasi']),            
              proses (ev) {
                  ev.preventDefault()
                  this.submit_realisasi().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_realisasi();
                        //close modal
                        this.$bvModal.hide('add-realisasi')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

