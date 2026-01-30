<script setup lang="ts">
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { LoadingOverlay } from '@/components/overlay';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { AppPageProps, BreadcrumbItem, SpecialistArea } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Area de especialistas", href:"/specialist-area"},
    {title:"Actualizar", href:"#"}
];

interface specialistAreaEditPageProps extends AppPageProps{
    specialistArea: SpecialistArea;
}

const page = usePage<specialistAreaEditPageProps>();

const specialistArea = computed(()=> page.props.specialistArea);

const form = useForm({
    name:specialistArea.value.name,
});

function submit(){
  form.put(`/specialist-areas/${specialistArea.value.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Editar area de especialista"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Editar area" return-url="/specialist-areas"/>
        <RecordFormBody  :handle="submit">

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        placeholder="Nombre del area del administrador"
                    />
                    <InputError class="mt-1" :message="form.errors.name" />
                </div>
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>