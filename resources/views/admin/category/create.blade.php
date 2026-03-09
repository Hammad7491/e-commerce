@extends('admin.layouts.app')

@section('title', $isEdit ? 'Edit Category' : 'Create Category')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $isEdit ? 'Edit Category' : 'Create Category' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    {{ $isEdit ? 'Update category name' : 'Add a new category name' }}
                </p>
            </div>

            <a href="{{ route('admin.categories.index') }}"
                class="px-5 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition">
                Back
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

        <div class="bg-white rounded-2xl shadow-sm p-8">
            <form action="{{ $isEdit ? route('admin.categories.update', $category->id) : route('admin.categories.store') }}"
                method="POST" class="space-y-6">
                @csrf

                @if ($isEdit)
                    @method('PUT')
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 outline-none focus:border-black"
                        placeholder="Enter category name"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition">
                        {{ $isEdit ? 'Update Category' : 'Save Category' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection