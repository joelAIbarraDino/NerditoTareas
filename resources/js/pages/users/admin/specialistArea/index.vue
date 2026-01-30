<script setup lang="ts">
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, SpecialistArea, User } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Area de especialistas',href: '#'}]
const columnsName = ['Nombre', 'Acciones'];

interface adminPageProps extends AppPageProps{
    specialistAreas: SpecialistArea[]
}

const page = usePage<adminPageProps>();

const specialistAreas = computed(() => page.props.specialistAreas);
const flash = computed(() => page.props.flash);

const deleteSpecialistArea = async(id:number)=>{
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

    router.delete(`/admins/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        }
    });
};


</script>

<template>
    <Head title="Area de especialistas"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/specialist-area/create" text="Nueva area de especialistas"/>

            <TableRecords caption="Lista de area de especialistas" :columns-head="columnsName">
                <TableRow v-for="specialistArea in specialistAreas":for="specialistArea.id">
                    <TableCell>{{ specialistArea.name }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/specialist-area/${specialistArea.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteSpecialistArea(specialistArea.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>

    </AppLayout>
</template>