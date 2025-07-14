<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const form = useForm({
  name: '',
  email: '',
  password: '',
  role: 'user',
});

const handleSubmit = () => {
    form.post('/users', {
        onSuccess: () => {
            toast('User added successfully!');
        },
        onError: (error) => {
            toast('Failed to add user. Please check the form.');
            console.error(error);
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
    {
        title: 'Add User',
        href: '/users/create',
    },
];
</script>

<template>
    <Head title="Add Task" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <CardTitle>Add New User</CardTitle>
                        <Link :href="`/users`">
                            <Button variant="outline">
                                <ArrowLeftIcon class="h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="handleSubmit" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="task_name">Name <span class="text-red-500">*</span></Label>
                            <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Enter name"
                            />
                            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email <span class="text-red-500">*</span></Label>
                            <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            placeholder="Enter email"
                            />
                            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="password">Password <span class="text-red-500">*</span></Label>
                            <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Enter password"
                            />
                            <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
                        </div>

                        <div class="flex justify-between items-center">
                            <Link :href="`/users`">
                                <Button variant="outline">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Adding...' : 'Add User' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
