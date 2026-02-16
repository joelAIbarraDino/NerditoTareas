<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue} from '@/components/ui/select'
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { LoadingOverlay } from '@/components/overlay';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {Head, useForm, usePage} from '@inertiajs/vue3';
import { AppPageProps, BreadcrumbItem, Client, Enum, Homework } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Tarea", href:"/clients"},
    {title:"Crear link de pago", href:"#"}
];

interface SpecialistPageProps extends AppPageProps{
    homework:Homework,
}

const page = usePage<SpecialistPageProps>();
const homework = computed(()=>page.props.homework);

const form = useForm({
    amount:'',
});



function submit(){
  form.post(`/mercado-pago/${homework.value.id}/payment-link`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}


</script>

<template>
    <Head title="Crear link de pago"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
      <LoadingOverlay :show="form.processing" />
      <RecordForm>
        <RecordFormHeader title-form="Creando link" return-url="/homework"/>
        <RecordFormBody  :handle="submit">

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="amount">Monto de pago</Label>
                    <Input
                        id="amount"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.amount"
                        placeholder="Monto a pagar"
                    />
                    <InputError class="mt-1" :message="form.errors.amount" />
                </div>
            </div>

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>

</template>