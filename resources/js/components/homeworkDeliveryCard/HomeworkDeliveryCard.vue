<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { File, Landmark, Package } from 'lucide-vue-next';
import { Homework } from '@/types';

interface homeworkProps {
    homework: Homework;
}

const props = defineProps<homeworkProps>();

const pedingPage = props.homework.final_price - props.homework.amount_paid;
const formatDate = (date:string) => {

    const isoStr = date.replace(" ", "T");

    const dateObject = new Date(isoStr);

    return dateObject.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}


const formatHour = (date:string) => {
    const isoStr = date.replace(" ", "T");

    const dateObject = new Date(isoStr);

    return dateObject.toLocaleTimeString('es-MX', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}


</script>

<template>
    <div class="bg-primary/5 p-2 rounded border shadow-md transition">         
        <div class="flex items-start justify-between text-md mb-2 border-b">
            <p class="font-black">{{ homework.name }}</p>
            <p>#{{ homework.order_id }}</p>
        </div>
        
        <div class="flex text-md items-center justify-between">
            <p class="font-bold">Fecha de entrega:</p>
            <p class="font-bold">{{ formatDate(homework.specialist_delivery) }} </p>
        </div>

        <div class="flex text-md items-center justify-between">
            <p class="font-bold">Hora de entrega:</p>
            <p class="font-bold">{{ formatHour(homework.specialist_delivery) }} </p>
        </div>

        <Button as-child size="lg" class="w-full mt-4" v-if=" homework.status =='Completado' && pedingPage <= 0">
            <a :href="homework.drive_link" target="_blank">Ver entrega   <Package /> </a>
        </Button>

        <Button as-child size="lg" class="w-full mt-4 bg-amber-300 hover:bg-amber-300/80 transition text-black" v-else-if="pedingPage > 0">
            <a :href="`/client/${homework.order_id}/payment`"><Landmark /> Pagar monto pendiente: <span class="font-black text-red-600">$ {{  pedingPage }}</span> </a>
        </Button>

        <p v-else class="w-full bg-primary text-white text-center rounded mt-4 p-2 text-md font-bold">
            Esta en proceso tu tarea...
        </p>

    </div>  
</template>