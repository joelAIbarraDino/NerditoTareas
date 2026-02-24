<script setup lang="ts">
import { Sheet, SheetClose, SheetContent, SheetDescription, SheetFooter, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Button } from '@/components/ui/button';
import { Link, router } from '@inertiajs/vue3';
import { Send } from 'lucide-vue-next';
import { Homework } from '@/types';
import { ref } from 'vue';

interface homeworkProps {
    homework: Homework;
}

defineProps<homeworkProps>();

const formatDate = (date:string) => {

    const isoStr = date.replace(" ", "T");

    const dateObject = new Date(isoStr);

    return dateObject.toLocaleDateString('es-MX', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

const formatHour = (date:string) => {
    const isoStr = date.replace(" ", "T");

    const dateObject = new Date(isoStr);

    return dateObject.toLocaleTimeString('es-MX', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}
const responseMessage = ref('');
const updated = ref(false);
const updateResultError = ref(false);

const deliveryChange = (homeworkID:number) => {
    router.patch(`/homework/${homeworkID}/status`, {}, {
        preserveScroll:true,
        onSuccess: () => {
            updated.value=true;
            updateResultError.value = false;
            setTimeout(() => {
                updated.value = false;
            }, 1000);
        },
        onError : () => {
            updated.value=true;
            updateResultError.value = true;
            responseMessage.value = "Ocurrio un error al actualizar la tarea, intente mas tarde"
            setTimeout(() => {
                updated.value = false;
                updateResultError.value = false;
                responseMessage.value = "¡Cambio guardado!"
            }, 1000);
        }
    });
}

</script>

<template>
    <Sheet>
        <SheetTrigger>
            <div class="bg-primary/5 p-2 rounded border shadow-md hover:cursor-pointer hover:bg-primary/10 transition">         
                <div class="flex items-start justify-between text-md mb-2 border-b">
                    <p class="font-black">{{ homework.name }}</p>
                    <p>#{{ homework.order_id }}</p>
                </div>
                
                <div class="flex text-md items-center justify-between">
                    <p class="font-bold">Fecha de entrega:</p>
                    <p class="font-bold">{{ formatDate(homework.specialist_delivery) }} </p>
                </div>

                <div class="flex text-md items-center justify-between">
                    <p class="font-bold">Hora de entrega:</p>
                    <p class="font-bold">{{ formatHour(homework.specialist_delivery) }} </p>
                </div>

                <div class="flex text-md items-center justify-between">
                    <p class="font-bold">Tipo de tarea:</p>
                    <p>{{ homework.type_homework.name }} </p>
                </div>

                <div class="flex text-md items-center justify-between">
                    <p class="font-bold">Estatus:</p>
                    <p class="font-bold">{{ homework.status }}</p>
                </div>
            </div>  
        </SheetTrigger>

        <SheetContent class="
            overflow-y-auto 
            [&::-webkit-scrollbar]:w-1 
            [&::-webkit-scrollbar-track]:bg-gray-100 
            [&::-webkit-scrollbar-thumb]:bg-gray-300 
            dark:[&::-webkit-scrollbar-track]:bg-neutral-700 
            dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500"
        >

        <SheetHeader>
            <SheetTitle>Detalles de la tarea</SheetTitle>
            <SheetDescription>
                <p>Instrucciones y documentos de la tarea</p>
            </SheetDescription>
        </SheetHeader>
        
        <div class="px-4">

            <Button @click="deliveryChange(homework.id)" class="bg-primary text-white hover:bg-primary/85 w-full mb-4" :disabled="homework.status === 'Completado'">
                <p>{{ homework.status === 'Completado'? 'Tarea completada':'Marcar tarea como lista' }}</p>
            </Button>
            <span v-if="updated" class="text-xs px-2 py-0.5 rounded-full" :class="!updateResultError?'bg-green-100 text-green-700':'bg-red-100 text-red-700'">
                {{responseMessage}}
            </span>
            <div class="mb-3 text-white font-bold w-full bg-primary p-2 rounded border-black ">
                <a v-if="homework.drive_link" :href="homework.drive_link" class="hover:underline hover:cursor-pointer" target="_blank">Ir a carpeta</a>
                <p v-else target="_blank">No hay un link de drive todavia</p>
            </div>
            
            <p class=" mb-2 font-bold text-md">Instrucciones de la tarea:</p>            
            <div 
                ref="homeworkContainer"
                class="border-2 w-full h-40 p-2 rounded overflow-y-auto
                    [&::-webkit-scrollbar]:w-1 
                    [&::-webkit-scrollbar-track]:bg-gray-100 
                    [&::-webkit-scrollbar-thumb]:bg-gray-300 
                    dark:[&::-webkit-scrollbar-track]:bg-neutral-700 
                    dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500
                " 
                v-html="homework.description" 
            />

            <p class=" my-2 font-bold text-md">Cambios en la tarea:</p>            
            <div 
                ref="homeworkContainer"
                class="border-2 w-full h-40 p-2 rounded overflow-y-auto
                    [&::-webkit-scrollbar]:w-1 
                    [&::-webkit-scrollbar-track]:bg-gray-100 
                    [&::-webkit-scrollbar-thumb]:bg-gray-300 
                    dark:[&::-webkit-scrollbar-track]:bg-neutral-700 
                    dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500
                " 
                v-html="homework.change_notes" 
            />
        </div>

        <SheetFooter>
            <SheetClose as-child>
                <Button>Cerrar</Button>
            </SheetClose>
        </SheetFooter>
        </SheetContent>
    </Sheet>
</template>