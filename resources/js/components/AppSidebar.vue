<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, User, Glasses, NotebookTabs, Handshake, BookA, LayoutList, NotebookPen } from 'lucide-vue-next';

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
import { computed } from 'vue';

const page = usePage();

const role = computed(()=>{
    try {
        const auth = page.props.auth;
        if(!auth) return null;
        
        const user = auth.user;
        if(!user || !user.roles || user.roles.length === 0) return null;
        
        return user.roles[0].name;
    } catch (error) {
        console.error('Error getting user role:', error);
        return null;
    }
});

const adminNavItems: NavItem[] = [
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

const specialistNavItems: NavItem[] = [
    {
        title: 'Mis tareas asignadas',
        href: '/specialist',
        icon: BookA,
    },
]

const clientNavItems: NavItem[] = [
    {
        title: 'Mis tareas activas',
        href: '/client',
        icon: NotebookPen,
    },

    {
        title: 'Pedir tareas',
        href: '/client/request',
        icon: Handshake,
    },
]

const emptyNavItems: NavItem[] = []

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link v-if="role === 'admin'" :href="dashboard()">
                            <AppLogo />
                        </Link>

                        <Link v-else-if="role === 'specialist'" href="/specialist">
                            <AppLogo />
                        </Link>

                        <Link v-else-if="role === 'client'" href="/client">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain v-if="role === 'admin'" :items="adminNavItems" />
            <NavMain v-else-if="role === 'specialist'" :items="specialistNavItems" />
            <NavMain v-else-if="role === 'client'" :items="clientNavItems" />
            <NavMain v-else :items="emptyNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
