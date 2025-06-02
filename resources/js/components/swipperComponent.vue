<template>
  <div class="simple-swiper">
    <div class="swiper-container" :style="{ transform: `translateX(${translateX}px)` }">
      <div class="swiper-slide" v-for="(item, index) in items" :key="index">
        <slot :item="item"></slot>
      </div>
    </div>
    <button class="swiper-button-prev" @click="prevSlide">&#10094;</button>
    <button class="swiper-button-next" @click="nextSlide">&#10095;</button>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true,
    },
    cardWidth: {
      type: Number,
      default: 300, // Default width untuk card
    },
  },
  data() {
    return {
      currentIndex: 0,
    };
  },
  computed: {
    translateX() {
      return -this.currentIndex * this.cardWidth;
    },
  },
  methods: {
    nextSlide() {
      if (this.currentIndex < this.items.length - 1) {
        this.currentIndex++;
      }
    },
    prevSlide() {
      if (this.currentIndex > 0) {
        this.currentIndex--;
      }
    },
  },
};
</script>

<style scoped>
.simple-swiper {
  position: relative;
  width: 100%;
  overflow: hidden;
}

.swiper-container {
  display: flex;
  transition: transform 0.5s ease;
}

.swiper-slide {
  flex: 0 0 auto;
}

.swiper-button-prev,
.swiper-button-next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
}

.swiper-button-prev {
  left: 10px;
}

.swiper-button-next {
  right: 10px;
}
</style>