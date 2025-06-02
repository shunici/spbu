<template>  
 <b-modal size="xl" id="add-user" ref="add-user" title="Tambah User">  
 
        <div class="form-group">
            <label for="nama" class="text-uppercase">Nama</label>
            <input   type="text" id="nama" class="form-control" placeholder="Nama" v-model="user.name">        
        </div>  
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>

               
        <div class="form-group">
            <label for="hp"  class="text-uppercase" >Telp/WA</label>                      
            <PhoneInput v-model="user.hp" id="hp" placeholder="62813-XXXX-XXXX"             />           
        </div>

        <div class="form-group">
            <label for="email" class="text-uppercase">email</label>
            <input   type="email" id="email" class="form-control" placeholder="email" v-model="user.email">        
        </div>  
                <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        
        <div class="form-group">
            <label for="password" class="text-uppercase">password</label>
            <input   type="text" id="password" class="form-control" placeholder="password" v-model="user.password">        
        </div>  
    
        <div class="form-group">
            <label for="alamat" class="text-uppercase">alamat</label>             
            <textarea name="" id="" cols="30" rows="10" class="form-control" v-model="user.alamat"></textarea>     
        </div>  
        
        <div class="form-group">
          <label for="nomor" class="text-uppercase">Nomor Urut</label>
          <input type="number" id="nomor" class="form-control" v-model="user.nomor_urut">
        </div>

         <div class="form-group">
          <label for="jabatan" class="text-uppercase">Jabatan</label>
          <v-select v-model="selected_jabatan" :options="jabatan"></v-select>        
        </div>
            <p class="text-danger" v-if="errors.jabatan_id">{{ errors.jabatan_id[0] }}</p>
        
        <div class="form-group">      
                <label for="status" class="text-uppercase">Status</label>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="user" id="" :value="1" v-model="user.status" checked> Aktif
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="user" id="" :value="0" v-model="user.status"> Non Aktif
                  </label>
                </div>
         </div>
             
        <div class="form-group">
            <img class="profile-user-img mt-3" :src="user.foto" alt="" width="100">
        </div>

        <div class="form-group">
           <label for="foto_utama" class="text-uppercase">Foto</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="exampleInputFile" @change="uploadImage($event)">
                  <label class="custom-file-label" for="exampleInputFile">Pilih Foto</label>
              </div>
        </div>
        </div>
        <div class="form-group">
          <label for="gaji" class="text-uppercase">Gaji Pokok</label>
          <input type="text" class="form-control" disabled :value="selected_jabatan.gajih_pokok | currency">
        </div>


  <template #modal-footer>
        <div class="w-100">         
          <b-button
            variant="primary"
            size="sm"
            class="float-right"
            @click="proses"      
            @dblclick="dobel"   
            :disabled="pesan.sukses"         
          >                          
            <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
             {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}}   
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('edit-user')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
import PhoneInput from "../../components/PhoneInput.vue";
import vSelect from 'vue-select';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {
                  selected_jabatan: this.$store.state.user_stores.selected_jabatan,                 
              }
          },
          components : {
              vSelect, PhoneInput
          },
          watch : {
            selected_jabatan() {
               this.user.jabatan_id = this.selected_jabatan.value
            }
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('user_stores', {                 
                    user: state => state.user,
                    pesan : state => state.pesan
                }),
                
                ...mapState('jabatan_stores', {                 
                    jabatan: state => state.userJabatan,                 
                }),
          },
          methods : {
                ...mapMutations('user_stores', ['CLEAR_FORM', 'SHOW_FOTO', 'SIMPAN_FOTO']),
                ...mapActions('user_stores', ['submit_user', 'get_user']),            
              proses (ev) {
                  ev.preventDefault()
                  this.submit_user().then(() => {
                          this.pesan.sukses = true
                     setTimeout(function () {
                        this.get_user();
                        //close modal                   
                        this.$bvModal.hide('add-user')
                        this.CLEAR_FORM();
                         this.pesan.sukses = false
                    }.bind(this), 1500);

                  } ); //this submit                                                   
              },                                  
              uploadImage(event) {  
                  let poto = event.target.files[0];   //file yang dikirim ke db               
                  let tampil_foto =   URL.createObjectURL(poto)            
                this.SHOW_FOTO(tampil_foto) //ini kirim ke vuex utk citra
                 this.SIMPAN_FOTO(poto) //ini simpan db
              
              },               
              dobel(){
                alert('sedang diproses....');
              },
          }
      }
</script>

