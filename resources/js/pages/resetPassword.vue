<!-- HTML SECTION -->
<template>

       <div class="register-box bg-dark">
                <div class="register-logo">                  
                    <h2><b>Reset Password</b></h2>                    
                    <button type="button" class="btn btn-primary"  @click="$router.go(-1)"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>                                                   
        </div>

      <div v-if="loading" class="alert alert-primary alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                             <div class="spinner-border"></div> Tunggu Sebentar Permintaan {{pesan}} sedang diproses....
    </div>

  <div class="card">    
    <div class="card-body register-card-body">       

                    <div v-if="satu" class="contact-form bg-white">                     
                        <div class="form-group">
                            <label for="Email / NIP">Email / username</label>
                            <input  type="email" class="form-control" placeholder="Masukkan NIP / Email" v-model="email">     
                            <p v-if="error" class="text-danger"> <i>{{error}}</i></p>                     
                        </div>                                                                
                        <button type="submit" class="btn btn-primary btn-block" @click.prevent="proses">Proses</button>                                                                         
                        <div class="clearfix"><router-link :to="{name : 'login' }" > Login</router-link></div>
                    </div>
      

   
                    <div v-if="dua" class="contact-form bg-white">                     
                        <div class="form-group">
                            <label for="token">Token</label>
                            <input disabled  type="text" class="form-control"  v-model="token">     
                 
                        </div>                                                                
                        <button type="submit" class="btn btn-primary btn-block" @click.prevent="validasi_token">Kirim Token Konfirmasi</button>                                                                         

                    </div>


        
      
                     <div class="contact-form bg-white" v-if="tiga">                            
                       <h3>Password Baru</h3>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" v-model="password">
                        </div>    
                        <div class="form-group">
                            <label for="password2">Masukkan Lagi Password</label>
                            <input  :class="{'is-invalid' : salah}" type="password" class="form-control" v-model="password2">
                                    <p class="text-danger" v-if="salah">Password tidak sama</p>
                        </div>   
                    <button type="submit" class="btn btn-primary btn-block" @click.prevent="reset_password">Proses</button>                                                                          
                 
                
                </div>  
        
  
    
      <p class="mb-2">  <router-link class="text-center" :to="{name : 'login' }" > Saya Sudah Punya Akun</router-link></p>

    </div> <!-- cardbody -->    
  </div><!-- /.card -->
</div>

</template>

<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            email : '', 
             token : '',
             user_id : '',         
            password : '',
             password2 : '',
            salah : false,           
            error : '',
            pesan : '',
            satu : true,
            dua : false, 
            tiga : false,  
            loading : false
        }
    }, 
    //SEBELUM COMPONENT DI-RENDER
    created() {
     
    },
    computed: {        
      	...mapState(['errors']),
        ...mapState('aplikasi_stores', {
            aplikasi : state=> state.aplikasi
        }), //sudah di reload mapactionnya di app.vue
    },
    watch : {
        password2(){
             let ps1 = this.password;
             let ps2 = this.password2;            
             if(ps1 != ps2) {                 
                    this.salah = true
             }else {
                    this.salah = false
             }
        }
    },
    methods: {    
        ...mapActions('auth', ['submit', 'register']), //MENGISIASI FUNGSI submit() DARI VUEX AGAR DAPAT DIGUNAKAN PADA COMPONENT TERKAIT. submit() BERASAL DARI ACTION PADA FOLDER STORES/auth.js
        ...mapMutations(['CLEAR_ERRORS']),      
      	         	
        proses() {    
            this.loading = true;    
            this.pesan = 'verifikasi email';            
            setTimeout(function () {            
                this.loading = false;
                
                 let data = { email : this.email};
                axios.post('/api/send-token', data)
                .then( response => {                  
                    this.satu = false;
                     this.dua = true;
                    this.token = response.data.data
                })
                .catch(error => {
                    this.satu = true;
                 
                   this.error = error.response.data;
                });

            }.bind(this), 2500)
        },        
         validasi_token() {    
            this.loading = true;    
            this.pesan = 'token konfirmasi';   
           
            setTimeout(function () {            
                let data = { token : this.token}
                axios.post('/api/validasi-token', data)
                .then( response => {   
                      this.loading = false;       
                         this.dua = false;
                         this.tiga = true;
                    this.user_id = response.data
                })
                .catch(error => {        
                   this.error = error.response.data.errors;
                });
            }.bind(this), 2500)                
        },
        reset_password() {
            this.loading = true;    
            this.pesan = 'reset password';  
            setTimeout(function () {            
            let data = {user_id : this.user_id, password : this.password };
                axios.post('/api/reset-password', data)
                .then( response => { 
                    console.info('ress', response.data)
                   this.loading = false;               
                     $(document).Toasts('create', {title: 'Berhasil diproses..', body: 'Sekarang Masuk dengan password baru anda'}); 
                   this.$router.push({name : 'login'})
                })
                .catch(error => {        
                   this.error = error.response.data.errors;
                });
            }.bind(this), 2500)
              
        }
       
    }
}
</script>

<style>
  .register-page {
        z-index: 4000;
    position: relative;
    top: -111px;
  }
</style>