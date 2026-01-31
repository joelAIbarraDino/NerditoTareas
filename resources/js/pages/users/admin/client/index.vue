<script setup lang="ts">
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, Client } from '@/types';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { Pencil, Trash } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Clientes', href: '#'}]
const columnsName = ['Nombre', 'WhatsApp', 'Email', 'Nivel de estudios', 'Acciones'];

interface adminPageProps extends AppPageProps{
    clients: Client[]
}

const page = usePage<adminPageProps>();

const clients = computed(() => page.props.clients);
const flash = computed(() => page.props.flash);

const deleteClient = async(id:number)=>{
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

    router.delete(`/clients/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        }
    });
};


</script>

<template>
    <Head title="Clientes"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/clients/create" text="Nuevo cliente"/>

            <TableRecords caption="Lista de clientes" :columns-head="columnsName">
                <TableRow v-for="client in clients":for="client.id">
                    <TableCell>{{ client.user?.name??'Sin nombre' }}</TableCell>
                    <TableCell>
                        <Link :href="`https://wa.me/52${client.user.whatsapp}`" class="hover:underline hover:text-green-600">
                            Abrir WhatsApp
                        </Link>
                    </TableCell>
                    <TableCell>{{ client.user?.email ?? 'Sin correo especificado' }}</TableCell>
                    <TableCell>
                        <div class="bg-blue-600 text-center text-white font-black rounded-sm w-8/10">
                            {{ client.educational_level??'Nivel de estudios no especificado' }}
                        </div>
                    </TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/clients/${client.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteClient(client.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>

    </AppLayout>
</template>