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
export interface Role {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
    pivot: {
        model_type: string;
        model_id: number;
        role_id: number;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    whatsapp:string;
    avatar?: string;
    email_verified_at: string | null;
    roles: Role[];
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Payment{
    id:number;
    order_id:number;
    payment_provider:string;
    amount:number;
    payment_id:string;
    destination_account:string;
    method:string;
    status:string;
}

export interface OrderPayment{
    id:number;
    id_homework:number;
    amount:number;
    mp_link:string;
    status:string;
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

export interface SpecialistArea{
    id:number;
    name:string;
    created_at: string;
    updated_at: string;
}

export interface TypeHomework{
    id:number;
    name:string;
    created_at: string;
    updated_at: string;
}


export interface Homework{
    id:number;
    order_id:string;
    private_order_id:string;
    name:string;
    description:string;
    change_notes:string;
    admin: User;
    client: Client;
    type_homework:TypeHomework;
    client_delivery:string;
    specialist_delivery:string;
    drive_link:string;
    status:string;
    change:string;
    specialist:Specialist;
    final_price:number;
    specialist_payment:number;
    proft:number;
    amount_paid:number;
    payments:Payment[];
    order_payments:OrderPayment[];
    created_at: string;
    updated_at: string;
}

export interface Enum{
    value:string;
    label:string;
}

export type BreadcrumbItemType = BreadcrumbItem;
