<template>  
 <b-modal id="edit-kas" ref="edit-kas" title="Edit">
  
        <div class="form-group">
            <label for="nama">Nama</label>
            <input  v-on:keyup.enter="proses" type="text" id="nama" class="form-control" placeholder="Nama" v-model="kas.nama">
        <p class="text-danger" v-if="errors.nama"><i>{{ errors.nama[0] }}</i></p>
        </div>  

         <label for="jenis-transaksi" class="text-uppercase">jenis transaksi</label> <br>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="kas.jenis" type="radio" name="kas" id="" value="pengeluaran"> Pengeluaran
           </label>
         </div>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="kas.jenis"  type="radio" name="kas" id="" value="pemasukan"> pemasukan
           </label>
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
          >
            Proses
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('edit-kas')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formEdit',
          data () {
              return {

              }
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('kas_stores', {                 
                    kas: state => state.kas,
                    pesan : state => state.pesan
                }),
          },
          methods : {
                ...mapMutations('kas_stores', ['CLEAR_FORM']),
                ...mapActions('kas_stores', ['update_kas', 'get_kas']),            
              proses (ev) {
                  ev.preventDefault()
                  this.update_kas().then(() => {
                          this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_kas();
                        //close modal                   
                        this.$bvModal.hide('edit-kas')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

