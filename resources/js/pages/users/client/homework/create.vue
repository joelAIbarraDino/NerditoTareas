<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue} from '@/components/ui/select';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { AppPageProps, BreadcrumbItem, TypeHomework } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { SimpleEditor } from '@/components/textEditor';
import { LoadingOverlay } from '@/components/overlay';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { computed } from 'vue';

import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Mis tareas activas", href:"/client"},
    {title:"Crear", href:"#"}
];

interface homeworkPageProps extends AppPageProps{
    typesHomework: TypeHomework[];
}

const page = usePage<homeworkPageProps>();
const typesHomework = computed(()=>page.props.typesHomework);

const form = useForm({
    name:'',
    description:'',
    type_homework:'',
    client_delivery:'',
    final_price:0,
    specialist_payment:0,
});

function submit(){
  form.post('/client/save-homework',{
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
        <RecordFormHeader title-form="Nueva tarea" return-url="/client"/>
        <RecordFormBody  :handle="submit">

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="name">Nombre de la tarea</Label>
                    <Input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        placeholder="Nombre de la tarea"
                    />
                    <InputError class="mt-1" :message="form.errors.name" />
                </div>
            </div>

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-2">
                    <Label for="area">¿Qué tipo de tarea es?</Label>

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
                <div class="flex-1 grid gap-1">
                    <Label for="description">¿Que necesita hacer el nuestros especialistas?</Label>
                    <SimpleEditor 
                        v-model="form.description" 
                        id="description"
                    />
                </div>
            </div>

            <div class="flex-1 grid gap-2">
                <Label for="client_delivery">¿Cuando necesitas la tarea?</Label>
                <Input
                    type="datetime-local"
                    id="client_delivery"     
                    v-model="form.client_delivery"
                />
                <InputError class="mt-1" :message="form.errors.client_delivery" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>