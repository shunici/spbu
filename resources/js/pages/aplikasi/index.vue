<template>

        <div class="card pl-0 pr-0 p-md-3">            
            <div class="card-header">
                <h3>PROFIL APLIKASI</h3>
            </div>
            <div class="card-body ">
                <data-masjid></data-masjid>
                        <card-form></card-form>   
            </div>

        </div>
                   

      

</template>
<script>
  import { mapActions, mapState, mapMutations } from 'vuex'
import cardForm from './cardform.vue';
import dataMasjid from './dataMasjid.vue';


        export default {
            name : 'aplikasi-index',
      data() {
          return {
            
          }
      },
      components : {
          cardForm, dataMasjid
      },
     created() {
        this.get_aplikasi();            
    },
    methods : {
          ...mapActions('aplikasi_stores', ['get_aplikasi']), 
    }
      
    }
</script>
