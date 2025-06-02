<template>
    

<div class="row  justify-content-center text-center">


  <!-- /.login-logo -->
  <div class="card shadow">
    <div class="card-header">
      <h4 class="text-uppercase text-center font-weight-bold">{{aplikasi.nama}}</h4>
      </div>

    <div class="card-body">
 
      <p>Masuk Kehalaman</p>

   
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" v-model="data.email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>          
        </div>
        <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password"  v-model="data.password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>           
        </div>
          <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" @click.prevent="postLogin">Masuk</button>
          </div>
          <!-- /.col -->
        </div>
   

      <!-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> -->
      <!-- /.social-auth-links -->
   <div class="alert alert-danger mt-2" v-if="errors.invalid">{{ errors.invalid }}</div>
    <p class="mb-0"> <router-link class="text-center" :to="{name : 'reset' }" > Lupa Password</router-link></p>
      <p class="mb-0" v-if="aplikasi.register_rule">
        <router-link class="text-center" :to="{ name: 'register' }">Daftar Akun Baru</router-link>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
</template>


<script>
import axios from 'axios'
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            data: {
                email: '',
                password: '',
                remember_me: false
            }
        }
    },
    created() {
        if (this.isAuth) {
            this.$router.push({ name: 'home' })
        }
    },
    computed: {
        ...mapGetters(['isAuth']),
        ...mapState(['errors']),        
        ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),
    },
    methods: {
        ...mapActions('auth', ['submit']),
        ...mapActions('user', ['getUserLogin']),
        ...mapMutations(['CLEAR_ERRORS']),
        postLogin() {
                    
            this.submit(this.data).then(() => {              
                if (this.isAuth) {
                    this.CLEAR_ERRORS()
                    this.$router.push({ name: 'home' })
                }
            })
            
                              
        }
    },
    destroyed() {
        this.getUserLogin()
    }
}
</script>
