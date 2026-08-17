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

const form = useForm({
    name: '',
    owner_name: '',
});

const submit = () => {
    form.post('/businesses');
};
</script>

<template>
    <Head title="Create Business" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6 max-w-2xl mx-auto w-full">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <Button as-child variant="outline" size="icon" class="h-9 w-9">
                <Link href="/businesses">
                    <ArrowLeft class="h-4 w-4" />
                </Link>
            </Button>
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Create Business</h1>
                <p class="text-sm text-muted-foreground">
                    Add a new business entity to your POS multi-tenant account.
                </p>
            </div>
        </div>

        <Card>
            <form @submit.prevent="submit">
                <CardHeader>
                    <CardTitle>Business Information</CardTitle>
                    <CardDescription>
                        Fill in the details below to create a new business.
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
                        Save Business
                    </Button>
                </CardFooter>
            </form>
        </Card>
    </div>
</template>
