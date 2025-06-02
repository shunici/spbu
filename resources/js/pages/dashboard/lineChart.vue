<template>
  <div>
    <bar-chart :chart-data="datacollection" :options="chartOptions"></bar-chart>
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js';

// Registrasi komponen chart yang diperlukan
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  components: {
    BarChart: Bar, // Komponen Bar Chart
  },
  data() {
    return {
      datacollection: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June'],
        datasets: [
          {
            label: 'Sales 2023',
            backgroundColor: '#42A5F5',
            borderColor: '#1E88E5',
            data: [40, 55, 75, 81, 56, 55],
          },
          {
            label: 'Sales 2022',
            backgroundColor: '#9CCC65',
            borderColor: '#7CB342',
            data: [50, 65, 85, 95, 66, 75],
          },
        ],
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },
};
</script>

<style scoped>
div {
  height: 400px;
  width: 600px;
}
</style>
