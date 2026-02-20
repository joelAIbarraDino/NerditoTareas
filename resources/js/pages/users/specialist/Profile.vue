<script setup lang="ts">

import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle} from '@/components/ui/empty'
import { AppPageProps, Homework } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { PartyPopper } from 'lucide-vue-next';

interface specialistPageProps extends AppPageProps{
    homework: Homework[]
}

const page = usePage<specialistPageProps>();

const homeworkArray = computed(() => page.props.homework);

</script>

<template>
    <Head title="Mi espacio"/>
    <AppLayout>
        <div class="p-4 space-y-2">
            <p class="text-2xl font-bold text-primary mb-4">Mis tareas asignadas</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div v-if="homeworkArray.length > 0" class="bg-primary/5 p-2 rounded border shadow-md" v-for="homework in homeworkArray":for="homework.id" >
                    
                    <div class="flex items-center justify-between text-md mb-2 border-b">
                        <p class="font-black">Nombre de la tarea a realizar</p>
                        <p>#{{ homework.order_id }}</p>
                    </div>
                    
                    <div class="flex text-sm">
                        <p class="flex-1 font-bold">Fecha de entrega:</p>
                        <p class="flex-1">{{ homework.specialist_delivery }} </p>
                    </div>

                    <div class="flex text-sm">
                        <p class="flex-1 font-bold">Tipo de tarea:</p>
                        <p class="flex-1">{{ homework.type_homework.name }} </p>
                    </div>

                    <div class="flex text-sm">
                        <p class="flex-1 font-bold">Estatus:</p>
                        <p class="flex-1">{{ homework.status }}</p>
                    </div>

                    <div class="flex text-sm">
                        <p class="flex-1 font-bold">Pago:</p>
                        <p class="flex-1">$ {{ homework.specialist_payment }} </p>
                    </div>
                </div>

                <Empty v-else class="col-span-3">
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

        </div>
    </AppLayout>
</template>