<template>
    
<div class="row  justify-content-center text-center">

  <div class="card shadow">

    <div class="card-header">
      <h4 class="text-uppercase text-center font-weight-bold">{{aplikasi.nama}}</h4>
      </div>
    <div class="card-body">
{{errors}}
      <p class="login-box-msg">Mendaftar Akun Baru</p>
  <form action="#" method="post">

        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Masukkan Nama" v-model="data.name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" v-model="data.email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Masukkan Password" v-model="data.password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
                <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Ketik Ulang password"  v-model="password2">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
             <p class="text-danger" v-if="salah">Password tidak sama</p>      

      </form>
   
   <button type="submit" class="btn btn-primary btn-block mb-2" @click.prevent="daftar">Proses</button>
  <router-link class="text-center" :to="{name : 'login' }" > Saya Sudah Punya Akun</router-link>
          
    </div>
    <!-- /.card body -->
  </div><!-- /.card -->

</div>
</template>


<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            data: {
                name : '',
                email: '',
                password: '',   
                password_confirmation:'' 
                 
               
            },
             password2 : '',
              salah : false
        }
    },
    //SEBELUM COMPONENT DI-RENDER
    created() {     
      
     
    },
    computed: {
        ...mapGetters(['isAuth']), //MENGAMBIL GETTERS isAuth DARI VUEX
      	...mapState(['errors']),                 
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),           
    },
    watch : {
        password2(){
             let ps1 = this.data.password;
             let ps2 = this.password2;   
             this.data.password_confirmation = ps2;         
             if(ps1 != ps2) {                 
                    this.salah = true
             }else {
                    this.salah = false
             }
        }
    },
    methods: {    

        ...mapActions('auth', ['register']), //MENGISIASI FUNGSI submit() DARI VUEX AGAR DAPAT DIGUNAKAN PADA COMPONENT TERKAIT. submit() BERASAL DARI ACTION PADA FOLDER STORES/auth.js
        ...mapMutations(['CLEAR_ERRORS']),      
      	
        daftar() {  
             let ps1 = this.data.password;
             let ps2 = this.password2; 
           if(ps1 === ps2){
                this.register(this.data).then(() => {                         
                    this.CLEAR_ERRORS()  
                      this.$router.push({ name: 'home' })                  
                                 
                 })
           }

        }
    }
}
</script>
