<template>  
 <div class="card">  
   
<div class="card-header">
  <button class="btn btn-outline-primary  no-print mr-2" @click="$router.go(-1)"> <i class="fa fa-code"></i> Kembali  </button>
</div>

<div class="card-body pl-0 pr-0 p-md-3">


<form-laporan ref="proses"></form-laporan>
         <div v-if="errors.koneksi" class="alert alert-danger" role="alert">
           <strong>{{errors.koneksi}}</strong> 
        </div>

<button type="button" class="btn btn-outline-primary mt-4"  :disabled="pesan.sukses" @click.prevent="proses">
    <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
        {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}}   
</button>

</div> <!-- cardbody -->
  </div>
</template>

<script>
import formLaporan from './form.vue'
      import { mapActions, mapState } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {

              }
          },
          components : {
            formLaporan
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('laporan_stores', {                 
                    laporan: state => state.laporan,
                    pesan : state => state.pesan
                }),
          },
          methods : {  
                ...mapActions('laporan_stores', ['submit_laporan', 'get_laporan']),            
              proses () {                              
                  this.$refs.proses.submit()
                    this.laporan.template = 0;
             
              }
          },
      }
</script>

