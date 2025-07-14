<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
        $stats = [
            'total' => Task::count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::whereDate('deadline', '<', now())->where('status', '!=', 'completed')->count(),
        ];

        $activities = Task::with('user')
            ->latest()
            ->take(10)
            ->get();

        $upcoming = Task::whereDate('deadline', '>=', now())
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get();
    }

    // Regular user dashboard
    else {
        $stats = [
            'total' => Task::where('user_id', $user->id)->count(),
            'in_progress' => Task::where('user_id', $user->id)->where('status', 'in_progress')->count(),
            'completed' => Task::where('user_id', $user->id)->where('status', 'completed')->count(),
            'overdue' => Task::where('user_id', $user->id)->whereDate('deadline', '<', now())->where('status', '!=', 'completed')->count(),
        ];

        $activities = Task::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $upcoming = Task::where('user_id', $user->id)
            ->whereDate('deadline', '>=', now())
            ->where('status', '!=', 'completed')
            ->orderBy('deadline')
            ->take(5)
            ->get();
    }

    return inertia('Dashboard', [
        'stats' => $stats,
        'activities' => $activities,
        'upcoming' => $upcoming,
        'role' => $user->role,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
