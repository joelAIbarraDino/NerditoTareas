<script setup lang="ts">
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, TypeHomework } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Tipo de tareas',href: '#'}]
const columnsName = ['Nombre', 'Acciones'];

interface adminPageProps extends AppPageProps{
    typesHomework: TypeHomework[]
}

const page = usePage<adminPageProps>();

const typesHomework = computed(() => page.props.typesHomework);
const flash = computed(() => page.props.flash);

const deleteTypeHomework = async(id:number)=>{
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if(!result.isConfirmed)return;

    router.delete(`/type-homework/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        }
    });
};


</script>

<template>
    <Head title="Tipo de tareas"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/type-homework/create" text="Nuevo tipo de tarea"/>

            <TableRecords caption="Lista de tipos de tareas" :columns-head="columnsName">
                <TableRow v-for="typeHomework in typesHomework":for="typeHomework.id">
                    <TableCell>{{ typeHomework.name }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/type-homework/${typeHomework.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteTypeHomework(typeHomework.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>

    </AppLayout>
</template>z