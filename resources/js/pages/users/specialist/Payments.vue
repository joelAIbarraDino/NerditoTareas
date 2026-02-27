<script setup lang="ts">

import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle} from '@/components/ui/empty'
import { AppPageProps, BreadcrumbItem, Homework, paymentSpecialist } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { Receipt } from 'lucide-vue-next';

interface specialistPageProps extends AppPageProps{
    payments: paymentSpecialist[]
}

const page = usePage<specialistPageProps>();
const payments = computed(() => page.props.payments);
const breadcrumbs: BreadcrumbItem[] = [{title: 'Mis pagos',href: '#'}];

const formatearMoneda = (numero:number) => {
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN', // Cambiar a 'USD', 'EUR', etc.
    minimumFractionDigits: 2
  }).format(numero);
};

const formatDate = (date:string) => {

    const isoStr = date.replace(" ", "T");

    const dateObject = new Date(isoStr);

    return dateObject.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}


</script>

<template>
    <Head title="Mi espacio"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-2">
            <p class="text-2xl font-bold text-primary mb-4">Historial de pagos</p>

            <div class="flex flex-col gap-4" v-if="payments.length > 0" >    
                <div class="p-2 rounded border shadow-md w-full" :class="payment.type === 'Penalización'?'bg-red-400/50':'bg-primary/10'" v-for="payment in payments":for="payment.id">         
                    <div class="flex items-start justify-start text-md mb-2 border-b">
                        <p class="font-black">{{ payment.type}}</p>
                    </div>
                    
                    <div class="flex text-md items-start md:items-center justify-between md:flex-row flex-col mb-1 md:mb-0">
                        <p class="font-bold">Tarea:</p>
                        <p class="font-bold">{{ payment.homework.name }} </p>
                    </div>

                    <div class="flex text-md items-start md:items-center justify-between md:flex-row flex-col mb-1 md:mb-0">
                        <p class="font-bold">Monto:</p>
                        <p class="font-bold">{{ formatearMoneda(payment.amount) }} </p>
                    </div>

                    <div class="flex text-md items-start md:items-center justify-between md:flex-row flex-col mb-1 md:mb-0">
                        <p class="font-bold">{{ payment.type}} {{ payment.type == 'Penalización'?'registrada':'registrado' }} el:</p>
                        <p class="font-bold">{{ formatDate(payment.created_at) }} </p>
                    </div>
                </div>  
            </div>

            <Empty v-else>
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <Receipt />
                    </EmptyMedia>
                    <EmptyTitle>No hay pagos registrados</EmptyTitle>

                    <EmptyDescription>
                        Pronto se realizara el pago de tus tareas realizadas
                    </EmptyDescription>
                </EmptyHeader>
            </Empty>

        </div>
    </AppLayout>
</template>