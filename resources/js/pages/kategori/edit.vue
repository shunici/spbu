<template>  
 <b-modal id="edit-kategori" ref="edit-kategori" title="Edit"  :header-bg-variant="'info'">   

        <div class="form-group">
            <label for="nama">Nama</label>
            <input  v-on:keyup.enter="proses" type="text" id="nama" class="form-control" placeholder="Nama" v-model="kategori.nama">
        <p class="text-danger" v-if="errors.nama"><i>{{ errors.nama[0] }}</i></p>
        </div>  

         <label for="jenis-transaksi" class="text-uppercase">jenis transaksi</label> <br>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="kategori.jenis" type="radio" name="kategori" id="" value="pengeluaran"> Pengeluaran
           </label>
         </div>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="kategori.jenis"  type="radio" name="kategori" id="" value="pemasukan"> pemasukan
           </label>
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
            @click="$bvModal.hide('edit-kategori')"
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
                ...mapState('kategori_stores', {                 
                    kategori: state => state.kategori,
                    pesan : state => state.pesan
                }),
          },
          methods : {
                ...mapMutations('kategori_stores', ['CLEAR_FORM']),
                ...mapActions('kategori_stores', ['update_kategori', 'get_kategori']),            
              proses (ev) {
                  ev.preventDefault()
                  this.update_kategori().then(() => {
                          this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_kategori();
                        //close modal                   
                        this.$bvModal.hide('edit-kategori')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

