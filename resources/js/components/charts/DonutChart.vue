<script setup lang="ts">
import { 
  Chart as ChartJS, 
  ArcElement, 
  Tooltip, 
  Legend, 
  type ChartData, 
  type ChartOptions 
} from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import { computed } from 'vue'

ChartJS.register(ArcElement, Tooltip, Legend)

const props = defineProps<{
  data: { network: string, count: number }[]
}>()

const chartData = computed<ChartData<'doughnut'>>(() => ({
  labels: props.data.map(item => item.network),
  datasets: [
    {
      backgroundColor: [
        '#3b82f6', // Blue (Facebook/LinkedIn)
        '#ec4899', // Pink (Instagram)
        '#10b981', // Green (WhatsApp/Otros)
        '#f59e0b', // Amber
        '#6366f1'  // Indigo
      ],
      hoverOffset: 10,
      borderWidth: 2,
      borderColor: 'transparent',
      data: props.data.map(item => item.count),
    },
  ],
}))

const chartOptions: ChartOptions<'doughnut'> = {  
    responsive: true,
    maintainAspectRatio: false,
    cutout: '80%', // Esto define el grosor de la dona
        plugins: {
            legend: {
                position:'bottom',
                labels:{
                    usePointStyle:true,
                    padding:10,
                    font:{
                        size:12,
                    },
                },
            },
    },
    layout: {
        padding: 10 // Añade un respiro interno para que el borde no toque el canvas
    },
}
</script>

<template>
  <div class="flex flex-col h-[450px] w-full p-6 bg-card border rounded-xl shadow-md">
    
    <div class="mb-4">
      <h3 class="text-lg font-bold">Origen de Clientes</h3>
    </div>

    <div class="flex-1 relative min-h-0"> 
      <Doughnut :data="chartData" :options="chartOptions" />
    </div>

  </div>
</template>