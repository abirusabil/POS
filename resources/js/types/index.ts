export * from './auth';
export * from './navigation';
export * from './ui';

export interface Business {
    id: string;
    name: string;
    owner_name: string;
    outlets?: Outlets[];
}

export interface Outlets {
    id: string;
    business_id: string;
    business?: Business;
    name: string;
    address: string;
    phone: string;
    created_at: string;
    updated_at: string;
}

export interface PaginationLinks {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedOutlets {
    data: Outlets[];
    links: PaginationLinks[];
    current_page: number;
    last_page: number;
    total: number;
}

export interface User {
    id: string;
    name: string;
    email: string;
    business_id: string | null;
    role: 'owner' | 'admin' | 'staff';
    created_at: string;
    updated_at: string;
}

export interface PaginatedBusinesses {
    data: Business[];
    links: PaginationLinks[];
    current_page: number;
    last_page: number;
    total: number;
}
