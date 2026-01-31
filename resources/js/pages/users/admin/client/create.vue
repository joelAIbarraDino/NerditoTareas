<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue} from '@/components/ui/select'
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { LoadingOverlay } from '@/components/overlay';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { AppPageProps, BreadcrumbItem, Enum } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Crear", href:"#"}
];

interface SpecialistPageProps extends AppPageProps{
    educationalLevel:Enum[];
}

const form = useForm({
    name:'',
    educational_level:'',
    email:'',
    whatsapp:'',
    password:'',
    password_confirmation:'',
});

const page = usePage<SpecialistPageProps>();
const educationalLevelArray = computed(()=>page.props.educationalLevel);


function submit(){
  form.post('/clients',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Nuevo cliente"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Nuevo cliente" return-url="/clients"/>
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
                    <Label for="area">Nivel educativo</Label>

                    <Select v-model="form.educational_level" class="w-full" id="area">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Seleccione el nivel educativo"/>
                        </SelectTrigger>

                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Nivel educativo</SelectLabel>
                                <SelectItem v-for="educationalLevel in educationalLevelArray":key="educationalLevel.value" :value="educationalLevel.value">
                                    {{ educationalLevel.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>

                    </Select>
                    <InputError class="mt-1" :message="form.errors.educational_level" />
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
                        placeholder="Correo de cliente"
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
                        placeholder="Numero de WhatsApp de cliente"
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
                  placeholder="Contraseña"
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
                  placeholder="Confirma contraseña"
              />
              <InputError class="mt-1" :message="form.errors.password_confirmation" />
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>