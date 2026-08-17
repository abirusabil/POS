<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Save } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

interface Business {
    id: string;
    name: string;
    owner_name: string;
}

const props = defineProps<{
    business: Business;
}>();

const form = useForm({
    name: props.business.name,
    owner_name: props.business.owner_name,
});

const submit = () => {
    form.put(`/businesses/${props.business.id}`);
};
</script>

<template>
    <Head title="Edit Business" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6 max-w-2xl mx-auto w-full">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Button as-child variant="outline" size="icon" class="h-9 w-9">
                <Link href="/businesses">
                    <ArrowLeft class="h-4 w-4" />
                </Link>
            </Button>
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Edit Business</h1>
                <p class="text-sm text-muted-foreground">
                    Update business details for "{{ business.name }}".
                </p>
            </div>
        </div>

        <Card>
            <form @submit.prevent="submit">
                <CardHeader>
                    <CardTitle>Business Information</CardTitle>
                    <CardDescription>
                        Update the information for this business entity.
                    </CardDescription>
                </CardHeader>

                <CardContent class="space-y-4">
                    <div class="space-y-2">
                        <Label for="name">Business Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="e.g. Toko Berkah Jaya"
                            :class="{ 'border-destructive': form.errors.name }"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="owner_name">Owner Name</Label>
                        <Input
                            id="owner_name"
                            v-model="form.owner_name"
                            type="text"
                            placeholder="e.g. Budi Santoso"
                            :class="{ 'border-destructive': form.errors.owner_name }"
                        />
                        <InputError :message="form.errors.owner_name" />
                    </div>
                </CardContent>

                <CardFooter class="flex justify-end gap-3 pt-4 border-t">
                    <Button as-child variant="outline" type="button">
                        <Link href="/businesses">Cancel</Link>
                    </Button>
                    <Button type="submit" :disabled="form.processing" class="gap-2">
                        <Save class="h-4 w-4" />
                        Update Business
                    </Button>
                </CardFooter>
            </form>
        </Card>
    </div>
</template>
