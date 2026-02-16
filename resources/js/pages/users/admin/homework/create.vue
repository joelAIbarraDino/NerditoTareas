<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue} from '@/components/ui/select'
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { SimpleEditor } from '@/components/textEditor';
import { LoadingOverlay } from '@/components/overlay';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { AppPageProps, BreadcrumbItem, Client, Enum, Specialist, TypeHomework } from '@/types';

import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Tareas", href:"/homework"},
    {title:"Crear", href:"#"}
];

interface homeworkPageProps extends AppPageProps{
    typesHomework: TypeHomework[];
    specialists: Specialist[];
    clients:Client[];
    conversions:Enum[]
}

const page = usePage<homeworkPageProps>();
const typesHomework = computed(()=>page.props.typesHomework);
const clients = computed(()=>page.props.clients);
const specialists = computed(()=>page.props.specialists);
const conversions = computed(()=>page.props.conversions);

const form = useForm({
    name:'',
    description:'',
    client:'',
    conversion:'',
    type_homework:'',
    client_delivery:'',
    specialist_delivery:'',
    drive_link:'',
    specialist:'',
    final_price:'',
    specialist_payment:'',
});

function submit(){
  form.post('/homework',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Nueva tarea"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Nueva tarea" return-url="/homework"/>
        <RecordFormBody  :handle="submit">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 space-y-2 md:space-y-0">
                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label for="area">Cliente</Label>

                        <Select v-model="form.client" class="w-full" id="area">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Seleccione a un cliente"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Clientes</SelectLabel>
                                    <SelectItem v-for="client in clients":key="client.id" :value="client.id">{{ client.user?.name??'Sin nombre registrado' }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>
                        <InputError class="mt-1" :message="form.errors.client" />
                    </div>
                </div>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label for="area">¿Como se entero el cliente de Nerdito?</Label>

                        <Select v-model="form.conversion" class="w-full" id="area">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Seleccione la conversión"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Conversión</SelectLabel>
                                    <SelectItem v-for="conversion in conversions":key="conversion.value" :value="conversion.value">{{ conversion.label }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>
                        <InputError class="mt-1" :message="form.errors.conversion" />
                    </div>
                </div>


            </div>

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                        <Label for="name">Tarea</Label>
                        <Input
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            placeholder="Nombre del administrador"
                        />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>
                </div>
            
            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="description">Descripción o instrucciones de la tarea</Label>
                    <SimpleEditor 
                        v-model="form.description" 
                        id="description"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-2">
                        <Label for="area">Tipo de tarea</Label>

                        <Select v-model="form.type_homework" class="w-full" id="area">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Seleccione el tipo de tarea"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Tipo de tareas</SelectLabel>
                                    <SelectItem v-for="typeHomework in typesHomework":key="typeHomework.id" :value="typeHomework.id">{{ typeHomework.name }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>
                        <InputError class="mt-1" :message="form.errors.type_homework" />
                    </div>
                </div>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-2">
                        <Label for="area">Especialista (opcional)</Label>

                        <Select v-model="form.specialist" class="w-full" id="area">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Seleccione un especialista"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Especialistas</SelectLabel>
                                    <SelectItem v-for="specialist in specialists":key="specialist.id" :value="specialist.id">{{ specialist.user?.name??'Sin nombre registrado' }}</SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>
                        <InputError class="mt-1" :message="form.errors.specialist" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex-1 grid gap-2">
                    <Label for="client_delivery">Entrega al cliente</Label>
                    <Input
                        type="datetime-local"
                        id="client_delivery"     
                        v-model="form.client_delivery"
                    />
                    <InputError class="mt-1" :message="form.errors.client_delivery" />
                </div>

                <div class="flex-1 grid gap-2">
                    <Label for="specialist_delivery">Entrega del especialista</Label>
                    <Input
                        type="datetime-local"
                        id="specialist_delivery"     
                        v-model="form.specialist_delivery"
                    />
                    <InputError class="mt-1" :message="form.errors.specialist_delivery" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex-1 grid gap-2">
                    <Label for="specialist_payment">Pago al especialista</Label>
                    <Input
                        type="number"
                        id="specialist_payment"     
                        v-model="form.specialist_payment"
                    />
                    <InputError class="mt-1" :message="form.errors.specialist_payment" />
                </div>

                <div class="flex-1 grid gap-2">
                    <Label for="final_price">Precio final</Label>
                    <Input
                        type="number"
                        id="final_price"     
                        v-model="form.final_price"
                    />
                    <InputError class="mt-1" :message="form.errors.final_price" />
                </div>
            </div>
            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>