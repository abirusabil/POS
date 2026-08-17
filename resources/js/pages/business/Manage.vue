<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Plus, Edit3, Trash2, Building2, Save } from '@lucide/vue';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '@/components/ui/alert-dialog'
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Business, Outlets } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Manage Business', href: '/business' },
        ],
    },
});

const showModal = ref(false)
const editingId = ref<string | null>(null)   // null = create, ada id = edit

const outletForm = useForm({ name: '', address: '', phone: '' })

const openCreate = () => {
    editingId.value = null
    outletForm.reset()
    showModal.value = true
}

const openEdit = (outlet: Outlets) => {
    editingId.value = outlet.id
    outletForm.name = outlet.name
    outletForm.address = outlet.address
    outletForm.phone = outlet.phone
    showModal.value = true
}

const submitOutlet = () => {
    const onSuccess = () => {
 showModal.value = false; outletForm.reset() 
}

    if(editingId.value){
        outletForm.put(`/outlets/${editingId.value}`, { onSuccess })
    } else {
        outletForm.post('/outlets', { onSuccess })
    }
}

const props = defineProps<{
    business: Business | null,
    flash?: {
        success?: string;
    };
}>()


const isEditing = computed(() => props.business !== null)

const form = useForm({
    name: props.business?.name ?? '',
    owner_name: props.business?.owner_name ?? '',
})

const submit = () => {
    if (isEditing.value) {
        form.put('/business')
    } else {
        form.post('/business')
    }
}

const showDelete = ref(false)
const outletToDelete = ref<Outlets | null>(null)

const askDelete = (outlet: Outlets) => {
    outletToDelete.value = outlet
    showDelete.value = true
}

const confirmDelete = () => {
    if (!outletToDelete.value) {
return
}

    router.delete(`/outlets/${outletToDelete.value.id}`, {
        onSuccess: () => {
            showDelete.value = false
            outletToDelete.value = null
        },
    })
}
</script>

<template>

    <Head title="Businesses" />

    <h1 class="sr-only">Businesses</h1>

    <!-- Card Bussiness -->

    <div class="flex h-full flex-1 flex-col gap-6 p-6">

        <Card>
            <form @submit.prevent="submit">
                <CardHeader class="pb-4">
                    <CardTitle>Business</CardTitle>
                    <CardDescription>Manage your business</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-6">
                        <div class="grid gap-2">
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" type="text" placeholder="e.g. Toko Berkah Jaya" />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="owner_name">Owner Name</Label>
                            <Input id="owner_name" v-model="form.owner_name" type="text"
                                placeholder="e.g. Budi Santoso" />
                            <InputError :message="form.errors.owner_name" />
                        </div>
                    </div>
                    <CardFooter class="flex justify-end pt-5 w-full px-0">
                        <Button type="submit" :disabled="form.processing">
                            <Save class="mr-2 h-4 w-4" />
                            Save
                        </Button>
                    </CardFooter>

                </CardContent>
            </form>
        </Card>

        <!-- Card Outlet -->
        <Card class="flex-1">
            <CardHeader>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">Outlets</h1>
                        <p class="text-sm text-muted-foreground">
                            Manage business entities registered in your POS system.
                        </p>
                    </div>
                    <div v-if="business">
                        <Button variant="default" class="gap-2" @click="openCreate">
                            <Plus class="h-4 w-4" /> Add Outlet
                        </Button>
                    </div>

                </div>
            </CardHeader>
            <CardContent>
                <div v-if="business">
                    <!-- Header -->

                    <div v-if="flash?.success"
                        class="rounded-lg bg-emerald-500/15 p-4 text-sm text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        {{ flash.success }}
                    </div>

                    <!-- Content Card -->
                    <Card class="flex-1">
                        <CardContent>
                            <div v-if="business.outlets?.length === 0"
                                class="flex flex-col items-center justify-center py-12 text-center">
                                <div class="rounded-full bg-muted p-3 mb-3">
                                    <Building2 class="h-6 w-6 text-muted-foreground" />
                                </div>
                                <h3 class="font-semibold text-lg">No outlets found</h3>
                                <p class="text-sm text-muted-foreground mt-1 max-w-sm">
                                    Get started by adding a new business entity to the system.
                                </p>
                                <Button @click="openCreate">
                                    <Plus class="h-4 w-4" />
                                    Add Outlet
                                </Button>
                            </div>

                            <div v-else class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="border-b bg-muted/50 text-xs uppercase text-muted-foreground">
                                        <tr>
                                            <th scope="col" class="px-4 py-3">Outlet Name</th>
                                            <th scope="col" class="px-4 py-3">Address</th>
                                            <th scope="col" class="px-4 py-3">Phone</th>
                                            <th scope="col" class="px-4 py-3 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        <tr v-for="item in business.outlets" :key="item.id">

                                            <td class="px-4 py-3.5 text-muted-foreground">
                                                {{ item.name }}
                                            </td>
                                            <td class="px-4 py-3.5 text-muted-foreground">
                                                {{ item.address }}
                                            </td>
                                            <td class="px-4 py-3.5 text-muted-foreground">
                                                {{ item.phone }}
                                            </td>
                                            <td class="px-4 py-3.5 text-right">
                                                <div class="flex justify-end gap-2">
                                                    <Button variant="ghost" size="icon" class="h-8 w-8" title="Edit"
                                                        @click="openEdit(item)">
                                                        <Edit3 class="h-4 w-4 ..." />
                                                    </Button>
                                                    <Button variant="ghost" size="icon"
                                                        class="h-8 w-8 text-destructive ..." title="Delete"
                                                       @click="askDelete(item)">
                                                        <Trash2 class="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->

                        </CardContent>
                    </Card>
                </div>
                <p v-else class="text-muted-foreground">Buat business dulu untuk menambah outlet.</p>
            </CardContent>
        </Card>
    </div>

    <!-- Modal-nya (taruh di mana saja dalam <template>) -->
    <Dialog v-model:open="showModal">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ editingId ? 'Edit Outlet' : 'Add Outlet' }}</DialogTitle>
            </DialogHeader>
            <form @submit.prevent="submitOutlet" class="grid gap-4">
                <Input v-model="outletForm.name" placeholder="Outlet name" />
                <InputError :message="outletForm.errors.name" />
                <Input v-model="outletForm.address" placeholder="Outlet address" />
                <InputError :message="outletForm.errors.address" />
                <Input v-model="outletForm.phone" placeholder="Outlet phone" />
                <InputError :message="outletForm.errors.phone" />
                <div class="flex justify-end gap-2">
                    <Button type="button" variant="outline" @click="showModal = false">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="outletForm.processing">
                        <Save class="mr-2 h-4 w-4" />
                        {{ editingId ? 'Update' : 'Create' }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>

    <AlertDialog v-model:open="showDelete">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>Hapus outlet?</AlertDialogTitle>
                <AlertDialogDescription>
                    "{{ outletToDelete?.name }}" akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Batal</AlertDialogCancel>
                <AlertDialogAction @click="confirmDelete">Hapus</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>

</template>