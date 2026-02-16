<script setup lang="ts">
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, Homework, Specialist } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash, Landmark } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Tareas',href: '#'}]
const columnsName = ['Order ID', 'Order ID Publico', 'Tarea', 'Cliente', 'Status', 'Especialista', 'Acciones'];

interface adminPageProps extends AppPageProps{
    homework: Homework[];
    specialists : Specialist[];
}

const page = usePage<adminPageProps>();

const homeworkArray = computed(() => page.props.homework);
const specialists = computed(()=> page.props.specialists);

const flash = computed(() => page.props.flash);

const updateSpecialist = (homeworkID: number, specialistID:number) =>{
    router.patch(`/homework/${homeworkID}/assign`, {
        specialist_id: specialistID
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Opcional: una notificación pequeña (Toast)
            console.log('Especialista actualizado');
        },
        onError: () => {
            Swal.fire('Error', 'No se pudo actualizar el especialista', 'error');
        }
    });
}

const deleteHomework = async(id:number)=>{
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

    router.delete(`/homework/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        }
    });
};


</script>

<template>
    <Head title="Administradores"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/homework/create" text="Nueva tarea"/>

            <TableRecords caption="Lista de tareas" :columns-head="columnsName">
                <TableRow v-for="homework in homeworkArray":for="homework.id">
                    <TableCell>{{ homework.private_order_id }}</TableCell>
                    <TableCell>{{ homework.order_id }}</TableCell>
                    <TableCell>{{ homework.name }}</TableCell>
                    <TableCell>{{ homework.client.user.name }}</TableCell>
                    <TableCell>{{ homework.status }}</TableCell>
                    <TableCell>
                        <select 
                            class="p-1 border rounded bg-transparent text-sm focus:ring-2 focus:ring-blue-500"
                            :value="homework.specialist?.id" 
                            @change="(e) => updateSpecialist(homework.id, Number((e.target as HTMLSelectElement).value))"
                        >
                            <option value="" disabled >Sin especialista</option>
                            <option 
                                v-for="spec in specialists" 
                                :key="spec.id" 
                                :value="spec.id"
                            >
                                {{ spec.user.name }}
                            </option>
                        </select>
                    </TableCell>
                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-amber-300"
                            hover="bg-amber-500"
                            :icon=Landmark
                            :action="`/mercado-pago/${homework.id}`"
                        />

                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/homework/${homework.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteHomework(homework.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>

    </AppLayout>
</template>