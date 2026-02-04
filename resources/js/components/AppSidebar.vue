<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, User, Glasses, NotebookTabs, Handshake, BookA, LayoutList } from 'lucide-vue-next';

import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';

import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },

    {
        title: 'Tareas',
        href: '/homework',
        icon: BookA
    },

    {
        title: 'Clientes',
        href: '/clients',
        icon: Handshake,
    },

    {
        title: 'Administradores',
        href: '/admins',
        icon: User,
    },

    {
        title: 'Especialistas',
        href: '/specialists',
        icon: Glasses,
    },

    {
        title: 'Area de especialistas',
        href: '/specialist-areas',
        icon: LayoutList,
    },

    {
        title: 'Tipo de tareas',
        href: '/type-homework',
        icon: NotebookTabs,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
