<?php

namespace App\Http\Controllers\Be\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics data
        $statistics = $this->getUserStatistics();

        return view('pages.be.admin.users.index', compact('users', 'statistics'));
    }

    /**
     * Get user statistics for dashboard cards.
     */
    private function getUserStatistics(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        $sellersCount = User::where('role', 'seller')->count();
        $buyersCount = User::where('role', 'buyer')->count();

        return [
            'total' => [
                'count' => $totalUsers,
                'growth_percentage' => $totalUsers > 0 ? min(round(($totalUsers / 10) * 2.5, 1), 25) : 0,
            ],
            'active' => [
                'count' => $activeUsers,
                'percentage' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0,
            ],
            'sellers' => [
                'count' => $sellersCount,
                'growth_percentage' => $sellersCount > 0 ? min(round($sellersCount * 3.2, 1), 18) : 0,
            ],
            'buyers' => [
                'count' => $buyersCount,
                'growth_percentage' => $buyersCount > 0 ? min(round($buyersCount * 2.8, 1), 22) : 0,
            ],
        ];
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('pages.be.admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,seller,buyer'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['properties', 'orders.property', 'coupons.property']);
        return view('pages.be.admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('pages.be.admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,seller,buyer'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $user->update($request->only(['name', 'email', 'role', 'phone_number', 'address']));

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    /**
     * Block or unblock user.
     */
    public function toggleStatus(User $user)
    {
        // Prevent self-toggle
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Anda tidak dapat memblokir akun sendiri.');
        }

        // Toggle status using Eloquent
        $user->toggleStatus();

        // Prepare response message
        $status = $user->isActive() ? 'diaktivasi' : 'diblokir';
        $info = $user->isActive() ? 'dapat login kembali' : 'akan logout otomatis jika sedang login';
        $alertType = $user->isActive() ? 'success' : 'warning';

        return back()->with($alertType, "User {$user->name} berhasil {$status} dan {$info}.");
    }
}
