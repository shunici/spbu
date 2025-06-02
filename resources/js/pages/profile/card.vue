<template>
<div class="card card-dark bg-navy">
    <div class="card-body box-profile">
        <div class="text-center">        
          <img class="profile-user-img img-fluid img-circle" width="200"  :src="auth.foto ? auth.foto : 'img/user2-160x160.jpg'" alt="User profile picture"/>
        </div>
        <h3 class="profile-username text-center">{{auth.name}}</h3>
        <p class="text-center">{{auth.email}}</p>  
    </div>

</div>
</template>

<script>
import {mapState} from 'vuex'
export default {
    name:"cardComp",
    data(){
        return {
        
        }
    },
    computed : {
        ...mapState('user', {         
             auth : state => state.authenticated
        }),               
    },
  
}
</script>