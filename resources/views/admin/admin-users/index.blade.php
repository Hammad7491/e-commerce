@extends('admin.layouts.app')

@section('title', 'Admin Users')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Admin Users</h1>
            <p class="text-gray-600 mt-1">Manage all admin accounts</p>
        </div>

        <a href="{{ route('admin.admin-users.create') }}"
            class="inline-flex items-center px-5 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition">
            + Add Admin
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Created</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $admin->id }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $admin->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $admin->email }}</td>
                            <td class="px-6 py-4">
                                @if ($admin->is_active)
                                    <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded-full">
                                        Active
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded-full">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $admin->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.admin-users.edit', $admin->id) }}"
                                        class="px-4 py-2 text-sm bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                                        Edit
                                    </a>

                                    @if ((int) session('admin_id') !== (int) $admin->id)
                                        <form action="{{ route('admin.admin-users.destroy', $admin->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 text-sm bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-4 py-2 text-sm bg-gray-100 text-gray-500 rounded-lg">
                                            Current User
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No admin users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection