<template>  
 <b-modal size="xl" id="edit-realisasi" ref="edit-realisasi" title="Edit">   

       <form-realisasi></form-realisasi>
        
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
            @click="$bvModal.hide('edit-realisasi')"
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
          name : 'formEdit',
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
                ...mapActions('realisasi_stores', ['update_realisasi', 'get_realisasi']),            
              proses (ev) {
                  ev.preventDefault()
                  this.update_realisasi().then(() => {
                          this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_realisasi();
                        //close modal                   
                        this.$bvModal.hide('edit-realisasi')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

