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
import { computed } from 'vue';

const props = defineProps<{
  task: {
    id: number;
    task_name: string;
    user_id: number;
    name: string;
    email: string;
    status?: string;
    deadline?: string;
  };
  users?: { id: number; name: string; email: string }[];
  role: string;
}>();

const form = useForm({
  task_name: props.task.task_name,
  user_id: props.task.user_id.toString(),
  name: props.task.name,
  email: props.task.email,
  status: props.task.status || 'pending',
  deadline: props.task.deadline ?? '',
});

const selectedUser = computed(() =>
  props.users?.find((u) => u.id === parseInt(form.user_id)) || null
);

const handleSubmit = () => {
    if(props.role === 'admin' && selectedUser.value) {
        form.name = selectedUser.value.name;
        form.email = selectedUser.value.email;
    }

    form.put(`/tasks/${props.task.id}`, {
        onSuccess: () => {
            toast('Task updated successfully!');
        },
        onError: (error) => {
            toast('Failed to update task.');
            console.error(error);
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: '/tasks',
    },
    {
        title: 'Edit Task',
        href: `/tasks/${props.task.id}/edit`,
    },
];
</script>

<template>
    <Head title="Edit Task" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <Card>
                <CardHeader>
                    <div class="flex justify-between items-center">
                        <CardTitle>Edit Task</CardTitle>
                        <Link :href="`/tasks`">
                            <Button variant="outline">
                                <ArrowLeftIcon class="h-4 w-4" />
                                Back
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="handleSubmit" class="space-y-4">
                        <div v-if="props.role === 'admin'" class="space-y-2">
                            <Label for="task_name">Task Name <span class="text-red-500">*</span></Label>
                            <Input
                            id="task_name"
                            v-model="form.task_name"
                            type="text"
                            placeholder="Enter task name"
                            />
                            <p v-if="form.errors.task_name" class="text-sm text-red-500">{{ form.errors.task_name }}</p>
                        </div>

                        <div v-if="props.role === 'admin'" class="space-y-2">
                            <Label for="name">User<span class="text-red-500">*</span></Label>
                            <Select v-model="form.user_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a user" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectLabel>Available Users</SelectLabel>
                                        <SelectItem v-for="user in props.users" :key="user.id" :value="user.id.toString()">
                                            {{ user.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.user_id" class="text-sm text-red-500">{{ form.errors.user_id }}</p>
                            </div>

                            <div class="space-y-2">
                                <Label for="status">Status</Label>
                                <Select v-model="form.status">
                                    <SelectTrigger>
                                    <SelectValue placeholder="Select status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Task Status</SelectLabel>
                                            <SelectItem value="pending">Pending</SelectItem>
                                            <SelectItem value="in_progress">In Progress</SelectItem>
                                            <SelectItem value="completed">Completed</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.status" class="text-sm text-red-500">{{ form.errors.status }}</p>
                            </div>

                            <div v-if="props.role === 'admin'" class="space-y-2">
                                <Label for="deadline">Deadline</Label>
                                <Input
                                    id="deadline"
                                    v-model="form.deadline"
                                    type="date"
                                />
                                <p v-if="form.errors.deadline" class="text-sm text-red-500">{{ form.errors.deadline }}</p>
                            </div>

                            <div v-if="selectedUser">
                                <Input type="hidden" :value="selectedUser.email" disabled />
                            </div>

                            <div class="flex justify-between items-center">
                                <Link :href="`/tasks`">
                                    <Button variant="outline">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? 'Updating...' : 'Update Task' }}
                                </Button>
                            </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
