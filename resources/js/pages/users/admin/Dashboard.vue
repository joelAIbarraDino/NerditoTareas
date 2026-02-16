<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { AreaChart, DonutChart } from '@/components/charts';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const monthlyData = [
  { month: 'Ene', ingresos: 2400 },
  { month: 'Feb', ingresos: 3000 },
  { month: 'Mar', ingresos: 2000 },
  { month: 'Abr', ingresos: 2780 },
  { month: 'May', ingresos: 1890 },
  { month: 'Jun', ingresos: 2390 },
];


const socialData = [
  { network: 'Instagram', count: 450 },
  { network: 'Facebook', count: 300 },
  { network: 'TikTok', count: 250 },
  { network: 'WhatsApp', count: 120 },
  { network: 'LinkedIn', count: 80 },
];

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="">
            <div class="p-4">
                <AreaChart 
                    :data="monthlyData" 
                    index="month" 
                    category="ingresos" 
                />
            </div>

            <div class="p-4 grid gap-4 grid-cols-1 md:grid-cols-3">
                <DonutChart :data="socialData" />
                <DonutChart :data="socialData" />
                <DonutChart :data="socialData" />
            </div>
        </div>
        
    </AppLayout>
</template>
