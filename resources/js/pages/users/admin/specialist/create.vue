<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue} from '@/components/ui/select'
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
    {title:"Especialistas", href:"/specialists"},
    {title:"Crear", href:"#"}
];

interface SpecialistPageProps extends AppPageProps{
    specialistAreas:SpecialistArea[];
}

const form = useForm({
    name:'',
    area:'',
    email:'',
    whatsapp:'',
    password:'',
    password_confirmation:'',
});

const page = usePage<SpecialistPageProps>();
const specialistAreaArray = computed(()=>page.props.specialistAreas);


function submit(){
  form.post('/specialists',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Nuevo especialista"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Nuevo especialista" return-url="/specialists"/>
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
                    <Label for="area">Area de especialidad</Label>

                    <Select v-model="form.area" class="w-full" id="area">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Seleccione un area de especialidad"/>
                        </SelectTrigger>

                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Areas</SelectLabel>
                                <SelectItem v-for="specialistArea in specialistAreaArray":key="specialistArea.id" :value="specialistArea.id">{{ specialistArea.name }}</SelectItem>
                            </SelectGroup>
                        </SelectContent>

                    </Select>
                    <InputError class="mt-1" :message="form.errors.area" />
                </div>
            </div>

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="email">Correo</Label>
                    <Input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        placeholder="Correo de administrador"
                    />
                    <InputError class="mt-1" :message="form.errors.email" />
                </div>
            </div>

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="whatsapp">Numero de WhatsApp</Label>
                    <Input
                        id="whatsapp"
                        type="tel"
                        class="mt-1 block w-full"
                        v-model="form.whatsapp"
                        placeholder="Numero de WhatsApp de administrador"
                    />
                    <InputError class="mt-1" :message="form.errors.whatsapp" />
                </div>
            </div>

            <div class="grid gap-1">
              <Label for="password">Contraseña</Label>
              <Input
                  id="password"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password"
                  placeholder="Correo de usuario"
              />
              <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div class="grid gap-1">
              <Label for="password_confirmation">Confirmar contraseña</Label>
              <Input
                  id="password_confirmation"
                  type="password"
                  class="mt-1 block w-full"
                  v-model="form.password_confirmation"
                  placeholder="Correo de usuario"
              />
              <InputError class="mt-1" :message="form.errors.password_confirmation" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>