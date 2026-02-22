<script setup lang="ts">

import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle} from '@/components/ui/empty'
import { AppPageProps, BreadcrumbItem, Homework } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { PartyPopper } from 'lucide-vue-next';
import { HomeworkCard } from '@/components/homeworkCard';

interface specialistPageProps extends AppPageProps{
    homework: Homework[]
}

const page = usePage<specialistPageProps>();
const homeworkArray = computed(() => page.props.homework);
const breadcrumbs: BreadcrumbItem[] = [{title: 'Mis tareas',href: '#'}]

</script>

<template>
    <Head title="Mi espacio"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 space-y-2">
            <p class="text-2xl font-bold text-primary mb-4">Mis tareas asignadas</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" v-if="homeworkArray.length > 0">    
                <HomeworkCard v-for="homework in homeworkArray":for="homework.id" :homework="homework"/>
            </div>

            <Empty v-else>
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <PartyPopper />
                    </EmptyMedia>
                    <EmptyTitle>No hay tareas asignadas</EmptyTitle>

                    <EmptyDescription>
                        Espera a que un administrador te asigne una tarea
                    </EmptyDescription>
                </EmptyHeader>
            </Empty>

        </div>
    </AppLayout>
</template>