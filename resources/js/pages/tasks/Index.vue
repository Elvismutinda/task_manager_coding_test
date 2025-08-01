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


type Task = {
    id: number;
    task_name: string;
    user_id: number;
    name: string;
    email: string;
    status?: string;
    deadline?: string;
}

const { tasks, role } = defineProps<{
    tasks: Task[];
    role: string;
}>();

const deleteDialogOpen = ref(false);
const deleteTaskId = ref<number | null>(null);

const handleDelete = (id: number) => {
    deleteTaskId.value = id;
    deleteDialogOpen.value = true;
};

const handleConfirmDelete = () => {
    if (deleteTaskId.value !== null) {
    router.delete(`/tasks/${deleteTaskId.value}`, {
      onSuccess: () => {
        toast('Task deleted successfully!');
      },
      onError: () => {
        toast('Failed to delete task.');
      },
      onFinish: () => {
        deleteDialogOpen.value = false;
        deleteTaskId.value = null;
      },
    });
  }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: '/tasks',
    },
];
</script>

<template>
    <Head title="Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Tasks</h1>
                <Link v-if="role === 'admin'" :href="`/tasks/create`">
                    <Button>
                        <PlusIcon class="h-4 w-4" />
                        Create Task
                    </Button>
                </Link>
            </div>
            <div class="flex flex-col gap-4">
                <Card>
                    <CardHeader>
                        <CardTitle>All Tasks</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col gap-4">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead class="w-[100px]">#</TableHead>
                                        <TableHead>Task</TableHead>
                                        <TableHead>Assigned to</TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Deadline</TableHead>
                                        <TableHead>Action</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="task in tasks" :key="task.id">
                                        <TableCell class="font-medium">#{{ task.id  }}</TableCell>
                                        <TableCell>{{ task.task_name }}</TableCell>
                                        <TableCell>{{ task.name }}</TableCell>
                                        <TableCell>{{ task.email }}</TableCell>
                                        <TableCell>{{ task.status ? task.status.replace('_', ' ') : '—' }}</TableCell>
                                        <TableCell>{{ task.deadline ?? '—' }}</TableCell>
                                        <TableCell>
                                            <div class="flex items-center space-x-2">
                                                <Link v-if="role === 'admin' || task.user_id === $page.props.auth.user.id" :href="`/tasks/${task.id}/edit`">
                                                    <Button variant="outline">
                                                        <EditIcon class="h-4 w-4" />
                                                    </Button>
                                                </Link>

                                                <Button v-if="role === 'admin'" variant="destructive" @click="() => handleDelete(task.id)">
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
            <AlertDialog :open="deleteDialogOpen" @update:open="(val: boolean) => deleteDialogOpen = val">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Are you sure you want to delete this task?</AlertDialogTitle>
                        <AlertDialogDescription>
                            This action cannot be undone. This will permanently delete this task.
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
