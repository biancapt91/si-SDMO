<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(25);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin',
        ];

        // If users.nip column exists, enforce uniqueness; otherwise validate without unique rule
        if (Schema::hasColumn('users', 'nip')) {
            $rules['nip'] = 'required|string|unique:users,nip';
        } else {
            $rules['nip'] = 'required|string';
        }

        $data = $request->validate($rules);

        if (!Schema::hasColumn('users', 'nip')) {
            // Friendly notice so developer knows to run migrations
            session()->flash('warning', 'Kolom users.nip belum ada di database. Jalankan: php artisan migrate');
        }

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted');
    }
}
