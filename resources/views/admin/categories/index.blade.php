@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')
<div class="card">
    <h2>Manage Categories</h2>

    <form action="{{ route('categories.store') }}" method="POST" style="margin-bottom: 2rem;">
        @csrf
        <div style="display: flex; gap: 1rem;">
            <div style="flex: 1;">
                <input type="text" name="name" placeholder="Category name" required style="width: 100%;">
            </div>
            <div style="flex: 2;">
                <input type="text" name="description" placeholder="Description" style="width: 100%;">
            </div>
            <button type="submit" class="btn btn-success">Add Category</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.85rem;" onclick="return confirm('Delete this category?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($categories->hasPages())
        <div class="pagination">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
