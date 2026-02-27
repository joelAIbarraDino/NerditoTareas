<script setup lang="ts">
import { AppPageProps, BreadcrumbItem, Homework } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowUpLeft } from 'lucide-vue-next';


interface clientPageProps extends AppPageProps{
    homework: Homework
}

const page = usePage<clientPageProps>();
const homework = computed(() => page.props.homework);
const breadcrumbs: BreadcrumbItem[] = [{title: 'Mis tareas activas',href: '/client'}, {title: `Pago tarea #${homework.value.order_id}`,href: '#'}];
const amount = homework.value.final_price - homework.value.amount_paid;
const emailUser = page.props.auth.user.email;
const nameUser = page.props.auth.user.name;

onMounted(() => {
    // Inicializamos MP usando el script global
    const mp = new window.MercadoPago(import.meta.env.VITE_MERCADO_PAGO_PUBLIC_KEY);
    const bricksBuilder = mp.bricks();

    const renderPaymentBrick = async (bricksBuilder:any) => {
        const settings = {
            initialization: {
                amount: amount,
                payer:{
                    email: emailUser,
                    firstName:nameUser,
                },
            },
            customization: {
                visual: {
                    hideStatusDetails: false, // Muestra u oculta el detalle del resultado
                    hidePaymentButton: false, // Si quieres usar tu propio botón (no recomendado)
                    style: {
                        theme: 'bootstrap', // 'default', 'dark', 'flat', 'bootstrap'
                        customVariables: {
                            colorPrimary: '#0884e2', // Color de tu marca para el botón
                            borderRadius: '10px',
                        }
                    }
                },

                paymentMethods: {
                    creditCard: "all",
                    ticket: "all", // Para pagos en efectivo (OXXO)
                    debitCard: "all",
                    maxInstallments: 6
                },
            },
            callbacks: {
                onReady: () => {
                    console.log("Brick de Pago listo");
                },
                onSubmit: ({ selectedPaymentMethod, formData }) => {
                    // Aquí el Brick ya generó el TOKEN de la tarjeta
                    return new Promise((resolve, reject) => {
                        router.post(`/mercado-pago/${homework.value.id}/process-payment`, {
                            token: formData.token,
                            issuer_id: formData.issuer_id,
                            payment_method_id: formData.payment_method_id,
                            transaction_amount: formData.transaction_amount,
                            installments: formData.installments,
                            payer: formData.payer,
                        }, {
                            onSuccess: () => {
                                resolve();
                                router.visit('/client');
                            },
                            onError: (errors) => {
                                reject();
                            }
                        });
                    });
                },
                onError: (error:any) => {
                    console.error('Error en el Brick:', error);
                },
            },
        };
        
        // Renderizamos el Brick en el contenedor
        window.paymentBrickController = await bricksBuilder.create(
            'payment',
            'paymentBrick_container',
            settings
        );
    };

    renderPaymentBrick(bricksBuilder);
});

</script>

<template>
    <Head :title="`Pago de tarea ${homework.order_id}`"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <div class="flex flex-col gap-4 p-4 xl:w-1/2 md:w-3/4 w-11/12 mx-auto my-10 border rounded-md shadow-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold">Pagar tarea</h3>
                    <Button as-child >
                        <Link href="/client"><ArrowUpLeft /> Regresar</Link>
                    </Button>
                </div>
                <span class="text-zinc-500 font-medium">Concepto: Tarea #{{ homework.order_id }}</span>
                <div class="flex justify-between items-centerborder-b border-zinc-100 dark:border-zinc-800">
                    <span class="text-zinc-500 font-medium">Monto a pagar</span>
                    <span class="text-2xl font-black text-primary">${{ homework.final_price - homework.amount_paid }}</span>
                </div>

                <div id="paymentBrick_container"></div>

            </div>
        </div>
    </AppLayout>
</template>