<template>  
 <div>


   
        <div class="form-group">
            <label for="nama" class="text-uppercase">Nama</label>
            <input  v-on:keyup.enter="proses" type="text" id="nama" class="form-control" placeholder="Nama" v-model="barang.nama">
        <p class="text-danger" v-if="errors.nama"><i>{{ errors.nama[0] }}</i></p>
        </div>              

        <div class="form-group">
            <label for="kuantitas" class="text-uppercase">kuantitas</label>
            <input  v-on:keyup.enter="proses" type="text" id="kuantitas" class="form-control" placeholder="kuantitas" v-model="barang.kuantitas">
        <p class="text-danger" v-if="errors.kuantitas"><i>{{ errors.kuantitas[0] }}</i></p>
        </div>   
  
         <label for="satuan" class="text-uppercase">Satuan</label> <br>
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="barang.satuan" type="radio" name="satuan" id="satuan" value="rupiah"> rupiah
           </label>
         </div> 
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="barang.satuan" type="radio" name="satuan" id="satuan" value="gram"> gram
           </label>
         </div> 
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="barang.satuan" type="radio" name="satuan" id="satuan" value="liter"> liter
           </label>
         </div> 
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="barang.satuan" type="radio" name="satuan" id="satuan" value="kg"> kg
           </label>
         </div> 
         <div class="form-check form-check-inline">
           <label class="form-check-label text-uppercase" >
             <input class="form-check-input" v-model="barang.satuan" type="radio" name="satuan" id="satuan" value="gram"> gram
           </label>
         </div> 
           <p class="text-danger" v-if="errors.satuan"><i>Satiam Wajid Diconteng Salah Satu</i></p>

      <div class="form-group mt-3">
        <label for="total" class="text-uppercase">Nominal Setara</label>
           <uangInput v-model="barang.nominal" placeholder="Nominal"></uangInput>
                    {{barang.nominal ? barang.nominal : 0  | terbilang}}<br>
              <p class="text-danger" v-if="errors.nominal"><i>{{ errors.nominal[0] }}</i></p>
      </div>                         


 <div v-if="Object.keys(errors).length">
      <ul>
        <li v-for="(messages, field) in errors" :key="field">
          <span v-for="(message, index) in messages" :key="index">
            {{ message }}
          </span>
        </li>
      </ul>
    </div>

       </div>


</template>

<script>
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formBarang',
          data () {
              return {

              }
          },
          components : {
                uangInput
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
             
          }
      }
</script>

