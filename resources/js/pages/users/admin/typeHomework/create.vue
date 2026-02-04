<script setup lang="ts">
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { LoadingOverlay } from '@/components/overlay';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {Head, useForm} from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Tipo de tareas", href:"/type-homework"},
    {title:"Crear", href:"#"}
];


const form = useForm({
    name:'',
});

function submit(){
  form.post('/type-homework',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Nuevo tipo de tarea"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Nuevo tipo de tarea" return-url="/type-homework"/>
        <RecordFormBody  :handle="submit">

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        placeholder="Nombre del tipo de tarea"
                    />
                    <InputError class="mt-1" :message="form.errors.name" />
                </div>
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>