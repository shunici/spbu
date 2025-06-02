<template>  
 <b-modal size="xl" id="add-kategori" title="Kategori Baru"  :header-bg-variant="'info'">  
   
        <div class="form-group">
            <label for="nama" class="text-uppercase">Nama</label>
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
                 <p class="text-danger" v-if="errors.jenis"><i>Jenis Transaksi Wajid Diconteng Salah Satu</i></p>

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
            @click="$bvModal.hide('add-kategori')"
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
          name : 'formAdd',
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
                ...mapActions('kategori_stores', ['submit_kategori', 'get_kategori']),            
              proses (ev) {
                  ev.preventDefault()
                  this.submit_kategori().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_kategori();
                        //close modal
                        this.$bvModal.hide('add-kategori')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

