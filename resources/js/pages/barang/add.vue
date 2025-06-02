<template>  
 <b-modal size="xl" id="add-barang" title="barang Baru">  
   


        
<form-barang></form-barang>
     
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
            @click="$bvModal.hide('add-barang')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
import formBarang from './form.vue'
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {

              }
          },
          components : {
            formBarang
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('barang_stores', {                 
                    barang: state => state.barang,
                    pesan : state => state.pesan
                }),
          },
          methods : {
              ...mapMutations('barang_stores', ['CLEAR_FORM']),    
                ...mapActions('barang_stores', ['submit_barang', 'get_barang']),            
              proses (ev) {
                  ev.preventDefault()
                  this.submit_barang().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_barang();
                        //close modal
                        this.$bvModal.hide('add-barang')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

