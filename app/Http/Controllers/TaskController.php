<?php

namespace App\Http\Controllers;

use App\Mail\TaskAssigned;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin can view all tasks and users can only view their own tasks
        $tasks = $user->role === 'admin'
            ? Task::all()
            : Task::where('user_id', $user->id)->get();

        return Inertia::render('tasks/Index', [
            'tasks' => $tasks,
            'role' => $user->role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Task::class);

        $users = User::where('role', 'user')->get(['id', 'name', 'email']);

        return Inertia::render('tasks/Create', [
            'users'=> $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'task_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'status' => 'nullable|in:pending,in_progress,completed',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        Task::create($validatedData);

        Mail::to($validatedData['email'])
            ->send(new TaskAssigned(Task::latest()->first()));

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $users = User::where('role', 'user')->get();

        return Inertia::render('tasks/Edit', [
            'task' => $task,
            'users' => Auth::user()->role === 'admin' ? $users : null,
            'role' => Auth::user()->role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        if (Auth::user()->role === 'admin') {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'task_name' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'status' => 'required|string|in:pending,in_progress,completed',
                'deadline' => 'nullable|date|after_or_equal:today',
            ]);

            $wasReassigned = $task->user_id !== $validated['user_id'];

            $task->update($validated);

            if ($wasReassigned) {
                Mail::to($validated['email'])
                    ->send(new TaskAssigned($task));
            }
        } else {
            $validated = $request->validate([
                'status' => 'required|string|in:pending,in_progress,completed',
            ]);
        }

        $task->update([
            'status' => $validated['status'],
        ]);

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
