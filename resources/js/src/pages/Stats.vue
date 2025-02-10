<script setup>
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';

const chartData = ref(null);

onMounted(() => {
  const ctx = document.getElementById('statsChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Claims', 'Approved', 'Pending', 'Rejected'],
      datasets: [{
        label: 'Claims Statistics',
        data: [12, 19, 3, 5],
        backgroundColor: [
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(255, 99, 132, 0.2)'
        ],
        borderColor: [
          'rgba(75, 192, 192, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(255, 99, 132, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Statistics Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Chart Container -->
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
        <canvas id="statsChart"></canvas>
      </div>
      
      <!-- Summary Cards -->
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-emerald-100 dark:bg-emerald-800 p-4 rounded-lg">
          <h3 class="font-bold">Total Claims</h3>
          <p class="text-2xl">39</p>
        </div>
        <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
          <h3 class="font-bold">Approved</h3>
          <p class="text-2xl">19</p>
        </div>
        <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
          <h3 class="font-bold">Pending</h3>
          <p class="text-2xl">3</p>
        </div>
        <div class="bg-red-100 dark:bg-red-800 p-4 rounded-lg">
          <h3 class="font-bold">Rejected</h3>
          <p class="text-2xl">5</p>
        </div>
      </div>
    </div>
  </div>
</template>
