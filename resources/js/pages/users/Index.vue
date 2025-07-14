<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { EditIcon, PlusIcon, TrashIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';


type User = {
    id: number;
    name: string;
    email: string;
}

const { users, role } = defineProps<{
    users: User[];
    role: string;
}>();

const isAdmin = role === 'admin';

const deleteDialogOpen = ref(false);
const deleteUserId = ref<number | null>(null);

const handleDelete = (id: number) => {
    deleteUserId.value = id;
    deleteDialogOpen.value = true;
};

const handleConfirmDelete = () => {
    if (deleteUserId.value !== null) {
        // Assuming you have a method to delete the user
        router.delete(`/users/${deleteUserId.value}`, {
            onSuccess: () => {
                toast('User deleted successfully!');
            },
            onError: () => {
                toast('Failed to delete user.');
            },
            onFinish: () => {
                deleteDialogOpen.value = false;
                deleteUserId.value = null;
            },
        });
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Users</h1>
                <Link v-if="isAdmin" :href="`/users/create`">
                    <Button>
                        <PlusIcon class="h-4 w-4" />
                        Add User
                    </Button>
                </Link>
            </div>
            <div class="flex flex-col gap-4">
                <Card>
                    <CardHeader>
                        <CardTitle>All Users</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-4">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="w-[100px]">#</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead v-if="isAdmin">Action</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="user in users" :key="user.id">
                                        <TableCell class="font-medium">#{{ user.id  }}</TableCell>
                                        <TableCell>{{ user.name }}</TableCell>
                                        <TableCell>{{ user.email }}</TableCell>
                                        <TableCell v-if="isAdmin">
                                            <div class="flex items-center space-x-2">
                                                <Link :href="`/users/${user.id}/edit`">
                                                    <Button variant="outline">
                                                        <EditIcon class="h-4 w-4" />
                                                    </Button>
                                                </Link>

                                                <Button variant="destructive" @click="() => handleDelete(user.id)">
                                                    <TrashIcon class="h-4 w-4" />
                                                </Button>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>
            <AlertDialog v-if="isAdmin" :open="deleteDialogOpen" @update:open="(val: boolean) => deleteDialogOpen = val">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Are you sure you want to delete this user?</AlertDialogTitle>
                        <AlertDialogDescription>
                            This action cannot be undone.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                        <AlertDialogAction @click="handleConfirmDelete">Delete</AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>
