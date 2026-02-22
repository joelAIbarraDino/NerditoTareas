<script setup lang="ts">

import { AppPageProps, BreadcrumbItem, Homework } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowUpLeft } from 'lucide-vue-next';

interface clientPageProps extends AppPageProps{
    homework: Homework
}

const page = usePage<clientPageProps>();
const user = page.props.auth.user;
const homework = computed(() => page.props.homework);
const breadcrumbs: BreadcrumbItem[] = [{title: 'Mis tareas activas',href: '/client'}, {title: `Pago tarea #${homework.value.order_id}`,href: '#'}];

const errorMessage = ref<string | null>(null);

onMounted(async () => {
    // Inicializar Mercado Pago
    const mp = new (window as any).MercadoPago(import.meta.env.VITE_MERCADO_PAGO_PUBLIC_KEY);
    const bricksBuilder = mp.bricks();

    const renderCardPaymentBrick = async (bricksBuilder: any) => {
        const settings = {
            initialization: {
                amount: homework.value.final_price - homework.value.amount_paid,
                payer: {
                    email: user.email,
                },
            },
            customization: {
                visual: {
                    style: { theme: 'default' },
                }
            },
            callbacks: {
                onReady: () => console.log("Brick listo"),
                onSubmit: ({ formData }: any) => {
                    // Retornamos una Promesa para que el Brick gestione el estado del botón
                    return new Promise((resolve, reject) => {
                        router.post(`mercado-pago/${homework.value.id}/payment-gateway`, {
                            token: formData.token,
                            issuer_id: formData.issuer_id,
                            payment_method_id: formData.payment_method_id,
                            transaction_amount: formData.transaction_amount,
                            installments: formData.installments,
                            payer: formData.payer,
                        }, {
                            onSuccess: () => {
                                resolve();
                            },
                            onError: (errors) => {
                                errorMessage.value = Object.values(errors)[0];
                                reject();
                            },
                            preserveScroll: true
                        });
                    });
                },
                onError: (error: any) => console.error("Error en Brick:", error),
            },
        };

        window.cardPaymentBrickController = await bricksBuilder.create(
            'cardPayment',
            'cardPaymentBrick_container',
            settings
        );
    };

    renderCardPaymentBrick(bricksBuilder);
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

                <div id="cardPaymentBrick_container"></div>

                <div v-if="errorMessage" class="mt-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                    {{ errorMessage }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>