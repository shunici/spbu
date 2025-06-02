<template>  
 <div class="row">
  
      <div class="col-12">
<img src="/img/AdminLTELogo.png" alt="" class="position-absolute float-left" width="80">
<h3 class="text-center">Masjid Agung Al Mujahidin</h3>
<p class="text-center">Alamat : Jakarta Utara Kelurahan Kemuning RT. 2 RW. 01 Banjabaru Selatan</p>

<hr>
<br>


<h2 class="text-center text-uppercase font-weight-bolder">Slip Gajih</h2>
<p class="text-center">Tanggal 10 Januari 2024</p>

   


      <table class="table">
        <thead>
          <tr>
            <td rowspan="6">
                <img :src="'/storage/user/'+user.foto" alt="" class="float-left" width="80">
            </td>
          </tr>
          <tr>       
       
            <td  class="font-weight-bolder">Nama</td>  
                        
            <td> {{user.name}}</td>        
          </tr>
          
          <tr>           
        
            <td  class="font-weight-bolder">Jabatan</td>  
                        
            <td> {{user.jabatan.nama_jabatan}}</td>        
          </tr>
          <tr>                 
            <td  class="font-weight-bolder">HP</td>                          
            <td> {{user.hp}}</td>        
          </tr>
          <tr>                 
            <td  class="font-weight-bolder">Alamat</td>                          
            <td> {{user.alamat}}</td>        
          </tr>

        </thead>


      </table>


      <table class="table table-bordered table-sm mt-3">          

        <thead>
          <tr class="bg-light">           
            <th class="text-uppercase">PENERIMAAN</th>                         
                <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td scope="row">Gajih Pokok</td>         
            <td>{{gajih.gajih_pokok | currency}}</td>          
          </tr>  

            <tr  v-for="(item, index) in gajih.ket_penerimaan" :key="index"> 
                    <td>
                        <input class="form-control" v-if="item.editing" v-model="item.uraian" />
                        <span v-else>{{ item.uraian}}</span>
                    </td>                
                    <td>                   
                         <uangInput v-if="item.editing" v-model="item.total" ></uangInput>
                        <span v-else>{{ item.total | currency }}</span>
                    </td>
           </tr>                    
        </tbody>
         <thead>
          <tr class="bg-light">           
            <th class="text-uppercase">total penerimaan</th>   
            <th>{{gajih.penerimaan | currency}}</th>  
              
          </tr>
          <tr>
            <th colspan="2"></th>
          </tr>
        </thead>
        <tfoot>
          <tr class="bg-light">
              <th class="text-uppercase" colspan="2">pengurangan</th>                        
          </tr>
          <tr  v-for="(item, index) in gajih.ket_pengurangan" :key="index"> 
                    <td>
                        <input class="form-control" v-if="item.editing" v-model="item.uraian" />
                        <span v-else>{{ item.uraian}}</span>
                    </td>                
                    <td>
                       <uangInput v-if="item.editing" v-model="item.total" ></uangInput>
                        <span v-else>{{ item.total | currency}}</span>
                    </td>                  
           </tr> 
            <tr class="bg-light">
              <th class="text-uppercase">Total pengurangan</th>          
              <th>{{gajih.pengurangan | currency}}</th>        
           </tr>
           
            <tr>
              <th class="text-uppercase"></th>          
              <th></th>
     
           </tr>
            <tr class="bg-light">
              <th class="text-uppercase">Total yang diterima petugas</th>          
              <th>{{gajih.penerimaan - gajih.pengurangan | currency}}</th>
    
           </tr>
          
        </tfoot>
        
      </table>         

    
            </div> <!-- col -->

  </div>
</template>

<script>
import vSelect from 'vue-select';
import uangInput from '../../components/uang_input.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formedit',
          data () {
              return {
                  user_selected: this.$store.state.gajih_stores.user_selected,                                  
              }
          },
          components : {
            uangInput, vSelect
          },              
         
          computed : {
                ...mapState(['errors']),
                ...mapState('gajih_stores', {    
                      user : state => state.user,             
                    gajih: state => state.gajih, 
                     ket_penerimaan: state => state.gajih.ket_penerimaan,                   
                      ket_pengurangan: state => state.gajih.ket_pengurangan,                   
                    pesan : state => state.pesan,
                    user_list : state => state.user_list,
                }), 
          },
          methods : {
              ...mapMutations('gajih_stores', ['CLEAR_FORM']),    
                ...mapActions('gajih_stores', ['update_gajih', 'get_gajih']),            
              proses (ev) {
                
                  ev.preventDefault()
                  this.update_gajih().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_gajih();
                        //close modal
                        this.$bvModal.hide('edit-gajih')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1700);

                  } ); //this submit                                                   
              },
              
            hapus_penerimaan(index) {
                          this.ket_penerimaan.splice(index, 1);
                            this.jumlahkan_penerimaan()
              },
            tambah_penerimaan() {
                  this.ket_penerimaan.push({
                          uraian: '',
                          total: parseFloat(0),
                          editing: false,
                      });                    
              },
                 
            hapus_pengurangan(index) {
                          this.ket_pengurangan.splice(index, 1);
                            this.jumlahkan_pengurangan()
              },
            tambah_pengurangan() {
                  this.ket_pengurangan.push({
                          uraian: '',
                          total: parseFloat(0),
                          editing: false,
                      });
              },
              jumlahkan_penerimaan (hanya_kosong = '') {                
                  var tes = hanya_kosong;
                //menjumlahkan bagian total pada ket penerimaan
                  const totalSum = this.ket_penerimaan.reduce((sum, item) => parseFloat(sum) + parseFloat(item.total), 0);
                  this.gajih.penerimaan = parseFloat(totalSum) + parseFloat(this.gajih.gajih_pokok)
              },
              jumlahkan_pengurangan (hanya_kosong = '') {                
                  var tes = hanya_kosong;
                //menjumlahkan bagian total pada ket pengurangan
                  const totalSum = this.ket_pengurangan.reduce((sum, item) => parseFloat(sum) + parseFloat(item.total), 0);
                  this.gajih.pengurangan = parseFloat(totalSum) 
              },

          }
      }
</script>

