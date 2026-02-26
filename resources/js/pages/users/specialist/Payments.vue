<script setup lang="ts">

import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle} from '@/components/ui/empty'
import { AppPageProps, BreadcrumbItem, Homework, paymentSpecialist } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { PartyPopper } from 'lucide-vue-next';

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
            <p class="text-2xl font-bold text-primary mb-4">Mis tareas asignadas</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" v-if="payments.length > 0" >    
                <div class="p-2 rounded border shadow-md hover:cursor-pointer hover:bg-primary/10 transition" :class="payment.type === 'Penalización'?'bg-red-400/50':'bg-primary/10'" v-for="payment in payments":for="payment.id">         
                    <div class="flex items-start justify-start text-md mb-2 border-b">
                        <p class="font-black">{{ payment.type}}</p>
                    </div>
                    
                    <div class="flex text-md items-center justify-between">
                        <p class="font-bold">Tarea:</p>
                        <p class="font-bold">{{ payment.homework.name }} </p>
                    </div>

                    <div class="flex text-md items-center justify-between">
                        <p class="font-bold">Monto:</p>
                        <p class="font-bold">{{ formatearMoneda(payment.amount) }} </p>
                    </div>

                    <div class="flex text-md items-center justify-between">
                        <p class="font-bold">Pago registrado el:</p>
                        <p class="font-bold">{{ formatDate(payment.created_at) }} </p>
                    </div>
                </div>  
            </div>

            <Empty v-else>
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <PartyPopper />
                    </EmptyMedia>
                    <EmptyTitle>No hay pagos registrados</EmptyTitle>

                    <EmptyDescription>
                        Espera a que un administrador te asigne una tarea
                    </EmptyDescription>
                </EmptyHeader>
            </Empty>

        </div>
    </AppLayout>
</template>