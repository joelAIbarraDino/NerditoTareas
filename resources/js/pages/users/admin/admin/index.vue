<script setup lang="ts">
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, User } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Administradores',href: '#'}]
const columnsName = ['Nombre', 'WhatsApp', 'Correo', 'Acciones'];

interface adminPageProps extends AppPageProps{
    admins: User[]
}

const page = usePage<adminPageProps>();

const admins = computed(() => page.props.admins);
const flash = computed(() => page.props.flash);

const deleteAdmins = async(id:number)=>{
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
    <Head title="Administradores"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/admins/create" text="Nuevo administrador"/>

            <TableRecords caption="Lista de administradores" :columns-head="columnsName">
                <TableRow v-for="admin in admins":for="admin.id">
                    <TableCell>{{ admin.name }}</TableCell>
                    <TableCell>
                        <Link :href="`https://wa.me/52${admin.whatsapp}`" class="hover:underline hover:text-green-600">
                            Abrir WhatsApp
                        </Link> 
                    </TableCell>
                    <TableCell>{{ admin.email }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/admins/${admin.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteAdmins(admin.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>

    </AppLayout>
</template>