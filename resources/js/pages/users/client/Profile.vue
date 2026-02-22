<script setup lang="ts">

import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { AppPageProps, BreadcrumbItem, Homework } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import HomeworkDeliveryCard from '@/components/homeworkDeliveryCard/HomeworkDeliveryCard.vue';
import { ArrowUpRightIcon, Handshake, PartyPopper } from 'lucide-vue-next';

interface clientPageProps extends AppPageProps{
    homework: Homework[]
}

const page = usePage<clientPageProps>();
const homeworkArray = computed(() => page.props.homework);
const breadcrumbs: BreadcrumbItem[] = [{title: 'Mis tareas activas',href: '#'}]


</script>

<template>
    <Head title="Mi espacio"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <p class="text-2xl font-bold text-primary mb-4">Mis tareas en proceso</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" v-if="homeworkArray.length > 0">
                
                <HomeworkDeliveryCard v-for="homework in homeworkArray":for="homework.id" :homework="homework"/>
            </div>

            <Empty v-else>
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <PartyPopper />
                    </EmptyMedia>
                    <EmptyTitle>No hay tareas en proceso</EmptyTitle>

                    <EmptyDescription>
                        Si necesitas ayuda en una tarea, puedes realizar un pedido
                    </EmptyDescription>
                </EmptyHeader>
                <EmptyContent>
                    <div class="flex gap-2">
                        <Button as-child size="lg">
                            <a href="#">
                                Solicitar ayuda
                                <Handshake />
                            </a>
                        </Button>
                        <Button variant="outline" size="lg">
                            <a href="#">
                            Ver tareas anteriores
                            </a>
                        </Button>
                    </div>
                </EmptyContent>
                <Button as-child variant="link" >
                    <a href="#">
                        Contáctanos <ArrowUpRightIcon />
                    </a>
                </Button>
            </Empty>
        </div>
    </AppLayout>
</template>