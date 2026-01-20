@extends('layouts.app')

@section('title', 'Manage Sizes')

@section('content')
<div class="card">
    <h2>Manage Sizes</h2>

    <form action="{{ route('sizes.store') }}" method="POST" style="margin-bottom: 2rem;">
        @csrf
        <div style="display: flex; gap: 1rem;">
            <div style="flex: 1;">
                <input type="text" name="name" placeholder="Size name" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <input type="text" name="value" placeholder="Value (e.g., S, M, L, XL)" style="width: 100%;">
            </div>
            <button type="submit" class="btn btn-success">Add Size</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Value</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sizes as $size)
                <tr>
                    <td>{{ $size->name }}</td>
                    <td>{{ $size->value }}</td>
                    <td>
                        <form action="{{ route('sizes.destroy', $size) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.85rem;" onclick="return confirm('Delete this size?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No sizes found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($sizes->hasPages())
        <div class="pagination">
            {{ $sizes->links() }}
        </div>
    @endif
</div>
@endsection
