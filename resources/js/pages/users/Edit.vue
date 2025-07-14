<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps<{ user: { id: number; name: string; email: string; role: string } }>();

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  password: '',
  role: props.user.role,
});

const handleSubmit = () => {
    form.put(`/users/${props.user.id}`, {
        onSuccess: () => {
            toast('User updated successfully!');
        },
        onError: (error) => {
            toast('Failed to update user.');
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
        title: 'Edit User',
        href: `/users/${props.user.id}/edit`,
    },
];
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <CardTitle>Edit User</CardTitle>
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
                            <Label for="password">New Password (optional)<span class="text-red-500">*</span></Label>
                            <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            placeholder="Enter new password"
                            />
                            <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="role">Role <span class="text-red-500">*</span></Label>
                            <Select v-model="form.role">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a role" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Available Roles</SelectLabel>
                                        <SelectItem value="admin">Admin</SelectItem>
                                        <SelectItem value="user">User</SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.role" class="text-sm text-red-500">{{ form.errors.role }}</p>
                        </div>

                        <div class="flex justify-between items-center">
                            <Link :href="`/users`">
                                <Button variant="outline">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update User' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
