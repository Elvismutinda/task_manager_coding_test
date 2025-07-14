<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::where('role', 'user')->get();
        return Inertia::render('users/Index', [
            'users' => $users,
            'role' => Auth::user()->role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return Inertia::render('users/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|in:user,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return Inertia::render('users/Edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|string|in:user,admin',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
