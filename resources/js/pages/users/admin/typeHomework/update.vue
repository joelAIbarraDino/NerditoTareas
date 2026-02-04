<script setup lang="ts">
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { AppPageProps, BreadcrumbItem, TypeHomework } from '@/types';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { LoadingOverlay } from '@/components/overlay';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Tipo de tareas", href:"/type-homework"},
    {title:"Actualizar", href:"#"}
];

interface typeHomeworkEditPageProps extends AppPageProps{
    typeHomework: TypeHomework;
}

const page = usePage<typeHomeworkEditPageProps>();
const typeHomework = computed(()=> page.props.typeHomework);

const form = useForm({
    name:typeHomework.value.name,
});

function submit(){
  form.put(`/type-homework/${typeHomework.value.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Editar tipo de tarea"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Editar tipo de tarea" return-url="/type-homework"/>
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