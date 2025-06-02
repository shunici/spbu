<template>
  <div class="row">
<div class="col-12" v-html="aplikasi.tentang">
  
</div>
  
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
  data() {
    return {
     
    };
  },
  methods: {
    
  },
  computed : {
     ...mapState('aplikasi_stores', {   
          aplikasi : state => state.aplikasi,
        }),
  }
};
</script>

<style>



</style>
