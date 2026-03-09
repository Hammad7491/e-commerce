<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();

        return view('admin.admin-users.index', compact('admins'));
    }

    public function create()
    {
        $adminUser = new User();
        $isEdit = false;

        return view('admin.admin-users.form', compact('adminUser', 'isEdit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'admin',
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function edit($id)
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);
        $isEdit = true;

        return view('admin.admin-users.form', compact('adminUser', 'isEdit'));
    }

    public function update(Request $request, $id)
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($adminUser->id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $adminUser->name = $validated['name'];
        $adminUser->email = $validated['email'];
        $adminUser->is_active = $request->has('is_active');

        if (!empty($validated['password'])) {
            $adminUser->password = $validated['password'];
        }

        $adminUser->save();

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user updated successfully.');
    }

    public function destroy($id)
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);

        if (auth()->id() === $adminUser->id) {
            return redirect()
                ->route('admin.admin-users.index')
                ->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $adminUser->delete();

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user deleted successfully.');
    }
}