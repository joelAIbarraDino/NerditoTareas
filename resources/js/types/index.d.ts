import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    flash:{
        message:string;
    };
    [key: string]: unknown;
};

export interface User {
    id: number;
    name: string;
    email: string;
    whatsapp:string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Specialist {
    id:number;
    id_user:number;
    area:number;
    specialist_area:SpecialistArea;
    user:User;
    created_at: string;
    updated_at: string;
}

export interface Client {
    id:number;
    id_user:number;
    educational_level:string;
    user:User;
    created_at: string;
    updated_at: string;    

}

export interface Enum{
    value:string;
    label:string;
}

export interface SpecialistArea{
    id:number;
    name:string;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
