<template>

 <div> 
    <b-card v-for="(item, index) in aplikasi.bantuan" :key="index" class="mb-3">
      <b-card-header class="bg-navy">
        <strong>{{ item.question }}</strong>
      </b-card-header>
      <b-card-body>
        <p>{{ item.answer }}</p>
      </b-card-body>
    </b-card>
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
