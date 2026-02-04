<script setup lang="ts">
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { SimpleEditor } from '@/components/textEditor';
import { LoadingOverlay } from '@/components/overlay';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { AppPageProps, BreadcrumbItem, Client, Specialist, TypeHomework } from '@/types';

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
    clients:Client[]
}

const page = usePage<homeworkPageProps>();
const typesHomework = computed(()=>page.props.typeHomework);
const clients = computed(()=>page.props.clients);
const specialists = computed(()=>page.props.typeHomework);

const form = useForm({
    name:'',
    description:'',
    client:'',
    conversion:'',
    type_homework:'',
    client_delivery:'',
    specialist_delivery:'',
    drive_link:'',
    status:'',
    change:'',
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

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="name">Nombre</Label>
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


            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>