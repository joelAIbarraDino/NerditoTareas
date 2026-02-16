<script setup lang="ts">
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Filler,
  Legend,
  type ChartOptions,
  type ChartData
} from 'chart.js'
import { Line } from 'vue-chartjs'
import { computed } from 'vue'

// Registramos los componentes necesarios de Chart.js
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Filler,
  Legend
)

const props = defineProps<{
  data: any[],
  index: string,     // El campo de los meses (e.g., 'month')
  category: string   // El campo de los ingresos (e.g., 'ventas')
}>()

// Transformamos los datos prop al formato que entiende Chart.js
const chartData = computed<ChartData<'line'>>(() => ({
  labels: props.data.map(item => item[props.index]),
  datasets: [
    {
      label: 'Ingresos Mensuales',
      backgroundColor: 'rgba(59, 130, 246, 0.2)', // El color de fondo (fill)
      borderColor: '#3b82f6',                     // El color de la línea (primary)
      borderWidth: 2,
      fill: true,                                 // Esto lo convierte en Area Chart
      tension: 0.4,                               // Suaviza la línea (curvatura)
      pointBackgroundColor: '#3b82f6',
      data: props.data.map(item => item[props.category]),
    },
  ],
}))

const chartOptions: ChartOptions<'line'> = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false, // Ocultamos la leyenda para un look más "shadcn"
    },
    tooltip: {
      mode: 'index',
      intersect: false,
    }
  },
  scales: {
    y: {
      grid: {
        color: 'rgba(156, 163, 175, 0.1)', // Líneas de cuadrícula sutiles
      },
      ticks: {
        callback: (value) => `$${value}` // Formato de moneda
      }
    },
    x: {
      grid: {
        display: false // Limpiamos el eje X
      }
    }
  }
}
</script>

<template>
  <div class="h-[350px] w-full p-4 bg-card text-card-foreground rounded-xl border shadow-sm">
    <div class="mb-4">
      <h3 class="text-lg font-bold tracking-tight">Ingresos</h3>
      <p class="text-sm text-muted-foreground">Resumen mensual de ventas registradas.</p>
    </div>
    <div class="h-[250px]">
      <Line :data="chartData" :options="chartOptions" />
    </div>
  </div>
</template>