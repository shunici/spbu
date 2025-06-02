<template>
  <div>
 <b-carousel
      id="carousel-1"
      v-model="slide"
      :interval="4000"
      controls
      indicators
      background="#ababab"
      img-width="1024"
      img-height="480"
      class="mb-4"
      style="text-shadow: 1px 1px 2px #333;"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >
   
      <b-carousel-slide    
        :imgSrc="aplikasi.foto4"
      ></b-carousel-slide>   
      <b-carousel-slide    
        :imgSrc="aplikasi.foto3"
      ></b-carousel-slide>   
      <b-carousel-slide    
        :imgSrc="aplikasi.foto2"
      ></b-carousel-slide>   
 
    </b-carousel>
<p class="text-center mb-3">{{waktu.mHari}}, {{waktu.mTanggal}} {{waktu.mBulan}} {{waktu.mTahun}} M / {{waktu.hTanggal}} {{waktu.hBulan}} {{waktu.hTahun}} H </p>
  <blockquote :class="aplikasi.alert_warna" class="shadow" v-if="aplikasi.alert_tampil">
  <h5 id="tip">{{aplikasi.alert_judul}}</h5>
  <p>{{aplikasi.alert_isi}}</p>
</blockquote>

  </div>
</template>

<script>
import { mapState } from 'vuex'
import  {getMasehiHijriah}  from "../hijriahmasehi";


export default {
  data() {
    return {
      waktu: '',
      slide: 0,
        sliding: null,
    };
  },
   created() {
    this.waktu = getMasehiHijriah();
  },
  methods: {
     onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      },

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