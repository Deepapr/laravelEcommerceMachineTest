@extends('layouts.app')

@section('title', 'Manage Colors')

@section('content')
<div class="card">
    <h2>Manage Colors</h2>

    <form action="{{ route('colors.store') }}" method="POST" style="margin-bottom: 2rem;">
        @csrf
        <div style="display: flex; gap: 1rem;">
            <div style="flex: 1;">
                <input type="text" name="name" placeholder="Color name" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
                <input type="text" name="code" placeholder="Hex code (e.g., #FF0000)" style="width: 100%;">
            </div>
            <button type="submit" class="btn btn-success">Add Color</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th style="width: 100px;">Color</th>
                <th style="width: 100px;">Code</th>
                <th style="width: 100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($colors as $color)
                <tr>
                    <td>{{ $color->name }}</td>
                    <td>
                        @if ($color->code)
                            <div style="width: 50px; height: 30px; background-color: {{ $color->code }}; border: 1px solid #ddd; border-radius: 4px;"></div>
                        @endif
                    </td>
                    <td>{{ $color->code }}</td>
                    <td>
                        <form action="{{ route('colors.destroy', $color) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem; font-size: 0.85rem;" onclick="return confirm('Delete this color?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No colors found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($colors->hasPages())
        <div class="pagination">
            {{ $colors->links() }}
        </div>
    @endif
</div>
@endsection
