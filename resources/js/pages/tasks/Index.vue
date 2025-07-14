<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { EditIcon, PlusIcon, TrashIcon } from 'lucide-vue-next';


type Task = {
    id: number;
    task_name: string;
    user_id: number;
    name: string;
    email: string;
}

const { tasks } = defineProps<{
    tasks: Task[];
}>();

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
                <Link :href="`/tasks/create`">
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
                                        <TableHead>Action</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="task in tasks" :key="task.id">
                                        <TableCell class="font-medium">#{{ task.id  }}</TableCell>
                                        <TableCell>{{ task.task_name }}</TableCell>
                                        <TableCell>{{ task.name }}</TableCell>
                                        <TableCell>{{ task.email }}</TableCell>
                                        <TableCell>
                                            <div class="flex items-center space-x-2">
                                                <Link :href="`/tasks/${task.id}/edit`">
                                                    <Button variant="outline">
                                                        <EditIcon class="h-4 w-4" />
                                                    </Button>
                                                </Link>

                                                <Link :href="`/tasks/${task.id}/delete`" method="post" as="button">
                                                    <Button variant="destructive">
                                                        <TrashIcon class="h-4 w-4" />
                                                    </Button>
                                                </Link>
                                            </div>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
