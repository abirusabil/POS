<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Search, Edit3, Trash2, Building2 } from '@lucide/vue';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import type { PaginatedBusinesses} from '@/types';


defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Manage Business', href: '/business' },
        ],
    },
});

const props = defineProps<{
    businesses: PaginatedBusinesses;
    filters: {
        search?: string;
    };
    flash?: {
        success?: string;
    };
}>();

const search = ref(props.filters.search || '');

let searchTimeout: ReturnType<typeof setTimeout>;
watch(search, (newSearch) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            '/businesses',
            { search: newSearch },
            { preserveState: true, replace: true }
        );
    }, 300);
});

const deleteBusiness = (id: string, name: string) => {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        router.delete(`/businesses/${id}`);
    }
};
</script>

<template>

    <Head title="Manage Business" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Businesses</h1>
                <p class="text-sm text-muted-foreground">
                    Manage business entities registered in your POS system.
                </p>
            </div>
            <div>
                <Button as-child variant="default" class="gap-2">
                    <Link href="/businesses/create">
                        <Plus class="h-4 w-4" />
                        Add Business
                    </Link>
                </Button>
            </div>
        </div>

        <!-- Success Flash Message -->
        <div v-if="flash?.success"
            class="rounded-lg bg-emerald-500/15 p-4 text-sm text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
            {{ flash.success }}
        </div>

        <!-- Content Card -->
        <Card class="flex-1">
            <CardHeader class="pb-4">
                <div class="flex flex-col sm:flex-row justify-between gap-4">
                    <div>
                        <CardTitle>Business Directory</CardTitle>
                        <CardDescription>
                            Total {{ businesses.total }} business record(s) found
                        </CardDescription>
                    </div>
                    <div class="relative w-full sm:w-72">
                        <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                        <Input v-model="search" type="search" placeholder="Search by name or owner..." class="pl-8" />
                    </div>
                </div>
            </CardHeader>
            <CardContent>
                <div v-if="businesses.data.length === 0"
                    class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="rounded-full bg-muted p-3 mb-3">
                        <Building2 class="h-6 w-6 text-muted-foreground" />
                    </div>
                    <h3 class="font-semibold text-lg">No businesses found</h3>
                    <p class="text-sm text-muted-foreground mt-1 max-w-sm">
                        Get started by adding a new business entity to the system.
                    </p>
                    <Button as-child variant="outline" class="mt-4 gap-2">
                        <Link href="/businesses/create">
                            <Plus class="h-4 w-4" />
                            Add Business
                        </Link>
                    </Button>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b bg-muted/50 text-xs uppercase text-muted-foreground">
                            <tr>
                                <th scope="col" class="px-4 py-3">Business Name</th>
                                <th scope="col" class="px-4 py-3">Owner Name</th>
                                <th scope="col" class="px-4 py-3">ID</th>
                                <th scope="col" class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr v-for="item in businesses.data" :key="item.id"
                                class="hover:bg-muted/40 transition-colors">
                                <td class="px-4 py-3.5 font-medium">
                                    {{ item.name }}
                                </td>
                                <td class="px-4 py-3.5 text-muted-foreground">
                                    {{ item.owner_name }}
                                </td>
                                <td class="px-4 py-3.5 font-mono text-xs text-muted-foreground">
                                    {{ item.id }}
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <Button as-child variant="ghost" size="icon" class="h-8 w-8" title="Edit">
                                            <Link :href="`/businesses/${item.id}/edit`">
                                                <Edit3 class="h-4 w-4 text-muted-foreground hover:text-foreground" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="icon"
                                            class="h-8 w-8 text-destructive hover:bg-destructive/10" title="Delete"
                                            @click="deleteBusiness(item.id, item.name)">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="businesses.links.length > 3" class="flex items-center justify-between mt-6 pt-4 border-t">
                    <div class="text-xs text-muted-foreground">
                        Showing page {{ businesses.current_page }} of {{ businesses.last_page }}
                    </div>
                    <div class="flex gap-1">
                        <template v-for="(link, key) in businesses.links" :key="key">
                            <Button v-if="link.url" as-child :variant="link.active ? 'default' : 'outline'" size="sm"
                                class="h-8 min-w-8">
                                <Link :href="link.url">
                                    <span v-html="link.label" />
                                </Link>
                            </Button>
                            <span v-else v-html="link.label"
                                class="h-8 px-3 inline-flex items-center justify-center text-xs text-muted-foreground opacity-50" />
                        </template>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
