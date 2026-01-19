<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Hanya admin yang bisa akses
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::where('role', 'viewer')->paginate(10);
        $adminCount = User::where('role', 'admin')->count();
        
        return view('users.index', compact('users', 'adminCount'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tanggal_lahir' => 'required|date',
            'role' => 'required|in:viewer',
        ]);

        // Password berdasarkan tanggal lahir (format: DDMMYYYY)
        $tanggalLahir = \Carbon\Carbon::parse($validated['tanggal_lahir']);
        $password = $tanggalLahir->format('dmY');

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'role' => 'viewer',
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        if ($user->role !== 'viewer') {
            abort(404);
        }
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->role !== 'viewer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tanggal_lahir' => 'required|date',
        ]);

        // Update password jika tanggal lahir berubah
        $tanggalLahir = \Carbon\Carbon::parse($validated['tanggal_lahir']);
        $newPassword = $tanggalLahir->format('dmY');

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($newPassword),
            'tanggal_lahir' => $validated['tanggal_lahir'],
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil diperbarui']);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'viewer') {
            abort(404);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    /**
     * Get user data for AJAX.
     */
    public function getUser(User $user)
    {
        if ($user->role !== 'viewer') {
            abort(404);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'tanggal_lahir' => $user->tanggal_lahir->format('Y-m-d'),
            'role' => $user->role,
        ]);
    }
}
