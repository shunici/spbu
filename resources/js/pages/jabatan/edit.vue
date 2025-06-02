<template>  
 <b-modal id="edit-jabatan" ref="edit-jabatan" title="Edit"  :header-bg-variant="'info'">
  
        <div v-if="pesan.sukses" class="alert alert-primary" role="alert">
           <strong>Editing Berhasil!</strong> 
        </div>

        <div class="form-group">
            <label for="nama_jabatan" class="text-uppercase">Nama jabatan</label>
            <input  v-on:keyup.enter="proses" type="text" id="nama_jabatan" class="form-control" placeholder="nama_jabatan" v-model="jabatan.nama_jabatan">
        <p class="text-danger" v-if="errors.nama_jabatan"><i>{{ errors.nama_jabatan[0] }}</i></p>
        </div>  
 <div class="form-group">
        <label for="gajih_pokok" class="text-uppercase">gajih_pokok</label>
           <uangInput v-model="jabatan.gajih_pokok" placeholder="input gaji" style="font-size:1.5rem" class="font-weight-bold"></uangInput>
                    {{jabatan.gajih_pokok ? jabatan.gajih_pokok : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.gajih_pokok"><i>{{ errors.gajih_pokok[0] }}</i></p>
      </div>

<div class="form-group">
  <label for="fungsi_tugas" class="text-uppercase">Fungsi Tugas</label>
  <textarea class="form-control" name="" id="" cols="30" rows="10" v-model="jabatan.fungsi_tugas"></textarea>
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
            :disabled="pesan.sukses"             
          >
        <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
             {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}} 
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('edit-jabatan')"
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
          name : 'formEdit',
          data () {
              return {

              }
          },
           components : {
            uangInput
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('jabatan_stores', {                 
                    jabatan: state => state.jabatan,
                    pesan : state => state.pesan
                }),
          },
          methods : {
                ...mapMutations('jabatan_stores', ['CLEAR_FORM']),
                ...mapActions('jabatan_stores', ['update_jabatan', 'get_jabatan']),            
              proses (ev) {
                  ev.preventDefault()
                  this.update_jabatan().then(() => {
                          this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_jabatan();
                        //close modal                   
                        this.$bvModal.hide('edit-jabatan')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1500);

                  } ); //this submit
                  
                    
             
              }
          }
      }
</script>

