<script setup lang="ts">
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, Enum, Homework, Specialist } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { TableCell, TableRow } from '@/components/ui/table';
import { CurrencyInput } from '@/components/currencyMoney';
import { Pencil, Trash, Landmark } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { computed, ref } from 'vue';

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title: 'Tareas',href: '#'}]
const columnsName = ['Tarea', 'Cliente', 'Especialista', 'Cambios', 'Status', 'Acciones'];

interface adminPageProps extends AppPageProps{
    homework: Homework[];
    specialists : Specialist[];
    changeStatus : Enum[];
}

const page = usePage<adminPageProps>();

const homeworkArray = computed(() => page.props.homework);
const specialists = computed(()=> page.props.specialists);
const changeStatusArray = computed(() => page.props.changeStatus);

const flash = computed(() => page.props.flash);

const paymentForm = useForm({
    amount:null,
});

const generateLink = (homeworkID:number) =>{
    paymentForm.post(`/mercado-pago/${homeworkID}/payment-link`,{
    preserveScroll:true,
    onSuccess: () => paymentForm.reset()
  })
}

const updateSpecialist = (homeworkID: number, specialistID:number|null) =>{
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
};

const updateChanges = (homeworkID:number, changeStatus:string) =>{
    router.patch(`/homework/${homeworkID}/change`, {
        changeStatus: changeStatus
    }, 
    {
        preserveScroll:true,
        onSuccess: () => {
            console.log('Status actualizado');
        },
        onError : () => {
            Swal.fire('Error', 'No se pudo actualizar el status', 'error');
        }
    });
};


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
                    <TableCell>{{ homework.name }}</TableCell>
                    <TableCell>{{ homework.client.user.name }}</TableCell>
                    <TableCell>
                        <select 
                            class="p-1 border rounded bg-transparent text-sm focus:ring-2 focus:ring-blue-500"
                            :value="homework.specialist?.id ??''" 
                            @change="(e) => updateSpecialist(homework.id, Number((e.target as HTMLSelectElement).value) == 0?null:Number((e.target as HTMLSelectElement).value))"
                        >
                            <option value="">Sin especialista</option>
                            <option 
                                v-for="spec in specialists" 
                                :key="spec.id" 
                                :value="spec.id"
                            >
                                {{ spec.user.name }}
                            </option>
                        </select>
                    </TableCell>

                    <TableCell>
                        <select 
                            class="p-1 border rounded bg-transparent text-sm focus:ring-2 focus:ring-blue-500"
                            :value="homework.change" 
                            :disabled="homework.status === 'No asignado'"
                            @change="(e) => updateChanges(homework.id, (e.target as HTMLSelectElement).value)"
                        >
                            <option 
                                v-for="changeStatus in changeStatusArray"
                                :key="changeStatus.value" 
                                :value="changeStatus.value"
                            >
                                {{ changeStatus.label }}
                            </option>
                        </select>
                    </TableCell>
                    <TableCell>{{ homework.status }}</TableCell>
                    <TableActions>
                        <Sheet>
                            <SheetTrigger>
                                <Button size="sm" class="bg-amber-300 hover:bg-amber-500 text-white"  >
                                    <Landmark/>
                                </Button>
                            </SheetTrigger>

                            <SheetContent  class="
                                overflow-y-auto 
                                [&::-webkit-scrollbar]:w-1 
                                [&::-webkit-scrollbar-track]:bg-gray-100 
                                [&::-webkit-scrollbar-thumb]:bg-gray-300 
                                dark:[&::-webkit-scrollbar-track]:bg-neutral-700 
                                dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500"
                            >
                                <SheetHeader>
                                    <SheetTitle class="text-2xl mb-2">Centro de pagos</SheetTitle>
                                    <SheetDescription>
                                        <p class="text-md">Aqui podras ver los pagos, ordenes de pago y generar nuevos links de pago</p>
                                    </SheetDescription>
                                </SheetHeader>

                                <div class="px-4 flex flex-col">
                                    <div class="text-sm flex bg-primary/10 p-2">
                                        <p class="font-bold flex-1">Monto pagado:</p>
                                        <p class="flex-1 text-primary font-bold">$ {{ homework.amount_paid }}</p>
                                    </div>

                                    <div class="text-sm flex bg-primary/20 p-2">
                                        <p class="font-bold flex-1">Monto pendiente:</p>
                                        <p class="flex-1 font-bold" :class="(homework.final_price - homework.amount_paid) > 0?'text-orange-700':'text-lime-700'">$ {{ homework.final_price - homework.amount_paid }}</p>
                                    </div>
                                </div>

                                <form class="grid auto-rows-min gap-3 px-4 my-4" @submit.prevent="generateLink(homework.id)">
                                    <p class="text-sm font-bold border-b pb-1 mb-2">Generar Link de Mercado Pago</p>

                                    <div class="grid gap-2">
                                        <Label for="amount">Monto de pago</Label>
                                        <CurrencyInput
                                            id="amount"
                                            placeholder="Monto a pagar"
                                            v-model="paymentForm.amount"
                                        />
                                        
                                        <InputError class="mt-1" :message="paymentForm.errors.amount" />
                                    </div>
                                    
                                    <Button type="submit" class="bg-lime-600">Generar Link de pago</Button>
                                </form>

                                <div class="mx-4">
                                    <p class="text-sm font-bold border-b pb-1 mb-2 last:mb-0">Links de Mercado Pago</p>

                                    <div v-if="homework.order_payments.length > 0" class="p-2 bg-primary/10 rounded my-3 mx-2 border-primary" v-for="orderPayment in homework.order_payments":key="orderPayment.id">
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">Monto:</p>
                                            <p class="flex-1 text-lime-700 font-bold">${{ orderPayment.amount }}</p>
                                        </div>
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">Link de MP:</p>
                                            <TextLink class="flex-1" :href="orderPayment.mp_link">Link de Mercado Pago</TextLink>
                                        </div>
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">Estatus:</p>
                                            <p class="flex-1 font-bold">{{ orderPayment.status }}</p>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <p class="text-center text-sm font-bold mt-2 text-orange-400">No hay ordenes de pago</p>
                                    </div>

                                </div>

                                <div class="mx-4">
                                    <p class="text-sm font-bold border-b pb-1 mb-2">Pagos registrados</p>

                                    <div v-if="homework.payments.length > 0" class="p-2 bg-primary/10 rounded my-3 mx-2 border-primary" v-for="payment in homework.payments":key="payment.id">
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">Monto:</p>
                                            <p class="flex-1 text-lime-700 font-bold">${{ payment.amount }}</p>
                                        </div>
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">ID de Mercado Pago:</p>
                                            <p class="flex-1 font-bold">{{ payment.payment_id??'Sin ID de Mercado Pago' }}</p>
                                        </div>
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">Método de pago:</p>
                                            <p class="flex-1 font-bold">{{ payment.method }}</p>
                                        </div>
                                        <div class="text-sm flex">
                                            <p class="font-bold flex-1">Estatus:</p>
                                            <p class="flex-1 font-bold">{{ payment.status }}</p>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <p class="text-center text-sm font-bold mt-2 text-orange-400">No hay pagos registrados</p>
                                    </div>
                                </div>
                            </SheetContent>

                        </Sheet>
                        
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