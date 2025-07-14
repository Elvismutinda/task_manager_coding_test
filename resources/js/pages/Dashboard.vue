<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';


const props = defineProps<{
  stats: {
    total: number;
    in_progress: number;
    completed: number;
    overdue: number;
  };
  activities: {
        id: number;
        task_name: string;
        user: { name: string };
        created_at: string;
        status: string;
    }[];
    upcoming: {
        id: number;
        task_name: string;
        deadline: string;
        status: string;
    }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-xl border p-4 shadow-sm bg-white dark:bg-muted">
                <h3 class="text-lg font-medium">Total Tasks</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.total }}</p>
                </div>
                <div class="rounded-xl border p-4 shadow-sm bg-white dark:bg-muted">
                <h3 class="text-lg font-medium">In Progress</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.in_progress }}</p>
                </div>
                <div class="rounded-xl border p-4 shadow-sm bg-white dark:bg-muted">
                <h3 class="text-lg font-medium">Completed</h3>
                <p class="text-3xl font-bold mt-2">{{ stats.completed }}</p>
                </div>
                <div class="rounded-xl border p-4 shadow-sm bg-white dark:bg-muted">
                <h3 class="text-lg font-medium">Overdue</h3>
                <p class="text-3xl font-bold mt-2 text-red-500">{{ stats.overdue }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <!-- Task Activity Feed -->
                <div class="border border-sidebar-border dark:border-sidebar-border rounded-xl p-4 bg-white dark:bg-muted">
                <h2 class="text-lg font-semibold mb-2">📝 Task Activity Feed</h2>
                <ul class="space-y-2">
                    <li v-for="activity in props.activities" :key="activity.id" class="text-sm border-b pb-2">
                    <p>
                        <span class="font-medium">{{ activity.user.name }}</span>
                        assigned task
                        <span class="font-semibold text-blue-600">“{{ activity.task_name }}”</span>
                    </p>
                    <p class="text-xs text-gray-500">{{ new Date(activity.created_at).toLocaleString() }}</p>
                    </li>
                </ul>
                </div>

                <div class="border border-sidebar-border dark:border-sidebar-border rounded-xl p-4 bg-white dark:bg-muted">
                <h2 class="text-lg font-semibold mb-2">📅 Upcoming Deadlines</h2>
                <ul class="space-y-2">
                    <li v-if="props.upcoming.length === 0" class="text-sm text-gray-500">No upcoming tasks.</li>
                    <li
                    v-for="task in props.upcoming"
                    :key="task.id"
                    class="flex justify-between items-center text-sm border-b pb-2"
                    >
                    <div>
                        <p class="font-medium">{{ task.task_name }}</p>
                        <p class="text-xs text-gray-500">
                        Due: {{ new Date(task.deadline).toLocaleDateString() }}
                        </p>
                    </div>
                    <span
                        class="text-xs rounded-full px-2 py-1"
                        :class="{
                        'bg-yellow-100 text-yellow-800': task.status === 'pending',
                        'bg-blue-100 text-blue-800': task.status === 'in_progress',
                        'bg-green-100 text-green-800': task.status === 'completed',
                        }"
                    >
                        {{ task.status.replace('_', ' ') }}
                    </span>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
