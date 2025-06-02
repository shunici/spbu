<template>
<div class="card">
    <div class="card-header">
        <h4>Form Inputan</h4>
    </div>
    <div class="card-body">   
          
        <div class="form-group row">
            <label for="inputName" class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9">
                <input type="text" v-model="auth.name" class="form-control" id="inputName" placeholder="Name">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
                <input type="email" v-model="auth.email" class="form-control" id="email" placeholder="Email" disabled>
            </div>
        </div>   
       <!-- auth.roles[0].name -->
        <div class="form-group row">
            <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
            <div class="col-sm-9">
                <input id="jabatan" type="text" :value="auth.jabatan"  class="form-control"  disabled>
            </div>
        </div> 
         
        <div class="form-group row">
            <label for="aktif" class="col-sm-3 col-form-label">Status Kepengurusan</label>
            <div class="col-sm-9">
                <span class="badge badge-primary">Aktif</span>
            </div>
        </div>   
            
        <div class="form-group row">
            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
            <div class="col-sm-9">
                <input  type="text" v-model="auth.alamat" class="form-control" id="alamat" placeholder="alamat">
            </div>
        </div> 
           
        <div class="form-group row">
            <label for="hp" class="col-sm-3 col-form-label">Telp/Hp</label>
            <div class="col-sm-9">              
                <PhoneInput
                      v-model="auth.hp"                  
                      id="hp"
                      placeholder="62813-XXXX-XXXX"
                    />  
            </div>
        </div>

        <div class="form-group row">
            <label for="deskripsi" class="col-sm-3 col-form-label">Foto</label>
            <div class="col-sm-9">
               <div class="custom-file">
                    <input type="file" class="custom-file-input" id="exampleInputFile" @change="uploadImage($event)">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                </div>
            </div>
        </div>    

        <div class="form-group">
            <div class="offset-sm-3 col-sm-9">
                <button  class="btn btn-primary float-left" v-on:click.prevent="simpan">Save</button>
                <b-button  class="float-right" variant="outline-success"  v-b-modal.modal-1>Ubah Password</b-button>
            </div>
        </div>        
  <b-modal id="modal-1" title="Ubah Password" hide-footer>
      <div class="form-group">
          <label for="psi">Password Saat ini</label>
          <input type="text" id="psi" class="form-control" placeholder="password" v-model="data.current_password">
                     <p class="text-danger" v-if="errors.current_password">
                         <!-- {{ errors.current_password[0] }} -->
                        <i> Password Saat ini wajib diisi</i>
                     </p>
      </div>
      <div class="form-group">
          <label for="psb">Password Baru</label>
          <input type="text" id="psb" class="form-control" placeholder="Password Baru" v-model="data.new_password">
           <p class="text-danger" v-if="errors.new_password">
                         <!-- {{ errors.new_password[0] }} -->
                        <i> Password baru wajib diisi</i>
                     </p>
      </div>
      
      <div class="form-group">
          <label for="psb1">Konfirmasi Password Baru</label>
          <input type="text" id="psb1" class="form-control" placeholder="Password Baru" v-model="password2">
            <p class="text-danger" v-if="salah">Password tidak sama</p>      
      </div>

      
<!--  
    <p v-for="error in errors" :key="error" class="text-sm">
      {{ error }}
    </p> -->

      <b-button class="mt-3" variant="outline-success" block @click="ubahPassword">Proses</b-button>
  </b-modal>
            
    </div>
</div>
</template>

<script>
import {mapState, mapMutations, mapActions} from 'vuex'
import PhoneInput from "../../components/PhoneInput.vue";
export default {
    name:"formComp",
    data(){
        return {
            data : {
                current_password : '',
                new_password : '',
            },         
            password2 : '', //duplikasi new password
            salah : false,


        }
    },
    components: {
           PhoneInput
          },
    mounted(){
        console.info('tes', this.$store.state.user.authenticated.permission)
    },
    computed : {
          	...mapState(['errors']),   
        ...mapState('user', {           
             auth : state => state.authenticated,   
            foto_db : state=> state.foto_db, //data untuk v-model bekerja       
        }),               
    },
    methods : {
          ...mapMutations('user', ['SHOW_FOTO', 'SIMPAN_FOTO']),
          ...mapActions('user', ['update_user', 'ubah_password']),
            ...mapMutations(['CLEAR_ERRORS']),  
          uploadImage(event) {  //interaksi vuex utk tampil foto di undangan.vue
              let poto = event.target.files[0];   //file yang dikirim ke db               
              let tampil_foto =   URL.createObjectURL(poto)            
            this.SHOW_FOTO(tampil_foto) //ini kirim ke vuex utk citra
           this.SIMPAN_FOTO(poto) //ini simpan db             
          }, 
          simpan(){
            let form = new FormData();
            form.append('name', this.auth.name)                
            form.append('foto', this.foto_db)     
            form.append('alamat', this.auth.alamat)  
              form.append('hp', this.auth.hp)  
                this.update_user(form);                     
          },
          ubahPassword(){
              
             let ps1 = this.data.new_password;
             let ps2 = this.password2; 
                this.data.password_confirmation = ps2; 
           if(ps1 === ps2){
                this.ubah_password(this.data).then(() => {                         
                    this.CLEAR_ERRORS()  
                  
                    //   this.$router.push({ name: 'home' })                  
                                 
                 })
           }
          },
    },
    watch : {
        
        password2(){
             let ps1 = this.data.new_password;
             let ps2 = this.password2;               
             if(ps1 != ps2) {                 
                    this.salah = true
             }else {
                    this.salah = false
             }
        }
    }
  
}
</script>